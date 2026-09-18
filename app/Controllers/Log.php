<?php

namespace App\Controllers;

use App\Models\FilmModel;
use App\Models\DiaryEntryModel;

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
        if(!session()->get('user_id')) {
            return redirect()->to(base_url('login'));   
        }

        $film_id = $this->request->getPost('film_id');
        $review = trim($this->request->getPost('entry_text'));
        $score = $this->request->getPost('score');

        if(empty($film_id)) {
            return redirect()->to(base_url('log'))->with('error', 'please select a film and enter a review');
        }

        if($score !== '' && ($score < 0 || $score > 10)) {
            return redirect()->to(base_url('log'))->with('error', 'score must be between 0 and 10');
        }

        $diary_entry_model = new DiaryEntryModel();

        $diary_entry_model->insert([
            'entry_text' => $review,
            'score' => $score == '' ? null : $score,
            'date_posted' => date('Y-m-d H:i:s'),
            'user_id' => session()->get('user_id'),
            'film_id' => $film_id
        ]);

        return redirect()->to(base_url('profile'))->with('success','your review has been logged.');
    }
}