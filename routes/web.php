<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MemoController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ChangeEmailController;
use App\Http\Controllers\AdminHomeController;

Route::get('/', function () {
    return view('auth.register');
});

Route::get('/dashboard', [MemoController::class, 'index'])->middleware(['auth','verified'])->name('dashboard');

Route::prefix('users')->group(function () {
    Route::get('show', [UserController::class, 'show'])->name('user.show');
    Route::post('update_name', [UserController::class, 'updateAccountName'])->middleware('throttle:3, 1')->name('user.update.name');
    Route::get('update_email_form', function (){
        return view('EngineerStack.change-email-form');
    })->middleware('auth')->name('user.update.email.form');
    Route::post('update_email', [ChangeEmailController::class, 'sendChangeEmailLink'])->name('user.update.email');
    Route::get('update_password_form', function (){
        return view('EngineerStack.change-password-form');
    })->middleware('auth')->name('user.update.password.form');
    Route::post('update_password', [UserController::class, 'updatePassword'])->middleware('throttle:3, 1')->name('user.update.password');
    Route::get('reset/{token}', [ChangeEmailController::class, 'reset'])->name('email.reset');
    Route::post('destroy', [UserController::class, 'destroy'])->name('user.destroy');
    Route::get('guidelines', function (){
        return view('EngineerStack.guidelines');
    })->name('guidelines');
    Route::get('privacy_policy', function (){
        return view('EngineerStack.privacy_policy');
    })->name('privacy_policy');
});

Route::prefix('memos')->group(function () {
    Route::get('index', [MemoController::class, 'index'])->name('memos.index');
    Route::get('create', [MemoController::class, 'create'])->name('memos.create');
    Route::post('store', [MemoController::class, 'store'])->middleware('throttle:3, 1')->name('memos.store');
    Route::get('{memo_id}/edit', [MemoController::class, 'edit'])->name('memos.edit');
    Route::post('update', [MemoController::class, 'update'])->middleware('throttle:10, 1')->name('memos.update');
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
    Route::get('/dashboard', [AdminHomeController::class, 'index'])->name('dashboard');
    require __DIR__.'/admin.php';
});
