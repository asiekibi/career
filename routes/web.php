<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\CvController;

Route::get('/', function () {
    return view('welcome');
});

// Resource routes for users
Route::resource('users', UserController::class);

// Resource routes for locations
Route::resource('locations', LocationController::class);

// Resource routes for CVs
Route::resource('cvs', CvController::class);

// Resource routes for badges
Route::resource('badges', BadgeController::class);