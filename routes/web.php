<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UpdateController;
use Illuminate\Support\Facades\Route;

Route::view('/companies', 'companies');
Route::view('/analysis', 'analysis');
Route::view('/register', 'register');
Route::view('/login', 'login');

Route::get('/update', UpdateController::class);
Route::get('/leaveAccount', [RegisterController::class, 'leave']);

Route::post('/register', RegisterController::class);
Route::post('/login', [RegisterController::class, 'login']);
