<?php

use App\Http\Controllers\UpdateController;
use Illuminate\Support\Facades\Route;

Route::view('/companies', 'companies');
Route::view('/analysis', 'analysis');

Route::get('/update', UpdateController::class);
