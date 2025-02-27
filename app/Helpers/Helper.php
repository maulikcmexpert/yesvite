<?php

use App\Jobs\SendBroadcastEmailJob;
use App\Jobs\SendEmailJob;
use App\Models\contact_sync;
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
use App\Jobs\SendOwnInvitationEmail;
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
use Kreait\Laravel\Firebase\Facades\Firebase;
use Google\Auth\Credentials\ServiceAccountCredentials;
use App\Mail\BulkEmail;
use App\Models\Coin_transactions;
use App\Models\Url;
use App\Models\UserOpt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFProbe;
// use DB;

function getVideoDuration($filePath)
{

    $track = new GetId3($filePath);

    // Extract file metadata
    $info = $track->extractInfo();
    // dd($info);
    // Return playtime if available
    return $info['playtime_seconds'] ?? null;
}


function generate_thumbnail($fileName)
{
    try {
        $videoPath = public_path('storage/post_image/') . $fileName;

        $ffmpeg = FFMpeg::create([
            'ffmpeg.binaries' => "/usr/bin/ffmpeg",  // Change this path if necessary
            'ffprobe.binaries' => "/usr/bin/ffprobe", // Change this path if necessary
        ]);

        $video = $ffmpeg->open($videoPath);

        // Loop through and generate thumbnails at different timestamps
        // for ($i = 0; $i < 5; $i++) {
        $imgName = uniqid('thumb_', true) . '.jpg';
        $thumbnailPath = public_path('storage/thumbnails/') . $imgName;
        $videoDurationInSeconds = getVideoDuration($videoPath);  // You need a method to get the total duration of the video
        $randomSecond = rand(0, $videoDurationInSeconds);
        $video->frame(TimeCode::fromSeconds($randomSecond))
            ->save($thumbnailPath);

        // If the file is generated successfully, return it
        if (file_exists($thumbnailPath)) {

            return $imgName;
        }
        // }


        return null; // Return null if no thumbnail is generated
    } catch (\Exception $e) {


        return null;
    }
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
    $adults = EventInvitedUser::
        // whereHas('user', function ($query) {
        //     $query->where('app_user', '1');
        // })->
        where(['event_id' => $eventId, 'rsvp_status' => '1', 'rsvp_d' => '1'])->sum('adults');

    $kids = EventInvitedUser::
        // whereHas('user', function ($query) {
        //     $query->where('app_user', '1');
        // })->
        where(['event_id' => $eventId, 'rsvp_status' => '1', 'rsvp_d' => '1'])->sum('kids');

    return $adults + $kids;
    // return  EventInvitedUser::whereHas('user', function ($query) {
    //     $query->where('app_user', '1');
    // })->where(['event_id' => $eventId, 'rsvp_d' => '0'])->count();
}

