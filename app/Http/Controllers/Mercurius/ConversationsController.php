<?php

namespace App\Http\Controllers\Mercurius;

use Illuminate\Http\Request;
use Launcher\Mercurius\Repositories\ConversationRepository;
use Launcher\Mercurius\Repositories\UserRepository;

class ConversationsController extends Controller
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
     * Display a list of conversations for the home chat.
     *
     * @param ConversationRepository $conversation
     *
     * @return \Illuminate\Http\Response
     */
    public function index($userSlug, ConversationRepository $conversation, UserRepository $user)
    {
        $sender = $user->find($userSlug)->id;
        return response($conversation->all($sender));
    }

    /**
     * Display a single conversation for a given user.
     *
     * @param string                 $recipient
     * @param ConversationRepository $conversation
     * @param Request                $request
     *
     * @return \Illuminate\Http\Response
     */
    public function show($userSlug, $recipient, Request $request, ConversationRepository $conversation, UserRepository $user)
    {
        $request->validate([
            'offset'   => 'required|numeric',
            'pagesize' => 'required|numeric',
        ]);
        $recipient = $user->find($recipient)->id;
        $sender = $user->find($userSlug)->id;
        return response(
            $conversation->get($recipient, $request->offset, $request->pagesize, $sender)
        );
    }

    /**
     * Remove a conversation.
     *
     * @param string                 $recipient
     * @param ConversationRepository $conversation
     * @param Request                $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($userSlug, $recipient, Request $request, ConversationRepository $conversation, UserRepository $user)
    {
        $owner = $user->find($userSlug)->id;
        $recipient = $user->find($recipient)->id;

        return response($conversation->delete($owner, $recipient));
    }
}
