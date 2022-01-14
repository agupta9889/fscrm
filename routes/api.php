<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController;

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

Route::post('sale_phones',[APIController::class,'salePhones']);
Route::get('refresh-token',[APIController::class,'generateRefreshToken']);
Route::get('access-token',[APIController::class,'generateAccessToken']);
Route::post('insert-lead',[APIController::class,'execute']);
