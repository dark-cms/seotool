<?php

namespace App\Model;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use MysqliDb as DB;

class Settings
{

    private $request;
    private $response;
    private $db;
    private $session;
    private $projectData    = [];
    private $modelData      = [];
    private $additionalData = [];

    public function __construct(Request $request, Response $response, DB $db)
    {

        $this->request  = $request;
        $this->response = $response;
        $this->db       = $db;
        $this->session  = new \RKA\Session();


        $this->additionalData['title'] = 'SEO Tool: Einstellungen';

        $this->additionalData['currentProjectNameData'] = new \App\CurrentProject_TopBar($this->db);

        $this->additionalData['projectListData'] = new \App\ProjectList_TopBar($this->db);

    }

    public function getAllProjects()
    {
        $this->db->orderBy('projectID', 'ASC');
        $this->modelData['projectList'] = $this->db->get('projects', null, 'projectID,projectURL,projectDefault');

    }

    public function getSettings()
    {
        $settings = $this->db->get('settings');

        foreach ($settings as $settingKey => $settingData) {
            $this->modelData['settings'][$settingData['optionName']] = $settingData['value'];
        }

    }

    public function setProjectData()
    {

        $projectIDinSession = $this->session->get('currentProject');

        $this->db->where('projectID', $projectIDinSession);
        $currentProjectData = $this->db->getOne('projects');

        if($this->db->count == 1) {

            $this->saveProjectData($currentProjectData);
        }
        else {

            $this->db->where('projectDefault', 1);
            $alternativeProjectData = $this->db->getOne('projects');

            if($this->db->count == 1) {
                $this->saveProjectData($this->db->getOne('projects'));
            }
            else {
                $this->db->orderBy('projectID', 'ASC');
                $this->saveProjectData($this->db->getOne('projects'));
            }
        }


        $this->modelData['projectData'] = $this->projectData;

    }

    private function saveProjectData($dataSrc)
    {

        $this->projectData['currentProjectID']       = intval($dataSrc['projectID']);
        $this->projectData['currentProjectURL']      = $dataSrc['projectURL'];
        $this->projectData['currentProjectParentID'] = intval($dataSrc['parentProjectID']);
        $this->session->set('currentProject', intval($dataSrc['projectID']));

    }

    public function getModelData()
    {
        $this->setAdditionalData($this->additionalData);
        return $this->modelData;

    }

    public function setAdditionalData(array $additionalData)
    {

        foreach ($additionalData as $dataKey => $dataValue) {
            $this->modelData[$dataKey] = $dataValue;
        }

    }

}
