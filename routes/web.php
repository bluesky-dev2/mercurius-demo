<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/join-slack-launcher-host', ['uses'=>'SlackInvitationController@slackPage'])->name('slack-invitation');
Route::post('/join-slack-launcher-host',['uses'=>'SlackInvitationController@sendInvitation']);
Route::get('/joined-slack-launcher-host', ['uses'=>'SlackInvitationController@slackJoined'])->name('slack-joined');

Auth::routes();

Route::get('/usermessages', 'HomeController@userMessages');


Route::group([
    'as'         => 'mercurius.',
    'namespace'  => '\App\Http\Controllers\Mercurius',
    'middleware' => [
        // 'Mercurius',
        'web',
        'auth',
    ],
], function () {

    // Mercurius home
    Route::get('/{userSlug}/messages', function ($userSlug = null) {
        return View('mercurius::mercurius', [ 'userSlug' => $userSlug]);
    })->name('home');

    // User Profile
    Route::get('/{userSlug}/profile/refresh', 'ProfileController@refresh');
    Route::get('/{userSlug}/profile/notifications', 'ProfileController@notifications');
    Route::post('/{userSlug}/profile', 'ProfileController@update');

    // Conversations
    Route::get('/{userSlug}/conversations', 'ConversationsController@index');
    Route::post('/{userSlug}/conversations/{receiver}', 'ConversationsController@show');
    Route::delete('/{userSlug}/conversations/{receiver}', 'ConversationsController@destroy');

    // Messages
    Route::post('/{userSlug}/messages', 'MessagesController@send');
    Route::delete('/{userSlug}/messages/{id}', 'MessagesController@destroy');

    // Find Receivers
    Route::post('/{userSlug}/receivers', 'ReceiversController@search');

    // Dummy page example
    Route::get('/notification-page-sample', function () {
        return View('mercurius::example');
    })->name('example');
});
