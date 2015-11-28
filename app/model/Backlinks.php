<?php

namespace App\Model;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use MysqliDb as DB;

class Backlinks
{

    private $request;
    private $response;
    private $db;
    private $modelData      = [];
    private $additionalData = [];
    private $session;

    public function __construct(Request $request, Response $response, DB $db)
    {

        $this->request  = $request;
        $this->response = $response;
        $this->db       = $db;
        $this->session  = new \RKA\Session();

        $this->additionalData['title'] = 'SEO Tool: Backlinks';

        $this->additionalData['currentProjectNameData'] = new \App\CurrentProject_TopBar($this->db);

        $this->additionalData['projectListData'] = new \App\ProjectList_TopBar($this->db);

    }

    public function getBacklinks()
    {
        $this->db->join("projects p", "p.projectID=b.backlinkProject", "LEFT");
        $this->db->join("backlinkCategories bc", "bc.backlinkCategoryID=b.backlinkCategory", "LEFT");
        $this->db->join("backlinkSourceCategories bsc", "bsc.backlinkSourceID=b.backlinkSourceCategory", "LEFT");
        $this->db->join("backlinkRelations br", "br.backlinkRelationID=b.backlinkRelation", "LEFT");
        $this->db->orderBy("b.backlinkDateAdded", "DESC");
        $this->db->where("b.backlinkProject", 0, ">");

        $this->modelData['backlinkList'] = $this->db->get("backlinks b", null, "b.backlinkID, b.backlinkSource as backlinkSourceURL, b.backlinkTarget, b.backlinkLinkText, b.backlinkDateAdded, b.backlinkComment,bc.backlinkCategory,bsc.backlinkSource,br.backlinkRelation");

    }

    public function getRelations()
    {
        $this->db->orderBy('backlinkRelationID', 'ASC');
        $this->modelData['linkRelations'] = $this->db->get('backlinkRelations');

    }

    public function getLinkSourceTypes()
    {
        $this->db->orderBy('backlinkSourceID', 'ASC');
        $this->modelData['linkSourceTypes'] = $this->db->get('backlinkSourceCategories');

    }

    public function getLinkTypes()
    {
        $this->db->orderBy('backlinkCategoryID', 'ASC');
        $this->modelData['linkTypes'] = $this->db->get('backlinkCategories');

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

        $this->saveCompetition();
        $this->modelData['projectData'] = $this->projectData;

    }

    private function saveProjectData($dataSrc)
    {

        $this->projectData['currentProjectID']       = intval($dataSrc['projectID']);
        $this->projectData['currentProjectURL']      = $dataSrc['projectURL'];
        $this->projectData['currentProjectParentID'] = intval($dataSrc['parentProjectID']);
        $this->session->set('currentProject', intval($dataSrc['projectID']));

    }

    private function saveCompetition()
    {
        $this->db->where('parentProjectID', $this->projectData['currentProjectParentID']);
        $this->db->orderBy('projectID', 'ASC');
        $this->projectData['competitorList'] = $this->db->get('projects', NULL, 'projectID,projectURL');

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
