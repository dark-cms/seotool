<?php

namespace App;

class Logout
{

    public function __invoke($request, $response, $next)
    {

        \RKA\Session::destroy();
        $response = $next($request, $response);
        return $response;

    }

}
