<?php

use Illuminate\Support\Facades\Route;

// Login Routes...
Route::get('login/{role}', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login')->where('role', 'super-admin');
Route::post('login/{role}', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->where('role', 'super-admin');
// Login Routes...
//Route::get('login/{role?}', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login')->where('role', 'super-admin');
//Route::post('login/{role?}', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->where('role', 'super-admin');

// Logout Routes...
Route::post('logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Password Reset Routes...
Route::get('password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
Route::get('pin_code/notification', [\App\Http\Controllers\Auth\PinCodeController::class, 'notify']);
