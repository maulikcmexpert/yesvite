<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\{

    HomeController,
    HomeFrontController,
    ProfileController
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
Route::get('/', [HomeFrontController::class, 'index'])->name('home');

Route::middleware('checkUserExist')->group(function () {


    Route::get('dashboard', [HomeController::class, 'index'])->name('home');
    Route::post('/import-csv',  [HomeController::class, 'importCSV'])->name('import.csv');
    Route::get('profile',  [ProfileController::class, 'index'])->name('profile');
    Route::post('profile/update/{id}',  [ProfileController::class, 'update'])->name('profile.update');


    Route::post('upload',  [ProfileController::class, 'uploadProfile'])->name('profile.upload');
});

Route::controller(AuthController::class)->group(function () {

    Route::get('login', 'create')->name('auth.login')->middleware('isAuthenticate');
    Route::post('login', 'checkLogin')->name('auth.checkLogin');
    Route::get('register', 'register')->name('auth.register')->middleware('isAuthenticate');


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

        Session::forget('user');

        return redirect('/');
    })->name('logout');
});
