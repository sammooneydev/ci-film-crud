<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;

class Pages extends BaseController
{
    public function index()
    {
        return view('home');
    }

    public function view(string $page = 'home')
    {
        if (! is_file(APPPATH . 'Views/pages/' . $page .'.php')) {
            //throwing exception if no page exists
            throw new PageNotFoundException($page . ' page does not exist');
        }

        $data['title'] = ucfirst($page); //capitalising first letter in title

        return view('pages/'. $page, $data);
    }
}