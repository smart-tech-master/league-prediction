<?php

use Illuminate\Support\Facades\Route;

Route::get('experiments/{action}', [\App\Http\Controllers\ExperimentController::class, 'index']);

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('predictions', App\Http\Controllers\PredictionController::class);

Route::get('opt-mail', [App\Http\Controllers\AdController::class, 'optMail']);


