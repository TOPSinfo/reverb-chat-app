<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/chats/{to_id?}', [\App\Http\Controllers\ChatController::class, 'index'])->name('chats.index');
    Route::get('/chats/get-messages/{id}', [\App\Http\Controllers\ChatController::class, 'getMessages'])->name('chats.messages.get');
    Route::post('/chats/sent', [\App\Http\Controllers\ChatController::class, 'store'])->name('chats.store');
});

require __DIR__ . '/auth.php';
