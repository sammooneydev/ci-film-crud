<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profile extends BaseController
{
    public function index()
    {
        if(session()->get('logged_in') != true) {
            return redirect()->to(base_url('login'))->with('error','no user is logged in');
        }

        $user_id = session()->get('user_id');

        //connecting to database
        $db = \Config\Database::connect();

        //performing database query to get all of the logged in user's reviews
        $reviews = $db->table('diary_entry')
        ->select('diary_entry.*, film.film_name')
        ->join('film', 'film.film_id = diary_entry.film_id')
        ->where('diary_entry.user_id', $user_id)
        ->orderBy('diary_entry.date_posted', 'DESC')
        ->get()
        ->getResultArray();

        return view("pages/profile", ['reviews' => $reviews]);
    }

    public function update()
    {
        $user_id = session()->get('user_id');

        //making sure user_id is actually there
        if(!$user_id) {
            return redirect()->to(base_url('login'))->with('error','no user is logged in');
        }

        $username = $this->request->getPost('username');
        $current_password = $this->request->getPost('current_password');
        $new_password = $this->request->getPost('new_password');

        $user_model = new UserModel();

        //getting user to update by using id from signed in user
        $user = $user_model->find($user_id);

        //redirecting user back to login (which contains account creation) if user is not stored in database
        if(!$user) {
            return redirect()->to(base_url('login'))->with('error','user does not exist');
        }

        //checking if new username is already in use
        if($username !== $user['username']) {

            $existing_user = $user_model ->where('username', $username)->first();

            if($existing_user) {
                return redirect()->to(base_url('profile'))->withInput()->with('error','that username is already taken');
            }
        }

        $data = [
            'username' => $username
        ];

        //if a new password has been entered, that will be verified first along with their original password
        if(!empty($new_password)) {

            if(empty($current_password)) {
                return redirect()->to(base_url('profile'))->withInput()->with('error','you must enter your current password to change your password');
            }

            if(!password_verify($current_password, $user['password_hash'])) {
                return redirect()->to(base_url('profile'))->withInput()->with('error','your current password is incorrect');
            }

            $data['password_hash'] = password_hash($new_password, PASSWORD_BCRYPT);
        }

        $user_model->update($user_id, $data);

        //also updating session username
        session()->set('username', $username);

        return redirect()->to(base_url('profile'))->with('success','your profile has been updated');
    }

    public function delete()
    {
        $user_id = session()->get('user_id');

        if(!$user_id) {
            return redirect()->to(base_url('login'))->with('error','no user logged in');
        }

        $user_model = new UserModel();

        $user_model->delete($user_id);

        //destroying session after deleting user
        session()->destroy();

        return redirect()->to(base_url('home'))->with('success','your account has been successfully deleted');
    }
}