<?php

use App\Http\Controllers\ListingsController;
use App\Http\Controllers\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/listings', [ListingsController::class, 'index']);

Route::get('/shops', [ShopController::class, 'index']);

Route::get('/listings/{id}', [ListingsController::class, 'show']);

Route::get('/listings/shop/{id}', [ListingsController::class, 'shop']);

Route::post('/shops', [ShopController::class, 'create']);

Route::post('/listings', [ListingsController::class, 'create']);

Route::put('/listings/{id}', [ListingsController::class, 'store']);

Route::delete('/listings/{id}', [ListingsController::class, 'destroy']);