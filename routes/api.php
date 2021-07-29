<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Models\Product;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register',[UserController::class,'registration']);
Route::post('/login',[UserController::class,'login'])->name('login');
Route::get('/login',[UserController::class,'login'])->name('login');
Route::get('/rooms',[ProductController::class,'index'])->name('rooms.index');
Route::get('/rooms/{room}',[ProductController::class,'show'])->name('rooms.show');
Route::get('/rooms/create',[ProductController::class,'create'])->name('rooms.create');
Route::get('/rooms/search', [SearchController::class, 'roomSearch']);
Route::get('/articles/search', [SearchController::class, 'articleSearch']);
Route::middleware('auth:api')->group( function () {
    Route::get('user',[UserController::class,'details']);
    Route::post('/rooms',[ProductController::class,'store'])->name('rooms.store');
    Route::put('/rooms',[ProductController::class,'update'])->name('rooms.update');
    Route::patch('/rooms',[ProductController::class,'update'])->name('rooms.update');
    Route::delete('/rooms/{room}',[ProductController::class,'delete'])->name('rooms.delete');
    Route::post('logout',[UserController::class,'logoutApi']);
});
