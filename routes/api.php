<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ApiControllerv1 as ApiControllerv1;
use App\Http\Controllers\ApiControllerv2 as ApiControllerv2;
use App\Http\Controllers\ApiControllerv3 as ApiControllerv3;
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
Route::get('verify/{token}', [ApiAuthController::class, 'verifyAccount'])->name('user.verify');
Route::post('/user/forgotpassword', [ApiAuthController::class, 'passwordLink']);
Route::post('/user/verify_otp', [ApiAuthController::class, 'verifyOtp']);
Route::post('/user/reset_password', [ApiAuthController::class, 'resetPassword']);




Route::prefix('user/v2/')->group(function () {
    Route::post('login', [ApiAuthController::class, 'login']);
    Route::post('social_login', [ApiAuthController::class, 'socialLogin']);
    Route::post('signup', [ApiAuthController::class, 'signup']);
    Route::get('verify/{token}', [ApiAuthController::class, 'verifyAccount']);
    Route::post('forgotpassword', [ApiAuthController::class, 'passwordLink']);
    Route::post('verify_otp', [ApiAuthController::class, 'verifyOtp']);
    Route::post('reset_password', [ApiAuthController::class, 'resetPassword']);
});




Route::prefix('user/v3/')->group(function () {
    Route::post('login', [ApiAuthController::class, 'login']);
    Route::post('social_login', [ApiAuthController::class, 'socialLogin']);
    Route::post('signup', [ApiAuthController::class, 'signup']);
    Route::get('verify/{token}', [ApiAuthController::class, 'verifyAccount']);
    Route::post('forgotpassword', [ApiAuthController::class, 'passwordLink']);
    Route::post('verify_otp', [ApiAuthController::class, 'verifyOtp']);
    Route::post('reset_password', [ApiAuthController::class, 'resetPassword']);
});
// M 31/08/2023 //




Route::get('send_thanks', [ApiControllerv2::class, 'sendThanks']);
Route::get('install_android_app', [ApiControllerv2::class, 'installAndroidApp']);
Route::get('install_ios_app', [ApiControllerv2::class, 'installIosApp']);
Route::get('sendSMS', [ApiControllerv2::class, 'sendSMS']);

Route::get('send_thanks', [ApiControllerv3::class, 'sendThanks']);
Route::get('install_android_app', [ApiControllerv3::class, 'installAndroidApp']);
Route::get('install_ios_app', [ApiControllerv3::class, 'installIosApp']);
Route::get('sendSMS', [ApiControllerv3::class, 'sendSMS']);

