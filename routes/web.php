<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/**
 * Este metodo se ejecuta una sola vez y crea una capeta de acceso directo en public llamada storage
 * Con esto se podra acceder a lo que se guarde en storage/app/public
 * https://www.youtube.com/watch?v=tDgFOKvQajg&ab_channel=Aprendible
 * NOTE: Adicional modificar config/filesystems.php links, el nombre asignado a public_path sera el nombre del folder que se cree en public
 */
Route::get('storage-link', function(){
  return Artisan::call('storage:link');
});