function sendNotification($notificationType, $postData)
{
    //'invite', 'upload_post', 'like_post', 'comment', 'reply', 'poll', 'rsvp'
    $user  = Auth::guard('api')->user();

    $senderData = User::where('id', $postData['sender_id'])->first();

    if ((isset($postData['sync_id']) && $postData['sync_id'] != "") && $postData['sender_id'] == null || $postData['sender_id'] == "") {
        $senderData = contact_sync::where('id', $postData['sync_id'])->first();
    } else {
        $senderData = User::where('id',  $postData['sender_id'])->first();
    }
    // if (isset($postData['newUser']) && count($postData['newUser']) != 0) {
    //     $filteredIds = array_map(
    //         fn($guest) => $guest['id'],
    //         array_filter($postData['newUser'], fn($guest) => $guest['app_user'] === 1)
    //     );
    //     $filteredIdsguest = array_map(
    //         fn($guest) => $guest['id'],
    //         array_filter($postData['newUser'], fn($guest) => $guest['app_user'] === 0)
    //     );
    //     // dd($filteredIdsguest);
    //     // $postData['newUser'] = $filteredIds;
    // }



    if ($notificationType == 'owner_notify') {
        $event = Event::with('event_image', 'event_invited_user', 'event_schedule')->where('id', $postData['event_id'])->first();
        // $event_host=EventInvitedUser::where('event_id', $postData['event_id']);
        $event_time = "";
        if ($event->event_schedule->isNotEmpty()) {

            $event_time =  $event->event_schedule->first()->start_time;
        }
        $eventData = [
            'event_invited_user_id' => "",
            'event_id' => $postData['event_id'],
            'owner_id' => $event->user_id,
            'host_email' => $senderData->email,
            'event_name' => $event->event_name,
            'event_image' => ($event->event_image->isNotEmpty()) ? $event->event_image[0]->image : "no_image.png",
            'date' =>   date('l - M jS, Y', strtotime($event->start_date)),
            'time' => $event->rsvp_start_time,
        ];
        dispatch(new SendOwnInvitationEmail(array($senderData->email, $eventData)));
        // $invitation_email = new OwnInvitationEmail($eventData);
        // Mail::to($senderData->email)->send($invitation_email);
    }
    $notification_message = "";

    $invitedusers = EventInvitedUser::with(['event', 'event.event_settings', 'event.event_schedule', 'user'])
        // ->whereHas('user', function ($query) {
        //  $query->where('app_user', '1');
        // })
        ->where('event_id', $postData['event_id'])
        ->where('user_id', '!=', '')
        ->whereNull('sync_id')
        ->get();

    if ($notificationType == 'invite') {
        // dd(1);

        if (count($invitedusers) != 0) {
            if (isset($postData['newUser']) && count($postData['newUser']) != 0) {
                $invitedusers = EventInvitedUser::with(['event', 'event.event_image', 'event.user', 'event.event_settings', 'event.event_schedule', 'user'])
                    // ->whereHas('user', function ($query) {
                    //  $query->where('app_user', '1');
                    // })
                    ->whereIn('user_id', $postData['newUser'])->where('event_id', $postData['event_id'])
                    ->where('user_id', '!=', '')
                    ->orderBy('id', 'DESC')
                    ->get();
            }

            foreach ($invitedusers as $value) {

                // Notification::where(['user_id' => $value->user_id, 'sender_id' => $postData['sender_id'], 'event_id' => $postData['event_id']])->delete();

                $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " has invited you to " . $value->event->event_name;
                if ($value->is_co_host == '1') {
                    // $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " has turned you into a co-host for: " . $value->event->event_name;
                    $notification_message = "You have been made a co host for " . $value->event->event_name;
                }
                $notification = new Notification;
                $notification->event_id = $postData['event_id'];
                $notification->user_id = $value->user_id;
                $notification->notification_type = $notificationType;
                $notification->sender_id = $postData['sender_id'];
                $notification->notification_message = $notification_message;
                $notification->is_co_host = $value->is_co_host;
                $notification->event_invited_user_id = $value->id;

                if ($notification->save()) {

                    $deviceData = Device::where('user_id', $value->user_id)->first();
                    $checkNotificationSetting = checkNotificationSetting($value->user_id);
                    if (!empty($deviceData)) {

                        $notificationImage = EventImage::where('event_id', $postData['event_id'])->orderBy('type', 'ASC')->first();

                        $notification_image = "";
                        if ($notificationImage != NULL) {

                            $notification_image = asset('public/storage/event_images/' . $notificationImage->image);
                        }

                        $isCoHost =  EventInvitedUser::where(['event_id' => $postData['event_id'], 'user_id' => $value->user_id, 'is_co_host' => '1'])->first();
                        $is_co_host = (isset($isCoHost) && $isCoHost->is_co_host != "") ? $isCoHost->is_co_host : "0";

                        $notificationData = [
                            'message' => $notification_message,
                            'type' => (string)$notificationType,
                            'notification_image' => $notification_image,
                            'event_id' => (string)$postData['event_id'],
                            'event_name' => (string)$value->event->event_name,
                            'event_wall' => (string)$value->event->event_settings->event_wall,
                            'guest_list_visible_to_guests' => (string)$value->event->event_settings->guest_list_visible_to_guests,
                            'event_potluck' => (string)$value->event->event_settings->podluck,
                            'guest_pending_count' => (string)getGuestPendingRsvpCount($postData['event_id']),
                            'rsvp_status' => '0',
                            'is_co_host' => $is_co_host
                        ];

                        $checkNotificationSetting = checkNotificationSetting($value->user_id);
                        if ((count($checkNotificationSetting) && $checkNotificationSetting['invitations']['push'] == '1') &&  $value->notification_on_off == '1') {
                            send_notification_FCM_and($deviceData->device_token, $notificationData);
                        }
                    }

                    if ($value->prefer_by == 'email') {

                        if ($value->user->app_user == '1' &&  count($checkNotificationSetting) != 0 && $checkNotificationSetting['invitations']['email'] == '1') {

                            // $event_time = "";
                            // if ($value->event->event_schedule->isNotEmpty()) {

                            //     $event_time = $value->event->event_schedule->first()->start_time;
                            // }

                            $eventData = [
                                'event_invited_user_id' => (int)$value->id,
                                'event_id' => (int)$postData['event_id'],
                                'user_id' => $value->user->id,
                                'event_name' => $value->event->event_name,
                                'hosted_by' => $value->event->user->firstname . ' ' . $value->event->user->lastname,
                                'profileUser' => ($value->event->user->profile != NULL || $value->event->user->profile != "") ? $value->event->user->profile : "no_profile.png",
                                'event_image' => ($value->event->event_image->isNotEmpty()) ? $value->event->event_image[0]->image : "no_image.png",
                                'date' =>   date('l - M jS, Y', strtotime($value->event->start_date)),
                                'time' => $value->event->rsvp_start_time,
                                'host_email' => $senderData->email,
                                'address' => $value->event->event_location_name . ' ' . $value->event->address_1 . ' ' . $value->event->address_2 . ' ' . $value->event->state . ' ' . $value->event->city . ' - ' . $value->event->zip_code,
                            ];

                            $emailCheck = dispatch(new sendInvitation(array($value->user->email, $eventData)));
                            // $updateinvitation = EventInvitedUser::where(['event_id' => $postData['event_id'], 'user_id' => $value->user_id, 'prefer_by' => 'email'])->orderBy('id','DESC')->first();
                            $updateinvitation = EventInvitedUser::where('id', $value->id)->first();

                            if (!empty($emailCheck)) {
                                $updateinvitation->invitation_sent = '1';
                                $updateinvitation->save();
                            } else {
                                $updateinvitation->invitation_sent = '9';
                                $updateinvitation->save();
                            }
                        } else {
                            // $event_time = "";
                            // if ($value->event->event_schedule->isNotEmpty()) {

                            // $event_time = $value->event->event_schedule->first()->start_time;
                            // $event_time = $value->event->rsvp_start_time;
                            // }

                            $eventData = [
                                'event_invited_user_id' => (int)$value->id,
                                'event_id' => (int)$postData['event_id'],
                                'user_id' => $value->user->id,
                                'event_name' => $value->event->event_name,
                                'hosted_by' => $value->event->user->firstname . ' ' . $value->event->user->lastname,
                                'profileUser' => ($value->event->user->profile != NULL || $value->event->user->profile != "") ? $value->event->user->profile : "no_profile.png",
                                'event_image' => ($value->event->event_image->isNotEmpty()) ? $value->event->event_image[0]->image : "no_image.png",
                                'date' =>   date('l - M jS, Y', strtotime($value->event->start_date)),
                                'time' => $value->event->rsvp_start_time,
                                'address' => $value->event->event_location_name . ' ' . $value->event->address_1 . ' ' . $value->event->address_2 . ' ' . $value->event->state . ' ' . $value->event->city . ' - ' . $value->event->zip_code,
                            ];

                            $emailCheck = dispatch(new sendInvitation(array($value->user->email, $eventData)));
                            // $updateinvitation = EventInvitedUser::where(['event_id' => $postData['event_id'], 'user_id' => $value->user_id, 'prefer_by' => 'email'])->first();
                            $updateinvitation = EventInvitedUser::where('id', $value->id)->first();

                            if (!empty($emailCheck)) {
                                $updateinvitation->invitation_sent = '1';
                                $updateinvitation->save();
                            } else {
                                $updateinvitation->invitation_sent = '9';
                                $updateinvitation->save();
                            }
                        }
                    }


                    if ($value->prefer_by == 'phone') {

                        $logData = [
                            'user_phone' => $value->user->phone_number,
                            'host_name' => $value->event->user->firstname . ' ' . $value->event->user->lastname,
                            'event_name' => $value->event->event_name,
                            'event_id' => $postData['event_id'],
                            'event_invited_user_id' => $value->id
                        ];

                        // Log the parameters
                        Log::info('Sending SMS Invite', $logData);
                        $sent = handleSMSInvite($value->user->phone_number,  $value->event->user->firstname . ' ' . $value->event->user->lastname, $value->event->event_name, $postData['event_id'], $value->id);
                        // $sent = sendSMSForApplication($value->user->phone_number, $notification_message);
                        if ($sent == true) {
                            $updateinvitation = EventInvitedUser::where(['event_id' => $postData['event_id'], 'user_id' => $value->user_id, 'prefer_by' => 'phone'])->first();
                            $updateinvitation->invitation_sent = '1';
                            $updateinvitation->save();
                        } else {
                            $updateinvitation = EventInvitedUser::where(['event_id' => $postData['event_id'], 'user_id' => $value->user_id, 'prefer_by' => 'phone'])->first();
                            $updateinvitation->invitation_sent = '9';
                            $updateinvitation->save();
                        }
                    }
                }
            }
        }
    }

    if ($notificationType == 'update_address' || $notificationType == 'update_time' || $notificationType == 'update_event' || $notificationType == 'update_date') {
        if (count($invitedusers) != 0) {
            foreach ($invitedusers as $value) {
                if ($value->user_id == '') {
                    continue;
                }
                if (isset($postData['newUser']) && count($postData['newUser']) != 0) {
                    if (in_array($value->user_id, $postData['newUser'])) {
                        continue;
                    }
                }
                if ($value->user->app_user == '1') {
                    // Notification::where(['user_id' => $value->user_id, 'sender_id' => $postData['sender_id'], 'event_id' => $postData['event_id']])->delete();
                    $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " has updated the event details for " . $value->event->event_name;
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

                            $notificationImage = EventImage::where('event_id', $postData['event_id'])->orderBy('type', 'ASC')->first();

                            $notification_image = "";
                            if ($notificationImage != NULL) {

                                $notification_image = asset('storage/event_images/' . $notificationImage->image);
                            }
                            $push_notification_message = $senderData->firstname . ' ' . $senderData->lastname . " has updated the event details for " . $value->event->event_name;

                            $isCoHost =  EventInvitedUser::where(column: ['event_id' => $postData['event_id'], 'user_id' => $value->user_id, 'is_co_host' => '1'])->first();
                            $is_co_host = (isset($isCoHost) && $isCoHost->is_co_host != "") ? $isCoHost->is_co_host : "0";

                            $notificationData = [
                                'message' => $notification_message,
                                'type' => (string)$notificationType,
                                'notification_image' => (string)$push_notification_message,
                                'event_id' => (string)$postData['event_id'],
                                'event_name' => $value->event->event_name,
                                'event_wall' => (string)$value->event->event_settings->event_wall,
                                'guest_list_visible_to_guests' => (string)$value->event->event_settings->guest_list_visible_to_guests,
                                'event_potluck' => (string)$value->event->event_settings->podluck,
                                'rsvp_status' => (isset($value->rsvp_status) && $value->rsvp_status != null) ? (string)$value->rsvp_status : '',
                                'guest_pending_count' => (string)getGuestPendingRsvpCount($postData['event_id']),
                                'is_co_host' => $is_co_host

                            ];

                            if ($value->notification_on_off == '1') {
                                send_notification_FCM_and($deviceData->device_token, $notificationData);
                            }
                        }
                    }
                }
            }
        }
    }

    if ($notificationType == 'update_potluck') {
        if (count($invitedusers) != 0) {
            foreach ($invitedusers as $value) {
                if ($value->user_id == '') {
                    continue;
                }
                if (isset($postData['newUser']) && count($postData['newUser']) != 0) {
                    if (in_array($value->user_id, $postData['newUser'])) {
                        continue;
                    }
                }
                if ($value->user->app_user == '1') {
                    // Notification::where(['user_id' => $value->user_id, 'sender_id' => $postData['sender_id'], 'event_id' => $postData['event_id']])->delete();
                    $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " has updated the potluck for " . $value->event->event_name;
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

                            $notificationImage = EventImage::where('event_id', $postData['event_id'])->orderBy('type', 'ASC')->first();

                            $notification_image = "";
                            if ($notificationImage != NULL) {

                                $notification_image = asset('storage/event_images/' . $notificationImage->image);
                            }
                            $push_notification_message = $senderData->firstname . ' ' . $senderData->lastname . " has updated the event details for " . $value->event->event_name;

                            $isCoHost =  EventInvitedUser::where(['event_id' => $postData['event_id'], 'user_id' => $value->user_id, 'is_co_host' => '1'])->first();
                            $is_co_host = (isset($isCoHost) && $isCoHost->is_co_host != "") ? $isCoHost->is_co_host : "0";

                            $notificationData = [
                                'message' => $notification_message,
                                'type' => (string)$notificationType,
                                'notification_image' => (string)$push_notification_message,
                                'event_id' => (string)$postData['event_id'],
                                'event_name' => $value->event->event_name,
                                'event_wall' => (string)$value->event->event_settings->event_wall,
                                'guest_list_visible_to_guests' => (string)$value->event->event_settings->guest_list_visible_to_guests,
                                'event_potluck' => (string)$value->event->event_settings->podluck,
                                'rsvp_status' => (isset($value->rsvp_status) && $value->rsvp_status != null) ? (string)$value->rsvp_status : '',
                                'guest_pending_count' => (string)getGuestPendingRsvpCount($postData['event_id']),
                                'is_co_host' => $is_co_host

                            ];

                            if ($value->notification_on_off == '1') {
                                send_notification_FCM_and($deviceData->device_token, $notificationData);
                            }
                        }
                    }
                }
            }
        }
    }

    if ($notificationType == 'accept_reject_co_host') {
        // if(isset($postData['notification_id']) && $postData['notification_id'] != '' ){
        //     Notification::where('id',$postData['notification_id'])->first();
        // }
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
                $notificationImage = EventImage::where('event_id', $postData['event_id'])->orderBy('type', 'ASC')->first();

                $notification_image = '';
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
                    'type' => (string)$notificationType,
                    'notification_image' => $notification_image,
                    'event_id' => (string)$postData['event_id'],
                    'event_name' => $getEventOwner->event_name,
                    'event_wall' => (string)$getEventOwner->event_settings->event_wall,
                    'guest_list_visible_to_guests' => isset($getEventOwner->event_settings->guest_list_visible_to_guests) ? (string)$getEventOwner->event_settings->guest_list_visible_to_guests : '',
                    'event_potluck' => (string)$getEventOwner->event_settings->podluck,
                    'guest_pending_count' => (string)getGuestPendingRsvpCount($postData['event_id'])
                ];


                if ($getEventOwner->notification_on_off == '1') {
                    send_notification_FCM_and($deviceData->device_token, $notificationData);
                }
            }
        }
    }

    if ($notificationType == 'upload_post' || $notificationType == 'photos') {

        // post notify to  owner//
        $ownerEvent = Event::with('event_settings')->where('id', $postData['event_id'])->first();
        $postControl = PostControl::with('event_posts')->where(['event_id' => $ownerEvent->id, 'user_id' => $ownerEvent->user_id, 'post_control' => 'mute'])->get();
        $postControlUserId = [];
        if ($postData['is_in_photo_moudle'] == 1) {
            $photo_module_type = 'photos';
        } else {
            $photo_module_type = 'Wall';
        }

        if (!$postControl->isEmpty()) {
            foreach ($postControl as $val) {
                if (isset($val->event_posts->user_id) && $val->event_posts->user_id != null) {
                    $postControlUserId[] = $val->event_posts->user_id;
                }
            }
        }
        if (!in_array($postData['sender_id'], $postControlUserId)) {

            if ($postData['sender_id'] != $ownerEvent->user_id) {

                if ($postData['video'] > 0 && $postData['image'] > 0) {
                    $video = ($postData['video'] > 1) ? 'videos' : 'video';
                    $image = ($postData['image'] > 1) ? 'images' : 'image';
                    $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " upload " . $postData['video'] . " " . $video . " and " . $postData['image'] . " " . $image . " to " . $ownerEvent->event_name . ' ' . $photo_module_type . '.';
                } elseif ($postData['video'] == 0 && $postData['image'] > 0) {
                    $image = ($postData['image'] > 1) ? 'images' : 'image';
                    // $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " upload " . $postData['image'] . " " . $image . " to " . $ownerEvent->event_name . ' ' . $photo_module_type . '.';
                    if ($image == 'image') {
                        $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " uploaded an " . $image . " to " . $ownerEvent->event_name . ' ' . $photo_module_type . '.';
                    } else {
                        $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " upload " . $postData['image'] . " " . $image . " to " . $ownerEvent->event_name . ' ' . $photo_module_type . '.';
                    }
                } elseif ($postData['image'] == 0 && $postData['video'] > 0) {
                    $video = ($postData['video'] > 1) ? 'videos' : 'video';
                    $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " uploaded " . $postData['video'] . " " . $video . " to " . $ownerEvent->event_name . ' ' . $photo_module_type . '.';
                } else {
                    $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " posted on the " . $photo_module_type . " for " . $ownerEvent->event_name;
                }

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
                            'event_id' => (string)$postData['event_id'],
                            'message' => $notification_message,
                            'type' => (string)$notificationType,
                            'notification_image' => $notification_image,
                            'post_id' => (string)$postData['post_id'],
                            'is_in_photo_moudle' => (string)$postData['is_in_photo_moudle'],
                            'post_type' => (string)$postData['post_type'],
                            'event_wall' => isset($ownerEvent->event_settings->event_wall) ? (string)$ownerEvent->event_settings->event_wall : '',
                            'guest_list_visible_to_guests' => isset($ownerEvent->event_settings->guest_list_visible_to_guests) ? (string)$ownerEvent->event_settings->guest_list_visible_to_guests : '',
                            'is_event_owner' => '1',
                            'is_post_by_host' => '1',
                            'is_owner_post' => '1',
                            'rsvp_staus' => '',

                        ];

                        $checkNotificationSetting = checkNotificationSetting($ownerEvent->user_id);

                        if ((count($checkNotificationSetting) && $checkNotificationSetting['wall_post']['push'] == '1') && $ownerEvent->notification_on_off == '1') {
                            send_notification_FCM_and($deviceData->device_token, $notificationData);
                        }
                    }
                }
            }
        }

        // post notify to  owner//
        $is_post_by_host = ($postData['sender_id'] == $ownerEvent->user_id) ? 1 : 0;
        foreach ($invitedusers as $key => $value) {
            $rsvp_status =  (isset($value->rsvp_status) && $value->rsvp_status != null) ? $value->rsvp_status : '';
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

                if ($postData['post_type'] == '2') {
                    // $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " upload new post";

                    $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " posted on " . $ownerEvent->event_name . " " . $photo_module_type;
                } else {
                    if ($postData['video'] > 0 && $postData['image'] > 0) {
                        $video = ($postData['video'] > 1) ? 'videos' : 'video';
                        $image = ($postData['image'] > 1) ? 'images' : 'image';
                        $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " upload " . $postData['video'] . " " . $video . " and " . $postData['image'] . " " . $image . " to " . $ownerEvent->event_name . ' ' . $photo_module_type . '.';
                    } elseif ($postData['video'] == 0 && $postData['image'] > 0) {
                        $image = ($postData['image'] > 1) ? 'images' : 'image';
                        if ($image == 'image') {
                            $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " uploaded an " . $image . " to " . $ownerEvent->event_name . ' ' . $photo_module_type . '.';
                        } else {
                            $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " upload " . $postData['image'] . " " . $image . " to " . $ownerEvent->event_name . ' ' . $photo_module_type . '.';
                        }
                    } elseif ($postData['image'] == 0 && $postData['video'] > 0) {
                        $video = ($postData['video'] > 1) ? 'videos' : 'video';
                        $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " upload " . $postData['video'] . " " . $video . " to " . $ownerEvent->event_name . ' ' . $photo_module_type . '.';
                    } else {
                        $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " posted on the " . $photo_module_type . " for " . $ownerEvent->event_name;
                    }
                }
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
                            'event_id' => (string)$postData['event_id'],
                            'message' => $notification_message,
                            'type' => (string)$notificationType,
                            'notification_image' => $notification_image,
                            'post_id' => (string)$postData['post_id'],
                            'is_in_photo_moudle' => (string)$postData['is_in_photo_moudle'],
                            'post_type' => (string)$postData['post_type'],
                            'event_wall' => isset($ownerEvent->event_settings->event_wall) ? (string)$ownerEvent->event_settings->event_wall : '',
                            'guest_list_visible_to_guests' => isset($ownerEvent->event_settings->guest_list_visible_to_guests) ? (string)$ownerEvent->event_settings->guest_list_visible_to_guests : '',
                            'is_event_owner' => '1',
                            'is_post_by_host' => '1',
                            'is_owner_post' => '1',
                            'rsvp_staus' => '',

                        ];
                        $checkNotificationSetting = checkNotificationSetting($value->user_id);

                        if ((count($checkNotificationSetting) && $checkNotificationSetting['wall_post']['push'] == '1') && $value->notification_on_off == '1') {
                            send_notification_FCM_and($deviceData->device_token, $notificationData);
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
                // $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " upload new post";
                $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " posted on " . $ownerEvent->event_name . " " . $photo_module_type;
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
                            'type' => (string)$notificationType,
                            'notification_image' => $notification_image,
                            'post_id' => (string)$postData['post_id'],
                            'is_in_photo_moudle' => (string)$postData['is_in_photo_moudle'],
                            'post_type' => (string)$postData['post_type'],
                            'event_wall' => isset($ownerEvent->event_settings->event_wall) ? (string)$ownerEvent->event_settings->event_wall : '',
                            'guest_list_visible_to_guests' => isset($ownerEvent->event_settings->guest_list_visible_to_guests) ? (string)$ownerEvent->event_settings->guest_list_visible_to_guests : '',
                            'is_event_owner' => (string)$is_event_owner,
                            'is_post_by_host' => (string)$is_post_by_host,
                            'is_owner_post' => '0',
                            'rsvp_status' => (string)$rsvp_status,

                        ];
                        $checkNotificationSetting = checkNotificationSetting($value->user_id);

                        if ((count($checkNotificationSetting) && $checkNotificationSetting['wall_post']['push'] == '1') && $value->notification_on_off == '1') {
                            send_notification_FCM_and($deviceData->device_token, $notificationData);
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
                // $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " upload new post";
                $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " posted on " . $ownerEvent->event_name . " " . $photo_module_type;
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
                            'type' => (string)$notificationType,
                            'notification_image' => $notification_image,
                            'post_id' => (string)$postData['post_id'],
                            'is_in_photo_moudle' => (string)$postData['is_in_photo_moudle'],
                            'post_type' => (string)$postData['post_type'],
                            'event_wall' => isset($ownerEvent->event_settings->event_wall) ? (string)$ownerEvent->event_settings->event_wall : '',
                            'guest_list_visible_to_guests' => isset($ownerEvent->event_settings->guest_list_visible_to_guests) ? (string)$ownerEvent->event_settings->guest_list_visible_to_guests : '',
                            'is_event_owner' => (string)$is_event_owner,
                            'is_post_by_host' => (string)$is_post_by_host,
                            'is_owner_post' => '0',
                            'rsvp_status' => (string)$rsvp_status,

                        ];
                        $checkNotificationSetting = checkNotificationSetting($value->user_id);

                        if ((count($checkNotificationSetting) && $checkNotificationSetting['wall_post']['push'] == '1') && $value->notification_on_off == '1') {
                            send_notification_FCM_and($deviceData->device_token, $notificationData);
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
                $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " posted on " . $ownerEvent->event_name . " " . $photo_module_type;
                // $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " upload new post";
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
                            'type' => (string)$notificationType,
                            'notification_image' => $notification_image,
                            'post_id' => (string)$postData['post_id'],
                            'is_in_photo_moudle' => (string)$postData['is_in_photo_moudle'],
                            'post_type' => (string)$postData['post_type'],
                            'event_wall' => isset($ownerEvent->event_settings->event_wall) ? (string)$ownerEvent->event_settings->event_wall : '',
                            'guest_list_visible_to_guests' => isset($ownerEvent->event_settings->guest_list_visible_to_guests) ? (string)$ownerEvent->event_settings->guest_list_visible_to_guests : '',
                            'is_event_owner' => (string)$is_event_owner,
                            'is_post_by_host' => (string)$is_post_by_host,
                            'is_owner_post' => '0',
                            'rsvp_status' => (string)$rsvp_status,

                        ];
                        $checkNotificationSetting = checkNotificationSetting($value->user_id);

                        if ((count($checkNotificationSetting) && $checkNotificationSetting['wall_post']['push'] == '1') && $value->notification_on_off == '1') {
                            send_notification_FCM_and($deviceData->device_token, $notificationData);
                        }
                    }
                }
            }
        }
    }

    if ($notificationType == 'like_post') {

        $getPostOwnerId = EventPost::where('id', $postData['post_id'])->first();
        // $eventSetting = EventSetting::where('event_id', $postData['event_id'])->first();
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
                // $event = Event::where('id', $postData['event_id'])->first();
                // $invitedusers = EventInvitedUser::where(['event_id' => $postData['event_id'], 'user_id' => $getPostOwnerId->user_id])->first();
                // $is_post_by_host = (isset($event) && $event->user_id == $getPostOwnerId->user_id) ? 1 : 0;
                // $rsvp_status = isset($invitedusers) ? $invitedusers->rsvp_status : '';
                if (!empty($deviceData)) {
                    $notificationImage = EventPostImage::where('event_post_id', $postData['post_id'])->first();
                    $notification_image = "";
                    if (!empty($notificationImage->post_image) && $notificationImage->post_image != NULL) {

                        $notification_image = asset('public/storage/post_image/' . $notificationImage->post_image);
                    }

                    $notificationData = [
                        'message' => $notification_message,
                        'type' => (string)$notificationType,
                        'notification_image' => $notification_image,
                        'event_id' => (string)$postData['event_id'],
                        'post_id' => (string)$postData['post_id'],
                        'post_type' => (string)$getPostOwnerId->post_type,
                        'is_in_photo_moudle' => (string)$postData['is_in_photo_moudle'],
                        // 'event_wall' => isset($eventSetting->event_wall) ? $eventSetting->event_wall : '',
                        // 'guest_list_visible_to_guests' => isset($eventSetting->guest_list_visible_to_guests) ? $eventSetting->guest_list_visible_to_guests : '',
                        // 'is_post_by_host' => $is_post_by_host,
                        // 'is_event_owner' => $is_post_by_host,
                        // 'is_owner_post' => 1,
                        // 'rsvp_status' => $rsvp_status,
                    ];

                    $notification_on_off = isOwnerOrInvited($getPostOwnerId->user_id, $postData['event_id']);

                    $checkNotificationSetting = checkNotificationSetting($getPostOwnerId->user_id);

                    if ((count($checkNotificationSetting) && $checkNotificationSetting['wall_post']['push'] == '1') && $notification_on_off == '1') {
                        send_notification_FCM_and($deviceData->device_token, $notificationData);
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
                        'type' => (string)$notificationType,
                        'notification_image' => $notification_image,
                        'post_id' => (string)$postData['post_id'],
                        'comment_id' => (string)$postData['comment_id'],
                        'event_id' => (string)$postData['event_id'],
                        'post_type' => (string)$getPostOwnerId->post_type,

                    ];

                    $notification_on_off = isOwnerOrInvited($ownerOfComment, $postData['event_id'], $postData['post_id']);

                    $checkNotificationSetting = checkNotificationSetting($ownerOfComment);

                    if ((count($checkNotificationSetting) && $checkNotificationSetting['wall_post']['push'] == '1') && $notification_on_off == '1') {
                        send_notification_FCM_and($deviceData->device_token, $notificationData);
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
                        'type' => (string)$notificationType,
                        'notification_image' => $notification_image,
                        'post_id' => (string)$postData['post_id'],
                        'event_id' => (string)$postData['event_id'],
                        'post_type' => (string)$getPostOwnerId->post_type,

                    ];

                    $notification_on_off = isOwnerOrInvited($getPostOwnerId->user_id, $postData['event_id']);
                    $checkNotificationSetting = checkNotificationSetting($getPostOwnerId->user_id);

                    if ((count($checkNotificationSetting) && $checkNotificationSetting['wall_post']['push'] == '1') && $notification_on_off == '1') {
                        send_notification_FCM_and($deviceData->device_token, $notificationData);
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
            $notification->comment_id = $postData['comment_id'];
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
                        'type' => (string)$notificationType,
                        'notification_image' => $notification_image,
                        'post_id' => (string)$postData['post_id'],
                        'comment_id' => (string)$postData['comment_id'],
                        'event_id' => (string)$postData['event_id'],
                        'post_type' => (string)$getPostOwnerId->post_type,
                        'reply_comment_id' => (string)$getCommentOwnerId->id,
                    ];


                    $notification_on_off = isOwnerOrInvited($ownerOfComment, $postData['event_id'], $postData['post_id']);
                    $checkNotificationSetting = checkNotificationSetting($ownerOfComment);

                    if ((count($checkNotificationSetting) && $checkNotificationSetting['wall_post']['push'] == '1') && $notification_on_off == '1') {
                        send_notification_FCM_and($deviceData->device_token, $notificationData);
                    }


                    // send_notification_FCM($deviceData->device_token, $notification_message);
                }
            }
        }
    }

    if ($notificationType == 'sent_rsvp') {

        $getPostOwnerId = Event::with(['event_settings', 'user'])->where('id', $postData['event_id'])->first();
        if ((isset($postData['sync_id']) && $postData['sync_id'] != "") && $postData['sender_id'] == null || $postData['sender_id'] == "") {
            $firstname = $senderData->firstName;
            $lastname = $senderData->lastName;
        } else {
            $firstname = $senderData->firstname;
            $lastname = $senderData->lastname;
        }
        if ($postData['rsvp_status'] == '1') {
            $notification_message = $firstname . ' '  . $lastname . " RSVP'd Yes for " . $getPostOwnerId->event_name;
        } elseif ($postData['rsvp_status'] == '0') {
            $notification_message = $firstname . ' '  . $lastname . " RSVP'd No for " . $getPostOwnerId->event_name;
        }
        if ($postData['sync_id'] == "" || $postData['sync_id'] == null) {
            if ($getPostOwnerId->user_id != $postData['sender_id']) {

                Notification::where([
                    'event_id' => $postData['event_id'],
                    'user_id' => $getPostOwnerId->user_id,
                    'sender_id' => $postData['sender_id'],
                    'notification_type' => $notificationType
                ])->delete();

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
                            'type' => (string)$notificationType,
                            'notification_image' => $notification_image,
                            'post_id' => "",
                            'event_id' => (string)$postData['event_id'],
                            'event_name' => (string)$getPostOwnerId->event_name,
                            'event_wall' => (string)$getPostOwnerId->event_settings->event_wall,
                            'guest_list_visible_to_guests' => (string)$getPostOwnerId->event_settings->guest_list_visible_to_guests,
                            'event_potluck' => (string)$getPostOwnerId->event_settings->podluck,
                            'guest_pending_count' => (string)getGuestPendingRsvpCount($postData['event_id']),
                        ];

                        $checkNotificationSetting = checkNotificationSetting($getPostOwnerId->user_id);

                        if ((count($checkNotificationSetting) && $checkNotificationSetting['guest_rsvp']['push'] == '1') && $getPostOwnerId->notification_on_off == '1') {
                            send_notification_FCM_and($deviceData->device_token, $notificationData);
                        }
                        if ((count($checkNotificationSetting) && $checkNotificationSetting['guest_rsvp']['email'] == '1') && $getPostOwnerId->notification_on_off == '1') {
                            $invitedUserRsvpMsg = EventInvitedUser::where(['event_id' => $postData['event_id'], 'user_id' => $senderData->id])->first();
                            $eventData = [
                                'event_invited_user_id' => (int)$invitedUserRsvpMsg->id,
                                'event_id' => $postData['event_id'],
                                'owner_id' => $getPostOwnerId->user_id,
                                'event_name' => $getPostOwnerId->event_name,
                                'guest_name' => $senderData->firstname . ' '  . $senderData->lastname,
                                'profileUser' => ($senderData->profile != NULL || $senderData->profile != "") ? $senderData->profile : "no_profile.png",
                                'rsvp_status' =>  $postData['rsvp_status'],
                                'kids' => $postData['kids'],
                                'adults' => $postData['adults'],
                                'host_email' => $senderData->email,
                                'rsvp_message' => ($invitedUserRsvpMsg->message_to_host != NULL || $invitedUserRsvpMsg->message_to_host != "") ? $invitedUserRsvpMsg->message_to_host : ""
                            ];
                            // dd($eventData);
                            $invitation_email = new NewRsvpsEmailNotify($eventData);
                            Mail::to($getPostOwnerId->user->email)->send($invitation_email);
                        }
                        
                    }
                }
            }
        }
    }

    if ($notificationType == 'potluck_bring') {

        $getPostOwnerId = Event::with('event_settings')->where('id', $postData['event_id'])->first();


        $notification_message = $senderData->firstname . ' '  . $senderData->lastname . " will be bring the item for " . $getPostOwnerId->event_name . ' Potluck';


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
                        'type' => (string)$notificationType,
                        'notification_image' => $notification_image,
                        'event_id' => (string)$postData['event_id'],
                        'event_name' => (string)$getPostOwnerId->event_name,
                        'event_wall' => (string)$getPostOwnerId->event_settings->event_wall,
                        'guest_list_visible_to_guests' => (string)$getPostOwnerId->event_settings->guest_list_visible_to_guests,
                        'event_potluck' => (string)$getPostOwnerId->event_settings->podluck,
                        'guest_pending_count' => (string)getGuestPendingRsvpCount($postData['event_id'])
                    ];
                    $checkNotificationSetting = checkNotificationSetting($getPostOwnerId->user_id);

                    if ((count($checkNotificationSetting) && $checkNotificationSetting['potluck_activity']['push'] == '1') && $getPostOwnerId->notification_on_off == '1') {
                        send_notification_FCM_and($deviceData->device_token, $notificationData);
                    }
                }
            }
        }
    }
}

