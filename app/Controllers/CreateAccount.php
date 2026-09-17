<?php

namespace App\Controllers;

use App\Models\UserModel;

class CreateAccount extends BaseController
{
    public function createAccount()
    {
        $user_model = new UserModel();

        //getting information entered on the form
        $username = $this->request->getPost("username");
        $password = $this->request->getPost("password");

        //simple validation
        if(empty($username) || empty($password)) {
            return redirect()->to('login')->with('error','username or password fields are empty');
        }

        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $user_model->insert(["username"=> $username,"password_hash"=> $password_hash]);

        return redirect()->to("login")->with("success","account created successfully!");
    }
}