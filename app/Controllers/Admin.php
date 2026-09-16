<?php

namespace App\Controllers;

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
}