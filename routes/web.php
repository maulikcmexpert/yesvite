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
    AccountSettingController,
    RsvpController,
    ChatController,
    PrivacyPolicyController,
    TermsAndConditionController
};
use Illuminate\Support\Facades\Auth;

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


Route::get('/', [HomeFrontController::class, 'index'])->name('front.home')->middleware('isAuthenticate');
Route::get('about-us', [AboutController::class, 'index'])->name('about');
Route::get('privacy_policy', [PrivacyPolicyController::class, 'index'])->name('privacy_policy');
Route::get('term_and_condition', [TermsAndConditionController::class, 'index'])->name('term_and_condition');
// Route::get('contact', [ContactController::class, 'index'])->name('contact');
Route::get('rsvp/{userId}/{eventId}', [RsvpController::class, 'index'])->name('rsvp');
Route::post('rsvp/store', [RsvpController::class, 'store'])->name('rsvp.store');




Route::get('add_account', [AuthController::class, 'addAccount'])->name('auth.add_account');
Route::post('check_add_account', [AuthController::class, 'checkAddAccount'])->name('auth.checkAddAccount');

Route::middleware('checkUserExist')->group(function () {


    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/import-csv', [HomeController::class, 'importCSV'])->name('import.csv');
    Route::get('profile',  [ProfileController::class, 'index'])->name('profile');
    Route::get('profile/edit',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('profile/change_password',  [ProfileController::class, 'changePassword'])->name('profile.change_password');

    Route::post('profile/verify_password', [ProfileController::class, 'verifyPassword'])->name('profile.verify_password');

    Route::post('profile/update/{id}',  [ProfileController::class, 'update'])->name('profile.update');
    Route::post('profile/check-phonenumber', [ProfileController::class, 'checkPhoneNumberExistence']);

    Route::post('profile/check_new_contactnumber', [ProfileController::class, 'checkNewContactNumber']);



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

    Route::get('contact',  [ContactController::class, 'index'])->name('profile.contact');

    Route::post('contacts/load', [ContactController::class, 'loadMore'])->name('.loadMore');
    Route::post('contacts/loadgroups', [ContactController::class, 'loadMoreGroup'])->name('.loadMoreGroup');
    Route::post('contacts/loadphones', [ContactController::class, 'loadMorePhones'])->name('.loadMorePhones');

    Route::post('contacts/check_new_contactemail', [ContactController::class, 'checkNewContactEmail']);

    Route::post('contacts/add/{id}', [ContactController::class, 'addContact'])->name('.addcontact');

    Route::post('contacts/edit/{id}', [ContactController::class, 'editContact'])->name('editcontact');

    Route::post('contacts/save_edit', [ContactController::class, 'save_editContact'])->name('.saveeditcontact');
    Route::get('messages',  [ChatController::class, 'index'])->name('message.list');
    Route::post('getChat',  [ChatController::class, 'getChat'])->name('message.getChat');
    Route::post('getConversation',  [ChatController::class, 'getConversation'])->name('message.getConversation');
    Route::post('updateUserinFB',  [ChatController::class, 'updateUserinFB'])->name('message.updateUserinFB');
    Route::get('/autocomplete-users', [ChatController::class, 'autocomplete'])->name('autocomplete.users');
});

Route::get('access_token', [AuthController::class, 'handleGoogleCallback'])->name('access_token');

Route::controller(AuthController::class)->group(function () {

    Route::get('google/auth', 'redirectToGoogle');
    Route::get('google/callback', 'handleGoogleCallback')->name('google/callback');

    Route::get('login', 'create')->name('auth.login')->middleware('isAuthenticate');
    Route::post('login', 'checkLogin')->name('auth.checkLogin');

    Route::get('register', 'register')->name('auth.register')->middleware('isAuthenticate');
    Route::post('store_register', 'userRegister')->name('store.register');
    Route::post('check-email', 'checkEmailExistence');
    Route::post('check_mail', 'checkEmail');


    Route::post('advertisement_status', 'storeAdvertisementStatus');
    Route::get('forget_password', 'forgetpassword')->name('auth.forgetpassword');
    Route::post('otp_verify', 'otpverification')->name('auth.otpverification');
    Route::post('check_otp', 'checkOtp')->name('auth.checkOtp');
    Route::post('forget_changepassword', 'forgetChangepassword')->name('auth.forgetChangepassword');
    Route::get('get_access_token', 'getAccessToken');




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

    Route::get('switch_account/{id}', 'switchAccount')->name('switchAccount');
    Route::get('/logout', function () {

        $user = Auth::guard('web')->user();
        add_user_firebase($user->id, 'offline');
        Auth::logout();
        // Invalidate the session and regenerate the CSRF token to prevent session fixation attacks
        Session::forget('advertisement_closed');
        Session::forget('user');
        Session::forget('secondary_user');
        return redirect('login');
    })->name('logout');
});

Route::fallback(function () {
    $title = "No Found";

    $page = 'not_found';

    return view('layout', compact(
        'title',
        'page',
    ));
});
