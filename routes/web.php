<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\TrackingController;

Route::get('/', function () {
    return redirect()->route('orders.index');
});

//order routes
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get( '/orders/create', [OrderController::class, 'create'])->name('orders.create');

Route::post( '/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get( '/orders/{order}',[OrderController::class, 'show'])->name('orders.show');

//ship an order
Route::post( '/orders/{order}/ship',[ShipmentController::class, 'ship'])->name('orders.ship');

//track shipment
Route::get('/orders/{order}/track', [TrackingController::class, 'track'])->name('orders.track');
