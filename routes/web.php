<?php

use App\Http\Controllers\TelegramController;
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