function sendNotificationGuest($notificationType, $postData)
{
    //'invite', 'upload_post', 'like_post', 'comment', 'reply', 'poll', 'rsvp'
    $user  = Auth::guard('api')->user();

    $senderData = User::where('id', $postData['sender_id'])->first();

    $notification_message = "";

    $invitedusers = EventInvitedUser::with(['event', 'event.event_settings', 'event.event_schedule', 'contact_sync'])
        ->where('event_id', $postData['event_id'])
        ->where('sync_id', '!=', '')
        ->get();

    if ($notificationType == 'invite') {
        // dd(0);
        if (count($invitedusers) != 0) {
            if (isset($postData['newUser']) && count($postData['newUser']) != 0) {
                $invitedusers = EventInvitedUser::with(['event', 'event.event_image', 'event.user', 'event.event_settings', 'event.event_schedule', 'contact_sync'])
                    ->whereIn('sync_id', $postData['newUser'])->where('event_id', $postData['event_id'])
                    ->where('sync_id', '!=', '')
                    ->get();
            }

            // dd($invitedusers);
            foreach ($invitedusers as $value) {
                // dd($value->event);
                $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " has invited you to " . $value->event->event_name;
                if ($value->is_co_host == '1') {
                    $notification_message = $senderData->firstname . ' ' . $senderData->lastname . " invited you to be co-host in " . $value->event->event_name . ' Accept?';
                }

                if ($value->prefer_by == 'email') {

                    $eventData = [
                        'event_invited_user_id' => (int)$value->id,
                        'event_id' => (int)$postData['event_id'],
                        'user_id' => $value->contact_sync->id,
                        'event_name' => $value->event->event_name,
                        'hosted_by' => $value->event->user->firstname . ' ' . $value->event->user->lastname,
                        'profileUser' => ($value->event->user->profile != NULL || $value->event->user->profile != "") ? $value->event->user->profile : "no_profile.png",
                        'event_image' => ($value->event->event_image->isNotEmpty()) ? $value->event->event_image[0]->image : "no_image.png",
                        'date' =>   date('l - M jS, Y', strtotime($value->event->start_date)),
                        'time' => $value->event->rsvp_start_time,
                        'host_email' => $senderData->email,
                        'address' => $value->event->event_location_name . ' ' . $value->event->address_1 . ' ' . $value->event->address_2 . ' ' . $value->event->state . ' ' . $value->event->city . ' - ' . $value->event->zip_code,
                    ];

                    $emailCheck = dispatch(new sendInvitation(array($value->contact_sync->email, $eventData)));
                    $updateinvitation = EventInvitedUser::where(['event_id' => $postData['event_id'], 'sync_id' => $value->sync_id, 'prefer_by' => 'email'])->first();

                    if (!empty($emailCheck)) {
                        $updateinvitation->invitation_sent = '1';
                        $updateinvitation->save();
                    } else {
                        $updateinvitation->invitation_sent = '9';
                        $updateinvitation->save();
                    }
                }
                if ($value->prefer_by == 'phone') {

                    $logData = [
                        'user_phone' => $value->contact_sync->phoneWithCode,
                        'host_name' => $value->event->user->firstname . ' ' . $value->event->user->lastname,
                        'event_name' => $value->event->event_name,
                        'event_id' => $postData['event_id'],
                        'event_invited_user_id' => $value->id
                    ];

                    // Log the parameters
                    Log::info('Sending SMS Invite', $logData);
                    $sent = handleSMSInvite($value->contact_sync->phoneWithCode,  $value->event->user->firstname . ' ' . $value->event->user->lastname, $value->event->event_name, $postData['event_id'], $value->id);
                    // $sent = sendSMSForApplication($value->contact_sync->phoneWithCode, $notification_message);
                    if ($sent == true) {
                        $updateinvitation = EventInvitedUser::where(['event_id' => $postData['event_id'], 'sync_id' => $value->sync_id, 'prefer_by' => 'phone'])->first();
                        $updateinvitation->invitation_sent = '1';
                        $updateinvitation->save();
                    } else {
                        $updateinvitation = EventInvitedUser::where(['event_id' => $postData['event_id'], 'sync_id' => $value->sync_id, 'prefer_by' => 'phone'])->first();
                        $updateinvitation->invitation_sent = '9';
                        $updateinvitation->save();
                    }
                }
            }
        }
    }
}

