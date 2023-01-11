<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;

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

Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:api', 'checkShop'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('get-user', [AuthController::class, 'userInfo']);
    Route::resource('app_product', ProductController::class);
});