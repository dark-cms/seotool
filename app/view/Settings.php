<?php

namespace App\View;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer as Renderer;

class Settings
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

    }

    public function loadSettings() {
        $this->viewData['settings'] = $this->modelData['settings'];  
        
        $this->viewData['settings']['cronjobHours'] = $this->viewData['settings']['cronjobHours'];
        $this->viewData['settings']['pauseVariable'] = (int) $this->viewData['settings']['pauseVariable'];
        $this->viewData['settings']['pauseStatic'] = (int) $this->viewData['settings']['pauseStatic'];
        
        $this->loadProjects();
    }
    
    private function loadProjects() {
        $html = [];
        
        foreach( $this->modelData['projectList'] as $aKey => $projectData ) {
            $selected = '';

            if( $projectData['projectDefault'] == 1 ) {
                $selected = 'selected="selected"';
            }
            $html[] = '<option value="'.$projectData['projectID'].'" '.$selected.'>'.\App\Tool::removeSchemeFromURL($projectData['projectURL']).'</option>';
        }
        
        $this->viewData['projectList'] = implode("\n",$html);
    }
    
    public function loadProjectData() {
        $this->viewData['projectData'] = $this->modelData['projectData'];  
    }
    
    public function create()
    {
        $this->renderer->render($this->response, 'header.php', $this->viewData);
        $this->renderer->render($this->response, 'navigation.php', $this->viewData);
        $this->renderer->render($this->response, 'settings/index.php', $this->viewData);
        $this->renderer->render($this->response, 'footer.php', $this->viewData);

    }

}
