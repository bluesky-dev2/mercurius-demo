<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Validator;


class SlackInvitationController extends Controller
{
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     *  Shows the form page.
     */
    public function slackPage()
    {
        return view('slack-invitation');
    }

    /**
     *  Joined Slack page.
     */
    public function slackJoined()
    {
        return view('slack-joined');
    }

    /**
     *  Send Slack Invitation.
     */
    public function sendInvitation(Request $request)
    {
        try {
            $validator= Validator::make($request->all(), ['email'=>'required|email']);

            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Please enter a valid e-mail.');
            }

            $email = $request->input('email');

            $_url = env('SLACK_TEAM_URL').'/api/users.admin.invite?t='.time();

            $res = $this->client->request('POST', $_url, [
                'form_params' => [
                    'token'      => env('SLACK_API_TOKEN'),
                    'email'      => $email,
                    'set_active' => true,
                    '_attempts'  => 1,
                ]
            ]);

            return redirect()->route('slack-joined');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
