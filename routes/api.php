<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\XenditController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Xendit\InvoiceController;

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
Route::get('/reviews/{room-id}', [ReviewController::class, 'index']);

# Searches
Route::get('/rooms/search', [SearchController::class, 'roomSearch']);
Route::get('/articles/search', [SearchController::class, 'articleSearch']);

# User Details
Route::get('/users/{id}', [UserController::class, 'show'])->name('user.show');

# Display Rooms
Route::get('/rooms', [ProductController::class, 'index'])->name('rooms.index');
Route::get('/rooms/{room}', [ProductController::class, 'show'])->name('rooms.show');

Route::middleware('auth:api')->group( function () {
    Route::get('/users',[UserController::class,'details']);
    Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:api');

    Route::post('/rooms',[ProductController::class,'store'])->name('rooms.store');
    Route::put('/rooms/{room}',[ProductController::class,'update'])->name('rooms.update');
    Route::patch('/rooms/{room}',[ProductController::class,'update']);
    Route::delete('/rooms/{room}',[ProductController::class,'destroy'])->name('rooms.delete');

    Route::get('/reviews', [ReviewController::class, 'index']);
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{id}', [ReviewController::class, 'update']);

    Route::get('/order', [OrderController::class, 'getOrder'])->name('order');
    Route::get('/order/{id}', [OrderController::class, 'index'])->name('order.index');
    Route::post('/order', [OrderController::class, 'create'])->name('order.create');
    Route::put('/order', [OrderController::class, 'update'])->name('order.update');

    Route::get('/payments/list', [XenditController::class, 'getVaList'])->name('payment.list');
    Route::post('/payments/pay/va', [XenditController::class, 'payVa'])->name('payment.pay.va');
    Route::post('/payments/pay/ewallet', [XenditController::class, 'payEwallet'])->name('payment.pay.ewallet');
    Route::post('/payments/create-customer', [XenditController::class, 'create'])->name('payment.create');
    Route::post('/payments/edit-customer', [XenditController::class, 'update'])->name('payments.update');

    Route::get('/invoice/all', [InvoiceController::class, 'getAllInvoice'])->name('invoice.all');
    Route::get('/invoice', [InvoiceController::class, 'getInvoice'])->name('invoice.show');
    Route::post('/invoice', [InvoiceController::class, 'createInvoice'])->name('invoice.create');
    Route::post('/invoice/expire', [InvoiceController::class, 'expireInvoice'])->name('invoice.expire');
    Route::post('/invoice/callback', [InvoiceController::class, 'handleCallback']);
});
