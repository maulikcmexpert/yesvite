<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\{
    AboutController,
    ContactController,
    HomeController,
    HomeFrontController,
    ProfileController,
    SocialController,
    AccountSettingController
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
Route::get('/', [HomeFrontController::class, 'index'])->name('front.home');
Route::get('about-us', [AboutController::class, 'index'])->name('about');
Route::get('contact', [ContactController::class, 'index'])->name('contact');

Route::middleware('checkUserExist')->group(function () {


    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/import-csv',  [HomeController::class, 'importCSV'])->name('import.csv');
    Route::get('profile',  [ProfileController::class, 'index'])->name('profile');
    Route::get('profile/edit',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('profile/change_password',  [ProfileController::class, 'changePassword'])->name('profile.change_password');

    Route::post('profile/verify_password', [ProfileController::class, 'verifyPassword'])->name('profile.verify_password');

    Route::post('profile/update/{id}',  [ProfileController::class, 'update'])->name('profile.update');
    Route::post('profile/check-phonenumber', [ProfileController::class, 'checkPhoneNumberExistence']);
    Route::post('profile/update_password',  [ProfileController::class, 'updatePassword'])->name('profile.update_password');

    Route::get('profile/public_profile',  [ProfileController::class, 'publicProfileView'])->name('profile.public_profile');
    Route::get('profile/profile_privacy',  [ProfileController::class, 'profilePrivacy'])->name('profile.privacy');
    Route::post('profile/update_profile_privacy',  [ProfileController::class, 'updateProfilePrivacy']);
    Route::get('account_settings',  [AccountSettingController::class, 'index'])->name('profile.account_settings');
    Route::get('account_settings/notification_setting',  [AccountSettingController::class, 'notificationSetting'])->name('account_settings.notificationSetting');
    Route::post('update_account_setting',  [AccountSettingController::class, 'updateAccountSetting']);
    Route::get('delete_account',  [AccountSettingController::class, 'deleteAccount'])->name('account.delete');
    Route::post('account_settings/update_notification_setting',  [AccountSettingController::class, 'updateNotificationSetting']);

    Route::get('account_settings/message_privacy',  [AccountSettingController::class, 'messagePrivacy'])->name('account_settings.messagePrivacy');
    Route::post('account_settings/update_message_privacy',  [AccountSettingController::class, 'updateMessagePrivacy']);

    Route::post('upload',  [ProfileController::class, 'uploadProfile'])->name('profile.upload');
    Route::post('upload_bg_profile',  [ProfileController::class, 'uploadBgProfile'])->name('profile.uploadbgprofile');
});

Route::controller(AuthController::class)->group(function () {

    Route::get('login', 'create')->name('auth.login')->middleware('isAuthenticate');
    Route::post('login', 'checkLogin')->name('auth.checkLogin');
    Route::get('register', 'register')->name('auth.register')->middleware('isAuthenticate');
    Route::post('store_register', 'userRegister')->name('store.register');
    Route::post('check-email', 'checkEmailExistence');

    Route::get('login/{provider}', [SocialController::class, 'redirectToProvider']);
    Route::get('login/{provider}/callback', [SocialController::class, 'handleProviderCallback']);

    // Route::get('register', function () {

    //     $data['page'] = 'admin/auth/register';

    //     $data['js'] = ['login'];

    //     return view('admin/auth/main', $data);
    // });

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

        return redirect('login');
    })->name('logout');
});
