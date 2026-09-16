<?php

namespace App\Controllers;

class Logout extends BaseController
{
    public function logout()
    {
        //destroying session to log user out
        session()->destroy();

        return redirect()->to(base_url('/'))->with('success!', 'logged out successfully');
    }
}