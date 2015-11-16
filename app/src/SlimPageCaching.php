<?php

namespace App;

class SlimPageCaching
{

    protected $path;
    protected $fileToCache;
    protected $timeToCache    = 5;
    protected $debug          = FALSE;
    protected $cacheActive    = TRUE;
    protected $compressActive = TRUE;
    protected $whitelist      = [];

    public function __construct($settings)
    {

        $this->path           = $settings['cache']['path'];
        $this->timeToCache    = $settings['cache']['lifetime'];
        $this->debug          = $settings['cache']['debug'];
        $this->cacheActive    = $settings['cache']['cacheActive'];
        $this->compressActive = $settings['cache']['compressActive'];
        $this->whitelist      = $settings['cache']['whitelist'];

    }

    public function run($request, $response, $next)
    {

        //wenn global, dann bzgl whitelisting
        /* if( $this->processWhitelist($request) ) {

          $response = $next($request, $response);

          } else */

        if(($request->isGet() && $this->cacheActive)) {

            $this->getCacheFilePath($request);

            if($this->cacheIsValid()) {

                $this->load();
            }
            else {
                if($response->getStatusCode() == 200) {
                    $this->save($request, $response, $next);
                }
            }
        }
        else {

            if($this->compressActive) {

                ob_start('\App\SlimPageCompress::Start');
            }

            $response = $next($request, $response);
        }

        return $response;

    }

    private function save($request, $response, $next)
    {

        if($this->compressActive) {
            ob_start('\App\SlimPageCompress::Start');
        }

        $response = $next($request, $response);

        if($this->compressActive) {
            $html = \App\SlimPageCompress::Start($response->getBody());
        }
        else {
            $html = $response->getBody();
        }


        if($this->debug) {
            $dt = new \DateTime('NOW');
            $dt = $dt->format('c');
            $html .='<!-- Cached on: ' . $dt . ' -->';
        }

        file_put_contents($this->fileToCache, $html);

    }

    private function load()
    {

        $fh = fopen($this->fileToCache, 'r');
        fpassthru($fh);
        exit;

    }

    private function cacheIsValid()
    {

        if(file_exists($this->fileToCache) && ( filemtime($this->fileToCache) > (time() - $this->timeToCache ))) {
            return TRUE;
        }
        return FALSE;

    }

    private function getCacheFilePath($request)
    {

        $cacheHash = $request->getUri()->getScheme() . '://' . $request->getUri()->getHost() . $request->getUri()->getPath();

        $cacheHash = md5($cacheHash);
        $cacheFile = $cacheHash . '.html';

        $this->fileToCache = $this->path . DIRECTORY_SEPARATOR . $cacheFile;

    }

    private function processWhitelist($request)
    {

        foreach ($this->whitelist as $notAllowed) {
            if(strpos($request->getUri()->getPath(), $notAllowed) !== FALSE) {
                return TRUE;
            }
        }
        return FALSE;

    }

}
