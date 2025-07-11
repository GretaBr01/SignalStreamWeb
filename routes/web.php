<?php

// use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FrontController;
use App\Http\Controllers\LangController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\SeriesRealTimeController;

use App\Http\Controllers\TestFileController;

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


// Route::get('/test-upload', [TestFileController::class, 'showForm'])->name('test.upload.form');
// Route::post('/test-upload', [TestFileController::class, 'uploadAndDownload'])->name('test.upload');

Route::middleware(['lang'])->group(function() {
    require __DIR__.'/auth.php';

    Route::get('/', [FrontController::class, 'getHome'])->name('home');

    Route::get('/come-funziona', [FrontController::class, 'getHowWorksPage'])->name('howitworks');
    

    Route::middleware(['auth'])->group(function () {
        Route::put('/workspace/users/{id}', [UserController::class, 'update'])->name('user.update');
        Route::get('/workspace/users/{id}/edit', [UserController::class, 'edit'])->name('user.edit');

        Route::get('/workspace/storico-dati', [SeriesController::class, 'index'])->name('workspace.series');
        Route::get('/workspace/storico-dati/series/create', [SeriesController::class, 'create'])->name('series.create');
        Route::get('/workspace/storico-dati/series/{id}', [SeriesController::class, 'show'])->name('series.show');
        Route::get('/workspace/storico-dati/series/{id}/destroy/confirm', [SeriesController::class, 'confirmDestroy'])->name('serie.destroy.confirm');
        Route::delete('/workspace/storico-dati/series/{id}', [SeriesController::class, 'destroy'])->name('series.destroy');

        Route::post('/series', [SeriesController::class, 'store'])->name('series.store');
        Route::get('/ajax/series/{id}/emg', [SeriesController::class, 'getEmgCsv']);
        Route::get('/ajax/series/{id}/imu', [SeriesController::class, 'getImuCsv']);

        Route::patch('/series/{id}/note', [SeriesController::class, 'updateNote'])->name('series.updateNote');


    });

    Route::middleware(['auth','isRegisteredUser'])->group(function() {
        Route::get('/acquisizione', [SeriesRealTimeController::class, 'index'])->name('workspace.acquisizione');
        // Route::post('/save-series', [SeriesRealTimeController::class, 'store']);
        Route::get('/review-series', [SeriesRealTimeController::class, 'review'])->name('rtseries.review');
        Route::post('/save-series', [SeriesRealTimeController::class, 'store'])->name('rtseries.store');

        // Route::get('/acquisizione-live', function () { return view('workspace.acquisizione'); });
    });

    Route::middleware(['auth','isAdmin'])->group(function() {
        Route::get('/series/{id}/download/emg', [SeriesController::class, 'downloadEmg'])->name('series.download.emg');
        Route::get('/series/{id}/download/imu', [SeriesController::class, 'downloadImu'])->name('series.download.imu');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
        // Route::get('/users/{id}/destroy/confirm', [UserController::class, 'confirmDestroy'])->name('user.destroy.confirm');
        // Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('user.destroy');

        Route::resource('categories', CategoryController::class);
        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/{id}/destroy/confirm', [CategoryController::class, 'confirmDestroy'])->name('categories.destroy.confirm');

        Route::get('/admin/category-image/{path}', [CategoryController::class, 'showImage'])->where('path', '.*')->name('category.image');

    });

});