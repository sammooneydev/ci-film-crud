<?php

namespace App\Controllers;

Class Login extends BaseController
{
    public function index()
    {
        return view('pages/login');
    }

    public function login()
    {
        dd('form worked');
    }
}