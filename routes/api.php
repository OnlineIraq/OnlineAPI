<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PodcastController;

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

  Route::get('/getChilds',[\App\Http\Controllers\UserController::class,'getChilds']);

    Route::post('/add',[\App\Http\Controllers\UserController::class,'add']);
    Route::post('/update',[\App\Http\Controllers\UserController::class,'update']);
    Route::post('/delete',[\App\Http\Controllers\UserController::class,'delete']);
    Route::get('/toggleLogin/{id}',[\App\Http\Controllers\UserController::class,'userToggleLogin']);
});

// News Routes
Route::middleware('auth:sanctum')->prefix('news')->group(function () {
    Route::get('/get',[\App\Http\Controllers\NewsController::class,'get']);
    Route::post('/add',[\App\Http\Controllers\NewsController::class,'add']);
    Route::post('/update',[\App\Http\Controllers\NewsController::class,'update']);
    Route::post('/delete',[\App\Http\Controllers\NewsController::class,'delete']);
});

// News Products
Route::middleware('auth:sanctum')->prefix('products')->group(function () {
    Route::get('/get',[\App\Http\Controllers\ProductsController::class,'get']);
    Route::post('/add',[\App\Http\Controllers\ProductsController::class,'add']);
    Route::post('/update',[\App\Http\Controllers\ProductsController::class,'update']);
    Route::post('/delete',[\App\Http\Controllers\ProductsController::class,'delete']);
});


// Package Routes
Route::middleware('auth:sanctum')->prefix('packages')->group(function () {
    Route::get('/get',[\App\Http\Controllers\PackagesController::class,'get']);
    Route::post('/add',[\App\Http\Controllers\PackagesController::class,'add']);
    Route::post('/update',[\App\Http\Controllers\PackagesController::class,'update']);
    Route::post('/delete',[\App\Http\Controllers\PackagesController::class,'delete']);
});

// Package Routes
Route::middleware('auth:sanctum')->prefix('banners')->group(function () {
    Route::get('/get',[\App\Http\Controllers\BannersController::class,'get']);
    Route::post('/add',[\App\Http\Controllers\BannersController::class,'add']);
    Route::post('/update',[\App\Http\Controllers\BannersController::class,'update']);
    Route::post('/delete',[\App\Http\Controllers\BannersController::class,'delete']);
    Route::get('/check/{id}',[\App\Http\Controllers\BannersController::class,'updateStatus']);

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

// Complain Routes
Route::middleware('auth:sanctum')->prefix('complains')->group(function () {
    Route::get('/get',[\App\Http\Controllers\ComplainController::class,'get']);
    Route::get('/read/{id}',[\App\Http\Controllers\ComplainController::class,'updateRead']);
    Route::post('/addReplies',[\App\Http\Controllers\ComplainController::class,'addReplies']);
    Route::get('/{complainId}/replies', [\App\Http\Controllers\ComplainController::class,'getReplies']);

});
Route::post('/send-notification',[\App\Http\Controllers\NotificationController::class,'sendNotification']);
Route::post('/send-notification-all',[\App\Http\Controllers\NotificationController::class,'sendPushNotificationsAll']);


// Podcast Routes
Route::middleware('auth:sanctum')->prefix('podcasts')->group(function () {
    Route::get('/get',[\App\Http\Controllers\PodcastController::class,'get']);
    Route::get('/{id}',[\App\Http\Controllers\PodcastController::class,'show']);
    Route::post('/add',[\App\Http\Controllers\PodcastController::class,'store']);
    Route::post('/update', [\App\Http\Controllers\PodcastController::class,'update']);
    Route::post('/delete', [\App\Http\Controllers\PodcastController::class,'destroy']);
    Route::get('/{id}/audio-files', [\App\Http\Controllers\PodcastController::class,'getAudioFiles']);
    Route::post('/audio-files/upload', [\App\Http\Controllers\PodcastController::class,'addAudioFile']);

});


// Moblie API
Route::get('online/news',[\App\Http\Controllers\NewsController::class,'getAll']);
Route::get('online/getNews/{id}',[\App\Http\Controllers\NewsController::class,'getNews']);
Route::get('online/getPackage/{id}',[\App\Http\Controllers\PackagesController::class,'getPackage']);
Route::get('online/packages',[\App\Http\Controllers\PackagesController::class,'getAll']);
Route::get('online/getBanner/{id}',[\App\Http\Controllers\BannersController::class,'getBanner']);
Route::get('online/banners',[\App\Http\Controllers\BannersController::class,'getAll']);
Route::get('online/about',[\App\Http\Controllers\AboutController::class,'get']);
Route::get('online/sites',[\App\Http\Controllers\SitesController::class,'getAll']);
Route::post('online/complain',[\App\Http\Controllers\ComplainController::class,'add']);
Route::get('online/complain/get',[\App\Http\Controllers\ComplainController::class,'get']);
Route::get('online/complain/{username}',[\App\Http\Controllers\ComplainController::class,'getComplain']);
Route::get('online/complain/{complainId}/replies', [\App\Http\Controllers\ComplainController::class,'getReplies']);
Route::post('online/addReplies',[\App\Http\Controllers\ComplainController::class,'addReplies']);
Route::get('online/podcasts',[\App\Http\Controllers\PodcastController::class,'getAll']);

