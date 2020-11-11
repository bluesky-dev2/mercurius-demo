<?php

namespace App\Http\Controllers\Mercurius;

use Illuminate\Http\Request;
use Launcher\Mercurius\Facades\Mercurius;
use App\Repositories\MessageRepository;
use App\Repositories\UserRepository;

class MessagesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Send a message from the current user.
     *
     * @param MessageRepository $repo
     * @param Request           $request
     *
     * @return array
     */
    public function send($userSlug, Request $request, MessageRepository $msg, UserRepository $user)
    {
        $request->validate([
            'recipient' => 'required|string',
            'message'   => 'required|string',
        ]);
        $from = $user->find($userSlug);
        $receiver = $user->find($request->recipient);
        $message = $request->message;

        return response($msg->send($from, $receiver, $message));
    }

    /**
     * Delete message for the current user.
     *
     * @param int               $message
     * @param MessageRepository $repo
     * @param Request           $request
     *
     * @return array
     */
    public function destroy($userSlug = null, $message, MessageRepository $repo, Request $request, UserRepository $user)
    {
        $msg = Mercurius::model('message')->findOrFail($message);
        $userId = $user->find($userSlug)->id;
        return $repo->delete($msg, $userId);
    }
}
