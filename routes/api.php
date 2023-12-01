<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Auth\AuthController;

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

Route::controller(AuthController::class)->group( function ()
{
    Route::post('/user/signup', 'register');
    Route::post('/user/login', 'login')->name('login');

    Route::group(['middleware' => 'auth:sanctum'], function ()
    {
        Route::post('/user/logout', 'logout');
    });
});
