<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ApiContactController;
use App\Http\Controllers\ApiController;
// use App\Http\Controllers\ApiControllerv1 as ApiControllerv1;
use App\Http\Controllers\ApiControllerv1 as ApiControllerv1;
use App\Http\Controllers\ApiControllerv2 as ApiControllerv2;
use App\Http\Controllers\ApiControllerv3 as ApiControllerv3;
use App\Http\Controllers\TwilioController;
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


Route::post('/twilio-webhook', [TwilioController::class, 'handleIncomingMessage']);
Route::get('/twilio-deliverystatus', [TwilioController::class, 'deliverystatus']);
Route::post('/twilio-deliverystatus', [TwilioController::class, 'deliverystatus']);

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
    Route::post('draft_event_list', [ApiControllerv2::class, 'draftEventList']);
    Route::post('get_event_data', [ApiControllerv2::class, 'getEventData']);
    Route::post('get_single_event', [ApiControllerv2::class, 'getSingleEvent']);

    Route::post('create_greeting_card', [ApiControllerv2::class, 'createGreetingCard']);
    Route::post('update_greeting_card', [ApiControllerv2::class, 'updateGreetingCard']);
    Route::post('delete_greeting_card', [ApiControllerv2::class, 'deleteGreetingCard']);
    Route::post('get_greeting_card_list', [ApiControllerv2::class, 'getGreetingCardList']);

    Route::post('create_gift_registry', [ApiControllerv2::class, 'createGiftregistry']);
    Route::post('update_gift_registry', [ApiControllerv2::class, 'updateGiftregistry']);
    Route::post('delete_gift_registry', [ApiControllerv2::class, 'deleteGiftregistry']);
    Route::post('get_gift_registry_list', [ApiControllerv2::class, 'getGiftRegistryList']);

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
    Route::post('update_post', [ApiControllerv2::class, 'updatePost']);
    Route::post('post_control', [ApiControllerv2::class, 'postControl']);
    Route::post('post_media_report', [ApiControllerv2::class, 'postMediaReport']);
    Route::post('chat_report', [ApiControllerv2::class, 'chatReport']);

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
    Route::post('remove_event_post_single_photo', [ApiControllerv2::class, 'removeEventPostSinglePhoto']);

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

    Route::post('get_all_template_data', [ApiControllerv2::class, 'getAllTemplateData']);
    Route::post('get_single_template_data', [ApiControllerv2::class, 'getSingleTemplateData']);

    Route::post('check_user_notification', [ApiControllerv2::class, 'checkUserNotification']);

    Route::get('notificationtest', [ApiControllerv2::class, 'notificationtest']);

    Route::get('logout', [ApiControllerv2::class, 'logout']);
    Route::post('sync_contact', [ApiContactController::class, 'sync_contact']);
    Route::post('coin_transactions', [ApiControllerv2::class, 'coin_transactions']);
    Route::post('coin_graph', [ApiControllerv2::class, 'coin_graph']);
    Route::post('search_tags', [ApiControllerv2::class, 'searchTags']);
    Route::get('pricing_list', [ApiControllerv2::class, 'pricing_list']);
    Route::get('check_category_adv', [ApiControllerv2::class, 'check_category_adv']);
});

