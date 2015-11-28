<?php

namespace App;

use MysqliDb as DB;

class CurrentProject_TopBar
{

    private $db;
    private $currentProjectArray = [];


    public function __construct(DB $db)
    {

        $this->db = $db;
        $this->getCurrentProjectData();

    }

    private function getCurrentProjectData()
    {

        $session = new \RKA\Session();
        $currentProjectID = intval($session->get('currentProject'));
        if( $currentProjectID > 0 ) {
            $this->db->where('projectID',$currentProjectID);
            $this->currentProjectArray = $this->db->getOne('projects','projectURL');
        } else {
            $this->db->where('projectDefault','1');
            $this->currentProjectArray = $this->db->getOne('projects','projectID,projectURL');
            
            
            if( $this->db->totalCount == 0 ) {
                $this->db->orderBy('projectID','ASC');
                $this->currentProjectArray = $this->db->getOne('projects','projectID,projectURL');
            } else {
                
            }
            
            $session->set('currentProject', $this->currentProjectArray['projectID']);
        }
        
        
        

    }

    public function getCurrentProjectName()
    {
        return \App\Tool::removeSchemeFromURL($this->currentProjectArray['projectURL']);
    }

}
