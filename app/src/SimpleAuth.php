<?php

namespace App;

class SimpleAuth
{

    public function __invoke($request, $response, $next)
    {

        if($this->checkSet() && $this->checkLogin()) {

            $this->setLoginSession();
            $response = $next($request, $response);
            return $response;
        }
        else {

            return $response->withStatus(301)->withHeader('Location', '/login/');
        }

    }

    private function checkSet()
    {
        if(isset($_POST['user']) && isset($_POST['password'])) {

            return TRUE;
        }

        return FALSE;

    }

    private function checkLogin()
    {
        if($_POST['user'] == USER && $_POST['password'] == PASS) {

            return TRUE;
        }

        return FALSE;

    }

    private function setLoginSession()
    {
        $session = new \RKA\Session();
        $session->set('loggedin', '1');

    }

}
