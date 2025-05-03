<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;

Route::middleware(['guest'])->group(function () {
    Route::get('/register', [RegistrationController::class, 'index'])->name('registration.index');
    Route::post('/register', [RegistrationController::class, 'register'])->name('registration.create');
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::fallback(function () {
        return redirect()->route('login');
    });
});
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/events/create', [EventController::class, 'eventForm'])->name('eventCreateForm');
    Route::post('/events/create', [EventController::class, 'create'])->name('event.create');
    Route::fallback(function () {
        return redirect()->route('profile.index');
    });
    Route::get('/events/{id}/edit', [EventController::class, 'editForm'])->name('event.edit');
    Route::put('/events/{id}/edit', [EventController::class, 'update'])->name('event.update');
    Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('event.destroy');
    Route::get('/user/events', [EventController::class, 'userEvents'])->name('event.user');
    Route::get('/user/events/bookmark', [EventController::class, 'bookmark'])->name('event.bookmark');
    Route::post('/events/{eventId}/bookmark', [EventController::class, 'joinEvent'])->name('event.join');
    Route::delete('/events/{eventId}/bookmark', [EventController::class, 'leaveEvent'])->name('event.leave');
    Route::delete('/events/{eventId}/users/{userId}', [EventController::class, 'removeUser'])->name('event.removeUser');
    Route::post('/comments/{id}', [EventController::class, 'updateComment'])->name('event.updateComment');
});

Route::get('/events', [EventController::class, 'index'])->name('event.index');
Route::get('/events/{id}', [EventController::class, 'show'])->name('event.show');