Route::prefix('user/v2/')->middleware('checkUser')->group(function () {

    Route::post('upload_application', [ApiControllerv2::class, 'uploadApplication']);

    Route::get('home', [ApiControllerv2::class, 'home']);
    Route::post('create_professional_account', [ApiControllerv2::class, 'createProfessionalAccount']);
    Route::post('update_profile', [ApiControllerv2::class, 'updateProfile']);
    Route::post('update_profile_or_bg_profile', [ApiControllerv2::class, 'updateProfileOrBgProfile']);
    Route::post('remove_profile', [ApiControllerv2::class, 'removeProfile']);
    Route::post('my_profile', [ApiControllerv2::class, 'myProfile']);
    Route::post('change_password', [ApiControllerv2::class, 'changePassword']);
    Route::post('privacy_setting', [ApiControllerv2::class, 'privacySetting']);
    Route::post('message_privacy_setting', [ApiControllerv2::class, 'MessageprivacySetting']);
    Route::post('general_setting', [ApiControllerv2::class, 'generalSetting']);
    Route::post('delete_account', [ApiControllerv2::class, 'deleteAccount']);
    Route::get('get_event_type', [ApiControllerv2::class, 'getEventType']);
    Route::post('get_design_list', [ApiControllerv2::class, 'getDesignList']);

    Route::get('get_design_style_option_data_list', [ApiControllerv2::class, 'getDesignStyleOptionDataList']);

    Route::post('get_design_option_data_list', [ApiControllerv2::class, 'getDesignOptionDataList']);
    Route::get('get_yesvite_contact_list', [ApiControllerv2::class, 'getYesviteContactList']);
    Route::post('get_yesvite_contact_list_page', [ApiControllerv2::class, 'getYesviteContactListPage']);
    Route::post('get_yesvite_selected_user_list_page', [ApiControllerv2::class, 'getYesviteSelectedUserListPage']);
    Route::post('add_contact', [ApiControllerv2::class, 'addContact']);
    Route::post('edit_contact', [ApiControllerv2::class, 'editContact']);
    Route::post('create_event', [ApiControllerv2::class, 'createEvent']);
    Route::post('edit_event', [ApiControllerv2::class, 'editEvent']);
    Route::post('set_user_event_create_step', [ApiControllerv2::class, 'setUserEventCreateStep']);
    Route::get('draft_event_list', [ApiControllerv2::class, 'draftEventList']);
    Route::post('get_event_data', [ApiControllerv2::class, 'getEventData']);
    Route::post('get_single_event', [ApiControllerv2::class, 'getSingleEvent']);

    Route::post('create_greeting_card', [ApiControllerv2::class, 'createGreetingCard']);
    Route::post('update_greeting_card', [ApiControllerv2::class, 'updateGreetingCard']);
    Route::post('delete_greeting_card', [ApiControllerv2::class, 'deleteGreetingCard']);
    Route::get('get_greeting_card_list', [ApiControllerv2::class, 'getGreetingCardList']);

    Route::post('create_gift_registry', [ApiControllerv2::class, 'createGiftregistry']);
    Route::post('update_gift_registry', [ApiControllerv2::class, 'updateGiftregistry']);
    Route::post('delete_gift_registry', [ApiControllerv2::class, 'deleteGiftregistry']);
    Route::get('get_gift_registry_list', [ApiControllerv2::class, 'getGiftRegistryList']);

    Route::post('store_event_image', [ApiControllerv2::class, 'storeEventImage']);
    Route::post('delete_event', [ApiControllerv2::class, 'deleteEvent']);
    Route::post('invite_user', [ApiControllerv2::class, 'inviteUser']);
    Route::post('event_list', [ApiControllerv2::class, 'EventList']);
    Route::post('event_list_for_calendar', [ApiControllerv2::class, 'eventListForCalendar']);
    Route::post('pending_rsvp_event_list', [ApiControllerv2::class, 'pendingRsvpEventList']);
    Route::post('sent_rsvp', [ApiControllerv2::class, 'sentRsvp']);
    Route::post('event_about', [ApiControllerv2::class, 'eventAbout']);
    Route::post('event_about_v2', [ApiControllerv2::class, 'eventAboutv2']);
    Route::post('create_post', [ApiControllerv2::class, 'createPost']);
    Route::post('post_control', [ApiControllerv2::class, 'postControl']);
    Route::post('post_media_report', [ApiControllerv2::class, 'postMediaReport']);

    Route::post('delete_post', [ApiControllerv2::class, 'deletePost']);
    Route::post('create_event_post_photo', [ApiControllerv2::class, 'createEventPostPhoto']);
    Route::post('event_wall', [ApiControllerv2::class, 'eventWall']);
    Route::post('event_wall_manage', [ApiControllerv2::class, 'eventWallManage']);
    Route::post('event_post_detail', [ApiControllerv2::class, 'eventPostDetail']);

    Route::post('create_story', [ApiControllerv2::class, 'createStory']);
    Route::post('user_seen_story', [ApiControllerv2::class, 'userSeenStory']);
    Route::post('delete_story', [ApiControllerv2::class, 'deleteStory']);

    Route::post('post_comment_reply_list', [ApiControllerv2::class, 'postCommentReplyList']);

    Route::post('user_post_like_dislike', [ApiControllerv2::class, 'userPostLikeDislike']);
    Route::post('user_post_photo_like_dislike', [ApiControllerv2::class, 'userPostPhotoLikeDislike']);

    Route::post('user_post_comment', [ApiControllerv2::class, 'userPostComment']);
    Route::post('user_post_comment_reply', [ApiControllerv2::class, 'userPostCommentReply']);
    Route::post('user_post_comment_reply_reaction', [ApiControllerv2::class, 'userPostCommentReplyReaction']);

    Route::post('user_post_photo_comment', [ApiControllerv2::class, 'userPostPhotoComment']);
    Route::post('user_post_photo_comment_reply', [ApiControllerv2::class, 'userPostPhotoCommentReply']);
    Route::post('user_post_photo_comment_reply_reaction', [ApiControllerv2::class, 'userPostPhotoCommentReplyReaction']);

    Route::post('event_post_photo_list', [ApiControllerv2::class, 'eventPostPhotoList']);
    Route::post('event_post_photo_list1', [ApiControllerv2::class, 'eventPostPhotoList1']);
    Route::post('remove_event_post_photo', [ApiControllerv2::class, 'removeEventPostPhoto']);

    Route::post('event_post_photo_detail', [ApiControllerv2::class, 'eventPostPhotoDetail']);


    Route::post('event_post_photo_list_filter', [ApiControllerv2::class, 'eventPostPhotoListFilter']);
    Route::post('post_photo_comment_reply_list', [ApiControllerv2::class, 'postPhotoCommentReplyList']);


    // potluck category and item add or edit //
    Route::post('add_potluck_category', [ApiControllerv2::class, 'addPotluckCategory']);
    Route::post('edit_potluck_category', [ApiControllerv2::class, 'editPotluckCategory']);
    Route::post('add_potluck_category_item', [ApiControllerv2::class, 'addPotluckCategoryItem']);
    Route::post('edit_potluck_category_item', [ApiControllerv2::class, 'editPotluckCategoryItem']);
    Route::post('event_potluck_category_delete', [ApiControllerv2::class, 'EventpotluckCategoryDelete']);
    Route::post('add_user_potluck_item', [ApiControllerv2::class, 'addUserPotluckItem']);
    Route::post('edit_user_potluck_item', [ApiControllerv2::class, 'editUserPotluckItem']);
    Route::post('delete_user_potluck_item', [ApiControllerv2::class, 'deleteUserPotluckItem']);
    Route::post('delete_potluck', [ApiControllerv2::class, 'deletePotluck']);

    Route::post('user_vote_of_poll', [ApiControllerv2::class, 'userVoteOfPoll']);
    Route::post('event_guest', [ApiControllerv2::class, 'eventGuest']);

    Route::post('faild_invites', [ApiControllerv2::class, 'faildInvites']);
    Route::post('send_invitation', [ApiControllerv2::class, 'sendInvitation']);

    Route::post('remove_guest_from_invite', [ApiControllerv2::class, 'removeGuestFromInvite']);
    Route::post('delete_contact', [ApiControllerv2::class, 'deleteContact']);

    Route::post('notification_list', [ApiControllerv2::class, 'notificationList']);
    Route::post('delete_notification', [ApiControllerv2::class, 'deleteNotification']);
    Route::post('notification_read_unread', [ApiControllerv2::class, 'notificationReadUnread']);
    Route::get('notification_all_read', [ApiControllerv2::class, 'notificationAllRead']);
    Route::get('get_events_list', [ApiControllerv2::class, 'getEventsList']);
    Route::post('event_potluck', [ApiControllerv2::class, 'eventPotluck']);

    Route::post('create_group', [ApiControllerv2::class, 'createGroup']);
    Route::post('group_list', [ApiControllerv2::class, 'groupList']);
    Route::post('delete_group', [ApiControllerv2::class, 'deleteGroup']);
    Route::post('add_group_member', [ApiControllerv2::class, 'addGroupMember']);
    Route::post('member_list', [ApiControllerv2::class, 'memberList']);
    Route::post('remove_user_from_group', [ApiControllerv2::class, 'removeUserFromGroup']);
    Route::post('my_account', [ApiControllerv2::class, 'myAccount']);
    Route::get('get_notification_setting', [ApiControllerv2::class, 'getNotificationSetting']);
    Route::post('notification_setting', [ApiControllerv2::class, 'notificationSetting']);
    Route::post('regenarate_token', [ApiControllerv2::class, 'regenarateToken']);
    Route::post('accept_reject_co_host', [ApiControllerv2::class, 'acceptRejectCoHost']);
    Route::post('notification_on_off', [ApiControllerv2::class, 'notificationOnOff']);
    Route::post('add_subscription', [ApiControllerv2::class, 'addSubscription']);
    Route::post('add_product_subscription', [ApiControllerv2::class, 'addProductSubscription']);
    Route::get('check_subscription', [ApiControllerv2::class, 'checkSubscription']);
    Route::post('send_app_link', [ApiControllerv2::class, 'appInviteLink']);



    Route::get('notificationtest', [ApiControllerv2::class, 'notificationtest']);

    Route::get('logout', [ApiControllerv2::class, 'logout']);
});


