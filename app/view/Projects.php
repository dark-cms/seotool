<?php

namespace App\View;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer as Renderer;

class Projects
{

    private $request;
    private $response;
    private $renderer;
    private $modelData;
    private $viewData = [];

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

                        
    public function generateCompetitionArea() {
        
        $html = [];
        
        foreach( $this->modelData['competetionData'] as $cKey => $cData) {
            $html[] = '<div class="form-group input-group p'.$cData['projectID'].'">';
            $html[] = '<input name="comp_'.$cData['projectID'].'" class="form-control" value="'.$cData['projectURL'].'"><span class="input-group-addon"><i data-pid="'.$cData['projectID'].'" class="fa fa-remove red projectRemove"></i></span>';
            $html[] = '</div>';
        }
        
        $countNewProjectFields = 0;
        
        while( $countNewProjectFields < 3) {
            $html[] = '<div class="form-group input-group">';
            $html[] = '<span class="input-group-addon"><i class="fa fa-link"></i></span><input name="new_'.$countNewProjectFields.'" class="form-control" placeholder="URL zu einem neuen Konkurrenten">';
            $html[] = '</div>';
            
            $countNewProjectFields++;
        }
        
        $this->viewData['competitionArea'] = implode("\n", $html);
        
    }

    public function generateMainProjectArea()
    {
        $this->viewData['projectID'] = $this->modelData['projectID'];
        $this->viewData['mainProjectArea'] = '<span class="input-group-addon"><i class="fa fa-link"></i></span><input name="project_'.$this->modelData['projectID'].'" class="form-control" value="'.$this->modelData['mainProjectData']['projectURL'].'">';
    }

    
    public function generateList()
    {

        $html = [];

        foreach ($this->modelData['projects'] as $projectID => $projectData) {

            $html[] = '<tr class="p' . $projectData['projectID'] . '">';
            $html[] = '<td>' . \App\Tool::removeSchemeFromURL($projectData['projectURL']) . '</td>';
            $html[] = '<td>' . \App\Tool::removeSchemeFromURL($projectData['projectCompetition']) . '</td>';
            $html[] = '<td>' . $projectData['projectsKeywordCount'] . '</td>';
            $html[] = '<td>' . $projectData['projectAdded'] . '</td>';
            $html[] = '<td><a href="/projects/edit/' . $projectData['projectID'] . '/"><i class="fa fa-edit projectEdit"></i></a></td>';
            $html[] = '<td><i data-pid="' . $projectData['projectID'] . '" class="fa fa-remove projectRemove"></i></td>';
            $html[] = '</tr>';
        }

        $this->viewData['projectsTable'] = implode("\n", $html);

    }

    public function create($template = 'index')
    {

        $this->renderer->render($this->response, 'header.php', $this->viewData);
        $this->renderer->render($this->response, 'navigation.php', $this->viewData);
        $this->renderer->render($this->response, 'projects/' . $template . '.php', $this->viewData);
        $this->renderer->render($this->response, 'footer.php', $this->viewData);

    }

}
