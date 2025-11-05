<?php

use Illuminate\Support\Facades\Route;
use Modules\Friends\Http\Controllers\FriendsController;


Route::group(
    [
        'as' => 'v1.',
        'prefix' => 'v1',
    ],
    function () {
        Route::group(
            [
                'middleware' => ['auth:sanctum', 'setlocale']
            ],
            function () {
                Route::group([
                    'as' => 'friendships.',
                    'prefix' => 'friendships'
                ], function () {
                    Route::post('/{id}/send', [\App\Http\Controllers\Api\FriendshipController::class, 'send'])->name('send');
                    Route::post('/{id}/accept', [\App\Http\Controllers\Api\FriendshipController::class, 'accept'])->name('accept');
                    Route::delete('/{id}/decline', [\App\Http\Controllers\Api\FriendshipController::class, 'decline'])->name('decline');
                    Route::delete('/{id}/remove', [\App\Http\Controllers\Api\FriendshipController::class, 'remove'])->name('remove');
                    Route::post('/{id}/block', [\App\Http\Controllers\Api\FriendshipController::class, 'block'])->name('block');
                    Route::post('/{id}/unlock', [\App\Http\Controllers\Api\FriendshipController::class, 'unlock'])->name('unlock');
                    Route::get('/', [\App\Http\Controllers\Api\FriendshipController::class, 'listFriends']);
                    Route::get('/pending', [\App\Http\Controllers\Api\FriendshipController::class, 'listPending']);
                    Route::get('/blocked', [\App\Http\Controllers\Api\FriendshipController::class, 'listBlocked']);
                });
            }
        );
    }
);
