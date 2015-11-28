# Part of SEO Tool v2
# Author: Damian Schwyrz
# URL: http://damianschwyrz.de
# Github: https://github.com/Damian89/seotool
# Date: 2015-11-28

#!/usr/bin/perl

## Used packages ##
use DBI;
use LWP::Simple;
use LWP::UserAgent;
use URI::Escape;
use autodie;
use Data::Dumper;
use strict;
use warnings;


## Settings ##
my $debug    = 0; ## 1: prints debug information, 0: no output
my $active   = 1; ## 1: normale usage; 0: use for debug, no google request, no extract, no db save

## Database settings ##
my $database = '';
my $user     = '';
my $pw       = '';
my $host     = 'localhost';
my $port     = '3306';
my $platform = 'mysql';
my $dsn      = "dbi:$platform:$database:$host:$port";

my $connect  = DBI->connect($dsn, $user, $pw);
$connect->do("SET NAMES 'utf8'");

my $query;
my $query_handle;
my $pid;
my $purl;
my $pp;

## Get all projects ##
$query = "SELECT projectID,projectURL, parentProjectID FROM st_projects";
$query_handle = $connect->prepare($query);
$query_handle->execute();
$query_handle->bind_columns(undef, \$pid, \$purl, \$pp);

my %projects;

while($query_handle->fetch())
{

        $projects{$pp}{$pid} = $purl;

}

$query_handle->finish();

## Get pause options ##
$query = "SELECT (SELECT value FROM st_settings WHERE optionName = 'pauseVariable') as variableSec, (SELECT value FROM st_settings WHERE optionName = 'pauseStatic' ) as staticSec";

my @row = $connect->selectrow_array($query);
my ($secVariable,$secStatic) = @row;


my $kid;
my $kname;
my $kpp;

## Get keywordlist for this hour ##
$query = "SELECT keywordID, keywordName, parentProjectID FROM st_keywords WHERE keywordUpdateHour=".get_time('hour')." AND keywordUpdated NOT LIKE '".get_time('c_date')."%'";
$query_handle = $connect->prepare($query);
$query_handle->execute();
$query_handle->bind_columns(undef, \$kid, \$kname, \$kpp);

my @googleResponse;

## Go through every keyword ##
while($query_handle->fetch())
{

    if( $debug eq 1 ) { print "\n".'Searching for Keyword "'.$kname.'" (ID: '.$kid.') '."\n"; }

    ## Get first 100 positions on google.de for current keyword ##
    if( $active eq 1 ) { @googleResponse = getGoogleResponse( $kname ); }

    ## Search in the current google response for projects associated with this keyword ##
    foreach my $projectID (keys ($projects{$kpp}) )
    {

        if( $active eq 1 ) {
            ## Extract data for this project ##
            my %rankingData = extractFromGoogleResponse( \@googleResponse, $projects{$kpp}{$projectID}, $kname );

            ## Save extracted data (postions, url) in mysql database ##
            saveRankingForURL( $kid, $projectID, $kpp, $rankingData{'pos'}, $rankingData{'url'} );

        } else {
            if( $debug eq 1 ) { print 'DB-Test: Current search: Project "'.$projects{$kpp}{$projectID}.'"'."\n"; }

        }



    }

    ## Wait between every request ##
    if( $active eq 1 ) {
        custom_sleep($secVariable,$secStatic);
    }


}

## Close mysql database connection ##
$query_handle->finish();
$connect->disconnect();

## Close perl script ##
exit 0;


## Method:       getGoogleResponse
## Description:  Sends request to google.de, response conains first 100 positions for current keyword name
## Input:        (string) keywordName
## Output:       (array) requestResultForKeyword

sub getGoogleResponse
{

    my $keywordName     = $_[0];

    my $userAgent = LWP::UserAgent->new(agent => "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36", cookie_jar => {});

    $userAgent->default_header('Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8','Accept-Language' => "de,en-US;q=0.7,en;q=0.3",'Cache-Control' => 'max-age=0');

    my $request = HTTP::Request->new(GET => 'https://www.google.de/search?oe=utf-8&pws=0&complete=0&hl=de&num=100&q='.uri_escape( $keywordName ));
    my $response = $userAgent->request($request);

    if ($response->is_success)
    {

    my @requestResultForKeyword = $response->content =~ /<(div|li) class=\"g\"(.+?)<\/(div|li)>/gi;

    return @requestResultForKeyword;

    } else {
        print "Probleme beim fetchen des Contents von Google.de\n";
    }

}

## Method:      extractFromGoogleResponse
## Description: Extracts position and ranked URL from current google response array for current project
## Input:       (array)  googleResponse
##              (string) projectURL
                (string) keywordName
## Output:      (string) rankedURLdetected
                (int)    rankingPositionDetected

