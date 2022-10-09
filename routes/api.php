<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OphyraController;
use App\Http\Controllers\DocumentosController;

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

Route::get('getItems', [OphyraController::class,'getItems']);

Route::post('deleteItems', [OphyraController::class,'deleteItems']);

Route::post('crearUsuario', [OphyraController::class,'deleteItems']);

Route::post('updateItems', [OphyraController::class,'updateItems']);

Route::post('createItems', [OphyraController::class,'createItems']);





Route::get('getDocuments', [DocumentosController::class,'getDocuments']);

Route::post('createDocuments', [DocumentosController::class,'createDocuments']);

Route::post('deleteDocuments', [DocumentosController::class,'deleteDocuments']);

Route::get('getMatriculados', [DocumentosController::class,'getMatriculados']);

Route::post('createMatricula', [DocumentosController::class,'createMatricula']);

Route::post('createCuota', [DocumentosController::class,'createCuota']);


