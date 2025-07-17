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


Route::get('/lang/{lang}', [LangController::class, 'changeLanguage'])->name('setLang');

Route::middleware(['lang'])->group(function() {
    require __DIR__.'/auth.php';

    Route::get('/', [FrontController::class, 'getHome'])->name('home');
    Route::get('/come-funziona', [FrontController::class, 'getHowWorksPage'])->name('howitworks');
    

    Route::middleware(['auth'])->group(function () {
        Route::put('/users/{id}', [UserController::class, 'update'])->name('user.update');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');

        Route::resource('series', SeriesController::class)->only([
            'index', 'create', 'store', 'show', 'destroy'
        ]);
        Route::get('/series/{id}/destroy/confirm', [SeriesController::class, 'confirmDestroy'])->name('serie.destroy.confirm');

        Route::get('/ajax/series/{id}/emg', [SeriesController::class, 'getEmgCsv']);
        Route::get('/ajax/series/{id}/imu', [SeriesController::class, 'getImuCsv']);

        Route::patch('/series/{id}/note', [SeriesController::class, 'updateNote'])->name('series.updateNote');


    });

    Route::middleware(['auth','isRegisteredUser'])->group(function() {
        Route::get('/acquisizione', [SeriesRealTimeController::class, 'index'])->name('workspace.acquisizione');
        Route::get('/review-series', [SeriesRealTimeController::class, 'review'])->name('rtseries.review');
        Route::post('/save-series', [SeriesRealTimeController::class, 'store'])->name('rtseries.store');

    });

    Route::middleware(['auth','isAdmin'])->group(function() {
        Route::get('/series/{id}/download/emg', [SeriesController::class, 'downloadEmg'])->name('series.download.emg');
        Route::get('/series/{id}/download/imu', [SeriesController::class, 'downloadImu'])->name('series.download.imu');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/search', [UserController::class, 'search'])->name('users.search');

        Route::resource('categories', CategoryController::class);
        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/{id}/destroy/confirm', [CategoryController::class, 'confirmDestroy'])->name('categories.destroy.confirm');

        Route::get('/admin/category-image/{path}', [CategoryController::class, 'showImage'])->where('path', '.*')->name('category.image');

        Route::get('/users/{id}/confirm-update', [UserController::class, 'confirmUpdate'])->name('user.confirm-update');

    });

});