<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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




/**
 * start route Auth
 */
Route::group([

    'middleware' => 'api',
    'prefix' => 'v1/auth'

], function ($router) {

    Route::get('index', [ AuthController::class, 'index' ])->name('UserIndex');
    Route::post('login', [ AuthController::class, 'login' ])->name('UserLogin'); 
    Route::post('store',[ AuthController::class, 'store' ])->name('UserStore');
    Route::post('update/{id}',[ AuthController::class, 'update' ])->name('UserUpdate');
    Route::get('trashed',[ AuthController::class, 'trashed' ])->name('UserTrashed');
    Route::delete('destroy/{id}',[ AuthController::class, 'destroy' ])->name('UserDestroy');
    Route::post('restore/{id}',[ AuthController::class, 'restore' ])->name('UserRestore');
    Route::post('forced/{id}',[ AuthController::class, 'forced' ])->name('UserForced'); 
    Route::post('logout',[ AuthController::class, 'logout' ])->name('UserLogout'); 
    Route::post('refresh',[ AuthController::class, 'refresh' ])->name('UserRefresh'); 
    Route::post('me', [ AuthController::class, 'me' ])->name('UserMe');
    Route::get('show/{id}', [ AuthController::class, 'show' ])->name('UserShow');
    Route::post('/forgot-password',[ AuthController::class, 'forgotpassword' ])->name('ForgotPassword'); 
    Route::post('/reset-password',[ AuthController::class, 'resetpassword' ])->name('ResetPassword');  

});
