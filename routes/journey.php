<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\JourneyAuthController;
use App\Http\Controllers\PklController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [JourneyAuthController::class, 'form'])->name('login');
Route::post('/login', [JourneyAuthController::class, 'login'])->middleware('throttle:6,1');
Route::post('/register', [JourneyAuthController::class, 'register'])->middleware('throttle:6,1');
Route::post('/logout', [JourneyAuthController::class, 'logout'])->middleware('auth');
Route::get('/booking', [BookingController::class, 'index']);
Route::get('/pkl', [PklController::class, 'index']);
Route::middleware('auth')->group(function () {
    Route::post('/booking', [BookingController::class, 'store']);
    Route::delete('/booking/{booking}', [BookingController::class, 'cancel']);
    Route::get('/pkl/dashboard', [PklController::class, 'dashboard']);
    Route::get('/pkl/create', [PklController::class, 'form']);
    Route::post('/pkl/create', [PklController::class, 'save']);
    Route::get('/pkl/jobs/{opportunity}/edit', [PklController::class, 'form']);
    Route::put('/pkl/jobs/{opportunity}', [PklController::class, 'save']);
    Route::post('/pkl/jobs/{opportunity}/apply', [PklController::class, 'apply']);
    Route::patch('/pkl/applications/{application}', [PklController::class, 'status']);
    Route::get('/pkl/applications/{application}/cv', [PklController::class, 'cv']);
    Route::get('/pkl/export/{format}', [PklController::class, 'export']);
});
Route::get('/pkl/jobs/{opportunity}', [PklController::class, 'show']);
Route::redirect('/bulan-8', '/inventaris');
Route::redirect('/bulan-9', '/inventaris');
Route::redirect('/bulan-10', '/booking');
Route::redirect('/bulan-11','/pkl');
Route::redirect('/bulan-12','/pkl/dashboard');
