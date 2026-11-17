<?php

use App\Http\Controllers\StoreCheckController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::post('/verificar', StoreCheckController::class)->name('check');
