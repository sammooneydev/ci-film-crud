<?php

namespace App\Controllers;

use App\Models\FilmModel;
use App\Models\DirectorModel;
use App\Models\DirectorFilmsModel;

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
            return redirect()->to(base_url('/'));
        }

        $film_model = new FilmModel();
        $director_model = new DirectorModel();
        $director_films_model = new DirectorFilmsModel();

        //getting values entered on form
        $film_name = $this->request->getPost('film_name');
        $film_desc = $this->request->getPost('film_desc');
        $director_name = $this->request->getPost('director_name');

        //validating that there are actual values
        if (empty($film_name) || empty($director_name)) {
            return redirect()->to(base_url('admin-panel'))->with('error', 'Film name and director are required.');
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

        if ($db->transStatus() === false) {
            return redirect()->to(base_url('admin-panel'))->with('error', 'Film could not be added.');
        }

        return redirect()->to(base_url('admin-panel'))->with('success', 'Film added successfully.');
    }
}