<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth Routes
Route::post('/login',[\App\Http\Controllers\AuthController::class, 'login']);
Route::get('/logout',[\App\Http\Controllers\AuthController::class, 'logout']);
Route::post('/mobilelogin',[\App\Http\Controllers\AuthController::class,'mobileLogin']);

// Admin Routes
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::get('/get',[\App\Http\Controllers\UserController::class,'get']);
    Route::post('/add',[\App\Http\Controllers\UserController::class,'add']);
    Route::post('/update',[\App\Http\Controllers\UserController::class,'update']);
    Route::post('/delete',[\App\Http\Controllers\UserController::class,'delete']);
});

// News Routes
Route::middleware('auth:sanctum')->prefix('news')->group(function () {
    Route::get('/get',[\App\Http\Controllers\NewsController::class,'get']);
    Route::post('/add',[\App\Http\Controllers\NewsController::class,'add']);
    Route::post('/update',[\App\Http\Controllers\NewsController::class,'update']);
    Route::post('/delete',[\App\Http\Controllers\NewsController::class,'delete']);
});

// Package Routes
Route::middleware('auth:sanctum')->prefix('packages')->group(function () {
    Route::get('/get',[\App\Http\Controllers\PakagesController::class,'get']);
    Route::post('/add',[\App\Http\Controllers\PakagesController::class,'add']);
    Route::post('/update',[\App\Http\Controllers\PakagesController::class,'update']);
    Route::post('/delete',[\App\Http\Controllers\PakagesController::class,'delete']);
});

// Site Routes
Route::middleware('auth:sanctum')->prefix('sites')->group(function () {
    Route::get('/get',[\App\Http\Controllers\SitesController::class,'get']);
    Route::post('/add',[\App\Http\Controllers\SitesController::class,'add']);
    Route::post('/update',[\App\Http\Controllers\SitesController::class,'update']);
    Route::post('/delete',[\App\Http\Controllers\SitesController::class,'delete']);
});

// About Routes
Route::middleware('auth:sanctum')->prefix('about')->group(function () {
    Route::get('/get',[\App\Http\Controllers\AboutController::class,'get']);
    Route::post('/update',[\App\Http\Controllers\AboutController::class,'update']);
});

Route::get('online/news',[\App\Http\Controllers\NewsController::class,'getAll']);
Route::get('online/packages',[\App\Http\Controllers\PakagesController::class,'getAll']);
Route::get('online/about',[\App\Http\Controllers\AboutController::class,'get']);
Route::get('online/sites',[\App\Http\Controllers\SitesController::class,'getAll']);

