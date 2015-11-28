<?php

namespace App\View;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer as Renderer;

class Notes
{

    private $request;
    private $response;
    private $renderer;
    private $modelData;
    private $app;

    public function __construct(Request $request, Response $response, Renderer $renderer, array $modelData)
    {

        $this->request   = $request;
        $this->response  = $response;
        $this->renderer  = $renderer;
        $this->modelData = $modelData;

        $this->viewData['title'] = $this->modelData['title'];

        $this->viewData['currentProjectNameSet'] = $this->modelData['currentProjectNameData']->getCurrentProjectName();

        $this->viewData['projectList'] = $this->modelData['projectListData']->getProjectList();
   
        $this->create();

    }

    public function create()
    {
        $this->renderer->render($this->response, 'header.php', $this->modelData);
        $this->renderer->render($this->response, 'navigation.php', $this->modelData);
        $this->renderer->render($this->response, 'notes/index.php', $this->modelData);
        $this->renderer->render($this->response, 'footer.php', $this->modelData);

    }

}
