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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/test', [AuthController::class, 'login']);

Route::group(['middleware' => 'api'], function () {
  Route::get('/products/categories', [ProductController::class, 'getCategories']);
  Route::get('/products/{category}/all', [ProductController::class, 'getProductsByCategory']);
  Route::apiResource('products', ProductController::class);
  Route::apiResource('users', UserController::class);
  Route::apiResource('roles', RoleController::class);
});

Route::fallback(function () {
  return response()->json([
    'message' => 'Recurso no encontrado'
  ], 404);
});