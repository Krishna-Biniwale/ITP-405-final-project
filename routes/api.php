<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController as ApiEventController;

Route::get('ping', function () {
    return ['message' => 'pong'];
});
Route::apiResource('events', ApiEventController::class);