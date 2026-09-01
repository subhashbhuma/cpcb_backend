<?php

use App\Http\Controllers\Website\CircularController;
use App\Http\Controllers\Website\AboutUsController;
use App\Http\Controllers\Website\HomeController;
use Illuminate\Support\Facades\Route;



Route::group(['middleware' => 'setLocale'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('homepage');
    Route::get('/{any}', [HomeController::class, 'page'])->where('any', '.*');
});
