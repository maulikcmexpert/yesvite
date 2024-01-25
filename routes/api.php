<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ApiControllerv1;
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
Route::post('/user/social_login', [ApiAuthController::class, 'socialLogin']);
Route::post('/user/signup', [ApiAuthController::class, 'signup']);
// Route::get('verify/{token}', [ApiAuthController::class, 'verifyAccount'])->name('user.verify');
Route::post('/user/forgotpassword', [ApiAuthController::class, 'passwordLink']);
Route::post('/user/verify_otp', [ApiAuthController::class, 'verifyOtp']);
Route::post('/user/reset_password', [ApiAuthController::class, 'resetPassword']);


Route::prefix('user/v1/')->group(function () {
    Route::post('login', [ApiAuthController::class, 'login']);
    Route::post('social_login', [ApiAuthController::class, 'socialLogin']);
    Route::post('signup', [ApiAuthController::class, 'signup']);
    Route::get('verify/{token}', [ApiAuthController::class, 'verifyAccount'])->name('user.verify');
    Route::post('forgotpassword', [ApiAuthController::class, 'passwordLink']);
    Route::post('verify_otp', [ApiAuthController::class, 'verifyOtp']);
    Route::post('reset_password', [ApiAuthController::class, 'resetPassword']);
});
// M 31/08/2023 //


