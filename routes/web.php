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

Route::get('/home', 'HomeController@index')->name('home');
