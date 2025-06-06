<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TelegramWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('auth')->group(function () {
        Route::get('/support', [SupportController::class, 'showForm']);
        Route::post('/support', [SupportController::class, 'submitTicket'])->name('support.submitTicket');
    });

    // Telegram Webhook Route
    Route::post('/telegram/webhook', [TelegramWebhookController::class, 'handle']);
});

require __DIR__.'/auth.php';
