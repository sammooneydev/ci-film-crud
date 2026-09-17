<?php

namespace App\Controllers;

class Profile extends BaseController
{
    public function index()
    {
        return view("pages/profile");
    }
}