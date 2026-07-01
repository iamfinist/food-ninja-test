<?php

use App\Http\Controllers\RedirectController;
use App\Http\Middleware\RecordClick;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{code}', RedirectController::class)
    ->middleware(RecordClick::class)
    ->where('code', '[a-z0-9]{6,}')
    ->name('short-link.redirect');
