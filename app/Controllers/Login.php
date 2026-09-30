<?php

namespace App\Controllers;

use App\Models\UserModel;

Class Login extends BaseController
{
    public function index()
    {
        return view('pages/login');
    }

    public function login()
    {
        //getting info entered on login form
        $login = $this->request->getPost('login');
        $password = $this->request->getPost('password');

        $user_model = new UserModel();

        //getting first record in database with matching username or email
        $user = $user_model ->groupStart()->where('username', $login)->orWhere('email', $login)->groupEnd()->first();

        //verifying that user exists and that the password matches the stored hash
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return redirect()->to(base_url('login'))->with('error','incorrect username, email, or password');
        }

        //regenerating session to avoid old sessions interfering
        session()->regenerate();

        session()->set([
            'user_id' => $user['user_id'],
            'username'=> $user['username'],
            'email' => $user['email'],
            'is_admin'=> $user['is_admin'],
            'logged_in' => true
        ]); 

        return redirect()->to(base_url('/'))->with('success','logged in successfully!');
    }
}