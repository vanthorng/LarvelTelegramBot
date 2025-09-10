<?php

use App\Http\Controllers\TelegramController;
use App\Http\Controllers\BotController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/send-message', [TelegramController::class , 'sendMessage'])->name('sendMessage');

// Route::get('/get-updates', [TelegramController::class,'getUpdates'])->name('getUpdates');

Route::get('/telegram/send', [TelegramController::class, 'sendMessage']);
Route::get('/telegram/updates', [TelegramController::class, 'getUpdates']);
Route::get('/telegram/files', [TelegramController::class, 'index'])->name('telegram.index');
Route::get('/telegram/files/upload', fn() => view('telegram.upload'))->name('telegram.upload');
Route::post('/telegram/files', [TelegramController::class, 'storeFile'])->name('telegram.store');
Route::get('/telegram/files/{id}', [TelegramController::class, 'show'])->name('telegram.show');
Route::delete('/telegram/files/{id}', [TelegramController::class, 'destroy'])->name('telegram.destroy');
Route::post('/telegram/files/send/{id}', [TelegramController::class, 'sendFileToTelegram'])->name('telegram.send');
Route::post('/telegram/chat-info', [TelegramController::class, 'getChatInfo']);

//
// Bot information and management
Route::get('/bot/info', [BotController::class, 'show']);
Route::post('/bot/webhook/set', [BotController::class, 'setWebhook']);
Route::delete('/bot/webhook', [BotController::class, 'removeWebhook']);
Route::get('/bot/webhook/info', [BotController::class, 'getWebhookInfo']);

// Webhook handler
Route::post('/telegram/webhook', [BotController::class, 'webhook']);

// Testing endpoints
Route::get('/bot/updates', [BotController::class, 'getUpdates']);
Route::post('/bot/send-message', [BotController::class, 'sendTestMessage']);
Route::post('/bot/send-photo', [BotController::class, 'sendPhoto']);
Route::post('/bot/send-document', [BotController::class, 'sendDocument']);