Route::prefix('user/v1/')->middleware('checkUser')->group(function () {

    Route::get('send_thanks', [ApiControllerv1::class, 'sendThanks']);
    Route::get('home', [ApiControllerv1::class, 'home']);
    Route::post('create_professional_account', [ApiControllerv1::class, 'createProfessionalAccount']);
    Route::post('update_profile', [ApiControllerv1::class, 'updateProfile']);
    Route::post('update_profile_or_bg_profile', [ApiControllerv1::class, 'updateProfileOrBgProfile']);
    Route::post('my_profile', [ApiControllerv1::class, 'myProfile']);
    Route::post('privacy_setting', [ApiControllerv1::class, 'privacySetting']);
    Route::post('general_setting', [ApiControllerv1::class, 'generalSetting']);
    Route::post('delete_account', [ApiControllerv1::class, 'deleteAccount']);
    Route::get('get_event_type', [ApiControllerv1::class, 'getEventType']);
    Route::post('get_design_list', [ApiControllerv1::class, 'getDesignList']);

    Route::get('get_design_style_option_data_list', [ApiControllerv1::class, 'getDesignStyleOptionDataList']);

    Route::get('get_design_option_data_list', [ApiControllerv1::class, 'getDesignOptionDataList']);
    Route::get('get_yesvite_contact_list', [ApiControllerv1::class, 'getYesviteContactList']);
    Route::post('add_contact', [ApiControllerv1::class, 'addContact']);
    Route::post('edit_contact', [ApiControllerv1::class, 'editContact']);
    Route::post('create_event', [ApiControllerv1::class, 'createEvent']);
    Route::post('edit_event', [ApiControllerv1::class, 'editEvent']);
    Route::get('draft_event_list', [ApiControllerv1::class, 'draftEventList']);
    Route::post('get_event_data', [ApiControllerv1::class, 'getEventData']);

    Route::post('create_greeting_card', [ApiControllerv1::class, 'createGreetingCard']);
    Route::post('update_greeting_card', [ApiControllerv1::class, 'updateGreetingCard']);
    Route::post('delete_greeting_card', [ApiControllerv1::class, 'deleteGreetingCard']);
    Route::get('get_greeting_card_list', [ApiControllerv1::class, 'getGreetingCardList']);

    Route::post('create_gift_registry', [ApiControllerv1::class, 'createGiftregistry']);
    Route::post('update_gift_registry', [ApiControllerv1::class, 'updateGiftregistry']);
    Route::post('delete_gift_registry', [ApiControllerv1::class, 'deleteGiftregistry']);
    Route::get('get_gift_registry_list', [ApiControllerv1::class, 'getGiftRegistryList']);

    Route::post('store_event_image', [ApiControllerv1::class, 'storeEventImage']);
    Route::post('delete_event', [ApiControllerv1::class, 'deleteEvent']);
    Route::post('invite_user', [ApiControllerv1::class, 'inviteUser']);
    Route::post('event_list', [ApiControllerv1::class, 'EventList']);
    Route::post('sent_rsvp', [ApiControllerv1::class, 'sentRsvp']);
    Route::post('event_about', [ApiControllerv1::class, 'eventAbout']);
    Route::post('create_post', [ApiControllerv1::class, 'createPost']);
    Route::post('post_control', [ApiControllerv1::class, 'postControl']);
    Route::post('delete_post', [ApiControllerv1::class, 'deletePost']);
    Route::post('create_event_post_photo', [ApiControllerv1::class, 'createEventPostPhoto']);
    Route::post('event_wall', [ApiControllerv1::class, 'eventWall']);
    Route::post('event_wall_manage', [ApiControllerv1::class, 'eventWallManage']);
    Route::post('event_post_detail', [ApiControllerv1::class, 'eventPostDetail']);

    Route::post('create_story', [ApiControllerv1::class, 'createStory']);
    Route::post('delete_story', [ApiControllerv1::class, 'deleteStory']);

    Route::post('post_comment_reply_list', [ApiControllerv1::class, 'postCommentReplyList']);

    Route::post('user_post_like_dislike', [ApiControllerv1::class, 'userPostLikeDislike']);
    Route::post('user_post_photo_like_dislike', [ApiControllerv1::class, 'userPostPhotoLikeDislike']);

    Route::post('user_post_comment', [ApiControllerv1::class, 'userPostComment']);
    Route::post('user_post_comment_reply', [ApiControllerv1::class, 'userPostCommentReply']);
    Route::post('user_post_comment_reply_reaction', [ApiControllerv1::class, 'userPostCommentReplyReaction']);

    Route::post('user_post_photo_comment', [ApiControllerv1::class, 'userPostPhotoComment']);
    Route::post('user_post_photo_comment_reply', [ApiControllerv1::class, 'userPostPhotoCommentReply']);
    Route::post('user_post_photo_comment_reply_reaction', [ApiControllerv1::class, 'userPostPhotoCommentReplyReaction']);
    Route::post('event_post_photo_list', [ApiControllerv1::class, 'eventPostPhotoList']);
    Route::post('event_post_photo_detail', [ApiControllerv1::class, 'eventPostPhotoDetail']);


    Route::post('event_post_photo_list_filter', [ApiControllerv1::class, 'eventPostPhotoListFilter']);
    Route::post('post_photo_comment_reply_list', [ApiControllerv1::class, 'postPhotoCommentReplyList']);


    // potluck category and item add or edit //
    Route::post('add_potluck_category', [ApiControllerv1::class, 'addPotluckCategory']);
    Route::post('add_potluck_category_item', [ApiControllerv1::class, 'addPotluckCategoryItem']);
    Route::post('event_potluck_category_delete', [ApiControllerv1::class, 'EventpotluckCategoryDelete']);


    Route::post('user_vote_of_poll', [ApiControllerv1::class, 'userVoteOfPoll']);
    Route::post('event_guest', [ApiControllerv1::class, 'eventGuest']);
    Route::post('faild_invites', [ApiControllerv1::class, 'faildInvites']);
    Route::post('send_invitation', [ApiControllerv1::class, 'sendInvitation']);
    Route::post('notification_list', [ApiControllerv1::class, 'notificationList']);
    Route::post('event_potluck', [ApiControllerv1::class, 'eventPotluck']);

    Route::get('logout', [ApiControllerv1::class, 'logout']);
});

