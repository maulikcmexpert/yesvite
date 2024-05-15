<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\{
    Home
};
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

// Route::get('/', function () {
//     check();

//     return view('welcome');
// });


Route::middleware('checkUserExist')->group(function () {


    Route::get('home', [Home::class, 'index']);
});

Route::controller(AuthController::class)->group(function () {

    Route::get('/', 'create')->name('auth.login');
    Route::post('login', 'checkLogin')->name('auth.checkLogin');;


    Route::get('register', function () {

        $data['page'] = 'admin/auth/register';

        $data['js'] = ['login'];

        return view('admin/auth/main', $data);
    });

    Route::post('/checkEmail', 'checkEmail');

    Route::post('/register', 'registerAdmin');

    Route::get('/forgotpassword', function () {

        $data['js'] = ['login'];

        $data['page'] = 'admin.auth.forgotpassword';

        return view('admin.auth.main', $data);
    });

    Route::post('/forgotpassword', 'forgotpassword');

    Route::get('/updatePassword/{id}', 'checkToken');

    Route::post('/updatePassword/{id}', 'updatePassword');

    Route::get('/logout', function () {

        Session::forget('admin');

        return redirect('/admin/login');
    });
});
