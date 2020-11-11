<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// User private channel
Broadcast::channel('fakechat.{slug}', function ($user, $slug) {
    return true;
});

// User conversation channel
Broadcast::channel('fakechat.conversation.{slug}', function ($user, $slug) {
    return true;
});
