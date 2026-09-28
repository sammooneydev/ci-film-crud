<?php

namespace App\Controllers;

class APITest extends BaseController
{
    public function index()
    {
        return view('pages/api-test');
    }
}