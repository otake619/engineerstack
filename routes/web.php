<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.register');
});

Route::get('/dashboard', function () {
    return view('EngineerStack.home');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
