<?php

use Illuminate\Support\Facades\Broadcast;

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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('Game.{game_id}', function ($user, $game_id) {
    return true;
});

Broadcast::channel('GameResult.{game_id}', function ($user, $game_id) {
    return true;
});

Broadcast::channel('User.Update.{user_id}', function ($user, $user_id) {
    return true;
});
