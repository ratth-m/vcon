<?php

use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\ExportController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProjectController;

Route::controller(LoginController::class)->group(function(){
    Route::get('/login', 'show')->name('login');
    Route::post('/login', 'login')->name('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    
    



    /*Route::controller(ExportController::class)->name('export.')->group(function(){
        Route::get('export/daily', 'dailyExport')->name('daily');
        Route::get('export/forecast', 'forecastExport')->name('forecast');
        Route::get('export/pm', 'pmExport')->name('pm');
    });*/
});