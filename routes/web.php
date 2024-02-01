<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
})->name('home');*/

Route::post('files/crear/{id?}', [FileController::class, 'crearcarpeta'])->name('files.crear');
Route::post('files/subir/{id?}', [FileController::class, 'subirarchivos'])->name('files.subir');
Route::post('files/crearArchivo/{id?}', [FileController::class, 'crearArchivo'])->name('files.crearArchivo');

Route::get('files/mostrar/{id}', [FileController::class, 'mostrar'])->name('files.mostrar');
Route::put('files/editar/{id}', [FileController::class, 'editar'])->name('files.editar');


Route::get('files/descargar/{id}', [FileController::class, 'descargar'])->name('files.descargar');

Route::get('/files/compartir/{id}', [FileController::class, 'shareMostrar'])->name('files.sharemostrar');
Route::post('/files/compartir/{id}', [FileController::class, 'shareFiles'])->name('files.compartir');
Route::get('/files/compartir/{id}/{permissionId}', [FileController::class, 'eliminarAcceso'])->name('files.sharedelete');

Route::get('/', [FileController::class, 'listarArchivos'])->name('home');
Route::get('files/listar', [FileController::class, 'listarArchivos'])->name('files.listar');

Route::get('files/visualizar/{id}', [FileController::class, 'visualizar'])->name('files.visualizar');

Route::delete('files/eliminar/{id}', [FileController::class, 'eliminar'])->name('files.eliminar');

