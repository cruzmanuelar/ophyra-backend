<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OphyraController;

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

Route::post('updateItems', [OphyraController::class,'updateItems']);

Route::post('createItems', [OphyraController::class,'createItems']);
