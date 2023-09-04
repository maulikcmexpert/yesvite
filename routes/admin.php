<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\Dashboard;
use App\Http\Controllers\admin\Auth;
use Illuminate\Support\Facades\Session;


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


Route::group(['middleware' => adminAuth::Class], function () {
    Route::controller(Dashboard::class)->group(function () {
        Route::get('/', 'index');
    });
    Route::controller(Event::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/', 'index');
    });
});
Route::controller(Auth::class)->group(function () {
    Route::get('/login', function () {
        $data['page'] = 'admin/auth/login';
        $data['js'] = ['login'];
        return view('admin/auth/main', $data);
    });


    Route::post('/login', 'checkLogin');

    Route::get('/register', function () {
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
        return redirect('/admin');
    });
});
