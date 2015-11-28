<?php

namespace App\Ajax;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use MysqliDb as DB;

class Settings
{

    private $request;
    private $response;
    private $db;
    private $session;
    private $error     = 0;
    private $message   = '';
    private $postArray = [];

    public function __construct(Request $request, Response $response, DB $db)
    {

        $this->request  = $request;
        $this->response = $response;
        $this->db       = $db;

        if(!$request->withMethod('POST')) {
            die('Not allowed');
        }

    }

    public function update()
    {
        $this->extractPostDataFromParsedBody('update');
        $this->updateSettings();
        $this->finish();

    }

    private function updateSettings()
    {
        $this->checkTiming();
        $this->checkPause();
        $this->checkProject();

        $this->db->where('optionName', 'cronjobHours');
        $this->db->update('settings', ['value' => $this->postArray['timing']]);
        
        $reorderKeywords = new \App\ReorderKeywords($this->db,$this->postArray['timing']);
        $reorderKeywords->start();

        $this->db->where('optionName', 'pauseStatic');
        $this->db->update('settings', ['value' => $this->postArray['pause_static']]);

        $this->db->where('optionName', 'pauseVariable');
        $this->db->update('settings', ['value' => $this->postArray['pause_variable']]);
        
        $this->updateProjects();
        
        $this->db->where('projectID',$this->postArray['defaultProject']);
        $this->db->update('projects',['projectDefault' => 1]);
        
        $this->message = '<strong>Sehr gut!</strong> Einstellungen wurden aktualisiert.';

    }

    private function updateProjects()
    {
        $this->db->where('projectDefault', 1);
        $this->db->update('projects', ['projectDefault' => 0]);

    }

    private function checkProject()
    {
        $this->postArray['defaultProject'] = intval($this->postArray['defaultProject']);

    }

    private function checkPause()
    {

        $this->postArray['pause_variable'] = ( $this->postArray['pause_variable'] > 0 ) ? intval($this->postArray['pause_variable']) : 45;
        $this->postArray['pause_static']   = ( $this->postArray['pause_static'] > 0 ) ? intval($this->postArray['pause_static']) : 45;

    }

    private function checkTiming()
    {

        if(isset($this->postArray['timing']) && $this->postArray['timing'] != '') {
            $tempTiming = [];
            $timing     = explode(',', $this->postArray['timing']);
            foreach ($timing as $tKey => $tValue) {

                if($tValue >= 0 && $tValue < 24) {
                    $tempTiming[] = intval($tValue);
                }
            }
            array_unique($tempTiming);

            $this->postArray['timing'] = implode(',', $tempTiming);
        }
        else {
            $this->postArray['timing'] = '0,1,2,3,4,5,6,7,8,9,10';
        }

    }

    private function extractPostDataFromParsedBody($jqeryPostName)
    {

        $postDataExploded = [];
        $p                = $this->request->getParsedBody()[$jqeryPostName];
        $postData         = explode('&', urldecode($p));

        foreach ($postData as $dKey => $dVal) {
            $pTemp                       = explode('=', $dVal);
            $postDataExploded[$pTemp[0]] = $pTemp[1];
        }

        $this->postArray = $postDataExploded;

    }

    private function finish()
    {

        $body = $this->response->withHeader('Content-type', 'application/json');
        $body->write(
                json_encode(
                        [
                            'error'   => $this->error,
                            'message' => $this->message
                        ]
                )
        );

    }

}
