<?php

namespace App\Model;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use MysqliDb as DB;

class Notes
{

    private $request;
    private $response;
    private $db;
    private $modelData = [];

    public function __construct(Request $request, Response $response, DB $db)
    {

        $this->request  = $request;
        $this->response = $response;
        $this->db       = $db;

        $this->additionalData['title'] = 'SEO Tool: Notizen';
        
        $this->additionalData['currentProjectNameData'] = new \App\CurrentProject_TopBar($this->db);
        
        $this->additionalData['projectListData'] = new \App\ProjectList_TopBar($this->db);
        
        $this->setAdditionalData();

    }
    
    public function getModelData() {
        return $this->modelData;
    }

    public function setAdditionalData(array $additionalData)
    {

        foreach ($additionalData as $dataKey => $dataValue) {
            $this->modelData[$dataKey] = $dataValue;
        }

    }  
}
