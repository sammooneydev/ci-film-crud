<?php

namespace App\Controllers;

use App\Models\FilmModel;
use App\Models\DirectorModel;
use App\Models\DirectorFilmsModel;

//xAPI module imports
use Modules\xAPI\Lrs\ManualLrs;
use Modules\xAPI\Models\Actor;
use Modules\xAPI\Models\Verb;
use Modules\xAPI\Models\xAPIObject;
use Modules\xAPI\Models\Statement;
use Modules\xAPI\Models\Definition;

class Admin extends BaseController
{
    public function index()
    {
        $is_admin = session()->get('is_admin');

        if ($is_admin == 1) {
            return view("pages/admin-panel");
        } else {
            return redirect()->to(base_url('/'));
        }
    }

    public function createFilm()
    {
        $is_admin = session()->get('is_admin');

        if ($is_admin != 1) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'error' => 'you do not have permission to do this']);
        }

        $film_model = new FilmModel();
        $director_model = new DirectorModel();
        $director_films_model = new DirectorFilmsModel();

        //getting values entered on form
        $film_name = trim($this->request->getPost('film_name'));
        $film_desc = trim($this->request->getPost('film_desc'));
        $director_name = trim($this->request->getPost('director_name'));

        //validating that there are actual values
        if (empty($film_name) || empty($director_name)) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'error' => 'film name and director are required']);
        }

        //establish database connection for transaction
        $db = \Config\Database::connect();

        $db->transStart();

        $film_model->insert([
            'film_name'=> $film_name,
            'film_desc'=> $film_desc
        ]);

        //getting film id from recently added film 
        $film_id = $film_model->getInsertID();

        //check if the director already exists
        $director = $director_model
        ->where('director_name', $director_name)
        ->first();

        if ($director) {
            //director alredy exists
            $director_id = $director['director_id'];
        } else {
            //director doesn't exist
            $director_model->insert([
                'director_name' => $director_name
            ]);

            $director_id = $director_model->getInsertID();
        }

        $director_films_model->insert([
            'film_id' => $film_id,
            'director_id'=> $director_id
        ]);

        $db->transComplete();

        //check if transaction succeeded 
        if ($db->transStatus() === false) {
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'error' => 'film could not be added']);
        }

        //the film has been successfully created
        //return the film information to javascript
        return $this->response->setJSON(['success' => true, 'film_id' => $film_id, 'film_name' => $film_name, 'film_desc' => $film_desc]);
    }

    public function sendFilmXAPI()
    {
        $is_admin = session()->get('is_admin');

        if ($is_admin != 1) { 
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'error' => 'you do not have permission to do this']); 
        }

        //getting film_id sent via js
        $film_id = $this->request->getPost('film_id');

        if(empty($film_id)) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'error' => 'film ID is missing']);
        }

        //getting the film from the db
        $film_model = new FilmModel();
        $film = $film_model->find($film_id);

        if(!$film) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'error' => 'film does not exist']);
        }

        //get the information from the logged in admin
        $username = session()->get('username');
        $email = session()->get('email');

        if (!$username || !$email) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'error' => 'user information is missing']);
        }

        //creating xAPI lrs connection
        $lrs = new ManualLrs();

        $actor = new Actor(
            name: $username,
            mbox: $email
        );

        $verb = new Verb(
            id: 'http://activitystrea.ms/schema/1.0/create',
            display: 'created'
        );

        $object = new xAPIObject(
            id: base_url('film/' . $film['film_id']),
            definition: new Definition(
                name: $film['film_name'],
                description: $film['film_desc'],
                type: 'http://id.tincanapi.com/activitytype/media'
            )
        );

        //assemble xAPI statement
        $statement = new Statement(
            actor: $actor,
            verb: $verb,
            object: $object,
            context: [
                'platform' => 'film diary crud thing',
                'extensions' => [
                    'https://learn.sssc.uk.com/xapi/extensions/tags/url' => base_url('/')
                ]
            ]
        );

        //sending statment to lrs
        try {
            $response = $lrs->sendStatement($statement);
        } catch(\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([ 'success' => false, 'error' => 'film was created but xAPI statement could not be sent' ]);
        }
        
        //checking the lrs response
        if(!$response || !in_array($response->getStatusCode(), [200,204])) {
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'error' => 'the fil was created but xAPI statement could not be sent']);
        }

        return $this->response->setJSON(['success' => true ]);
    }
}