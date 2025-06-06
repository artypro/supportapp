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

// Telegram Webhook Route (should not be behind auth)
Route::post('/telegram/webhook', [TelegramWebhookController::class, 'handle']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/support', [SupportController::class, 'showForm']);
    Route::post('/support', [SupportController::class, 'submitTicket'])->name('support.submitTicket');
});

require __DIR__.'/auth.php';
