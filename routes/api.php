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

Route::get('/products/export', [ProductController::class, 'exportData']);

Route::group(['middleware' => 'auth:sanctum'], function () {
// Route::group(['middleware' => 'api'], function () {
  Route::get('/products/export', [ProductController::class, 'exportData']);
  Route::get('/products/categories', [ProductController::class, 'getCategories']);
  Route::get('/products/offers/categories', [ProductController::class, 'getOffersCategories']);
  Route::get('/products/offers/count', [ProductController::class, 'getOffersCount']);
  Route::get('/products/offers', [ProductController::class, 'getOffers']);
  Route::get('/products/colores', [ProductController::class, 'getColores']);
  Route::get('/products/{category}/all', [ProductController::class, 'getProductsByCategory']);
  Route::get('/products/{category}/types', [ProductController::class, 'getProductTypesByCategory']);
  Route::post('/products/excel/upload', [ProductController::class, 'uploadExcel']);
  Route::apiResource('products', ProductController::class);
  Route::apiResource('users', UserController::class);
  Route::post('change-password/{user}', [UserController::class, 'changePassword']);
  Route::apiResource('roles', RoleController::class);
});

Route::post('/products/excel/tojson', [ProductController::class, 'excelToJSON']);

Route::group(['prefix' => 'client', 'middleware' => 'api'], function () {
  // Route::get('/products/categories', [ProductController::class, 'getCategories']);
  Route::get('/products/offers/categories', [ProductController::class, 'getOffersCategories']);
  Route::get('/products/offers/count', [ProductController::class, 'getOffersCount']);
  Route::get('/products/offers', [ProductController::class, 'getOffers']);
  Route::get('/products/{category}/all', [ProductController::class, 'getProductsByCategory']);
  Route::get('/products/{category}/types', [ProductController::class, 'getProductTypesByCategory']);
  Route::get('/products/{product}', [ProductController::class, 'show']);
});

Route::fallback(function () {
  return response()->json([
    'message' => 'Recurso no encontrado'
  ], 404);
});