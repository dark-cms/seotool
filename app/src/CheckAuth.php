<?php

namespace App;

class CheckAuth
{

    public function __invoke($request, $response, $next)
    {

        $session = new \RKA\Session();

        if($session->loggedin == '1') {

            $response = $next($request, $response);
            return $response;
        }
        else {

            return $response->withStatus(301)->withHeader('Location', '/login/');
        }

    }

}
