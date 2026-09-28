<?php

namespace App\Controllers;

//passing in models from modules directory
use Modules\xAPI\Lrs\ManualLrs;
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
}