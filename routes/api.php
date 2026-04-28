<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\TrackingController;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::post('/orders', [OrderController::class, 'apiStore']);
Route::get('/orders/{order}', [OrderController::class, 'apiShow']);

Route::post('/orders/{order}/ship', [ShipmentController::class, 'apiShip']);

Route::get('/shipments/{shipment}/track', [TrackingController::class, 'apiTrack']);
