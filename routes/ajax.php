<?php

use Illuminate\Support\Facades\Route;

Route::post('dashboard/sort-by', [\App\Http\Controllers\AJAX\DashboardController::class, 'sortBy'])->name('dashboard.sort-by');
