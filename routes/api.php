<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function () {

    //Product
    Route::post('/product/create', [ProductController::class, 'create']);
    Route::get('/product/viewall', [ProductController::class, 'viewAllProducts']);
    Route::get('/product/view/{id?}', [ProductController::class, 'viewProductById']);
    Route::post('/product/{id}', [ProductController::class, 'update']);
    Route::delete('/product/{id}', [ProductController::class, 'delete']);

    //Seller
    Route::post('/seller/create', [SellerController::class, 'create']);
    Route::get('/seller/viewall', [SellerController::class, 'viewAllSellers']);
    Route::get('/seller/view/{id?}', [SellerController::class, 'viewSellerById']);
    Route::post('/seller/{id}', [SellerController::class, 'update']);
    Route::delete('/seller/{id}', [SellerController::class, 'delete']);

});