Route::prefix('user/v3/')->middleware('checkUser')->group(function () {

    Route::post('upload_application', [ApiControllerv3::class, 'uploadApplication']);

    Route::get('home', [ApiControllerv3::class, 'home']);
    Route::post('create_professional_account', [ApiControllerv3::class, 'createProfessionalAccount']);
    Route::post('update_profile', [ApiControllerv3::class, 'updateProfile']);
    Route::post('update_profile_or_bg_profile', [ApiControllerv3::class, 'updateProfileOrBgProfile']);
    Route::post('remove_profile', [ApiControllerv3::class, 'removeProfile']);
    Route::post('my_profile', [ApiControllerv3::class, 'myProfile']);
    Route::post('change_password', [ApiControllerv3::class, 'changePassword']);
    Route::post('privacy_setting', [ApiControllerv3::class, 'privacySetting']);
    Route::post('message_privacy_setting', [ApiControllerv3::class, 'MessageprivacySetting']);
    Route::post('general_setting', [ApiControllerv3::class, 'generalSetting']);
    Route::post('delete_account', [ApiControllerv3::class, 'deleteAccount']);
    Route::get('get_event_type', [ApiControllerv3::class, 'getEventType']);
    Route::post('get_design_list', [ApiControllerv3::class, 'getDesignList']);

    Route::get('get_design_style_option_data_list', [ApiControllerv3::class, 'getDesignStyleOptionDataList']);

    Route::post('get_design_option_data_list', [ApiControllerv3::class, 'getDesignOptionDataList']);
    Route::get('get_yesvite_contact_list', [ApiControllerv3::class, 'getYesviteContactList']);
    Route::post('get_yesvite_contact_list_page', [ApiControllerv3::class, 'getYesviteContactListPage']);

    Route::post('add_contact', [ApiControllerv3::class, 'addContact']);
    Route::post('edit_contact', [ApiControllerv3::class, 'editContact']);
    Route::post('create_event', [ApiControllerv3::class, 'createEvent']);
    Route::post('edit_event', [ApiControllerv3::class, 'editEvent']);
    Route::post('set_user_event_create_step', [ApiControllerv3::class, 'setUserEventCreateStep']);
    Route::get('draft_event_list', [ApiControllerv3::class, 'draftEventList']);
    Route::post('get_event_data', [ApiControllerv3::class, 'getEventData']);

    Route::post('create_greeting_card', [ApiControllerv3::class, 'createGreetingCard']);
    Route::post('update_greeting_card', [ApiControllerv3::class, 'updateGreetingCard']);
    Route::post('delete_greeting_card', [ApiControllerv3::class, 'deleteGreetingCard']);
    Route::get('get_greeting_card_list', [ApiControllerv3::class, 'getGreetingCardList']);

    Route::post('create_gift_registry', [ApiControllerv3::class, 'createGiftregistry']);
    Route::post('update_gift_registry', [ApiControllerv3::class, 'updateGiftregistry']);
    Route::post('delete_gift_registry', [ApiControllerv3::class, 'deleteGiftregistry']);
    Route::get('get_gift_registry_list', [ApiControllerv3::class, 'getGiftRegistryList']);

    Route::post('store_event_image', [ApiControllerv3::class, 'storeEventImage']);
    Route::post('delete_event', [ApiControllerv3::class, 'deleteEvent']);
    Route::post('invite_user', [ApiControllerv3::class, 'inviteUser']);
    Route::post('event_list', [ApiControllerv3::class, 'EventList']);
    Route::post('event_list_for_calendar', [ApiControllerv3::class, 'eventListForCalendar']);
    Route::post('pending_rsvp_event_list', [ApiControllerv3::class, 'pendingRsvpEventList']);
    Route::post('sent_rsvp', [ApiControllerv3::class, 'sentRsvp']);
    Route::post('event_about', [ApiControllerv3::class, 'eventAbout']);
    Route::post('event_about_v2', [ApiControllerv3::class, 'eventAboutv2']);
    Route::post('create_post', [ApiControllerv3::class, 'createPost']);
    Route::post('post_control', [ApiControllerv3::class, 'postControl']);
    Route::post('post_media_report', [ApiControllerv3::class, 'postMediaReport']);

    Route::post('delete_post', [ApiControllerv3::class, 'deletePost']);
    Route::post('create_event_post_photo', [ApiControllerv3::class, 'createEventPostPhoto']);
    Route::post('event_wall', [ApiControllerv3::class, 'eventWall']);
    Route::post('event_wall_manage', [ApiControllerv3::class, 'eventWallManage']);
    Route::post('event_post_detail', [ApiControllerv3::class, 'eventPostDetail']);

    Route::post('create_story', [ApiControllerv3::class, 'createStory']);
    Route::post('user_seen_story', [ApiControllerv3::class, 'userSeenStory']);
    Route::post('delete_story', [ApiControllerv3::class, 'deleteStory']);

    Route::post('post_comment_reply_list', [ApiControllerv3::class, 'postCommentReplyList']);

    Route::post('user_post_like_dislike', [ApiControllerv3::class, 'userPostLikeDislike']);
    Route::post('user_post_photo_like_dislike', [ApiControllerv3::class, 'userPostPhotoLikeDislike']);

    Route::post('user_post_comment', [ApiControllerv3::class, 'userPostComment']);
    Route::post('user_post_comment_reply', [ApiControllerv3::class, 'userPostCommentReply']);
    Route::post('user_post_comment_reply_reaction', [ApiControllerv3::class, 'userPostCommentReplyReaction']);

    Route::post('user_post_photo_comment', [ApiControllerv3::class, 'userPostPhotoComment']);
    Route::post('user_post_photo_comment_reply', [ApiControllerv3::class, 'userPostPhotoCommentReply']);
    Route::post('user_post_photo_comment_reply_reaction', [ApiControllerv3::class, 'userPostPhotoCommentReplyReaction']);

    Route::post('event_post_photo_list', [ApiControllerv3::class, 'eventPostPhotoList']);
    Route::post('event_post_photo_list1', [ApiControllerv3::class, 'eventPostPhotoList1']);
    Route::post('remove_event_post_photo', [ApiControllerv3::class, 'removeEventPostPhoto']);

    Route::post('event_post_photo_detail', [ApiControllerv3::class, 'eventPostPhotoDetail']);
    Route::post('get_single_event', [ApiControllerv3::class, 'getSingleEvent']);


    Route::post('event_post_photo_list_filter', [ApiControllerv3::class, 'eventPostPhotoListFilter']);
    Route::post('post_photo_comment_reply_list', [ApiControllerv3::class, 'postPhotoCommentReplyList']);


    // potluck category and item add or edit //
    Route::post('add_potluck_category', [ApiControllerv3::class, 'addPotluckCategory']);
    Route::post('edit_potluck_category', [ApiControllerv3::class, 'editPotluckCategory']);
    Route::post('add_potluck_category_item', [ApiControllerv3::class, 'addPotluckCategoryItem']);
    Route::post('edit_potluck_category_item', [ApiControllerv3::class, 'editPotluckCategoryItem']);
    Route::post('event_potluck_category_delete', [ApiControllerv3::class, 'EventpotluckCategoryDelete']);
    Route::post('add_user_potluck_item', [ApiControllerv3::class, 'addUserPotluckItem']);
    Route::post('edit_user_potluck_item', [ApiControllerv3::class, 'editUserPotluckItem']);
    Route::post('delete_user_potluck_item', [ApiControllerv3::class, 'deleteUserPotluckItem']);
    Route::post('delete_potluck', [ApiControllerv3::class, 'deletePotluck']);

    Route::post('user_vote_of_poll', [ApiControllerv3::class, 'userVoteOfPoll']);
    Route::post('event_guest', [ApiControllerv3::class, 'eventGuest']);

    Route::post('faild_invites', [ApiControllerv3::class, 'faildInvites']);
    Route::post('send_invitation', [ApiControllerv3::class, 'sendInvitation']);

    Route::post('remove_guest_from_invite', [ApiControllerv3::class, 'removeGuestFromInvite']);
    Route::post('delete_contact', [ApiControllerv3::class, 'deleteContact']);

    Route::post('notification_list', [ApiControllerv3::class, 'notificationList']);
    Route::post('delete_notification', [ApiControllerv3::class, 'deleteNotification']);
    Route::post('notification_read_unread', [ApiControllerv3::class, 'notificationReadUnread']);
    Route::get('notification_all_read', [ApiControllerv3::class, 'notificationAllRead']);
    Route::get('get_events_list', [ApiControllerv3::class, 'getEventsList']);
    Route::post('event_potluck', [ApiControllerv3::class, 'eventPotluck']);

    Route::post('create_group', [ApiControllerv3::class, 'createGroup']);
    Route::post('group_list', [ApiControllerv3::class, 'groupList']);
    Route::post('delete_group', [ApiControllerv3::class, 'deleteGroup']);
    Route::post('add_group_member', [ApiControllerv3::class, 'addGroupMember']);
    Route::post('member_list', [ApiControllerv3::class, 'memberList']);
    Route::post('remove_user_from_group', [ApiControllerv3::class, 'removeUserFromGroup']);
    Route::post('my_account', [ApiControllerv3::class, 'myAccount']);
    Route::get('get_notification_setting', [ApiControllerv3::class, 'getNotificationSetting']);
    Route::post('notification_setting', [ApiControllerv3::class, 'notificationSetting']);
    Route::post('regenarate_token', [ApiControllerv3::class, 'regenarateToken']);
    Route::post('accept_reject_co_host', [ApiControllerv3::class, 'acceptRejectCoHost']);
    Route::post('notification_on_off', [ApiControllerv3::class, 'notificationOnOff']);
    Route::post('add_subscription', [ApiControllerv3::class, 'addSubscription']);
    Route::post('add_product_subscription', [ApiControllerv3::class, 'addProductSubscription']);
    Route::get('check_subscription', [ApiControllerv3::class, 'checkSubscription']);



    Route::get('notificationtest', [ApiControllerv3::class, 'notificationtest']);

    Route::get('logout', [ApiControllerv3::class, 'logout']);
});
