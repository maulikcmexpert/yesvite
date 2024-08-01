<?php

use App\Models\EventPost;
use App\Models\Event;
use App\Models\Device;
use App\Models\EventInvitedUser;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Owenoj\LaravelGetId3\GetId3;
use App\Mail\InvitationEmail;
use App\Models\PostControl;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendInvitationMailJob as sendInvitation;
use App\Mail\NewRsvpsEmailNotify;
use App\Models\EventImage;
use App\Models\EventPostImage;
use App\Models\EventPostComment;
use App\Models\UserNotificationType;
use App\Mail\OwnInvitationEmail;
use App\Models\EventSetting;
use App\Models\ServerKey;
use App\Models\UserSubscription;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Auth;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;


function getVideoDuration($filePath)
{


    $track = new GetId3($filePath);

    // Use static methods:
    $track = GetId3::fromUploadedFile($filePath);

    //get all info
    $track->extractInfo();


    //get title
    $track->getTitle();

    //get playtime
    return  $track->getPlaytime();
}




function checkIsimageOrVideo($postImage)
{
    $extension  = $postImage->getClientOriginalExtension();
    $mime = $postImage->getClientMimeType();

    if (in_array($extension, ['jpeg', 'jpg', 'png', 'gif']) || strpos($mime, 'image') !== false) {
        return 'image';
    } elseif (in_array($extension, ['mp4', 'avi', 'mov', 'wmv']) || strpos($mime, 'video') !== false) {
        return 'video';
    } elseif (str_contains($mime, 'audio')) {
        return 'record';
    }
}

function checkNotificationSetting($userId)
{
    $checkNotificationSetting = UserNotificationType::where('user_id', $userId)->get();
    $notification  = [];
    foreach ($checkNotificationSetting as $val) {
        $notification[$val->type] = [
            "push" => $val->push,
            "email" => $val->email,
        ];
    }
    return $notification;
}

function getGuestPendingRsvpCount($eventId)
{
    return  EventInvitedUser::whereHas('user', function ($query) {

        $query->where('app_user', '1');
    })->where(['event_id' => $eventId, 'rsvp_d' => '0'])->count();
}

