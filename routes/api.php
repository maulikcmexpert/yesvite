<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ApiController;
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


Route::post('/user/login', [ApiAuthController::class, 'login']);
Route::post('/user/signup', [ApiAuthController::class, 'signup']);
Route::get('verify/{token}', [ApiAuthController::class, 'verifyAccount'])->name('user.verify');
Route::post('/user/forgotpassword', [ApiAuthController::class, 'passwordLink']);


// M 31/08/2023 //


Route::prefix('user')->middleware('checkUser')->group(function () {

    Route::get('home', [ApiController::class, 'home']);
    Route::post('update_profile', [ApiController::class, 'updateProfile']);
    Route::post('my_profile', [ApiController::class, 'myProfile']);

    Route::get('logout', [ApiController::class, 'logout']);
});



// Route::prefix('user')->controller(ApiAuthController::class)->group(function () {
//     Route::post('/user/login', 'login');
//     Route::post('/user/signup', 'signup');
// });