function checkUserEmailExist($checkContactExist)
{
    $checkUserExist = User::where('email', $checkContactExist->email)->first();
    if ($checkUserExist == NULL) {
        $addUser = new User();
        $addUser->firstname = $checkContactExist->firstName;
        $addUser->lastname = $checkContactExist->lastName;
        $addUser->email = $checkContactExist->email;
        $addUser->app_user = '0';
        $addUser->save();
        $newUserId = $addUser->id;
    } else {
        $newUserId = $checkUserExist->id;
    }
    return $newUserId;
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


function adminNotification($notificationType, $postData)
{
    if ($notificationType == 'broadcast_message') {
        $deviceDataArray = [];
        $emailsSent = false;
        $notificationsSent = false;
        try {
            $deviceTokens = [];
            $userEmails = [];
            $userDataList = [];
            $users = User::select('email')->whereNotNull('email')
                ->whereNotNull('email_verified_at')
                ->get();
            $emails = $users->pluck('email')->toArray();
            $message = $postData['message'];

            $deviceData = Device::all();
            foreach ($deviceData as $device) {
                $deviceDataArray[] = [
                    'device_token' => $device->device_token,
                ];
                $notificationData = [
                    'message' => $message,
                    'type' => $notificationType,
                ];
                try {
                    send_notification_FCM_and($device->device_token, $notificationData);
                } catch (\Exception $e) {
                    return response()->json(['error' => 'Failed to send emails.'], 500);
                }
            }
            try {
                SendBroadcastEmailJob::dispatch($emails, $message);
                $emailsSent = true;
            } catch (\Exception $e) {
                // dd($e->getMessage());
                return response()->json(['error' => 'Failed to send emails.'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send emails.'], 500);
        }
    }
}
function send_notification_FCM($deviceToken, $notifyData)
{

    $serverKey  = ServerKey::first();
    $SERVER_API_KEY = $serverKey->firebase_key;

    $URL = 'https://fcm.googleapis.com/fcm/send';

    // $notificationLoad =  [
    //     'title' => "Yesvite",
    //     "body" => $notifyData['message'],
    //     'sound' => "default",
    //     'message' => $notifyData['message'],
    //     'color' => "#79bc64",
    // ];
    $notification = array(
        'title' => 'Yesvite',
        'body' => $notifyData['message'],
        'sound' => 'default',
        'message' => $notifyData['message'],
        'color' => "#79bc64",
        'image' => $notifyData['notification_image'],
        'category' => 'content_added_notification',
    );
    $message = array(
        'to' => $deviceToken,
        'notification' => $notification,
        'data' => $notifyData,
        'aps' => array(
            'alert' => array(
                'title' => "Yesvite",
                'body' => $notifyData['message']
            ),
            'category' => 'content_added_notification',
            'mutable-content' => true,
            'content-available' => true,
        ),
    );


    // $apsPayload = [
    //     'alert' => [
    //         'title' => "Yesvite",
    //         'body' => $notifyData['message']
    //     ],
    //     'color' => "#79bc64",
    //     'sound' => "default",
    //     'message' => $notifyData['message'],
    //     'mutable-content' => 1, // This should be inside the aps dictionary
    //     'category' => 'content_added_notification' // This should also be inside the aps dictionary
    // ];

    // $dataPayload = [
    //     "to" => $deviceToken,
    //     "data" => $notifyData,
    //     "notification" => $notificationLoad,
    //     "priority" => "high",

    // ];

    $post_data = json_encode($message);

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
    dd($rest);
    if ($rest === false) {
        $result_noti = 0;
    } else {
        $result_noti = 1;
    }

    return $result_noti;
}

function send_notification_FCM_and($deviceToken, $notifyData)
{
    // dd($deviceToken,$notifyData);
    $serverKey  = ServerKey::first();
    $SERVER_API_KEY = $serverKey->firebase_key;
    $URL = $serverKey->firebase_key;
    $accessToken = getAccessToken();

    // $dataPayload = [
    //     "to" => trim($deviceToken),
    //     "data" => $notifyData,
    // ];

    // $post_data = json_encode($dataPayload);
    $notification = array(
        'title' => 'Yesvite',
        'body' => $notifyData['message'],
    );
    $message = [
        'message' => [
            'token' => trim($deviceToken),
            'notification' => $notification,
            'data' => $notifyData,
            'apns' =>  ['payload' => ['aps' => array(
                'alert' => array(
                    'title' => "Yesvite",
                    'body' => $notifyData['message']
                ),
                'category' => 'content_added_notification',
                'mutable-content' => 1,
                'content-available' => 1,
            )]],
        ]
    ];

    $post_data = json_encode($message);
    $crl = curl_init();

    $headr = array();
    $headr[] = 'Content-type: application/json';
    $headr[] = 'Authorization: Bearer ' . trim($accessToken);
    $headr[] = 'Content-Length: ' . strlen($post_data);
    curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($crl, CURLOPT_URL, $URL);
    curl_setopt($crl, CURLOPT_HTTPHEADER, $headr);

    curl_setopt($crl, CURLOPT_POST, true);
    curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);

    $rest = curl_exec($crl);
    $responseData = json_decode($rest, true);
    if (isset($responseData['name'])) {
        $result_noti = 0;
    } else {
        $result_noti = 1;
    }

    return $result_noti;
}

function getAccessToken()
{
    $scopes = ['https://www.googleapis.com/auth/cloud-platform'];
    $serviceAccountPath = storage_path('app/google-play-service-account.json');
    if (!file_exists($serviceAccountPath)) {
        throw new \InvalidArgumentException("Service account file does not exist at path: $serviceAccountPath");
    }
    $credentials = new ServiceAccountCredentials($scopes, $serviceAccountPath);
    $accessToken = $credentials->fetchAuthToken()['access_token'];
    return $accessToken;
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
        // if (config('app.env', 'local') == 'local') {
        //     return false;
        // }

        // $account_sid = env('ACCOUNT_SID');
        // $auth_token = env("AUTH_TOKEN");
        // $twilio_number = env("TWILIO_NUMBER");
        $twl_acc=ServerKey::get()->first();
        // $account_sid = env('ACCOUNT_SID');
        // $auth_token = env("AUTH_TOKEN");
        // $twilio_number = env("TWILIO_NUMBER");
        $account_sid = $twl_acc->twilio_account_sid;
        $auth_token = $twl_acc->twilio_auth_token;
        $twilio_number = $twl_acc->twilio_number;

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

function validateAndFormatPhoneNumber($receiverNumber, $defaultCountryCode = 'IN')
{
    $phoneUtil = PhoneNumberUtil::getInstance();
    // dd($phoneUtil);

    try {
        $parsedNumber = $phoneUtil->parse($receiverNumber, $defaultCountryCode);

        // dd($phoneUtil);
        if (!$phoneUtil->isValidNumber($parsedNumber)) {
            throw new \Exception("Invalid phone number.");
        }

        return $phoneUtil->format($parsedNumber, PhoneNumberFormat::E164);
    } catch (NumberParseException $e) {
        throw new \Exception("Error parsing phone number: " . $e->getMessage());
    }
}

function cleanPhoneNumber(string $phoneNumber): string
{
    // Replace unwanted characters from the phone number
    $cleanedNumber = str_replace(
        [
            "+91",
            "+1",
            "+",
            " ",
            "(",
            ")",
            "-",
            "\n",
            "\r",
            "%0A",
            "%0a",
            "%0D",
            "%0d"
        ],
        "",
        $phoneNumber
    );

    // Remove any remaining whitespace or control characters
    $cleanedNumber = preg_replace([
        '/\s+/',                // Matches any whitespace character
        '/[\p{Cf}\p{Cn}\p{Cs}]/u' // Matches control characters
    ], "", $cleanedNumber);

    try {
        // Encode the cleaned number
        return urlencode($cleanedNumber);
    } catch (Exception $e) {
        // Handle encoding exception
        error_log($e->getMessage());
        return $cleanedNumber;
    }
}

function sendSMSForApplication($receiverNumber, $message)
{
    // return true;
    // dd($message);
    try {
        if (config('app.debug', true)) {
            // return false;
        }
        // $cleanedNumber = cleanPhoneNumber($phoneNumber);
        // $formattedNumber = validateAndFormatPhoneNumber($receiverNumber);
        $formattedNumber = $receiverNumber;
        $serverKeys = ServerKey::first();
        $client = new Client($serverKeys->twilio_account_sid, $serverKeys->twilio_auth_token);
        $client->messages->create($formattedNumber, [
            // 'from' => $serverKeys->twilio_number,
            'messagingServiceSid' => 'MGcebfc07e4d04619be31576c5ad1f906d', // Use Messaging Service SID
            'body' => $message
        ]);

        return  true;
    } catch (Exception $e) {


        return  false;
    }
}

function createShortUrl($longUrl)
{
    try {

        do {
            // Generate a random 15-character key
            $shortUrlKey = Str::random(10);
        } while (Url::where('short_url_key', $shortUrlKey)->exists()); // Ensure uniqueness

        // Insert into the database
        $url = Url::create([
            'long_url' => $longUrl,
            'short_url_key' => $shortUrlKey,
            'expires_at' => now()->addDays(90) // Expire after 90 days
        ]);
        //return "https://yesvite.com/invite/{$shortUrlKey}";
        // $base_url=config('app.url');
        $base_url = url('/');
        // return "{$base_url}/invite/{$shortUrlKey}";
        return $base_url . '/invite/' . $shortUrlKey;
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

function handleSMSInvite($receiverNumber, $hostName, $eventName, $event_id, $event_invited_user_id)
{
    try {
        $cleanedNumber = preg_replace('/[^0-9]/', '', $receiverNumber);
        if (strpos($cleanedNumber, '1') === 0 && strlen($cleanedNumber) > 10) {
            $cleanedNumber = substr($cleanedNumber, 1);
        }
        if (strlen($cleanedNumber) < 10) {
            $cleanedNumber = $receiverNumber;
        }
        $user = Useropt::where('phone', $cleanedNumber)->first();
        if (!$user) {
            $user = Useropt::create([
                'phone' => $cleanedNumber,
                'event_id' => $event_id,
                'event_invited_user_id' => $event_invited_user_id,
                'opt_in_status' => false, // Default opt-in status is false
            ]);
        } else {
            $existingEventUser = Useropt::where('phone', $cleanedNumber)
                ->where('event_id', $event_id)
                ->first();
            if (!$existingEventUser) {
                Useropt::create([
                    'phone' => $cleanedNumber,
                    'event_id' => $event_id,
                    'event_invited_user_id' => $event_invited_user_id,
                    'opt_in_status' => $user->opt_in_status, // Maintain opt_in_status across events
                ]);
            } else {
                $user = $existingEventUser;
            }
        }
        $eventLink = route('rsvp', ['event_invited_user_id' => encrypt($event_invited_user_id), 'eventId' => encrypt($event_id)]);
        $shortLink = createShortUrl($eventLink);
        if (!$user->opt_in_status) {
            $message = 'Yesvite: ' . $hostName . ' has invited you to an event. To View the invite/Event details a ONE TIME opt in is required by new sms regulations to receive future invites/messages. Please reply "Yes" to opt in. Reply STOP to opt out.';
        } else {
            $message = "Yesvite:  \" $hostName \" has invited you to  \"$eventName\" View invite, RSVP and message the host here: \"$shortLink\". Reply STOP to opt out.";
        }
        return sendSMSForApplication($receiverNumber, $message);
    } catch (\Exception $e) {
        // Log the error for debugging
        Log::error('Error sending SMS invite: ' . $e->getMessage(), [
            'receiverNumber' => $receiverNumber,
            'hostName' => $hostName,
            'eventName' => $eventName,
            'event_id' => $event_id,
            'event_invited_user_id' => $event_invited_user_id
        ]);
    }
}


function handleIncomingMessage($receiverNumber, $message)
{

    $cleanedNumber = preg_replace('/[^0-9]/', '', $receiverNumber);
    if (strpos($cleanedNumber, '1') === 0 && strlen($cleanedNumber) > 10) {
        $cleanedNumber = substr($cleanedNumber, 1);
    }
    if (strlen($cleanedNumber) < 10) {
        $cleanedNumber = $receiverNumber;
    }
    if (strtolower($message) == 'yes') {

        $users = UserOpt::where(['phone' => $cleanedNumber, 'opt_in_status' => false])
            ->select('id', 'event_id', 'event_invited_user_id')
            ->groupBy('id', 'event_id', 'event_invited_user_id')
            ->get();
        if ($users->isNotEmpty()) { // Corrected check
            // sendSMSForApplication($cleanedNumber, "Yesvite: You have been subscribed to receive SMS invites/messages. . Reply STOP to opt out.");
        }
        foreach ($users as $user) {
            $user->update(['opt_in_status' => true]);

            // Get event details dynamically
            $event = EventInvitedUser::with(['event', 'event.user'])
                ->where('id', $user->event_invited_user_id)
                ->first();

            if ($event) {

                $eventLink = route('rsvp', ['event_invited_user_id' => encrypt($user->event_invited_user_id), 'eventId' => encrypt($user->event_id)]);
                $shortLink = createShortUrl($eventLink);
                // dd($shortLink);
                $confirmationMessage = "Yesvite:  \"{$event->event->user->firstname} {$event->event->user->lastname}\" has invited you to  \"{$event->event->event_name}\"  View invite, RSVP and message the host here:\"{$shortLink}\". Reply STOP to opt out.";
                try {

                    sendSMSForApplication($cleanedNumber, $confirmationMessage);
                } catch (Exception $e) {
                    // Log::error("Failed to send confirmation SMS to {$receiverNumber}: " . $e->getMessage());
                }
            } else {
                // Log::warning("Event details not found for event_id: {$user->event_id}");
            }
        }
    } elseif (strtolower($message) == 'stop') {
        $users = UserOpt::where('phone', $cleanedNumber)->get();
        foreach ($users as $user) {
            $user->update(['opt_in_status' => false]);
        }

        // Unsubscribe confirmation
        $unsubscribeMessage = "You have successfully been unsubscribed from Yesvite via SMS invites. You will not receive any more messages from this number. Reply START to resubscribe.";
        //sendSMSForApplication($receiverNumber, $unsubscribeMessage);
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
        'refresh_token' => '1//0gNUrRx3nx_asCgYIARAAGBASNwF-L9Ir-s8ZuTC1TOFWoOvWDbyzUtdTG6z40XfSaTLekuuEEGW43Pqb_WMyS5qdJcb0v7H4KEg',
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

    $userSubscription = UserSubscription::where('user_id', $userId)
        ->where('type', 'subscribe')
        ->orderBy('id', 'DESC')
        ->limit(1)
        ->first();
    if ($userSubscription != null) {
        $app_id = $userSubscription->packageName;
        $product_id = $userSubscription->productId;
        $purchaseToken = $userSubscription->purchaseToken;
        if ($userSubscription->device_type == 'ios') {
            $responce = set_apple_iap($purchaseToken);

            foreach ($responce->latest_receipt_info as $key => $value) {
                if (isset($value->expires_date_ms) && $value->expires_date_ms != null && date('Y-m-d H:i', ($value->expires_date_ms /  1000)) >= date('Y-m-d H:i')) {
                    $enddate =  date('Y-m-d H:i:s', ($value->expires_date_ms /  1000));
                    $current_date = date('Y-m-d H:i:s');
                    if (strtotime($current_date) > strtotime($enddate)) {
                        $userSubscription->endDate = $enddate;
                        $userSubscription->save();
                        return false;
                    }
                    if (isset($responce->error)) {
                        return false;
                    }
                    return true;
                }
            }
        } else {

            $responce =  set_android_iap($app_id, $product_id, $purchaseToken, 'subscribe');
            if (isset($responce) && !empty($responce)) {
                if (isset($responce['expiryTimeMillis']) && $responce['expiryTimeMillis'] != null) {
                    $exp_date =  date('Y-m-d H:i:s', ($responce['expiryTimeMillis'] /  1000));
                    $date = Carbon::parse($exp_date);
                    $current_date = date('Y-m-d H:i:s');
                    if (strtotime($current_date) > strtotime($exp_date)) {
                        $userSubscription->endDate = $exp_date;
                        $userSubscription->save();
                        return false;
                    }
                }
                if (isset($responce['userCancellationTimeMillis'])) {
                    $cancellationdate =  date('Y-m-d H:i:s', ($responce['userCancellationTimeMillis'] /  1000));
                    $userSubscription->cancellationdate = $cancellationdate;
                    $userSubscription->save();
                    return false;
                }
                if (isset($responce['error'])) {
                    return false;
                }
            }

            // $exp_date =  date('Y-m-d H:i:s', ($responce['expiryTimeMillis'] /  1000));


            // $current_date = date('Y-m-d H:i:s');

            // if (strtotime($current_date) > strtotime($exp_date)) {

            //     $userSubscription->endDate = $exp_date;
            //     $userSubscription->save();
            //     return false;
            // }
            // if (isset($responce['userCancellationTimeMillis'])) {

            //     $cancellationdate =  date('Y-m-d H:i:s', ($responce['userCancellationTimeMillis'] /  1000));
            //     $userSubscription->cancellationdate = $cancellationdate;
            //     $userSubscription->save();
            //     return false;
            // }
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
    $redirectUri = env('INAPP_REDIRECT_URL');
    $refreshToken = env('INAPP_PURCHASE_REFRESH_TOKEN');

    // $clientSecret = env('InGOOGLE_CLIENT_SECRET');
    // $redirectUri = 'https://yesvite.cmexpertiseinfotech.in/google/callback';

    // $refreshToken = '1//0gNUrRx3nx_asCgYIARAAGBASNwF-L9Ir-s8ZuTC1TOFWoOvWDbyzUtdTG6z40XfSaTLekuuEEGW43Pqb_WMyS5qdJcb0v7H4KEg';


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

    // if (!$result || !$result["access_token"]) {
    //     //error
    //     // return;
    // }
    if (isset($result['access_token']) && $result['access_token'] != null) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $VALIDATE_URL . "?access_token=" . $result["access_token"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result1 = curl_exec($ch);
        $result1 = json_decode($result1, true);
        if (!$result1 || (isset($result1["error"]) && $result1["error"] != null)) {
            //error
            // return;
        }
    } else {
        $result1 = $result;
    }
    // dd($result1);
    return $result1;

    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, $VALIDATE_URL . "?access_token=" . $result["access_token"]);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // $result1 = curl_exec($ch);
    // $result1 = json_decode($result1, true);
    // if (!$result1 || (isset($result1["error"]) && $result1["error"] != null)) {
    //     //error
    //     // return;
    // }

    // return $result1;
}

function set_apple_iap($receipt)
{
    $data = array(
        'receipt-data' => $receipt,
        'password' => 'e26c3c7903f74a89a2103d424cd33d4b',
        'exclude-old-transactions' => 'true'
    );

    $payload = json_encode($data);

    // Prepare new cURL resource
    $ch = curl_init('https://sandbox.itunes.apple.com/verifyReceipt');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

    // Set HTTP Header for POST request
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload)
        )
    );

    // Submit the POST request
    $result = curl_exec($ch);
    // Close cURL session handle
    curl_close($ch);
    $userReceiptData = json_decode($result);
    return $userReceiptData;
}

function add_user_firebase($userId, $userStatus = null)
{

    $firebase = Firebase::database();
    $usersReference = $firebase->getReference('users');

    $userData = User::findOrFail($userId);
    // dd($userData);
    $userName =  $userData->firstname . ' ' . $userData->lastname;
    $updateData = [
        'userChatId' => '',
        'userCountryCode' => (string)$userData->country_code,
        'userGender' => (string)$userData->gender,
        'userEmail' => $userData->email,
        'userId' => (string)$userId,
        'userLastSeen' => now()->timestamp * 1000, // Convert to milliseconds
        'userName' => $userName,
        'userPhone' => (string)$userData->phone_number,
        'userProfile' => url('/public/storage/profile/' . $userData->profile),
        'userStatus' => ($userStatus != null) ? $userStatus : '',
        'userTypingStatus' => 'Not typing...',
        'userToken' => ''
    ];

    // Create a new user node with the userId
    $userRef = $usersReference->getChild((string)$userId);
    $userSnapshot = $userRef->getValue();

    if ($userSnapshot) {
        // User exists, update the existing data
        $userRef->update($updateData);
    } else {
        // User does not exist, create a new user node
        $userRef->set($updateData);
    }
    return true;
}

function delete_event_post_images($post_id)
{
    $event_post_images = EventPostImage::where('event_post_id', $post_id)->get();
    if (isset($event_post_images) && $event_post_images->isNotEmpty()) {
        foreach ($event_post_images as $key => $value) {
            if ($value->type == 'image') {
                if (file_exists(public_path('storage/post_image/') . $value->post_image)) {
                    $imagePath = public_path('storage/post_image/') . $value->post_image;
                    unlink($imagePath);
                }
            } elseif ($value->type == 'video') {
                if (file_exists(public_path('storage/thumbnails/') . $value->thumbnail)) {
                    $imagePath = public_path('storage/thumbnails/') . $value->thumbnail;
                    unlink($imagePath);
                }
                if (file_exists(public_path('storage/post_image/') . $value->post_image)) {
                    $imagePath = public_path('storage/post_image/') . $value->post_image;
                    unlink($imagePath);
                }
            }
            EventPostImage::where('id', $value->id)->delete();
        }
    }
}

function debit_coins($user_id, $event_id, $get_count_invited_user)
{


    $user_data = User::where('id', $user_id)->first();
    $event = Event::where('id', $event_id)->first();
    if ($get_count_invited_user > 0) {
        $current_balance = $user_data->coins - $get_count_invited_user;

        $coin_transaction = new Coin_transactions();
        $coin_transaction->user_id = $user_id;
        $coin_transaction->event_id = $event_id;
        $coin_transaction->type = 'debit';
        $coin_transaction->coins = $get_count_invited_user;
        $coin_transaction->description = (isset($event->event_name) && $event->event_name != '') ? $event->event_name : '';
        $coin_transaction->current_balance = $current_balance;
        $coin_transaction->save();
        $user_data->coins = $current_balance;
        $user_data->save();

        $current_debit_coin = $get_count_invited_user;
        $creditCoins = Coin_transactions::where(['user_id' => $user_id, 'type' => 'credit', 'status' => '0'])->get();

        foreach ($creditCoins as $getCreditCoin) {
            $remain = $getCreditCoin->coins - $getCreditCoin->used_coins;
            if ($remain > 0 && $current_debit_coin > 0) {
                $temp = $remain - $current_debit_coin;
                if ($temp > 0) {
                    $getCreditCoin->used_coins += $current_debit_coin;
                    if ($getCreditCoin->used_coins == $getCreditCoin->coins) {
                        $getCreditCoin->status = '1';
                    }
                    $getCreditCoin->save();
                    $current_debit_coin = 0;
                } else {

                    $getCreditCoin->used_coins += $remain;
                    if ($getCreditCoin->used_coins == $getCreditCoin->coins) {
                        $getCreditCoin->status = '1';
                    }
                    $getCreditCoin->save();
                    $current_debit_coin -= $remain;
                }
            }


            if ($current_debit_coin <= 0) {
                break;
            }
        }
    }
}
