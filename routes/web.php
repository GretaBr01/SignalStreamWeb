<?php

// use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FrontController;
use App\Http\Controllers\LangController;
use App\Http\Controllers\UserController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });



Route::get('/lang/{lang}', [LangController::class, 'changeLanguage'])->name('setLang');

Route::middleware(['lang'])->group(function() {
    require __DIR__.'/auth.php';

    Route::get('/', [FrontController::class, 'getHome'])->name('home');

    Route::middleware(['auth'])->group(function () {
        Route::post('/users/update', [UserController::class, 'update'])->name('users.update');
    });

    Route::middleware(['auth','isRegisteredUser'])->group(function() {
        Route::get('/workspace/editUser', [FrontController::class, 'getEditUser'])->name('workspace.edituser');
    });

    // Route::middleware(['auth','isAdmin'])->group(function() {
    // });

});