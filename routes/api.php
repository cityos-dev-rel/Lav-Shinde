<?php

use App\Http\Controllers\VideosController;
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

Route::get('/health', function () {
    return response('');
});


Route::get('/files/{file}', [VideosController::class, 'show']);
Route::delete('/files/{file}', [VideosController::class, 'destroy']);
Route::post('/files', [VideosController::class, 'store']);
Route::get('/files', [VideosController::class, 'index']);
