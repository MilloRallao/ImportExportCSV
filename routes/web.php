<?php

use App\Http\Controllers\ArticuloController;
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

Route::get('/', [ArticuloController::class, 'index']);

Route::post('import-file', [ArticuloController::class, 'importFile'])->name('import');
Route::get('export-file', [ArticuloController::class, 'exportFile'])->name('export');
Route::post('upload-images', [ArticuloController::class, 'uploadImages'])->name('upload');

