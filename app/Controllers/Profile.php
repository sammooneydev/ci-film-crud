<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profile extends BaseController
{
    public function index()
    {
        return view("pages/profile");
    }

    public function update()
    {
        $user_id = session()->get('user_id');

        //making sure user_id is actually there
        if(!$user_id) {
            return redirect()->to('login')->with('error','no user is logged in');
        }

        $username = $this->request->getPost('username');
        $current_password = $this->request->getPost('current_password');
        $new_password = $this->request->getPost('new_password');

        $user_model = new UserModel();

        //getting user to update by using id from signed in user
        $user = $user_model->find($user_id);

        //redirecting user back to login (which contains account creation) if user is not stored in database
        if(!$user) {
            return redirect()->to('login')->with('error','user does not exist');
        }

        //checking if new username is already in use
        if($username !== $user['username']) {

            $existing_user = $user_model ->where('username', $username)->first();

            if($existing_user) {
                return redirect()->back()->withInput()->with('error','that username is already taken');
            }
        }

        $data = [
            'username' => $username
        ];

        //if a new password has been entered, that will be verified first along with their original password
        if(!empty($new_password)) {

            if(empty($current_password)) {
                return redirect()->back()->withInput()->with('error','you must enter your current password to change your password');
            }

            if(!password_verify($current_password, $user['password_hash'])) {
                return redirect()->back()->withInput()->with('error','your current password is incorrect');
            }

            $data['password_hash'] = password_hash($new_password, PASSWORD_BCRYPT);
        }

        $user_model->update($user_id, $data);

        //also updating session username
        session()->set('username', $username);

        return redirect()->to('profile')->with('success','your profile has been updated');
    }
}