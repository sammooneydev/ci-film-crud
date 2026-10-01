<?php

namespace App\Controllers;

//passing in models from modules directory
use Modules\xAPI\Lrs\ManualLrs;
use Modules\xAPI\Models\Actor;
use Modules\xAPI\Models\Verb;
use Modules\xAPI\Models\xAPIObject;
use Modules\xAPI\Models\Statement;
use Modules\xAPI\Models\Definition;

class APITest extends BaseController
{
    public function index()
    {
        return view('pages/api-test');
    }

    public function prePrepared()
    {
        //declaring $lrs variable for use in statement
        $lrs = new ManualLrs();

        //using this function to pull in default actor details
        $actor = $lrs->getActorDetails();

        $verb = new Verb(
            id: 'http://id.tincanapi.com/verb/viewed',
            display:'viewed',
        );

        $object = new xAPIObject(
            id: base_url('api-test'),
            definition: new Definition(
                name: 'crud app xAPI test'
            )
        );

        $statement = new Statement(
            actor: $actor,
            verb: $verb,
            object: $object
        );

        //getting response from sent statement
        $response = $lrs->sendStatement($statement);

        if ($response && ($response->getStatusCode() === 200 || $response->getStatusCode() === 204)) {
            return redirect()->to(base_url('api-test'))->with('success', 'xAPI statement sent successfully.');
        }

        return redirect()->to(base_url('api-test'))->with('error', 'Failed to send xAPI statement.');
    }

    public function prepareStatement()
    {
        //declaring $lrs variable for use in statement
        $lrs = new ManualLrs();

        if(!session()->get('username')) {
            $username = $this->request->getPost('username');
        }
        else {
            $username = session()->get('username');
        }

        if (!session()->get('email')) {
            $email = $this->request->getPost('email');
        }
        else {
            $email = session()->get('email');
        }

        $selected_verb = $this->request->getPost('verb');

        if($selected_verb == 'Viewed') {
            $uri = "http://id.tincanapi.com/verb/viewed";
        }
        else if($selected_verb == 'Accessed') {
            $uri = 'http://activitystrea.ms/schema/1.0/access';
        }
        else if($selected_verb == 'Completed') {
            $uri = 'http://activitystrea.ms/schema/1.0/complete';
        }
        else if($selected_verb == 'Created') {
            $uri = 'http://activitystrea.ms/schema/1.0/create';
        }
        else if($selected_verb == 'Found') {
            $uri = 'http://activitystrea.ms/schema/1.0/find';
        }
        else if($selected_verb == 'Interacted') {
            $uri = 'http://activitystrea.ms/schema/1.0/interact';
        }
        else if($selected_verb == 'Opened') {
            $uri = 'http://activitystrea.ms/schema/1.0/open';
        }
        else if($selected_verb == 'Started') {
            $uri = 'http://activitystrea.ms/schema/1.0/start';
        }
        else {
            return redirect()->to(base_url('api-test'))->with('error','invalid verb entered');
        }

        $activity_id = $this->request->getPost('object');
        $activity_name = $this->request->getPost('activity-name');

        $actor = new Actor(
            name : $username,
            mbox : $email,
        );

        $verb = new Verb(
            id: $uri,
            display: $selected_verb,
        );

        $object = new xAPIObject(
            id: $activity_id,
            definition : new Definition(
                name: $activity_name,
            )
        );

        //building statement
        $statement = new Statement(
            actor: $actor,
            verb: $verb,
            object: $object
        );

        //getting response from sent statement
        $response = $lrs->sendStatement($statement);

        if ($response && ($response->getStatusCode() === 200 || $response->getStatusCode() === 204)) {
            return redirect()->to(base_url('api-test'))->with('success', 'xAPI statement sent successfully.');
        }

        return redirect()->to(base_url('api-test'))->with('error', 'Failed to send xAPI statement.');
    }
}