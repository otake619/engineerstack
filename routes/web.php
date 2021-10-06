<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MemoController;

Route::get('/', function () {
    return view('auth.register');
});

Route::get('/dashboard', function () {
    return view('EngineerStack.home');
})->middleware(['auth'])->name('dashboard');

Route::prefix('user')->group(function () {

});

Route::prefix('memos')->group(function () {
    Route::get('index', [MemoController::class, 'index'])->name('memos.index');
    Route::get('create', [MemoController::class, 'create'])->name('memos.create');
    Route::post('store', [MemoController::class, 'store'])->name('memos.store');
    Route::post('{memo_id}/edit', [MemoController::class, 'edit'])->name('memos.edit');
    Route::post('{memo_id}/update', [MemoController::class, 'update'])->name('memos.update');
    Route::post('{memo_id}/destroy', [MemoController::class, 'destroy'])->name('memos.destroy');
    Route::get('get/store', function () {
        return view('EngineerStack.input_memo');
    })->name('memos.get.form');
});

require __DIR__.'/auth.php';
