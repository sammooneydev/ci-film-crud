<?php

namespace App\Controllers;

use App\Models\FilmModel;
use App\Models\DiaryEntryModel;

//xAPI module imports
use Modules\xAPI\Lrs\ManualLrs;
use Modules\xAPI\Models\Actor;
use Modules\xAPI\Models\Verb;
use Modules\xAPI\Models\xAPIObject;
use Modules\xAPI\Models\Statement;
use Modules\xAPI\Models\Definition;

class Log extends BaseController
{
    public function index()
    {
        if(!session()->get('user_id')) {
            return redirect()->to(base_url('login'));
        }

        $film_model = new FilmModel();

        //getting all of the films currently stored in the database and sending them to the view
        $data = [
            'films' => $film_model->findAll(),
        ];

        return view("pages/log", $data);
    }

    public function create()
{
    if (!session()->get('user_id')) {
        return $this->response->setStatusCode(401)->setJSON(['success' => false,'error' => 'You must be logged in.']);
    }

    $film_id = $this->request->getPost('film_id');
    $review = trim($this->request->getPost('entry_text'));
    $score = $this->request->getPost('score');

    if (empty($film_id)) {
        return $this->response->setStatusCode(400)->setJSON(['success' => false,'error' => 'Please select a film.']);
    }

    if ($score !== '' && ($score < 0 || $score > 10)) {
        return $this->response->setStatusCode(400)->setJSON(['success' => false,'error' => 'Score must be between 0 and 10.']);
    }

    $diary_entry_model = new DiaryEntryModel();

    $diary_entry_model->insert([
        'entry_text' => $review,
        'score' => $score == '' ? null : $score,
        'date_posted' => date('Y-m-d H:i:s'),
        'user_id' => session()->get('user_id'),
        'film_id' => $film_id
    ]);

    //getting the film from the database
    $film_model = new FilmModel();
    $film = $film_model->find($film_id);

    if (!$film) {
        return $this->response->setStatusCode(404)->setJSON(['success' => false,'error' => 'Film does not exist.']);
    }

    //getting information from logged in user
    $username = session()->get('username');
    $email = session()->get('email');

    if (!$username || !$email) {
        return $this->response->setStatusCode(400)->setJSON(['success' => false,'error' => 'User information is missing.']);
    }

    //declaring $lrs variable
    $lrs = new ManualLrs();

    $actor = new Actor(
        name: $username,
        mbox: $email
    );

    $verb = new Verb(
        id: 'http://id.tincanapi.com/verb/reviewed',
        display: 'reviewed'
    );

    $object = new xAPIObject(
        id: base_url('film/' . $film['film_id']),
        definition: new Definition(
            name: $film['film_name'],
            description: $review,
            type: 'http://id.tincanapi.com/activitytype/media'
        )
    );

    //assembling the complete xAPI statement
    $statement = new Statement(
        actor: $actor,
        verb: $verb,
        object: $object,
        context: [
            'platform' => 'film diary crud thing',
            'extensions' => [
                'https://learn.sssc.uk.com/xapi/extensions/tags/url'
                    => base_url('film/' . $film['film_id'])
            ]
        ]
    );

    //getting response after sending statement to lrs
    $response = $lrs->sendStatement($statement);

    if (!$response || !in_array($response->getStatusCode(), [200, 204])) {
        return $this->response->setStatusCode(500)->setJSON(['success' => false,'error' => 'The review was saved, but the xAPI statement could not be sent.']);
    }

    return $this->response->setJSON([
        'success' => true
    ]);
}
}