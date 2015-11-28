<?php

namespace App\Ajax;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use MysqliDb as DB;

class Keywords
{

    private $request;
    private $response;
    private $db;
    private $postArray      = [];
    private $error          = 0;
    private $message        = '';
    private $currentProjectsParentID;
    private $competitorList = [];

    public function __construct(Request $request, Response $response, DB $db)
    {

        $this->request  = $request;
        $this->response = $response;
        $this->db       = $db;


        if(!$request->withMethod('POST')) {
            die('Not allowed');
        }

    }

    public function remove()
    {
        $kID = $this->request->getParsedBody()['keywordID'];

        if($kID > 0) {
            $this->db->where('keywordID', $kID);

            if(!$this->db->delete('keywords')) {
                $this->error   = 1;
                $this->message = '<strong>Verdammt!</strong> Das Keyword konnte nicht korrekt gelöscht werden.';
            }
            else {
                $this->message   = '<strong>Alles klar!</strong> Das Keyword wurde nun inklusive aller Rankings für alle Mitbewerber gelöscht.';
                $reorderKeywords = new \App\ReorderKeywords($this->db);
                $reorderKeywords->start();
            }
        }

        $this->finish();

    }

    public function add()
    {

        $this->extractKeywords();
        if($this->currentProjectsParentID > 0) {
            $this->loadCompetitors();
            $this->saveKeywords();
            
            $reorderKeywords = new \App\ReorderKeywords($this->db);
            $reorderKeywords->start();
            
            $this->message   = '<strong>Super!</strong> Alle Keywords wurden dem gewählten Projekt hinzugefügt und sollten nach einem Neuladen der Projektübersicht angezeigt werden.';
        }
        else {
            $this->error   = 1;
            $this->message = '<strong>Mist!</strong> Da scheint sich ein Fehler eingeschlichen zu haben. Vermutlich ist kein Projekt aktiv gesetzt. Schau da am besten nochmal nach!';
        }


        $this->finish();

    }

    private function loadCompetitors()
    {
        $this->db->where('parentProjectID', $this->currentProjectsParentID);
        $this->competitorList = $this->db->get('projects', NULL, 'projectID');

    }

    private function saveKeywords()
    {

        foreach ($this->keywords as $kwKey => $kwText) {
            if($kwText != '' && $kwText != "\n" && $kwText != "\r") {
                $newKeywordID = $this->db->insert('keywords', [
                    'keywordName'     => trim($kwText, " \t\n\r\0\x0B"),
                    'parentProjectID' => $this->currentProjectsParentID,
                ]);
//                Dont need this anymore
//                foreach ($this->competitorList as $compKey => $compVal) {
//                    $this->saveNullRankingFor($compVal['projectID'], $newKeywordID);
//                }
            }
        }

    }

    private function saveNullRankingFor($projectID, $keywordID)
    {
        $this->db->insert('rankings', [
            'keywordID'       => $keywordID,
            'projectID'       => $projectID,
            'rankingAdded'    => '0000-00-00 00:00:00',
            'rankingAddedDay' => '0000-00-00'
        ]);

    }

    private function extractKeywords()
    {

        
        $kwDataString = $this->request->getParsedBody()['add'];
        $kwDataString = strtolower(urldecode($kwDataString));

        $temp           = explode('&keywords=', $kwDataString);
        $temp[1]        = trim($temp[1]);
        $this->keywords = explode("\n", $temp[1]);


        $temp                          = $temp[0];
        $temp                          = explode('=', $temp);
        $this->currentProjectsParentID = $temp[1];

    }

    private function finish()
    {

        $body = $this->response->withHeader('Content-type', 'application/json');
        $body->write(
                json_encode(
                        [
                            'error'   => $this->error,
                            'message' => $this->message
                        ]
                )
        );

    }

}