function sendNotification($notificationType, $postData)
{

    //'invite', 'upload_post', 'like_post', 'comment', 'reply', 'poll', 'rsvp'
    $senderData = User::where('id', $postData['sender_id'])->first();
    if ($notificationType == 'owner_notify') {
        $event = Event::with('event_image', 'event_schedule')->where('id', $postData['event_id'])->first();
        $event_time = "";
        if ($event->event_schedule->isNotEmpty()) {

            $event_time =  $event->event_schedule->first()->start_time;
        }

        $eventData = [
            'event_name' => $event->event_name,
            'event_image' => ($event->event_image->isNotEmpty()) ? $event->event_image[0]->image : "no_image.png",
            'date' =>   date('l - M jS, Y', strtotime($event->start_date)),
            'time' => $event_time,
        ];

        $invitation_email = new OwnInvitationEmail($eventData);
        Mail::to($senderData->email)->send($invitation_email);
    }
    $notification_message = "";

    $invitedusers = EventInvitedUser::with(['event', 'event.event_settings', 'event.event_schedule', 'user'])->whereHas('user', function ($query) {
        //  $query->where('app_user', '1');
    })->where('event_id', $postData['event_id'])->get();

    if ($notificationType == 'invite') {
        if (count($invitedusers) != 0) {
            if (isset($postData['newUser']) && count($postData['newUser']) != 0) {
                $invitedusers = EventInvitedUser::with(['event', 'event.event_image', 'event.user', 'event.event_settings', 'event.event_schedule', 'user'])->whereHas('user', function ($query) {
                    //  $query->where('app_user', '1');
                })->whereIn('user_id', $postData['newUser'])->where('event_id', $postData['event_id'])->get();
            }
            foreach ($invitedusers as $value) {

                // user notification setting //
                Notification::where(['user_id' => $value->user_id, 'sender_id' => $postData['sender_id'], 'event_id' => $postData['event_id']])->delete();

                $notification_message = " have invited you to: " . $value->event->event_name;
                if ($value->is_co_host == '1') {
                    $notification_message = "invited you to co-host " . $value->event->event_name . ' Accept?';
                }
                $notification = new Notification;
                $notification->event_id = $postData['event_id'];
                $notification->user_id = $value->user_id;
                $notification->notification_type = $notificationType;
                $notification->sender_id = $postData['sender_id'];
                $notification->notification_message = $notification_message;

                if ($notification->save()) {

                    $deviceData = Device::where('user_id', $value->user_id)->first();
                    if (!empty($deviceData)) {

                        $notificationImage = EventImage::where('event_id', $postData['event_id'])->first();

                        $notification_image = "";
                        if ($notificationImage != NULL) {

                            $notification_image = asset('public/storage/event_images/' . $notificationImage->image);
                        }
                        $notificationData = [
                            'message' => $notification_message,
                            'type' => $notificationType,
                            'notification_image' => $notification_image,
                            'event_id' => $postData['event_id'],
                            'event_name' => $value->event->event_name,
                            'event_wall' => $value->event->event_settings->event_wall,
                            'guest_list_visible_to_guests' => $value->event->event_settings->guest_list_visible_to_guests,
                            'event_potluck' => $value->event->event_settings->podluck,
                            'guest_pending_count' => getGuestPendingRsvpCount($postData['event_id'])
                        ];

                        $checkNotificationSetting = checkNotificationSetting($value->user_id);
                        if ((count($checkNotificationSetting) && $checkNotificationSetting['invitations']['push'] == '1') &&  $value->notification_on_off == '1') {

                            if ($deviceData->model == 'And') {

                                send_notification_FCM_and($deviceData->device_token, $notificationData);
                            }

                            if ($deviceData->model == 'Ios') {

                                send_notification_FCM($deviceData->device_token, $notificationData);
                            }
                        }
                    }
                    $checkNotificationSetting = checkNotificationSetting($value->user_id);
                    if ($value->prefer_by == 'email') {

                        if ($value->user->app_user == '1' &&  count($checkNotificationSetting) != 0 && $checkNotificationSetting['invitations']['email'] == '1') {


                            $event_time = "";
                            if ($value->event->event_schedule->isNotEmpty()) {

                                $event_time = $value->event->event_schedule->first()->start_time;
                            }

                            $eventData = [
                                'event_name' => $value->event->event_name,
                                'hosted_by' => $value->event->user->firstname . ' ' . $value->event->user->lastname,
                                'profileUser' => ($value->event->user->profile != NULL || $value->event->user->profile != "") ? $value->event->user->profile : "no_profile.png",
                                'event_image' => ($value->event->event_image->isNotEmpty()) ? $value->event->event_image[0]->image : "no_image.png",
                                'date' =>   date('l - M jS, Y', strtotime($value->event->start_date)),
                                'time' => $event_time,
                                'address' => $value->event->event_location_name . ' ' . $value->event->address_1 . ' ' . $value->event->address_2 . ' ' . $value->event->state . ' ' . $value->event->city . ' - ' . $value->event->zip_code,
                            ];

                            $emailCheck = dispatch(new sendInvitation(array($value->user->email, $eventData)));

                            if (!empty($emailCheck)) {

                                $updateinvitation = EventInvitedUser::where(['event_id' => $postData['event_id'], 'user_id' => $value->user_id, 'prefer_by' => 'email'])->first();
                                $updateinvitation->invitation_sent = '1';
                                $updateinvitation->save();
                            }
                        } else {
                            $event_time = "";
                            if ($value->event->event_schedule->isNotEmpty()) {

                                $event_time = $value->event->event_schedule->first()->start_time;
                            }

                            $eventData = [
                                'event_name' => $value->event->event_name,
                                'hosted_by' => $value->event->user->firstname . ' ' . $value->event->user->lastname,
                                'profileUser' => ($value->event->user->profile != NULL || $value->event->user->profile != "") ? $value->event->user->profile : "no_profile.png",
                                'event_image' => ($value->event->event_image->isNotEmpty()) ? $value->event->event_image[0]->image : "no_image.png",
                                'date' =>   date('l - M jS, Y', strtotime($value->event->start_date)),
                                'time' => $event_time,
                                'address' => $value->event->event_location_name . ' ' . $value->event->address_1 . ' ' . $value->event->address_2 . ' ' . $value->event->state . ' ' . $value->event->city . ' - ' . $value->event->zip_code,
                            ];

                            $emailCheck = dispatch(new sendInvitation(array($value->user->email, $eventData)));

                            if (!empty($emailCheck)) {

                                $updateinvitation = EventInvitedUser::where(['event_id' => $postData['event_id'], 'user_id' => $value->user_id, 'prefer_by' => 'email'])->first();
                                $updateinvitation->invitation_sent = '1';
                                $updateinvitation->save();
                            }
                        }
                    }


                    if ($value->prefer_by == 'phone') {

                        $sent = sendSMSForApplication($value->user->phone_number, $notification_message);

                        if ($sent == true) {
                            $updateinvitation = EventInvitedUser::where(['event_id' => $postData['event_id'], 'user_id' => $value->user_id, 'prefer_by' => 'phone'])->first();
                            $updateinvitation->invitation_sent = '1';
                            $updateinvitation->save();
                        }
                    }
                }
                // }
            }
        }
    }

    if ($notificationType == 'update_address' || $notificationType == 'update_time' || $notificationType == 'update_event' || $notificationType == 'update_date') {

        if (count($invitedusers) != 0) {

            foreach ($invitedusers as $value) {

                if (isset($postData['newUser']) && count($postData['newUser']) != 0) {
                    if (in_array($value->user_id, $postData['newUser'])) {
                        continue;
                    }
                }
                if ($value->user->app_user == '1') {
                    // Notification::where(['user_id' => $value->user_id, 'sender_id' => $postData['sender_id'], 'event_id' => $postData['event_id']])->delete();
                    $notification_message = " has updated the event details for " . $value->event->event_name;
                    $notification = new Notification;
                    $notification->event_id = $postData['event_id'];
                    $notification->user_id = $value->user_id;
                    $notification->notification_type = $notificationType;
                    $notification->sender_id = $postData['sender_id'];
                    if ($notificationType == 'update_address') {
                        $notification->from_addr = $postData['from_addr'];
                        $notification->to_addr = $postData['to_addr'];
                    } else if ($notificationType == 'update_time') {
                        $notification->from_time = $postData['from_time'];
                        $notification->to_time = $postData['to_time'];
                    } else if ($notificationType == 'update_date') {
                        $notification->old_start_end_date = $postData['old_start_end_date'];
                        $notification->new_start_end_date = $postData['new_start_end_date'];
                    }
                    $notification->notification_message = $notification_message;

                    if ($notification->save()) {

                        $deviceData = Device::where('user_id', $value->user_id)->first();
                        if (!empty($deviceData)) {

                            $notificationImage = EventImage::where('event_id', $postData['event_id'])->first();

                            $notification_image = "";
                            if ($notificationImage != NULL) {

                                $notification_image = asset('storage/event_images/' . $notificationImage->image);
                            }
                            $push_notification_message = $senderData->firstname . ' ' . $senderData->lastname . " has updated the event details for " . $value->event->event_name;
                            $notificationData = [
                                'message' => $notification_message,
                                'type' => $notificationType,
                                'notification_image' => $push_notification_message,
                                'event_id' => $postData['event_id'],
                                'event_name' => $value->event->event_name,
                                'event_wall' => $value->event->event_settings->event_wall,
                                'guest_list_visible_to_guests' => $value->event->event_settings->guest_list_visible_to_guests,
                                'event_potluck' => $value->event->event_settings->podluck,
                                'rsvp_status' => (isset($value->rsvp_status) && $value->rsvp_status != null) ? $value->rsvp_status : '',
                                'guest_pending_count' => getGuestPendingRsvpCount($postData['event_id'])

                            ];

                            if ($value->notification_on_off == '1') {

                                if ($deviceData->model == 'And') {

                                    send_notification_FCM_and($deviceData->device_token, $notificationData);
                                }

                                if ($deviceData->model == 'Ios') {

                                    send_notification_FCM($deviceData->device_token, $notificationData);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    if ($notificationType == 'accept_reject_co_host') {

        $getEventOwner = Event::with('event_settings')->where('id', $postData['event_id'])->first();
        if ($postData['status'] == '1') {
            $notification_message = $senderData->firstname . ' '  . $senderData->lastname . ' $accepted your invitation to co-host';
        } elseif ($postData['status'] == '2') {
            $notification_message = $senderData->firstname . ' '  . $senderData->lastname . ' $rejected your invitation to co-host';
        }
        $notification = new Notification;
        $notification->event_id = $postData['event_id'];
        $notification->user_id =  $getEventOwner->user_id;
        $notification->notification_type = $notificationType;
        $notification->sender_id = $postData['sender_id'];
        $notification->notification_message = $notification_message;
        if ($notification->save()) {
            $deviceData = Device::where('user_id', $getEventOwner->user_id)->first();
            if (!empty($deviceData)) {
                $notificationImage = EventImage::where('event_id', $postData['event_id'])->first();


                if ($notificationImage != NULL) {

                    $notification_image = asset('public/storage/event_images/' . $notificationImage->image);
                }

                if ($postData['status'] == '1') {
                    $push_notification_message = $senderData->firstname . ' '  . $senderData->lastname . ' accepted your invitation to co-host';
                } elseif ($postData['status'] == '2') {
                    $push_notification_message = $senderData->firstname . ' '  . $senderData->lastname . ' rejected your invitation to co-host';
                }
                $notificationData = [
                    'message' => $push_notification_message,
                    'type' => $notificationType,
                    'notification_image' => $notification_image,
                    'event_id' => $postData['event_id'],
                    'event_name' => $getEventOwner->event_name,
                    'event_wall' => $getEventOwner->event_settings->event_wall,
                ];


                if ($getEventOwner->notification_on_off == '1') {


                    if ($deviceData->model == 'And') {

                        send_notification_FCM_and($deviceData->device_token, $notificationData);
                    }

                    if ($deviceData->model == 'Ios') {

                        send_notification_FCM($deviceData->device_token, $notificationData);
                    }
                }
            }
        }
    }

    if ($notificationType == 'upload_post' || $notificationType == 'photos') {

        // post notify to  owner//
        $ownerEvent = Event::with('event_settings')->where('id', $postData['event_id'])->first();
        $postControl = PostControl::with('event_posts')->where(['event_id' => $ownerEvent->id, 'user_id' => $ownerEvent->user_id, 'post_control' => 'mute'])->get();
        $postOwneruserId = [];

        if (!$postControl->isEmpty()) {
            foreach ($postControl as $val) {
                if (isset($val->event_posts->user_id) && $val->event_posts->user_id != null) {
                    $postOwneruserId[] = $val->event_posts->user_id;
                }
            }
        }
        if (!in_array($postData['sender_id'], $postOwneruserId)) {

            if ($postData['sender_id'] != $ownerEvent->user_id) {
                $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " upload new post";
                $notification = new Notification;
                $notification->event_id = $postData['event_id'];
                $notification->post_id = $postData['post_id'];
                $notification->user_id = $ownerEvent->user_id;
                $notification->notification_type = $notificationType;
                $notification->sender_id = $postData['sender_id'];
                $notification->notification_message = $notification_message;

                if ($notification->save()) {

                    $deviceData = Device::where('user_id', $ownerEvent->user_id)->first();

                    if (!empty($deviceData)) {
                        $notificationImage = EventPostImage::where('event_post_id', $postData['post_id'])->first();
                        $notification_image = "";
                        if (!empty($notificationImage->post_image) && $notificationImage->post_image != NULL) {

                            $notification_image = asset('pubilc/storage/post_image/' . $notificationImage->post_image);
                        }
                        $notificationData = [
                            'message' => $notification_message,
                            'type' => $notificationType,
                            'notification_image' => $notification_image,
                            'post_id' => $postData['post_id'],
                            'is_in_photo_moudle' => $postData['is_in_photo_moudle'],
                            'post_type' => $postData['post_type'],
                            'event_wall' => isset($ownerEvent->event_settings->event_wall) ? $ownerEvent->event_settings->event_wall : '',
                            'guest_list_visible_to_guests' => isset($ownerEvent->event_settings->guest_list_visible_to_guests) ? $ownerEvent->event_settings->guest_list_visible_to_guests : '',
                            'is_event_owner' => 0,
                            'is_post_by_host' => 0,
                            'is_owner_post' => 0,
                        ];
                        $checkNotificationSetting = checkNotificationSetting($ownerEvent->user_id);

                        if ((count($checkNotificationSetting) && $checkNotificationSetting['wall_post']['push'] == '1') && $ownerEvent->notification_on_off == '1') {


                            if ($deviceData->model == 'And') {
                                send_notification_FCM_and($deviceData->device_token, $notificationData);
                            }

                            if ($deviceData->model == 'Ios') {

                                send_notification_FCM($deviceData->device_token, $notificationData);
                            }
                        }
                    }
                }
            }
        }

        // post notify to  owner//
        $is_post_by_host = ($postData['sender_id'] == $ownerEvent->user_id) ? 1 : 0;
        foreach ($invitedusers as $key => $value) {
            $is_event_owner =  ($value->user_id == $ownerEvent->user_id) ? 1 : 0;
            if ($postData['post_privacy'] == '1') {
                $postControl = PostControl::with('event_posts')->where(['event_id' => $postData['event_id'], 'user_id' => $value->user_id, 'post_control' => 'mute'])->get();
                $postOwneruserId = [];

                if (!$postControl->isEmpty()) {
                    foreach ($postControl as $val) {
                        if (isset($val->event_posts->user_id) && $val->event_posts->user_id != null) {
                            $postOwneruserId[] = $val->event_posts->user_id;
                        }
                    }
                }

                if (in_array($postData['sender_id'], $postOwneruserId)) {
                    continue;
                }
                if ($postData['sender_id'] == $value->user_id) {
                    continue;
                }
                $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " upload new post";
                $notification = new Notification;
                $notification->event_id = $postData['event_id'];
                $notification->post_id = $postData['post_id'];
                $notification->user_id = $value->user_id;
                $notification->notification_type = $notificationType;
                $notification->sender_id = $postData['sender_id'];
                $notification->notification_message = $notification_message;

                if ($notification->save()) {

                    $deviceData = Device::where('user_id', $value->user_id)->first();

                    if (!empty($deviceData)) {

                        $notificationImage = EventPostImage::where('event_post_id', $postData['post_id'])->first();
                        $notification_image = "";
                        if (!empty($notificationImage->post_image) && $notificationImage->post_image != NULL) {

                            $notification_image = asset('pubilc/storage/post_image/' . $notificationImage->post_image);
                        }
                        $notificationData = [
                            'message' => $notification_message,
                            'type' => $notificationType,
                            'notification_image' => $notification_image,
                            'post_id' => $postData['post_id'],
                            'is_in_photo_moudle' => $postData['is_in_photo_moudle'],
                            'post_type' => $postData['post_type'],
                            'event_wall' => isset($ownerEvent->event_settings->event_wall) ? $ownerEvent->event_settings->event_wall : '',
                            'guest_list_visible_to_guests' => isset($ownerEvent->event_settings->guest_list_visible_to_guests) ? $ownerEvent->event_settings->guest_list_visible_to_guests : '',
                            'is_event_owner' => $is_event_owner,
                            'is_post_by_host' => $is_post_by_host,
                            'is_owner_post' => 0,

                        ];
                        $checkNotificationSetting = checkNotificationSetting($value->user_id);

                        if ((count($checkNotificationSetting) && $checkNotificationSetting['wall_post']['push'] == '1') && $value->notification_on_off == '1') {

                            if ($deviceData->model == 'And') {

                                send_notification_FCM_and($deviceData->device_token, $notificationData);
                            }

                            if ($deviceData->model == 'Ios') {

                                send_notification_FCM($deviceData->device_token, $notificationData);
                            }
                        }
                    }
                }
            }
            if ($postData['post_privacy'] == '2' && $value->rsvp_status == '1' &&  $value->rsvp_d == '1') {

                $postControl = PostControl::with('event_posts')->where(['event_id' => $postData['event_id'], 'user_id' => $value->user_id, 'post_control' => 'mute'])->get();
                $postOwneruserId = [];

                if (!$postControl->isEmpty()) {
                    foreach ($postControl as $value) {
                        $postOwneruserId[] = $value->event_posts->user_id;
                    }
                }
                if (in_array($postData['sender_id'], $postOwneruserId)) {
                    continue;
                }
                if ($postData['sender_id'] == $value->user_id) {
                    continue;
                }
                $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " upload new post";
                $notification = new Notification;
                $notification->event_id = $postData['event_id'];
                $notification->post_id = $postData['post_id'];
                $notification->user_id = $value->user_id;
                $notification->notification_type = $notificationType;
                $notification->sender_id = $postData['sender_id'];
                $notification->notification_message = $notification_message;

                if ($notification->save()) {

                    $deviceData = Device::where('user_id', $value->user_id)->first();
                    if (!empty($deviceData)) {
                        $notificationImage = EventPostImage::where('event_post_id', $postData['post_id'])->first();
                        $notification_image = "";
                        if (!empty($notificationImage->post_image) && $notificationImage->post_image != NULL) {

                            $notification_image = asset('pubilc/storage/post_image/' . $notificationImage->post_image);
                        }
                        $notificationData = [
                            'message' => $notification_message,
                            'type' => $notificationType,
                            'notification_image' => $notification_image,
                            'post_id' => $postData['post_id'],
                            'is_in_photo_moudle' => $postData['is_in_photo_moudle'],
                            'post_type' => $postData['post_type'],
                            'event_wall' => isset($ownerEvent->event_settings->event_wall) ? $ownerEvent->event_settings->event_wall : '',
                            'guest_list_visible_to_guests' => isset($ownerEvent->event_settings->guest_list_visible_to_guests) ? $ownerEvent->event_settings->guest_list_visible_to_guests : '',
                            'is_event_owner' => $is_event_owner,
                            'is_post_by_host' => $is_post_by_host,
                            'is_owner_post' => 0,

                        ];
                        $checkNotificationSetting = checkNotificationSetting($value->user_id);

                        if ((count($checkNotificationSetting) && $checkNotificationSetting['wall_post']['push'] == '1') && $value->notification_on_off == '1') {

                            if ($deviceData->model == 'And') {

                                send_notification_FCM_and($deviceData->device_token, $notificationData);
                            }

                            if ($deviceData->model == 'Ios') {

                                send_notification_FCM($deviceData->device_token, $notificationData);
                            }
                        }
                    }
                }
            }

            if ($postData['post_privacy'] == '3' && $value->rsvp_status == '0' &&  $value->rsvp_d == '1') {
                $postControl = PostControl::with('event_posts')->where(['event_id' => $postData['event_id'], 'user_id' => $value->user_id, 'post_control' => 'mute'])->get();
                $postOwneruserId = [];

                if (!$postControl->isEmpty()) {
                    foreach ($postControl as $value) {
                        if (isset($value->event_posts->user_id) && $value->event_posts->user_id != null) {
                            $postOwneruserId[] = $value->event_posts->user_id;
                        }
                    }
                }
                if (in_array($postData['sender_id'], $postOwneruserId)) {
                    continue;
                }
                if ($postData['sender_id'] == $value->user_id) {
                    continue;
                }
                $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " upload new post";
                $notification = new Notification;
                $notification->event_id = $postData['event_id'];
                $notification->post_id = $postData['post_id'];
                $notification->user_id = $value->user_id;
                $notification->notification_type = $notificationType;
                $notification->sender_id = $postData['sender_id'];
                $notification->notification_message = $notification_message;

                if ($notification->save()) {

                    $deviceData = Device::where('user_id', $value->user_id)->first();
                    if (!empty($deviceData)) {
                        $notificationImage = EventPostImage::where('event_post_id', $postData['post_id'])->first();
                        $notification_image = "";
                        if (!empty($notificationImage->post_image) && $notificationImage->post_image != NULL) {

                            $notification_image = asset('pubilc/storage/post_image/' . $notificationImage->post_image);
                        }
                        $notificationData = [
                            'message' => $notification_message,
                            'type' => $notificationType,
                            'notification_image' => $notification_image,
                            'post_id' => $postData['post_id'],
                            'is_in_photo_moudle' => $postData['is_in_photo_moudle'],
                            'post_type' => $postData['post_type'],
                            'event_wall' => isset($ownerEvent->event_settings->event_wall) ? $ownerEvent->event_settings->event_wall : '',
                            'guest_list_visible_to_guests' => isset($ownerEvent->event_settings->guest_list_visible_to_guests) ? $ownerEvent->event_settings->guest_list_visible_to_guests : '',
                            'is_event_owner' => $is_event_owner,
                            'is_post_by_host' => $is_post_by_host,
                            'is_owner_post' => 0,

                        ];
                        $checkNotificationSetting = checkNotificationSetting($value->user_id);

                        if ((count($checkNotificationSetting) && $checkNotificationSetting['wall_post']['push'] == '1') && $value->notification_on_off == '1') {

                            if ($deviceData->model == 'And') {

                                send_notification_FCM_and($deviceData->device_token, $notificationData);
                            }

                            if ($deviceData->model == 'Ios') {

                                send_notification_FCM($deviceData->device_token, $notificationData);
                            }
                        }
                    }
                }
            }

            if ($postData['post_privacy'] == '4' &&  $value->rsvp_d == '0') {
                $postControl = PostControl::with('event_posts')->where(['event_id' => $postData['event_id'], 'user_id' => $value->user_id, 'post_control' => 'mute'])->get();
                $postOwneruserId = [];

                if (!$postControl->isEmpty()) {
                    foreach ($postControl as $value) {
                        if (isset($value->event_posts->user_id) && $value->event_posts->user_id != null) {
                            $postOwneruserId[] = $value->event_posts->user_id;
                        }
                    }
                }
                if (in_array($postData['sender_id'], $postOwneruserId)) {
                    continue;
                }
                if ($postData['sender_id'] == $value->user_id) {
                    continue;
                }
                $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " upload new post";
                $notification = new Notification;
                $notification->event_id = $postData['event_id'];
                $notification->post_id = $postData['post_id'];
                $notification->user_id = $value->user_id;
                $notification->notification_type = $notificationType;
                $notification->sender_id = $postData['sender_id'];
                $notification->notification_message = $notification_message;

                if ($notification->save()) {

                    $deviceData = Device::where('user_id', $value->user_id)->first();
                    if (!empty($deviceData)) {
                        $notificationImage = EventPostImage::where('event_post_id', $postData['post_id'])->first();
                        $notification_image = "";
                        if (!empty($notificationImage->post_image) && $notificationImage->post_image != NULL) {

                            $notification_image = asset('pubilc/storage/post_image/' . $notificationImage->post_image);
                        }
                        $notificationData = [
                            'message' => $notification_message,
                            'type' => $notificationType,
                            'notification_image' => $notification_image,
                            'post_id' => $postData['post_id'],
                            'is_in_photo_moudle' => $postData['is_in_photo_moudle'],
                            'post_type' => $postData['post_type'],
                            'event_wall' => isset($ownerEvent->event_settings->event_wall) ? $ownerEvent->event_settings->event_wall : '',
                            'guest_list_visible_to_guests' => isset($ownerEvent->event_settings->guest_list_visible_to_guests) ? $ownerEvent->event_settings->guest_list_visible_to_guests : '',
                            'is_event_owner' => $is_event_owner,
                            'is_post_by_host' => $is_post_by_host,
                            'is_owner_post' => 0,

                        ];
                        $checkNotificationSetting = checkNotificationSetting($value->user_id);

                        if ((count($checkNotificationSetting) && $checkNotificationSetting['wall_post']['push'] == '1') && $value->notification_on_off == '1') {

                            if ($deviceData->model == 'And') {

                                send_notification_FCM_and($deviceData->device_token, $notificationData);
                            }

                            if ($deviceData->model == 'Ios') {

                                send_notification_FCM($deviceData->device_token, $notificationData);
                            }
                        }
                    }
                }
            }
        }
    }

    if ($notificationType == 'like_post') {

        $getPostOwnerId = EventPost::where('id', $postData['post_id'])->first();
        $eventSetting = EventSetting::where('event_id', $postData['event_id'])->first();
        $notification_message = $senderData->firstname . ' '  . $senderData->lastname . " liked your post";
        if ($getPostOwnerId->post_type == '2') {
            $notification_message = $senderData->firstname . ' '  . $senderData->lastname . " liked your poll";
        }
        if ($getPostOwnerId->post_type == '3') {
            $notification_message = $senderData->firstname . ' '  . $senderData->lastname . " liked your audio";
        }

        if ($getPostOwnerId->user_id != $postData['sender_id']) {
            $notification = new Notification;
            $notification->event_id = $postData['event_id'];
            $notification->post_id = $postData['post_id'];
            $notification->user_id =  $getPostOwnerId->user_id;
            $notification->notification_type = $notificationType;
            $notification->sender_id = $postData['sender_id'];
            $notification->notification_message = $notification_message;

            if ($notification->save()) {
                $deviceData = Device::where('user_id', $getPostOwnerId->user_id)->first();
                if (!empty($deviceData)) {
                    $notificationImage = EventPostImage::where('event_post_id', $postData['post_id'])->first();
                    $notification_image = "";
                    if (!empty($notificationImage->post_image) && $notificationImage->post_image != NULL) {

                        $notification_image = asset('public/storage/post_image/' . $notificationImage->post_image);
                    }

                    $notificationData = [
                        'message' => $notification_message,
                        'type' => $notificationType,
                        'notification_image' => $notification_image,
                        'event_id' => $postData['event_id'],
                        'post_id' => $postData['post_id'],
                        'post_type' => $getPostOwnerId->post_type,
                        'is_in_photo_moudle' => $postData['is_in_photo_moudle'],
                        'event_wall' => isset($eventSetting->event_wall) ? $eventSetting->event_wall : '',
                        'guest_list_visible_to_guests' => isset($eventSetting->guest_list_visible_to_guests) ? $eventSetting->guest_list_visible_to_guests : '',
                        'is_post_by_host' => 0,
                        'is_event_owner' => 0,
                        'is_owner_post' => 0,

                    ];

                    $notification_on_off = isOwnerOrInvited($getPostOwnerId->user_id, $postData['event_id']);

                    $checkNotificationSetting = checkNotificationSetting($getPostOwnerId->user_id);

                    if ((count($checkNotificationSetting) && $checkNotificationSetting['wall_post']['push'] == '1') && $notification_on_off == '1') {

                        if ($deviceData->model == 'And') {

                            send_notification_FCM_and($deviceData->device_token, $notificationData);
                        }

                        if ($deviceData->model == 'Ios') {

                            send_notification_FCM($deviceData->device_token, $notificationData);
                        }
                    }


                    // send_notification_FCM($deviceData->device_token, $notification_message);
                }
            }
        }
    }

    if ($notificationType == 'reply_comment_reaction') {

        $getPostOwnerId = EventPost::where('id', $postData['post_id'])->first();
        $getCommentOwnerId = EventPostComment::where('id', $postData['comment_id'])->first();
        $getmainCommentOwnerId = EventPostComment::where('id', $getCommentOwnerId->parent_comment_id)->first();
        $notification_message = $senderData->firstname . ' '  . $senderData->lastname . " like your comment";

        $ownerOfComment = $getCommentOwnerId->user_id;
        $commentId = $getCommentOwnerId->id;
        if ($getmainCommentOwnerId != null) {
            $ownerOfComment = $getmainCommentOwnerId->user_id;
            $commentId = $getmainCommentOwnerId->id;
        }

        if ($ownerOfComment != $postData['sender_id']) {

            $notification = new Notification;
            $notification->event_id = $postData['event_id'];
            $notification->post_id = $postData['post_id'];
            $notification->comment_id = $commentId;
            $notification->user_id =  $ownerOfComment;
            $notification->notification_type = $notificationType;
            $notification->sender_id = $postData['sender_id'];
            $notification->notification_message = $notification_message;

            if ($notification->save()) {
                $deviceData = Device::where('user_id', $ownerOfComment)->first();
                if (!empty($deviceData)) {
                    $notificationImage = EventPostImage::where('event_post_id', $postData['post_id'])->first();
                    $notification_image = "";
                    if (!empty($notificationImage->post_image) && $notificationImage->post_image != NULL) {

                        $notification_image = asset('public/storage/post_image/' . $notificationImage->post_image);
                    }
                    $notificationData = [
                        'message' => $notification_message,
                        'type' => $notificationType,
                        'notification_image' => $notification_image,
                        'post_id' => $postData['post_id'],
                        'comment_id' => $postData['comment_id'],
                        'event_id' => $postData['event_id'],
                        'post_type' => $getPostOwnerId->post_type,

                    ];

                    $notification_on_off = isOwnerOrInvited($ownerOfComment, $postData['event_id'], $postData['post_id']);

                    $checkNotificationSetting = checkNotificationSetting($ownerOfComment);

                    if ((count($checkNotificationSetting) && $checkNotificationSetting['wall_post']['push'] == '1') && $notification_on_off == '1') {

                        if ($deviceData->model == 'And') {

                            send_notification_FCM_and($deviceData->device_token, $notificationData);
                        }

                        if ($deviceData->model == 'Ios') {

                            send_notification_FCM($deviceData->device_token, $notificationData);
                        }
                    }


                    // send_notification_FCM($deviceData->device_token, $notification_message);
                }
            }
        }
    }

    if ($notificationType == 'comment_post') {

        $getPostOwnerId = EventPost::where('id', $postData['post_id'])->first();

        $notification_message = $senderData->firstname . ' '  . $senderData->lastname . " commented on your post";
        if ($getPostOwnerId->post_type == '2') {
            $notification_message = $senderData->firstname . ' '  . $senderData->lastname . " commented on your poll";
        }
        if ($getPostOwnerId->post_type == '3') {
            $notification_message = $senderData->firstname . ' '  . $senderData->lastname . " commented on your audio";
        }

        if ($getPostOwnerId->user_id != $postData['sender_id']) {

            $notification = new Notification;
            $notification->event_id = $postData['event_id'];
            $notification->post_id = $postData['post_id'];
            $notification->user_id =  $getPostOwnerId->user_id;
            $notification->notification_type = $notificationType;
            $notification->sender_id = $postData['sender_id'];
            $notification->notification_message = $notification_message;
            $notification->comment_id = $postData['comment_id'];

            if ($notification->save()) {
                $deviceData = Device::where('user_id', $getPostOwnerId->user_id)->first();
                if (!empty($deviceData)) {
                    $notificationImage = EventPostImage::where('event_post_id', $postData['post_id'])->first();
                    $notification_image = "";
                    if (!empty($notificationImage->post_image) && $notificationImage->post_image != NULL) {

                        $notification_image = asset('public/storage/post_image/' . $notificationImage->post_image);
                    }
                    $notificationData = [
                        'message' => $notification_message,
                        'type' => $notificationType,
                        'notification_image' => $notification_image,
                        'post_id' => $postData['post_id'],
                        'event_id' => $postData['event_id'],
                        'post_type' => $getPostOwnerId->post_type,

                    ];

                    $notification_on_off = isOwnerOrInvited($getPostOwnerId->user_id, $postData['event_id']);
                    $checkNotificationSetting = checkNotificationSetting($getPostOwnerId->user_id);

                    if ((count($checkNotificationSetting) && $checkNotificationSetting['wall_post']['push'] == '1') && $notification_on_off == '1') {

                        if ($deviceData->model == 'And') {

                            send_notification_FCM_and($deviceData->device_token, $notificationData);
                        }

                        if ($deviceData->model == 'Ios') {

                            send_notification_FCM($deviceData->device_token, $notificationData);
                        }
                    }
                    // send_notification_FCM($deviceData->device_token, $notification_message);
                }
            }
        }
    }

    if ($notificationType == 'reply_on_comment_post') {


        $getPostOwnerId = EventPost::where('id', $postData['post_id'])->first();
        $getCommentOwnerId = EventPostComment::where('id', $postData['comment_id'])->first();
        $getmainCommentOwnerId = EventPostComment::where('id', $getCommentOwnerId->parent_comment_id)->first();
        $notification_message = $senderData->firstname . ' '  . $senderData->lastname . " replied to your comment";
        $ownerOfComment = $getCommentOwnerId->user_id;
        $commentId = $getCommentOwnerId->id;
        if ($getmainCommentOwnerId != null) {
            $ownerOfComment = $getmainCommentOwnerId->user_id;
            $commentId = $getmainCommentOwnerId->id;
        }
        if ($ownerOfComment != $postData['sender_id']) {

            $notification = new Notification;
            $notification->event_id = $postData['event_id'];
            $notification->post_id = $postData['post_id'];
            $notification->comment_id = $commentId;
            $notification->user_id =  $ownerOfComment;
            $notification->notification_type = $notificationType;
            $notification->sender_id = $postData['sender_id'];
            $notification->notification_message = $notification_message;

            if ($notification->save()) {
                $deviceData = Device::where('user_id', $ownerOfComment)->first();
                if (!empty($deviceData)) {
                    $notificationImage = EventPostImage::where('event_post_id', $postData['post_id'])->first();
                    $notification_image = "";
                    if (!empty($notificationImage->post_image) && $notificationImage->post_image != NULL) {

                        $notification_image = asset('public/storage/post_image/' . $notificationImage->post_image);
                    }
                    $notificationData = [
                        'message' => $notification_message,
                        'type' => $notificationType,
                        'notification_image' => $notification_image,
                        'post_id' => $postData['post_id'],
                        'comment_id' => $postData['comment_id'],
                        'event_id' => $postData['event_id'],
                        'post_type' => $getPostOwnerId->post_type,
                        'reply_comment_id' => $getCommentOwnerId->id,
                    ];


                    $notification_on_off = isOwnerOrInvited($ownerOfComment, $postData['event_id'], $postData['post_id']);
                    $checkNotificationSetting = checkNotificationSetting($ownerOfComment);

                    if ((count($checkNotificationSetting) && $checkNotificationSetting['wall_post']['push'] == '1') && $notification_on_off == '1') {

                        if ($deviceData->model == 'And') {

                            send_notification_FCM_and($deviceData->device_token, $notificationData);
                        }

                        if ($deviceData->model == 'Ios') {

                            send_notification_FCM($deviceData->device_token, $notificationData);
                        }
                    }


                    // send_notification_FCM($deviceData->device_token, $notification_message);
                }
            }
        }
    }

    if ($notificationType == 'sent_rsvp') {

        $getPostOwnerId = Event::with(['event_settings', 'user'])->where('id', $postData['event_id'])->first();

        if ($postData['rsvp_status'] == '1') {
            $notification_message = $senderData->firstname . ' '  . $senderData->lastname . " RSVP'd Yes for" . $getPostOwnerId->event_name;
        } elseif ($postData['rsvp_status'] == '0') {
            $notification_message = $senderData->firstname . ' '  . $senderData->lastname . " RSVP'd No for" . $getPostOwnerId->event_name;
        }
        if ($getPostOwnerId->user_id != $postData['sender_id']) {
            $notification = new Notification;
            $notification->event_id = $postData['event_id'];
            $notification->user_id =  $getPostOwnerId->user_id;
            $notification->notification_type = $notificationType;
            $notification->sender_id = $postData['sender_id'];
            $notification->notification_message = $notification_message;
            $notification->kids = $postData['kids'];
            $notification->adults = $postData['adults'];
            $notification->rsvp_status = $postData['rsvp_status'];
            $notification->rsvp_video = $postData['rsvp_video'];
            $notification->rsvp_message = $postData['rsvp_message'];
            $notification->rsvp_attempt = $postData['rsvp_attempt'];

            if ($notification->save()) {
                $deviceData = Device::where('user_id', $getPostOwnerId->user_id)->first();
                if (!empty($deviceData)) {
                    $notification_image = "";
                    if (!empty($notificationImage->post_image) && $notificationImage->post_image != NULL) {
                        $notification_image = asset('public/storage/event_images/' . $notificationImage->image);
                    }



                    $notificationData = [
                        'message' => $notification_message,
                        'type' => $notificationType,
                        'notification_image' => $notification_image,
                        'post_id' => "",
                        'event_id' => $postData['event_id'],
                        'event_name' => $getPostOwnerId->event_name,
                        'event_wall' => $getPostOwnerId->event_settings->event_wall,
                        'guest_list_visible_to_guests' => $getPostOwnerId->event_settings->guest_list_visible_to_guests,
                        'event_potluck' => $getPostOwnerId->event_settings->podluck,
                        'guest_pending_count' => getGuestPendingRsvpCount($postData['event_id'])
                    ];

                    $checkNotificationSetting = checkNotificationSetting($getPostOwnerId->user_id);

                    if ((count($checkNotificationSetting) && $checkNotificationSetting['guest_rsvp']['push'] == '1') && $getPostOwnerId->notification_on_off == '1') {

                        if ($deviceData->model == 'And') {

                            send_notification_FCM_and($deviceData->device_token, $notificationData);
                        }

                        if ($deviceData->model == 'Ios') {

                            send_notification_FCM($deviceData->device_token, $notificationData);
                        }
                    }
                    if ((count($checkNotificationSetting) && $checkNotificationSetting['guest_rsvp']['email'] == '1') && $getPostOwnerId->notification_on_off == '1') {

                        $invitedUserRsvpMsg = EventInvitedUser::where(['event_id' => $postData['event_id'], 'user_id' => $senderData->id])->first();
                        $eventData = [
                            'event_name' => $getPostOwnerId->event_name,
                            'guest_name' => $senderData->firstname . ' '  . $senderData->lastname,
                            'profileUser' => ($senderData->profile != NULL || $senderData->profile != "") ? $senderData->profile : "no_profile.png",
                            'rsvp_status' =>  $postData['rsvp_status'],
                            'kids' => $postData['kids'],
                            'adults' => $postData['adults'],
                            'rsvp_message' => ($invitedUserRsvpMsg->message_to_host != NULL || $invitedUserRsvpMsg->message_to_host != "") ? $invitedUserRsvpMsg->message_to_host : ""
                        ];
                        $invitation_email = new NewRsvpsEmailNotify($eventData);
                        Mail::to($getPostOwnerId->user->email)->send($invitation_email);
                    }
                }
            }
        }
    }

    if ($notificationType == 'potluck_bring') {

        $getPostOwnerId = Event::with('event_settings')->where('id', $postData['event_id'])->first();


        $notification_message = $senderData->firstname . ' '  . $senderData->lastname . " will be bring the item below for " . $getPostOwnerId->event_name . ' Potluck';


        if ($getPostOwnerId->user_id != $postData['sender_id']) {
            $notification = new Notification;
            $notification->event_id = $postData['event_id'];
            $notification->user_id =  $getPostOwnerId->user_id;
            $notification->notification_type = $notificationType;
            $notification->sender_id = $postData['sender_id'];
            $notification->user_potluck_item_id = $postData['user_potluck_item_id'];
            $notification->user_potluck_item_count = $postData['user_potluck_item_count'];
            $notification->notification_message = $notification_message;


            if ($notification->save()) {
                $deviceData = Device::where('user_id', $getPostOwnerId->user_id)->first();
                if (!empty($deviceData)) {
                    $notification_image = "";
                    if (!empty($notificationImage->post_image) && $notificationImage->post_image != NULL) {
                        $notification_image = asset('public/storage/event_images/' . $notificationImage->image);
                    }

                    $notificationData = [
                        'message' => $notification_message,
                        'type' => $notificationType,
                        'notification_image' => $notification_image,
                        'event_id' => $postData['event_id'],
                        'event_name' => $getPostOwnerId->event_name,
                        'event_wall' => $getPostOwnerId->event_settings->event_wall,
                        'guest_list_visible_to_guests' => $getPostOwnerId->event_settings->guest_list_visible_to_guests,
                        'event_potluck' => $getPostOwnerId->event_settings->podluck,
                        'guest_pending_count' => getGuestPendingRsvpCount($postData['event_id'])
                    ];
                    $checkNotificationSetting = checkNotificationSetting($getPostOwnerId->user_id);

                    if ((count($checkNotificationSetting) && $checkNotificationSetting['wall_post']['push'] == '1') && $getPostOwnerId->notification_on_off == '1') {

                        if ($deviceData->model == 'And') {

                            send_notification_FCM_and($deviceData->device_token, $notificationData);
                        }

                        if ($deviceData->model == 'Ios') {

                            send_notification_FCM($deviceData->device_token, $notificationData);
                        }
                    }
                }
            }
        }
    }
}


function isOwnerOrInvited($userId, $eventId, $postId = null)
{
    $event = Event::with('event_invited_user')->find($eventId);
    $status = 'Unknown';
    if ($event && $event->user_id == $userId) {
        $status = 'Owner';
    } elseif ($event && $event->event_invited_user->contains('user_id', $userId)) {
        if ($postId != null) {
            $postControl = PostControl::where(['event_id' => $eventId, 'user_id' => $userId, 'event_post_id' => $postId, 'post_control' => 'mute'])->first();
            if (isset($postControl) && !empty($postControl)) {
                $status = 'Unknown';
            } else {
                $status = 'Invited';
            }
        } else {
            $status = 'Invited';
        }
    }


    $notifyOnOff = "";
    if ($status == 'Owner') {
        $res =  Event::where('id', $eventId)->first();
        $notifyOnOff = $res->notification_on_off;
    } else if ($status == 'Invited') {
        $res =  EventInvitedUser::where('user_id', $userId)->first();
        $notifyOnOff = $res->notification_on_off;
    }

    return $notifyOnOff;
}

function setpostTime($dateTime)
{

    $commentDateTime = $dateTime; // Replace this with your actual timestamp

    // Convert the timestamp to a Carbon instance
    $commentTime = Carbon::parse($commentDateTime);

    // Calculate the time difference
    $timeAgo = $commentTime->diffForHumans(); // This will give the time ago format


    // Display the time ago
    return $timeAgo;
}

function emailChecker($email)
{

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://email-checker.p.rapidapi.com/verify/v1?email=" . $email,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: email-checker.p.rapidapi.com",
            "X-RapidAPI-Key: 697aa4286dmshb19d320b22b23a2p18429cjsn2a23a2411f0e"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo $response;
    }
}



function send_notification_FCM($deviceToken, $notifyData)
{


    $serverKey  = ServerKey::first();
    $SERVER_API_KEY = $serverKey->firebase_key;

    $URL = 'https://fcm.googleapis.com/fcm/send';

    $notificationLoad =  [
        'title' => "Yesvite",
        "body" => $notifyData['message'],
        'sound' => "default",
        'message' => $notifyData['message'],
        'color' => "#79bc64",
        "data" => $notifyData
    ];

    $dataPayload = [
        "to" => $deviceToken,
        "data" => $notifyData,
        "notification" => $notificationLoad,
        "priority" => "high",
    ];

    $post_data = json_encode($dataPayload);

    $crl = curl_init();

    $headr = array();
    $headr[] = 'Content-type: application/json';
    $headr[] = 'Authorization: ' . $SERVER_API_KEY;
    curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($crl, CURLOPT_URL, $URL);
    curl_setopt($crl, CURLOPT_HTTPHEADER, $headr);

    curl_setopt($crl, CURLOPT_POST, true);
    curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);

    $rest = curl_exec($crl);

    if ($rest === false) {
        $result_noti = 0;
    } else {
        $result_noti = 1;
    }

    return $result_noti;
}

function send_notification_FCM_and($deviceToken, $notifyData)
{
    $serverKey  = ServerKey::first();
    $SERVER_API_KEY = $serverKey->firebase_key;
    $URL = 'https://fcm.googleapis.com/fcm/send';


    $dataPayload = [
        "to" => $deviceToken,
        "data" => $notifyData,
    ];

    $post_data = json_encode($dataPayload);

    $crl = curl_init();

    $headr = array();
    $headr[] = 'Content-type: application/json';
    $headr[] = 'Authorization: ' . $SERVER_API_KEY;
    curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($crl, CURLOPT_URL, $URL);
    curl_setopt($crl, CURLOPT_HTTPHEADER, $headr);

    curl_setopt($crl, CURLOPT_POST, true);
    curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);

    $rest = curl_exec($crl);

    if ($rest === false) {
        $result_noti = 0;
    } else {
        $result_noti = 1;
    }

    return $result_noti;
}
function logoutFromWeb($userId)
{

    $user = User::where('id', $userId)->first();

    $user->current_session_id = "NULL";
    $user->save();
    Auth::logout();
}
function sendSMS($receiverNumber, $message)
{
    try {

        $account_sid = env('ACCOUNT_SID');
        $auth_token = env("AUTH_TOKEN");
        $twilio_number = env("TWILIO_NUMBER");

        $client = new Client($account_sid, $auth_token);
        $client->messages->create($receiverNumber, [
            'from' => $twilio_number,
            'body' => $message
        ]);
        return  ["status" => true, "message" => "success"];
    } catch (Exception $e) {
        return  ["status" => false, "message" => $e->getMessage()];
    }
}

function validateAndFormatPhoneNumber($receiverNumber, $defaultCountryCode = 'US')
{
    $phoneUtil = PhoneNumberUtil::getInstance();

    try {
        $parsedNumber = $phoneUtil->parse($receiverNumber, $defaultCountryCode);

        if (!$phoneUtil->isValidNumber($parsedNumber)) {
            throw new \Exception("Invalid phone number.");
        }

        return $phoneUtil->format($parsedNumber, PhoneNumberFormat::E164);
    } catch (NumberParseException $e) {
        throw new \Exception("Error parsing phone number: " . $e->getMessage());
    }
}

function sendSMSForApplication($receiverNumber, $message)
{
    try {

        $formattedNumber = validateAndFormatPhoneNumber($receiverNumber);

        $serverKeys = ServerKey::first();

        $client = new Client($serverKeys->twilio_account_sid, $serverKeys->twilio_auth_token);
        $client->messages->create($formattedNumber, [
            'from' => $serverKeys->twilio_number,
            'body' => $message
        ]);

        return  true;
    } catch (Exception $e) {
        return  false;
    }
}

function dateDiffer($dateTime)
{
    $createdDateTime = Carbon::parse($dateTime);

    // Get the current date and time
    $currentDateTime = Carbon::now();

    // Calculate the difference in days
    return  $currentDateTime->diffInDays($createdDateTime);
}


function formatNumber($num)
{
    if ($num >= 1000 && $num < 1000000) {
        // Divide the number by 1000 and round it to remove decimal points
        $num = round($num / 1000, 1);
        return $num . 'k';
    } elseif ($num >= 1000000 && $num < 1000000000) {
        // Divide the number by 1000000 and round it to remove decimal points
        $num = round($num / 1000000, 1);
        return $num . 'M';
    } elseif ($num >= 1000000000) {
        // Divide the number by 1000000000 and round it to remove decimal points
        $num = round($num / 1000000000, 1);
        return $num . 'B';
    } else {
        return $num;
    }
}


function verifyApplePurchase($userId, $purchaseToken)
{
    $url = "https://buy.itunes.apple.com/verifyReceipt"; // Production URL
    // $url = "https://sandbox.itunes.apple.com/verifyReceipt"; // Sandbox URL

    $response = Http::post($url, [
        'receipt-data' => $purchaseToken,
        'password' => env('APPLE_SHARED_SECRET'),
    ]);

    if ($response->json('status') == 0) {
        updateSubscriptionStatus($userId, $response->json());
        return response()->json(['success' => true]);
    } else {
        return response()->json(['error' => 'Invalid receipt'], 400);
    }
}

function getGoogleAccessToken()
{
    $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
        'client_id' => env('InGOOGLE_CLIENT_ID'),
        'client_secret' => env('InGOOGLE_CLIENT_SECRET'),
        'refresh_token' => '1//0gHYN_Ai3rfAnCgYIARAAGBASNwF-L9IrdP-JOsDTkXeH-yqO_Z252HkBEfW7oqRZqcbTrsTQ_u_8eeif8HSml-a-i0Foi6iVH4Q',
        'grant_type' => 'refresh_token',
    ]);

    return $response->json('access_token');
}

function verifyGooglePurchase($userId, $purchaseToken)
{

    $getSubscription = UserSubscription::where('user_id', $userId)->first();
    $packageName = $getSubscription->packageName;
    $productId = $getSubscription->productId;
    $accessToken = getGoogleAccessToken();

    $url = "https://www.googleapis.com/androidpublisher/v3/applications/{$packageName}/purchases/subscriptions/{$productId}/tokens/{$purchaseToken}?access_token={$accessToken}";

    $response = Http::get($url);

    if ($response->json('purchaseState') == 0) {
        updateSubscriptionStatus($userId, $response->json());
        return true;
    } else {
        return false;
    }
}

function updateSubscriptionStatus($userId, $response)
{
    $userSubscription = UserSubscription::where('user_id', $userId)->first();

    if ($userSubscription) {
        $expiryDate = isset($response['expiryTimeMillis']) ? date('Y-m-d H:i:s', $response['expiryTimeMillis'] / 1000) : null;
        $userSubscription->subscription_status = 'active';
        $userSubscription->endDate = $expiryDate;
        $userSubscription->save();
    }
}

function checkSubscription($userId)
{

    $userSubscription = UserSubscription::where('user_id', $userId)->orderBy('id', 'DESC')->limit(1)->first();
    if ($userSubscription != null) {
        $app_id = $userSubscription->packageName;
        $product_id = $userSubscription->productId;
        $purchaseToken = $userSubscription->purchaseToken;

        $responce =  set_android_iap($app_id, $product_id, $purchaseToken, 'subscribe');


        $exp_date =  date('Y-m-d H:i:s', ($responce['expiryTimeMillis'] /  1000));


        $current_date = date('Y-m-d H:i:s');

        if (strtotime($current_date) > strtotime($exp_date)) {

            $userSubscription->endDate = $exp_date;
            $userSubscription->save();
            return false;
        }
        if (isset($responce['userCancellationTimeMillis'])) {

            $cancellationdate =  date('Y-m-d H:i:s', ($responce['userCancellationTimeMillis'] /  1000));
            $userSubscription->cancellationdate = $cancellationdate;
            $userSubscription->save();
            return false;
        }
        return true;
    }
    return false;
}

function set_android_iap($appid, $productID, $purchaseToken, $type)
{
    $ch = curl_init();
    $clientId = env('InGOOGLE_CLIENT_ID');

    $clientSecret = env('InGOOGLE_CLIENT_SECRET');
    $redirectUri = 'https://yesvite.cmexpertiseinfotech.in/google/callback';

    $refreshToken = '1//0gHYN_Ai3rfAnCgYIARAAGBASNwF-L9IrdP-JOsDTkXeH-yqO_Z252HkBEfW7oqRZqcbTrsTQ_u_8eeif8HSml-a-i0Foi6iVH4Q';


    $TOKEN_URL = "https://accounts.google.com/o/oauth2/token";

    $VALIDATE_URL = "https://www.googleapis.com/androidpublisher/v3/applications/" .
        $appid . "/purchases/subscriptions/" .
        $productID . "/tokens/" . $purchaseToken;
    if ($type == 'product') {

        $VALIDATE_URL = "https://www.googleapis.com/androidpublisher/v3/applications/" .
            $appid . "/purchases/products/" .
            $productID . "/tokens/" . $purchaseToken;
    }


    $input_fields = 'refresh_token=' . $refreshToken .
        '&client_secret=' . $clientSecret .
        '&client_id=' . $clientId .
        '&redirect_uri=' . $redirectUri .
        '&grant_type=refresh_token';

    //Request to google oauth for authentication
    curl_setopt($ch, CURLOPT_URL, $TOKEN_URL);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $input_fields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    $result = json_decode($result, true);

    if (!$result || !$result["access_token"]) {
        //error  
        // return;
    }


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $VALIDATE_URL . "?access_token=" . $result["access_token"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result1 = curl_exec($ch);
    $result1 = json_decode($result1, true);
    if (!$result1 || (isset($result1["error"]) && $result1["error"] != null)) {
        //error
        // return;
    }

    return $result1;
}
