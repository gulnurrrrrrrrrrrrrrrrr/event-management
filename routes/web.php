<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;

Route::get('/lang/{locale}', [LanguageController::class, 'switch'])
     ->name('lang.switch')
     ->where('locale', 'ru|en|kk');

Route::middleware(['web', \App\Http\Middleware\SetLocale::class])->group(function () {
Route::middleware(['web'])->get('/lang/{locale}', [LanguageController::class, 'switch'])
     ->name('lang.switch')
     ->where('locale', 'ru|en|kk');
    
    Route::get('/', [EventController::class, 'index'])->name('home');
    Route::get('/events', [EventController::class, 'index'])->name('events.index');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');

    Route::middleware(['auth', 'role:organizer'])->group(function () {
        Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
    });

    Route::middleware(['auth', 'role:organizer,admin,super_admin'])->group(function () {
        Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
        Route::delete('/events/{event}/images/{image}', [EventController::class, 'destroyImage'])
             ->name('events.images.destroy');
    });

    Route::middleware(['auth', 'role:user'])->group(function () {
        Route::post('/events/{event}/register', [EventController::class, 'registerParticipant'])->name('events.register');
        Route::delete('/events/{event}/unregister', [EventController::class, 'unregisterParticipant'])->name('events.unregister');
    });

    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

    Route::middleware(['auth', 'role:admin,super_admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users/{user}/toggle-block', [AdminController::class, 'toggleBlock'])->name('users.toggle-block');
        Route::get('/events/{event}/participants', [AdminController::class, 'eventParticipants'])->name('events.participants');
        Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
        Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');
    });

    Route::middleware(['auth', 'role:super_admin'])->group(function () {
        Route::post('/admin/users/{user}/assign-role', [AdminController::class, 'assignRole'])
             ->name('admin.users.assign-role');
    });

});
