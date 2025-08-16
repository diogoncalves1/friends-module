<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'as' => 'api.',
        'prefix' => 'api/',
        // 'middleware' => 'auth'
    ],
    function () {

        Route::group([
            'as' => 'friendships.',
            'prefix' => 'friendships'
        ], function () {
            Route::post('/send/{id}', [\App\Http\Controllers\Api\FriendshipController::class, 'send']);
            Route::post('/{id}/accept', [\App\Http\Controllers\Api\FriendshipController::class, 'accept'])->name('accept');
            Route::post('/{id}/decline', [\App\Http\Controllers\Api\FriendshipController::class, 'decline']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\FriendshipController::class, 'remove']);
            Route::post('/{id}/block', [\App\Http\Controllers\Api\FriendshipController::class, 'block']);
            Route::get('/', [\App\Http\Controllers\Api\FriendshipController::class, 'listFriends']);
            Route::get('/pending', [\App\Http\Controllers\Api\FriendshipController::class, 'listPending']);
        });
    }
);

Route::group([
    'as' => 'friendships.',
    'prefix' => 'friendships',
    // 'middleware' => ['auth']
], function () {
    Route::get('/', [\App\Http\Controllers\FriendshipController::class, 'index'])->name('index');
    Route::get('/pending', [\App\Http\Controllers\FriendshipController::class, 'pending'])->name('pending');
});
