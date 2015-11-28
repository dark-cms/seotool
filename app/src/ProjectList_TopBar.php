<?php

namespace App;

use MysqliDb as DB;

class ProjectList_TopBar
{

    private $db;
    private $projectArray;

    public function __construct(DB $db)
    {

        $this->db = $db;
        $this->getProjectData();

    }

    private function getProjectData()
    {

        $this->projectArray = $this->db->rawQuery(
                'SELECT
                    p1.projectID,
                    p1.projectURL,
                    IFNULL(p2.projectURL, p1.projectURL) as parentURL
                FROM
                    st_projects p1
                LEFT JOIN
                    st_projects p2
                ON 
                    p1.parentProjectID = p2.projectID
                ORDER BY 
                    parentURL, p1.projectID'
        );

    }

    public function getProjectList()
    {
        $html = [];

        $currentParentCounter = 0;
        foreach ($this->projectArray as $pArrayKey => $projectData) {



            if($projectData['projectURL'] != $projectData['parentURL']) {
                $html[] = '<li>';
                $html[] = '<a href="/projects/select/' . $projectData['projectID'] . '/"><i class="fa fa-caret-right"></i> <strong>' . \App\Tool::removeSchemeFromURL($projectData['parentURL']) . '</strong> <i class="fa fa-caret-right"></i> ' . \App\Tool::removeSchemeFromURL($projectData['projectURL']) . '</a>';
                $html[] = '</li>';
            }
            else {
                if($currentParentCounter > 0) {
                    $html[] = '<li class="divider"></li>';
                }

                $html[] = '<li>';
                $html[] = '<a href="/projects/select/' . $projectData['projectID'] . '/"><i class="fa fa-caret-right"></i> <strong>' . \App\Tool::removeSchemeFromURL($projectData['projectURL']) . '</strong></a>';
                $html[] = '</li>';
                $currentParentCounter++;
            }
        }

        return implode("\n", $html);

    }

}
