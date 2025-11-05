<?php

use Illuminate\Support\Facades\Route;
use Modules\Friends\Http\Controllers\FriendsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('friends', FriendsController::class)->names('friends');
});