Route::prefix('user/v1/')->middleware('checkUser')->group(function () {

    Route::post('upload_application', [ApiControllerv1::class, 'uploadApplication']);

    Route::get('home', [ApiControllerv1::class, 'home']);
    Route::post('create_professional_account', [ApiControllerv1::class, 'createProfessionalAccount']);
    Route::post('update_profile', [ApiControllerv1::class, 'updateProfile']);
    Route::post('update_profile_or_bg_profile', [ApiControllerv1::class, 'updateProfileOrBgProfile']);
    Route::post('remove_profile', [ApiControllerv1::class, 'removeProfile']);
    Route::post('my_profile', [ApiControllerv1::class, 'myProfile']);
    Route::post('change_password', [ApiControllerv1::class, 'changePassword']);
    Route::post('privacy_setting', [ApiControllerv1::class, 'privacySetting']);
    Route::post('message_privacy_setting', [ApiControllerv1::class, 'MessageprivacySetting']);
    Route::post('general_setting', [ApiControllerv1::class, 'generalSetting']);
    Route::post('delete_account', [ApiControllerv1::class, 'deleteAccount']);
    Route::get('get_event_type', [ApiControllerv1::class, 'getEventType']);
    Route::post('get_design_list', [ApiControllerv1::class, 'getDesignList']);

    Route::get('get_design_style_option_data_list', [ApiControllerv1::class, 'getDesignStyleOptionDataList']);

    Route::post('get_design_option_data_list', [ApiControllerv1::class, 'getDesignOptionDataList']);
    Route::get('get_yesvite_contact_list', [ApiControllerv1::class, 'getYesviteContactList']);
    Route::post('get_yesvite_contact_list_page', [ApiControllerv1::class, 'getYesviteContactListPage']);
    Route::post('get_yesvite_selected_user_list_page', [ApiControllerv1::class, 'getYesviteSelectedUserListPage']);
    Route::post('add_contact', [ApiControllerv1::class, 'addContact']);
    Route::post('edit_contact', [ApiControllerv1::class, 'editContact']);
    Route::post('create_event', [ApiControllerv1::class, 'createEvent']);
    Route::post('edit_event', [ApiControllerv1::class, 'editEvent']);
    Route::post('set_user_event_create_step', [ApiControllerv1::class, 'setUserEventCreateStep']);
    Route::post('draft_event_list', [ApiControllerv1::class, 'draftEventList']);
    Route::post('get_event_data', [ApiControllerv1::class, 'getEventData']);
    Route::post('get_single_event', [ApiControllerv1::class, 'getSingleEvent']);

    Route::post('create_greeting_card', [ApiControllerv1::class, 'createGreetingCard']);
    Route::post('update_greeting_card', [ApiControllerv1::class, 'updateGreetingCard']);
    Route::post('delete_greeting_card', [ApiControllerv1::class, 'deleteGreetingCard']);
    Route::post('get_greeting_card_list', [ApiControllerv1::class, 'getGreetingCardList']);

    Route::post('create_gift_registry', [ApiControllerv1::class, 'createGiftregistry']);
    Route::post('update_gift_registry', [ApiControllerv1::class, 'updateGiftregistry']);
    Route::post('delete_gift_registry', [ApiControllerv1::class, 'deleteGiftregistry']);
    Route::post('get_gift_registry_list', [ApiControllerv1::class, 'getGiftRegistryList']);

    Route::post('store_event_image', [ApiControllerv1::class, 'storeEventImage']);
    Route::post('delete_event', [ApiControllerv1::class, 'deleteEvent']);
    Route::post('invite_user', [ApiControllerv1::class, 'inviteUser']);
    Route::post('event_list', [ApiControllerv1::class, 'EventList']);
    Route::post('event_list_for_calendar', [ApiControllerv1::class, 'eventListForCalendar']);
    Route::post('pending_rsvp_event_list', [ApiControllerv1::class, 'pendingRsvpEventList']);
    Route::post('sent_rsvp', [ApiControllerv1::class, 'sentRsvp']);
    Route::post('event_about', [ApiControllerv1::class, 'eventAbout']);
    Route::post('event_about_v2', [ApiControllerv1::class, 'eventAboutv2']);
    Route::post('create_post', [ApiControllerv1::class, 'createPost']);
    Route::post('update_post', [ApiControllerv1::class, 'updatePost']);
    Route::post('post_control', [ApiControllerv1::class, 'postControl']);
    Route::post('post_media_report', [ApiControllerv1::class, 'postMediaReport']);
    Route::post('chat_report', [ApiControllerv1::class, 'chatReport']);

    Route::post('delete_post', [ApiControllerv1::class, 'deletePost']);
    Route::post('create_event_post_photo', [ApiControllerv1::class, 'createEventPostPhoto']);
    Route::post('event_wall', [ApiControllerv1::class, 'eventWall']);
    Route::post('event_wall_manage', [ApiControllerv1::class, 'eventWallManage']);
    Route::post('event_post_detail', [ApiControllerv1::class, 'eventPostDetail']);

    Route::post('create_story', [ApiControllerv1::class, 'createStory']);
    Route::post('user_seen_story', [ApiControllerv1::class, 'userSeenStory']);
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
    Route::post('event_post_photo_list1', [ApiControllerv1::class, 'eventPostPhotoList1']);
    Route::post('remove_event_post_photo', [ApiControllerv1::class, 'removeEventPostPhoto']);
    Route::post('remove_event_post_single_photo', [ApiControllerv1::class, 'removeEventPostSinglePhoto']);

    Route::post('event_post_photo_detail', [ApiControllerv1::class, 'eventPostPhotoDetail']);


    Route::post('event_post_photo_list_filter', [ApiControllerv1::class, 'eventPostPhotoListFilter']);
    Route::post('post_photo_comment_reply_list', [ApiControllerv1::class, 'postPhotoCommentReplyList']);


    // potluck category and item add or edit //
    Route::post('add_potluck_category', [ApiControllerv1::class, 'addPotluckCategory']);
    Route::post('edit_potluck_category', [ApiControllerv1::class, 'editPotluckCategory']);
    Route::post('add_potluck_category_item', [ApiControllerv1::class, 'addPotluckCategoryItem']);
    Route::post('edit_potluck_category_item', [ApiControllerv1::class, 'editPotluckCategoryItem']);
    Route::post('event_potluck_category_delete', [ApiControllerv1::class, 'EventpotluckCategoryDelete']);
    Route::post('add_user_potluck_item', [ApiControllerv1::class, 'addUserPotluckItem']);
    Route::post('edit_user_potluck_item', [ApiControllerv1::class, 'editUserPotluckItem']);
    Route::post('delete_user_potluck_item', [ApiControllerv1::class, 'deleteUserPotluckItem']);
    Route::post('delete_potluck', [ApiControllerv1::class, 'deletePotluck']);

    Route::post('user_vote_of_poll', [ApiControllerv1::class, 'userVoteOfPoll']);
    Route::post('event_guest', [ApiControllerv1::class, 'eventGuest']);

    Route::post('faild_invites', [ApiControllerv1::class, 'faildInvites']);
    Route::post('send_invitation', [ApiControllerv1::class, 'sendInvitation']);

    Route::post('remove_guest_from_invite', [ApiControllerv1::class, 'removeGuestFromInvite']);
    Route::post('delete_contact', [ApiControllerv1::class, 'deleteContact']);

    Route::post('notification_list', [ApiControllerv1::class, 'notificationList']);
    Route::post('delete_notification', [ApiControllerv1::class, 'deleteNotification']);
    Route::post('notification_read_unread', [ApiControllerv1::class, 'notificationReadUnread']);
    Route::get('notification_all_read', [ApiControllerv1::class, 'notificationAllRead']);
    Route::get('get_events_list', [ApiControllerv1::class, 'getEventsList']);
    Route::post('event_potluck', [ApiControllerv1::class, 'eventPotluck']);

    Route::post('create_group', [ApiControllerv1::class, 'createGroup']);
    Route::post('group_list', [ApiControllerv1::class, 'groupList']);
    Route::post('delete_group', [ApiControllerv1::class, 'deleteGroup']);
    Route::post('add_group_member', [ApiControllerv1::class, 'addGroupMember']);
    Route::post('member_list', [ApiControllerv1::class, 'memberList']);
    Route::post('remove_user_from_group', [ApiControllerv1::class, 'removeUserFromGroup']);
    Route::post('my_account', [ApiControllerv1::class, 'myAccount']);
    Route::get('get_notification_setting', [ApiControllerv1::class, 'getNotificationSetting']);
    Route::post('notification_setting', [ApiControllerv1::class, 'notificationSetting']);
    Route::post('regenarate_token', [ApiControllerv1::class, 'regenarateToken']);
    Route::post('accept_reject_co_host', [ApiControllerv1::class, 'acceptRejectCoHost']);
    Route::post('notification_on_off', [ApiControllerv1::class, 'notificationOnOff']);
    Route::post('add_subscription', [ApiControllerv1::class, 'addSubscription']);
    Route::post('add_product_subscription', [ApiControllerv1::class, 'addProductSubscription']);
    Route::get('check_subscription', [ApiControllerv1::class, 'checkSubscription']);
    Route::post('send_app_link', [ApiControllerv1::class, 'appInviteLink']);

    Route::post('get_all_template_data', [ApiControllerv1::class, 'getAllTemplateData']);
    Route::post('get_single_template_data', [ApiControllerv1::class, 'getSingleTemplateData']);

    Route::post('check_user_notification', [ApiControllerv1::class, 'checkUserNotification']);

    Route::get('notificationtest', [ApiControllerv1::class, 'notificationtest']);

    Route::get('logout', [ApiControllerv1::class, 'logout']);
    Route::post('sync_contact', [ApiContactController::class, 'sync_contact']);
    Route::post('coin_transactions', [ApiControllerv1::class, 'coin_transactions']);
    Route::post('coin_graph', [ApiControllerv1::class, 'coin_graph']);
    Route::post('search_tags', [ApiControllerv1::class, 'searchTags']);
    Route::get('pricing_list', [ApiControllerv1::class, 'pricing_list']);
    Route::get('check_category_adv', [ApiControllerv1::class, 'check_category_adv']);
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
    Route::post('draft_event_list', [ApiControllerv3::class, 'draftEventList']);
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

    Route::post('get_yesvite_selected_user_list_page', [ApiControllerv1::class, 'getYesviteSelectedUserListPage']);
});
