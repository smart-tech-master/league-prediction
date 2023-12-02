<?php

use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->group(function () {
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/downloadUserChartAsPdf', [App\Http\Controllers\DashboardController::class, 'downloadUserChartAsPdf'])->name('dashboard.download-user-chart-as-pdf');
});

Route::resource('ads', App\Http\Controllers\AdController::class);
Route::resource('settings', App\Http\Controllers\SettingController::class);
Route::resource('pages', App\Http\Controllers\PageController::class);
Route::resource('contacts', App\Http\Controllers\ContactController::class);
Route::resource('messages', App\Http\Controllers\MessageController::class);
Route::resource('users', App\Http\Controllers\UserController::class);

Route::prefix('l10n')->name('l10n.')->group(function () {
    Route::resource('translations', \App\Http\Controllers\L10n\TranslationController::class);
});

