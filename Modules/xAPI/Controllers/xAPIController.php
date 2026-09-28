<?php

namespace Modules\xAPI\Controllers;

use App\Controllers\BaseController;
use Modules\xAPI\Lrs\ManualLrs;
use Modules\xAPI\Models\Actor;
use Modules\xAPI\Models\Verb;
use Modules\xAPI\Models\xAPIObject;
use Modules\xAPI\Models\Statement;
use Modules\xAPI\Models\Definition;

class xAPIController extends BaseController
{

    /**
     * Sends a custom xAPI statement by accepting parameters using JSON payload, 
     * POST, or GET query strings, with sensible fallbacks.
     */
    public function sendCustomStatement()
    {
        // Tries to get JSON that is passed in in case the endpoint recieves a JSON body instead of POST data. 
        // Falls back to general input which I think could be form data like we have on the current ML platform.
        $input = $this->request->getJSON(true) ?? $this->request->getVar();

        // Extract params or fall back to defaults. Defaults are still in from testing so feel free to bin these unless you feel any suit your needs.
        $verbId = $input['verb_id'] ?? 'http://adlnet.gov/expapi/verbs/experienced';
        $verbDisplay = $input['verb_display'] ?? 'experienced';
        $activityId = $input['activity_id'] ?? base_url('dashboard');
        $activityName = $input['activity_name'] ?? 'MyLearning CodeIgnitor xAPI test!';

        // this tries to get user auth but if you want something 100% anon, then commend these out and use the block below... 
        // Not added anonymouse flag in my LRS config yet.
        $lrs = new ManualLrs();

        $actor = $lrs->getActorDetails();

        $verb = new Verb(
            id: $verbId,
            display: $verbDisplay
        );

        $object = new xAPIObject(
            id: $activityId,
            definition: new Definition(
                name: $activityName
            )
        );

        $statement = new Statement(
            actor: $actor,
            verb: $verb,
            object: $object
        );

        $response = $lrs->sendStatement($statement);

        if ($response && ($response->getStatusCode() === 200 || $response->getStatusCode() === 204)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'xAPI statement sent successfully!',
                'data' => [
                    'actor' => $actor->name,
                    'verb' => $verbDisplay,
                    'activity' => $activityName
                ]
            ]);
        }

        $statusCode = $response ? $response->getStatusCode() : 500;
        $body = $response ? $response->getBody() : 'No response generated';

        return $this->response->setStatusCode($statusCode)->setJSON([
            'status' => 'error',
            'message' => 'Failed to send xAPI statement.',
            'lrs_code' => $statusCode,
            'lrs_response' => $body
        ]);
    }

    // moved helper INSIDE the LRS as it already has the flags for anon stats.... Try that helper for now.
    // function getActorData(ManualLrs $lrs): Actor
    // {
    //     // default for anon...
    //     $name = 'A learner';
    //     $email = 'learner@sssc.uk.com';

    //     // ONLY check user credentials if anonymity is NOT being forced
    //     if (!$lrs->isForcingAnonStats()) {
    //         $userId = auth()->id();
    //         if ($userId) {
    //             $profile = (new \App\Models\UserProfileModel())->find($userId);
    //             $name = $profile['fullname'] ?? auth()->user()->username ?? 'A learner';
    //             $email = auth()->user()->email ?? 'learner@sssc.uk.com';
    //         }
    //     }
    //     $actor = new Actor(name: $name, mbox: $email);
    //     return $actor;
    // }

    public function testStatement()
    {
        $lrs = new ManualLrs();

        $actor = $lrs->getActorDetails();

        $verb = new Verb(
            id: 'http://adlnet.gov/expapi/verbs/experienced',
            display: 'experienced'
        );

        $object = new xAPIObject(
            id: base_url('dashboard'),
            definition: new Definition(
                name: 'MyLearning CodeIgnitor xAPI test!'
            )
        );

        $statement = new Statement(
            actor: $actor,
            verb: $verb,
            object: $object
        );

        $response = $lrs->sendStatement($statement);

        if ($response && ($response->getStatusCode() === 200 || $response->getStatusCode() === 204)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'xAPI statement sent successfully!'
            ]);
        }

        $statusCode = $response ? $response->getStatusCode() : 500;
        $body = $response ? $response->getBody() : 'No response generated';

        return $this->response->setStatusCode($statusCode)->setJSON([
            'status' => 'error',
            'message' => 'Failed to send xAPI statement.',
            'lrs_code' => $statusCode,
            'lrs_response' => $body
        ]);
    }
}