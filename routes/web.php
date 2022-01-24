<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MemoController;
use App\Http\Controllers\ContactController;

Route::get('/', function () {
    return view('auth.register');
});

Route::get('/dashboard', [MemoController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::prefix('users')->group(function () {
    Route::post('show', [UserController::class, 'show'])->name('user.show');
});

Route::prefix('memos')->group(function () {
    Route::get('index', [MemoController::class, 'index'])->name('memos.index');
    Route::get('create', [MemoController::class, 'create'])->name('memos.create');
    Route::post('store', [MemoController::class, 'store'])->name('memos.store');
    Route::get('{memo_id}/edit', [MemoController::class, 'edit'])->name('memos.edit');
    Route::post('update', [MemoController::class, 'update'])->name('memos.update');
    Route::post('{memo_id}/destroy', [MemoController::class, 'destroy'])->name('memos.destroy');
    Route::post('show', [MemoController::class, 'show'])->name('memos.show');
    Route::get('search', [MemoController::class, 'searchKeyword'])->name('memos.search');
    Route::get('search_category', [MemoController::class, 'searchCategory'])->name('memos.search.category');
    Route::get('all_categories', [MemoController::class, 'allCategories'])->name('memos.all_categories');
    Route::get('get/store', function () {
        return view('EngineerStack.input_memo');
    })->middleware('auth')->name('memos.get.input');
    Route::get('get/deleted', function () {
        return view('EngineerStack.deleted_memo');
    })->middleware('auth')->name('memos.deleted');
});

Route::prefix('contact')->group(function () {
    Route::get('index', [ContactController::class, 'index'])->name('contact.index');
    Route::post('confirm', [ContactController::class, 'confirm'])->name('contact.confirm');
    Route::post('send', [ContactController::class, 'send'])->name('contact.send');
});

require __DIR__.'/auth.php';

Route::prefix('admin')->name('admin.')->group(function(){

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->middleware(['auth:admin'])->name('dashboard');
    
    require __DIR__.'/admin.php';
});
