<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'auth'], function () {
  Route::post('admin-confirm', [AuthController::class, 'adminVerify']);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/admin/login', [AuthController::class, 'adminLogin']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/test', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
  Route::get('/products/categories', [ProductController::class, 'getCategories']);
  Route::get('/products/offers/categories', [ProductController::class, 'getOffersCategories']);
  Route::get('/products/offers/count', [ProductController::class, 'getOffersCount']);
  Route::get('/products/offers', [ProductController::class, 'getOffers']);
  Route::get('/products/{category}/all', [ProductController::class, 'getProductsByCategory']);
  Route::get('/products/{category}/types', [ProductController::class, 'getProductTypesByCategory']);
  Route::apiResource('products', ProductController::class);
  Route::apiResource('users', UserController::class);
  Route::post('change-password/{user}', [UserController::class, 'changePassword']);
  Route::apiResource('roles', RoleController::class);
});

Route::fallback(function () {
  return response()->json([
    'message' => 'Recurso no encontrado'
  ], 404);
});