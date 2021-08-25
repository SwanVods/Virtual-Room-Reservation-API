<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

# Auth
Route::post('/register', [UserController::class, 'registration']);
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::get('/login', function(){return response()->json(['message' => 'Unauthorized'], 401);});

# Rooms Image
Route::post('/rooms/image/', [ProductController::class, 'addImages']);
Route::delete('/rooms/image/{id}', [ProductController::class, 'destroyImages']);

# Display Reviews
Route::get('/reviews', [ReviewController::class, 'index']);

# Searches
Route::get('/rooms/search', [SearchController::class, 'roomSearch']);
Route::get('/articles/search', [SearchController::class, 'articleSearch']);

# User Details
Route::get('/users/{id}', [UserController::class, 'show'])->name('user.show');

# Display Rooms
Route::get('/rooms', [ProductController::class, 'index'])->name('rooms.index');
Route::get('/rooms/{id}', [ProductController::class, 'show'])->name('rooms.show');

Route::middleware('auth:api')->group( function () {
    Route::get('/users',[UserController::class,'details']);
    Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:api');

    Route::post('/rooms',[ProductController::class,'store'])->name('rooms.store');
    Route::put('/rooms/{id}',[ProductController::class,'update'])->name('rooms.update');
    Route::patch('/rooms/{id}',[ProductController::class,'update']);
    Route::delete('/rooms/{room}',[ProductController::class,'destroy'])->name('rooms.delete');

    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{id}', [ReviewController::class, 'update']);
});