sub extractFromGoogleResponse
{
    my @googleResponse;
    my $projectURL;
    my $keywordName;
    my $rankedURLdetected;
    my $rankingPositionDetected;


    @googleResponse  = @{$_[0]};
    $projectURL      = $_[1];
    $keywordName     = $_[2];

    $rankedURLdetected = '';
    $rankingPositionDetected = -1;

    if( $debug eq 1 ) { print 'Start extracting data for url "'.$projectURL.'"'."\n"; }

    foreach my $line (@googleResponse)
    {
        if (index($line,$projectURL) != -1)
        {

            my @inLineContent = $line=~ /<(h3|span) class=\"(r|_Tyb)\"><a(.*)href=\"(.*)\" onmousedown=\"return rwt\(this,'','','','(\d{1,3})','(.*)',event\)\">(.*)<\/a><\/(h3|span)>/gi;

            $rankedURLdetected          = $inLineContent[3];
            $rankingPositionDetected    = int($inLineContent[4]);

            last;
        }
    }


    my %rankingData;

    $rankingData{url} = $rankedURLdetected;
    $rankingData{pos} = $rankingPositionDetected;

    if( $debug eq 1 && $rankingPositionDetected > 0 ) { print 'Detected: "'.$rankingData{'url'}.'" at position '.$rankingData{'pos'}."\n"; }
    if( $debug eq 1 && $rankingPositionDetected < 0 ) { print "Nothing detected in serps!\n"; }

    return %rankingData;

}

## Method:      saveRankingForURL
## Description: Saves position data in given mysql database
## Input:       (int)     keywordID
##              (int)     projectID
                (int)     parentID
                (int)     rankingPos
                (string)  rankingURL

sub saveRankingForURL
{

    my $keywordID;
    my $projectID;
    my $parentID;
    my $rankingPos;
    my $rankingURL;

    my $insert;
    my $update;
    my $insert_handle;
    my $update_handle;

    $keywordID   = $_[0];
    $projectID   = $_[1];
    $parentID    = $_[2];
    $rankingPos  = $_[3];
    $rankingURL  = $connect->quote($_[4]);

    $connect ||= DBI->connect($dsn, $user, $pw);

    if( $rankingPos > 0 && $rankingURL ne '' )
    {
        $insert = "INSERT INTO st_rankings (keywordID, projectID, rankingPosition, rankingURL, rankingAddedDay) VALUES ($keywordID, $projectID, $rankingPos, $rankingURL, '".get_time('c_date')."')";
        $insert_handle = $connect->prepare($insert);
        $insert_handle->execute() || dberror ("Error at first insert: $DBI::errstr");
        $insert_handle->finish();

    }
    else {
        $insert = "INSERT INTO st_rankings (keywordID, projectID, rankingAddedDay) VALUES ($keywordID, $projectID, '".get_time('c_date')."')";
        $insert_handle = $connect->prepare($insert);
        $insert_handle->execute() || dberror ("Error at second insert: $DBI::errstr");
        $insert_handle->finish();
    }

    sleep(2);

    $update = "UPDATE st_keywords SET keywordUpdated='".get_time('iso')."' WHERE keywordID=$keywordID LIMIT 1";
    $update_handle = $connect->prepare($update);
    $update_handle->execute() || dberror ("Error at first update: $DBI::errstr");
    $update_handle->finish();
}

## Method:      custom_sleep
## Description: Generate random waiting time in seconds
## Input:       (int)     range
##              (int)     time_to_add

sub custom_sleep
{
    my $range           = $_[0];
    my $time_to_add     = $_[1];
    my $random_number   = int(rand($range))+$time_to_add;

    if( $debug eq 1 ) {  print "\nWaiting $random_number seconds till next request...\n\n"; }

    sleep($random_number);

}

## Method:      get_time
## Description: Generate datestring based on given parameter
## Input:       (string) [hour|iso|c_date]
## Output:      (int|string) [hh|yyyy-mm-dd hh:ii:ss|yyyy-mm-dd]

sub get_time
{

    my ($seconds, $minutes, $hours, $day, $month, $Jahr, $weekDay, $yearDay, $summerTime) = localtime(time);

    $month+=1;
    $yearDay+=1;
    $month = $month < 10 ? $month = "0".$month : $month;
    $day = $day < 10 ? $day = "0".$day : $day;
    $minutes = $minutes < 10 ? $minutes = "0".$minutes : $minutes;
    $seconds = $seconds < 10 ? $seconds = "0".$seconds : $seconds;
    $Jahr+=1900;

    if($_[0] eq 'hour')
    {

     return $hours;

    }

    if($_[0] eq 'iso')
    {

     return $Jahr."-".$month."-".$day." ".$hours.":".$minutes.":".$seconds;

    }

    if($_[0] eq 'c_date')
    {

     return $Jahr."-".$month."-".$day;

    }

    if($_[0] ne 'iso' || $_[0] ne 'hour' || $_[0] ne 'c_date' )
    {

     die "get_Time() -> Unknown or no argument given\n";

    }

}
