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
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(AuthController::class)->group(function () {
    Route::post('auth/login',  'login')->name('api.auth.login');
    Route::post('auth/register', 'register')->name('api.register');
});



// Route::get('auth/me', [AuthController::class, 'me'])->name('api.auth.me')->middleware('auth:api');
Route::middleware(['auth:api'])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('auth/me', 'me')->name('api.auth.me');
        Route::post('auth/logout', 'logout')->name('api.logout');
    });

});
