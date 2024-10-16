<?php



use Illuminate\Support\Facades\Route;

use App\Http\Controllers\admin\Dashboard;

use App\Http\Controllers\admin\Auth;

use App\Http\Controllers\admin\{
    CategoryController,
    DesignStyleController,
    SubCategoryController,
    DesignController,
    ProfessionalUserController,
    UserController,
    EventController,
    EventTypeController,
    UserPostReportController,
    UserChatReportController,
    TemplateController,
    EditTempalteController,
    UserResendEmailVerify
};

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
        Route::get('dashboard', 'index');
        Route::post('dashboard/get_upcoming_event', 'getUpcomingEvent')->name('getEventData');
        Route::get('dashboard/check_email_verify_html', 'checkEmailverifyhtml');
    });

    Route::resources([

        'category' => CategoryController::class,
        'subcategory' => SubCategoryController::class,
        'design_style' => DesignStyleController::class,
        'design' => DesignController::class,
        'users' => UserController::class,
        'professional_users' => ProfessionalUserController::class,
        'events' => EventController::class,
        'event_type' => EventTypeController::class,
        'user_post_report' => UserPostReportController::class,
        'create_template' => TemplateController::class,
        'user_chat_report' => UserChatReportController::class,
        'user_resend_verification'=>UserResendEmailVerify::class

    ]);
    Route::get('template/view/{id}', [TemplateController::class, 'View_template'])->name('template.view');

    // Route::post('/get_all_subcategory', [EditTempalteController::class, 'get_all_subcategory'])->name('get_all_subcategory');

    Route::get('template/edit_template/{id}', [EditTempalteController::class, 'index'])->name('create_template.edit_template');
    Route::post('/saveTextData', [EditTempalteController::class, 'saveTextData'])->name('saveTextData');
    // Route::post('/saveCanvasImage', [EditTempalteController::class, 'saveCanvasImage'])->name('saveCanvasImage');
    Route::post('/uploadImage', [EditTempalteController::class, 'uploadImage'])->name('uploadImage');
    Route::get('/loadTextData/{id}', [EditTempalteController::class, 'loadTextData']);
    Route::post('/saveData', [EditTempalteController::class, 'saveData'])->name('saveData');
    Route::get('/templates/view', [EditTempalteController::class, 'AllImage'])->name('displayAllImage');
    Route::get('/templates/view', [EditTempalteController::class, 'viewAllImages'])->name('viewAllImages');
    Route::get('/loadAllData', [EditTempalteController::class, 'loadAllData']);
    // Route::post('/user_image/{id}', [EditTempalteController::class, 'user_image']);
    // routes/web.php
    Route::post('/saveImagePath', action: [EditTempalteController::class, 'storeImagePath']);

    Route::post('create_template/get_all_subcategory', [TemplateController::class, 'get_all_subcategory'])->name('get_all_subcategory');


    Route::post('user/check_new_contactemail', [UserController::class, 'checkNewContactEmail']);

    Route::post('user/check_new_contactnumber', [UserController::class, 'checkNewContactNumber']);

    Route::post('category/check_category_is_exist', [CategoryController::class, 'checkCategoryIsExist'])->name('category_check_exist');
    Route::get('delete_post_report', [UserPostReportController::class, 'deletePostReport'])->name('delete_post_report');
    Route::get('re_send_email/{id}', [UserResendEmailVerify::class, 're_send_email'])->name('re_send_email');


    Route::post('subcategory/check_subcategory_is_exist', [SubCategoryController::class, 'checkSubCategoryIsExist'])->name('category_check_exist');
    Route::post('design_style/check_design_style_is_exist', [DesignStyleController::class, 'checkDesignStyleIsExist'])->name('category_check_exist');

    Route::post('design/get_subcatdata', [DesignController::class, 'getSubCatData']);
    Route::post('design/get_selected_subcatdata', [DesignController::class, 'getSelectedSubcatdata']);
    Route::post('events/get_events_by_date', [EventController::class, 'getEventsByDate']);
    Route::post('events/invited_users', [EventController::class, 'invitedUsers'])->name("invited_user_data");
    Route::get('events/event_posts/{event_id}', [EventController::class, 'eventPosts'])->name("eventPosts");

    Route::post('event_type/check_event_type_is_exist', [EventTypeController::class, 'checkEventTypeIsExist'])->name('category_check_exist');
})->prefix('admin');



Route::controller(Auth::class)->group(function () {

    Route::get('/', function () {

        $data['page'] = 'admin/auth/login';

        $data['js'] = ['login'];

        return view('admin/auth/main', $data);
    });



    Route::get('/factor_authenticate/{id}', 'twoFactorAuthenticate');
    Route::post('/check_factor_authentication', 'checkFactorAuthentication');



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
