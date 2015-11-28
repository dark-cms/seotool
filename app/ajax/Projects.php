<?php

namespace App\Ajax;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use MysqliDb as DB;

class Projects
{

    private $request;
    private $response;
    private $db;
    private $session;
    private $error         = 0;
    private $mainProjectID = 0;
    private $message       = '';
    private $postArray     = [];

    public function __construct(Request $request, Response $response, DB $db)
    {

        $this->request  = $request;
        $this->response = $response;
        $this->db       = $db;
        $this->session  = new \RKA\Session();

        if(!$request->withMethod('POST')) {
            die('Not allowed');
        }

    }

    public function add()
    {

        $this->extractPostDataFromParsedBody('add');
        $this->validateUrls();

        $this->isMainProjectSet();

        if($this->error == 0) {
            $this->saveProject();
            $this->saveCompetition();
        }

        $this->finish();

    }

    public function update()
    {

        $this->extractPostDataFromParsedBody('update');
        $this->validateUrls();
        $this->updateProject();
        $this->finish();

    }

    public function remove()
    {

        $pID = $this->request->getParsedBody()['projectID'];

        if($pID > 0) {
            $this->db->where('projectID', $pID);

            if(!$this->db->delete('projects')) {
                $this->error = 1;
            }
            else {
                $reorderKeywords = new \App\ReorderKeywords($this->db);
                $reorderKeywords->start();
            }
        }

        $this->checkProjectData();
        $this->finish();

    }

    private function updateProject()
    {
        foreach ($this->postArray as $k => $url) {
            $typeParsed = explode('_', $k);
            $type       = $typeParsed[0];
            $id         = $typeParsed[1];

            switch ($type) {
                case 'project':

                    if($url == '') {
                        $this->error   = 1;
                        $this->message = '<strong>Sorry!</strong> Die URL des Hauptprojektes darf nicht leer sein!';
                    }
                    else {
                        $this->mainProjectID = $id;
                        $this->db->where('projectID', $id);
                        $this->db->update('projects', [
                            'projectURL' => $url,
                        ]);
                    }

                    break;
                case 'comp':

                    if($url == '') {
                        $this->error   = 1;
                        $this->message = '<strong>Sorry!</strong> Lösch den Konkurrenten über den Löschen-Button. Eine leere URL ist hier nicht zulässig!';
                    }
                    else {
                        $this->db->where('projectID', $id);
                        $this->db->update('projects', [
                            'projectURL' => $url,
                        ]);
                    }

                    break;
                case 'new':
                    if($url != '') {
                        $this->db->insert('projects', [
                            'parentProjectID' => $this->mainProjectID,
                            'projectURL'      => $url,
                        ]);
                    }
                    break;
            }
        }

        if($this->error == 0) {
            $this->message = '<strong>Sehr gut!</strong> Du hast das Projekt erfolgreich aktualisiert. Änderungen siehst du nach dem Neuladen der Website!';
        }

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

    private function saveProject()
    {

        $this->mainProjectID = $this->db->insert('projects', [
            'projectIsParent' => '1',
            'projectURL'      => $this->postArray['project'],
        ]);


        if($this->mainProjectID == 0) {
            $this->error   = 1;
            $this->message = '<strong>Verdammt!</strong> Beim Eintragen des Hauptprojektes "' . $this->postArray['project'] . '" ist ein Fehler aufgetreten. Bitte versuch es noch einmal und schließ andere Fehler aus!';
        }
        else {

            $this->db->where('projectID', $this->mainProjectID);
            $this->db->update('projects', [
                'parentProjectID' => $this->mainProjectID
            ]);

            $this->message = '<strong>Super!</strong> "' . $this->postArray['project'] . '" samt Konkurrenz, sofern eingetragen, wurde erfolgreich angelegt. Du kannst loslegen!';
        }

    }

    private function saveCompetition()
    {

        if($this->error == 0) {

            unset($this->postArray['project']);

            foreach ($this->postArray as $competitionKey => $competitionURL) {
                if($competitionURL != '') {
                    $this->db->insert('projects', [
                        'parentProjectID' => $this->mainProjectID,
                        'projectURL'      => $competitionURL,
                    ]);
                }
            }
        }

    }

    private function extractPostDataFromParsedBody($jqeryPostName)
    {

        $postDataExploded = [];
        $p                = $this->request->getParsedBody()[$jqeryPostName];
        $postData         = explode('&', urldecode($p));

        foreach ($postData as $dKey => $dVal) {
            $pTemp                       = explode('=', $dVal);
            $postDataExploded[$pTemp[0]] = $pTemp[1];
        }

        $this->postArray = $postDataExploded;

    }

    private function validateUrls()
    {

        $notallowed = [
            '#',
            "'",
            '"',
        ];

        foreach ($this->postArray as $urlKey => $url) {
            $parsedURL = parse_url($this->postArray[$urlKey]);
            if( isset($parsedURL['host']) && $parsedURL['host'] != '') {
                $this->postArray[$urlKey] = $parsedURL['host'];
            }
            else if( isset($parsedURL['path']) && $parsedURL['path'] != '') {
                $this->postArray[$urlKey] = $parsedURL['path'];
            } 

            $tempURL                  = explode('/', $this->postArray[$urlKey]);
            $this->postArray[$urlKey] = $tempURL[0];
            $this->postArray[$urlKey] = str_ireplace($notallowed, '', $this->postArray[$urlKey]);

            if(!isset($parsedURL['scheme'])) {
                $parsedURL['scheme'] = 'http';
            }

            if($this->postArray[$urlKey] != '') {
                $this->postArray[$urlKey] = $parsedURL['scheme'] . '://' . $this->postArray[$urlKey];
            }
        }

    }

    private function isMainProjectSet()
    {

        if($this->postArray['project'] == '') {
            $this->error   = 1;
            $this->message = 'Du hast einen Fehler beim Eintragen des Hauptprojektes gemacht. Stimmt die URL? Hast du sie ggf. vergessen?';
        }

    }

    private function checkProjectData()
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

    }

    private function saveProjectData($dataSrc)
    {

        $this->session->set('currentProject', $dataSrc['projectID']);

    }

}
