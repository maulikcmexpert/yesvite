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


                // user notification setting //
                if ($value->user->app_user == '1') {
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

                        if (count($checkNotificationSetting) != 0 && $checkNotificationSetting['invitations']['email'] == '1') {


                            if ($value->prefer_by == 'email') {
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
                    }
                }
            }
        }
    }

    if ($notificationType == 'update_address' || $notificationType == 'update_time' || $notificationType == 'update_event') {

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
                    }
                    $notification->notification_message = $notification_message;

                    if ($notification->save()) {

                        $deviceData = Device::where('user_id', $value->user_id)->first();
                        if (!empty($deviceData)) {

                            $notificationImage = EventImage::where('event_id', $postData['event_id'])->first();

                            $notification_image = "";
                            if ($notificationImage != NULL) {

                                $notification_image = asset('public/storage/event_images/' . $notificationImage->image);
                            }
                            $push_notification_message = $senderData->firstname . ' ' . $senderData->lastname . " has updated the event details for " . $value->event->event_name;
                            $notificationData = [
                                'message' => $notification_message,
                                'type' => $notificationType,

                                'notification_image' => $push_notification_message,
                                'event_id' => $postData['event_id'],
                                'event_name' => $value->event->event_name,
                                'event_wall' => $value->event->event_settings->event_wall,
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
            foreach ($postControl as $value) {
                $postOwneruserId[] = $value->event_posts->user_id;
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

        foreach ($invitedusers as $key => $value) {

            if ($postData['post_privacy'] != '2' && $value->rsvp_status != '1' &&  $value->rsvp_d != '1') {
                echo "2";
                dd($invitedusers);
                continue;
            }

            if ($postData['post_privacy'] != '3' && $value->rsvp_status != '0' &&  $value->rsvp_d != '1') {
                echo "3";
                dd($invitedusers);
                continue;
            }


            if ($postData['post_privacy'] != '4' &&  $value->rsvp_d != '0') {
                echo "4";
                dd($invitedusers);
                continue;
            }

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

                $deviceData = Device::where('user_id', $value->id)->first();
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

    if ($notificationType == 'like_post') {

        $getPostOwnerId = EventPost::where('id', $postData['post_id'])->first();



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
                        'is_in_photo_moudle' => $postData['is_in_photo_moudle']

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

                    $notification_on_off = isOwnerOrInvited($ownerOfComment, $postData['event_id']);

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

                    ];


                    $notification_on_off = isOwnerOrInvited($ownerOfComment, $postData['event_id']);
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
            $notification->adults = $postData['adults'];;
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
                        'event_wall' => $getPostOwnerId->event_settings->event_wall
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
                        'event_wall' => $getPostOwnerId->event_settings->event_wall
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


function isOwnerOrInvited($userId, $eventId)
{
    $event = Event::with('event_invited_user')->find($eventId);
    $status = 'Unknown';
    if ($event && $event->user_id == $userId) {
        $status = 'Owner';
    } elseif ($event && $event->event_invited_user->contains('user_id', $userId)) {
        $status = 'Invited';
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
    // $SERVER_API_KEY = 'key=AAAAP6m84T0:APA91bHeuAm2ME_EmPEsOjMe2FatmHn2QU98ADg4Y5UxNMmXGg4MDD4OJQQhvsixNfhV1g2BWbgOCQGEf9_c3ngB8qH_N3MEMsgD7uuAQAq0_IO2GGPqCxjJPuwAME9MVX9ZvWgYbcPh';
    $SERVER_API_KEY = 'key=AAAAZh4vQmc:APA91bFTE_caQlb57lsvsBgbHRwWjobttbG8TbHcSeKzRuF4OhAbscz9Trwur9ATVYIj5Wnse9GspEtSHc2WO9Czk_DKWN2IS8EK4GPWWTQRnK3G1jp5_gpuNUIaMjaa-CFwinqS--4r';
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


    // $SERVER_API_KEY = 'key=AAAAP6m84T0:APA91bHeuAm2ME_EmPEsOjMe2FatmHn2QU98ADg4Y5UxNMmXGg4MDD4OJQQhvsixNfhV1g2BWbgOCQGEf9_c3ngB8qH_N3MEMsgD7uuAQAq0_IO2GGPqCxjJPuwAME9MVX9ZvWgYbcPh';
    $SERVER_API_KEY = 'key=AAAAZh4vQmc:APA91bFTE_caQlb57lsvsBgbHRwWjobttbG8TbHcSeKzRuF4OhAbscz9Trwur9ATVYIj5Wnse9GspEtSHc2WO9Czk_DKWN2IS8EK4GPWWTQRnK3G1jp5_gpuNUIaMjaa-CFwinqS--4r';
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