Route::prefix('user')->middleware('checkUser')->group(function () {

    Route::get('home', [ApiController::class, 'home']);
    Route::post('create_professional_account', [ApiController::class, 'createProfessionalAccount']);
    Route::post('update_profile', [ApiController::class, 'updateProfile']);
    Route::post('update_profile_or_bg_profile', [ApiController::class, 'updateProfileOrBgProfile']);
    Route::post('my_profile', [ApiController::class, 'myProfile']);
    Route::post('privacy_setting', [ApiController::class, 'privacySetting']);
    Route::post('general_setting', [ApiController::class, 'generalSetting']);
    Route::post('delete_account', [ApiController::class, 'deleteAccount']);
    Route::get('get_event_type', [ApiController::class, 'getEventType']);
    Route::post('get_design_list', [ApiController::class, 'getDesignList']);
    Route::get('get_yesvite_contact_list', [ApiController::class, 'getYesviteContactList']);
    Route::post('add_contact', [ApiController::class, 'addContact']);
    Route::post('edit_contact', [ApiController::class, 'editContact']);
    Route::post('create_event', [ApiController::class, 'createEvent']);
    Route::post('store_event_image', [ApiController::class, 'storeEventImage']);
    Route::post('delete_event', [ApiController::class, 'deleteEvent']);
    Route::post('invite_user', [ApiController::class, 'inviteUser']);
    Route::post('event_list', [ApiController::class, 'EventList']);
    Route::post('sent_rsvp', [ApiController::class, 'sentRsvp']);
    Route::post('event_about', [ApiController::class, 'eventAbout']);
    Route::post('event_view_by_user', [ApiController::class, 'eventViewByUser']);
    Route::post('create_post', [ApiController::class, 'createPost']);
    Route::post('create_event_post_photo', [ApiController::class, 'createEventPostPhoto']);
    Route::post('event_wall', [ApiController::class, 'eventWall']);
    Route::post('create_story', [ApiController::class, 'createStory']);

    Route::post('post_comment_reply_list', [ApiController::class, 'postCommentReplyList']);

    Route::post('user_post_like_dislike', [ApiController::class, 'userPostLikeDislike']);
    Route::post('user_post_photo_like_dislike', [ApiController::class, 'userPostPhotoLikeDislike']);

    Route::post('user_post_comment', [ApiController::class, 'userPostComment']);
    Route::post('user_post_comment_reply', [ApiController::class, 'userPostCommentReply']);
    Route::post('user_post_comment_reply_reaction', [ApiController::class, 'userPostCommentReplyReaction']);

    Route::post('user_post_photo_comment', [ApiController::class, 'userPostPhotoComment']);
    Route::post('user_post_photo_comment_reply', [ApiController::class, 'userPostPhotoCommentReply']);
    Route::post('user_post_photo_comment_reply_reaction', [ApiController::class, 'userPostPhotoCommentReplyReaction']);
    Route::post('event_post_photo_list', [ApiController::class, 'eventPostPhotoList']);
    Route::post('post_photo_comment_reply_list', [ApiController::class, 'postPhotoCommentReplyList']);


    // potluck category and item add or edit //
    Route::post('add_potluck_category', [ApiController::class, 'addPotluckCategory']);
    Route::post('add_potluck_category_item', [ApiController::class, 'addPotluckCategoryItem']);
    Route::post('event_potluck_category_delete', [ApiController::class, 'EventpotluckCategoryDelete']);


    Route::post('user_vote_of_poll', [ApiController::class, 'userVoteOfPoll']);
    Route::post('event_guest', [ApiController::class, 'eventGuest']);
    Route::post('faild_invites', [ApiController::class, 'faildInvites']);
    Route::post('send_invitation', [ApiController::class, 'sendInvitation']);
    Route::post('notification_list', [ApiController::class, 'notificationList']);

    Route::get('logout', [ApiController::class, 'logout']);
});

// Route::get('notificationtest', [ApiControllerv1::class, 'notificationtest']);


// Route::prefix('user')->controller(ApiAuthController::class)->group(function () {
//     Route::post('/user/login', 'login');
//     Route::post('/user/signup', 'signup');
// });
