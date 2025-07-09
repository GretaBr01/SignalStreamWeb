<?php

// use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FrontController;
use App\Http\Controllers\LangController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\SeriesRealTimeController;

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
        Route::get('/ajax/series/{id}/emg', [SeriesController::class, 'getEmgCsv']);
        Route::get('/ajax/series/{id}/imu', [SeriesController::class, 'getImuCsv']);  
        Route::get('/workspace/storico-dati/series/create', [SeriesController::class, 'create'])->name('series.create');
        Route::post('/series', [SeriesController::class, 'store'])->name('series.store');
    });

    Route::middleware(['auth','isRegisteredUser'])->group(function() {
        Route::put('/workspace/users/{id}', [UserController::class, 'update'])->name('user.update');
        Route::get('/workspace/users/{id}/edit', [UserController::class, 'edit'])->name('user.edit');

        Route::get('/workspace/storico-dati', [SeriesController::class, 'index'])->name('workspace.series');
        Route::get('/workspace/acquisizione-serie', [SeriesRealTimeController::class, 'index'])->name('workspace.acquisizione');

        // Route::get('/series', [SeriesController::class, 'index'])->name('series.index');
        Route::get('/workspace/storico-dati/series/{id}', [SeriesController::class, 'show'])->name('series.show');
        Route::get('/workspace/storico-dati/series/{id}/destroy/confirm', [SeriesController::class, 'confirmDestroy'])->name('serie.destroy.confirm');
        Route::delete('/workspace/storico-dati/series/{id}', [SeriesController::class, 'destroy'])->name('series.destroy');

    });

    // Route::middleware(['auth','isAdmin'])->group(function() {
    // });

});