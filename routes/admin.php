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
    UserPostReportController
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
        'user_post_report' => UserPostReportController::class
    ]);


    Route::post('category/check_category_is_exist', [CategoryController::class, 'checkCategoryIsExist'])->name('category_check_exist');

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
