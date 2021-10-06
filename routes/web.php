<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.register');
});

Route::get('/dashboard', function () {
    return view('EngineerStack.home');
})->middleware(['auth'])->name('dashboard');

Route::prefix('user')->group(function () {

});

Route::prefix('memo')->group(function () {
    Route::get('index', [MemoController::class, 'index'])->name('show_memo');
    Route::get('get_save', [MemoController::class, 'get_save'])->name('get_save_memo');
    Route::post('post_save', [MemoController::class, 'post_save'])->name('post_save_memo');
    Route::get('get_edit', [MemoController::class, 'get_edit'])->name('get_update_memo');
    Route::post('post_edit', [MemoController::class, 'post_edit'])->name('post_update_memo');
    Route::post('post_delete', [MemoController::class, 'post_delete'])->name('delete_memo');
});

require __DIR__.'/auth.php';
