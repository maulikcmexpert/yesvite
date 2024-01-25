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



function isEmailValid($email)
{
    list($user, $domain) = explode('@', $email);
    $mxRecords = [];
    if (getmxrr($domain, $mxRecords)) {
        $mxHost = $mxRecords[array_rand($mxRecords)];
        $timeout = 30; // timeout in seconds
        // $sock = fsockopen($mxHost, 25, $errno, $errstr, $timeout);
        $sock = fsockopen('reject2.heluna.com', 25, $errno, $errstr, 30); // 30 is the timeout in seconds

        if (!$sock) {
            echo "Error: $errstr ($errno)";
        } else {
            echo "Connection established!";
            fclose($sock);
        }

        if ($sock && strpos(get_status($sock), "220") === 0) {
            fputs($sock, "HELO yourdomain.com\r\n");
            if (strpos(get_status($sock), "250") === 0) {
                fputs($sock, "MAIL FROM: <yourname@yourdomain.com>\r\n");
                if (strpos(get_status($sock), "250") === 0) {
                    fputs($sock, "RCPT TO: <{$email}>\r\n");
                    $code = get_status($sock);
                    fputs($sock, "QUIT\r\n");
                    fclose($sock);
                    if (strpos($code, "250") === 0) {
                        return true;
                    }
                }
            }
        }
    }
    return false;
}

function checkIsimageOrVideo($postImage)
{
    $extension  = $postImage->getClientOriginalExtension();
    $mime = $postImage->getClientMimeType();

    if (in_array($extension, ['jpeg', 'jpg', 'png', 'gif']) || strpos($mime, 'image') !== false) {
        return 'image';
    } elseif (strpos($mime, 'video') !== false) {

        return 'video';
    } elseif (str_contains($mime, 'audio')) {
        return 'record';
    }
}

function sendNotification($notificationType, $postData)
{


    //'invite', 'upload_post', 'like_post', 'comment', 'reply', 'poll', 'rsvp'

    $senderData = User::where('id', $postData['sender_id'])->first();
    $notification_message = "";
    $invitedusers = EventInvitedUser::with('event', 'user')->whereHas('user', function ($query) {
        //  $query->where('app_user', '1');
    })->where('event_id', $postData['event_id'])->get();

    if ($notificationType == 'invite') {
        if (count($invitedusers) != 0) {

            foreach ($invitedusers as $value) {
                if ($value->user->app_user == '1') {
                    Notification::where(['user_id' => $value->user_id, 'sender_id' => $postData['sender_id'], 'event_id' => $postData['event_id']])->delete();
                    $notification_message = "You have invited in " . $value->event->event_name;
                    $notification = new Notification;
                    $notification->event_id = $postData['event_id'];
                    $notification->user_id = $value->user_id;
                    $notification->notification_type = $notificationType;
                    $notification->sender_id = $postData['sender_id'];
                    $notification->notification_message = $notification_message;

                    if ($notification->save()) {

                        $deviceData = Device::where('user_id', $value->user_id)->first();
                        if (!empty($deviceData)) {

                            send_notification_FCM($deviceData->device_token, $notification_message);
                        }
                        if ($value->prefer_by == 'email') {
                            $eventData = [
                                'event_name' => $value->event->event_name,
                                'hosted_by' => $value->event->hosted_by,
                                'date' =>  date('l, M. jS', strtotime($value->event->start_date)),
                                'time' => '1PM',
                                'address' => $value->event->event_location_name . ' ' . $value->event->address_1 . ' ' . $value->event->address_2 . ' ' . $value->event->state . ' ' . $value->event->city . ' - ' . $value->event->zip_code,
                            ];

                            $emailsent = Mail::to($value->user->email)->send(new InvitationEmail(array($eventData)));
                            if ($emailsent != null) {

                                $updateinvitation = EventInvitedUser::where(['event_id' => $postData['event_id'], 'user_id' => $value->user_id, 'prefer_by' => 'email'])->first();
                                $updateinvitation->invitation_sent = '1';
                                $updateinvitation->save();
                            }
                        }
                        if ($value->prefer_by == 'phone') {
                        }
                        if ($value->prefer_by == 'both') {

                            $eventData = [
                                'event_name' => $value->event->event_name,
                                'hosted_by' => $value->event->hosted_by,
                                'date' =>  date('l, M. jS', strtotime($value->event->start_date)),
                                'time' => '1PM',
                                'address' => $value->event->event_location_name . ' ' . $value->event->address_1 . ' ' . $value->event->address_2 . ' ' . $value->event->state . ' ' . $value->event->city . ' - ' . $value->event->zip_code,
                            ];

                            $emailsent =  Mail::to($value->user->email)->send(new InvitationEmail(array($eventData)));
                            if ($emailsent) {

                                $updateinvitation = EventInvitedUser::where(['event_id' => $postData['event_id'], 'user_id' => $value->user_id])->first();
                                $updateinvitation->invitation_sent = '1';
                                $updateinvitation->save();
                            }
                        }
                    }
                }
            }
        }
    }

    if ($notificationType == 'upload_post') {
        foreach ($invitedusers as $value) {

            $postControl = PostControl::with('event_posts')->where(['event_id' => $postData['event_id'], 'user_id' => $value->user_id, 'post_control' => 'mute'])->get();
            $postOwneruserId = [];
            foreach ($postControl as $value) {
                $postOwneruserId[] = $value->event_posts->user_id;
            }
            if (in_array($postData['sender_id'], $postOwneruserId)) {
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

                    send_notification_FCM($deviceData->device_token, $notification_message);
                }
            }
        }
    }

    if ($notificationType == 'like_post') {

        $getPostOwnerId = EventPost::where('id', $postData['post_id'])->first();

        $notification_message = $senderData->firstname . ' '  . $senderData->lastname . " liked your post";
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
                send_notification_FCM($deviceData->device_token, $notification_message);
            }
        }
    }
    if ($notificationType == 'comment_post') {

        $getPostOwnerId = EventPost::where('id', $postData['post_id'])->first();

        $notification_message = $senderData->firstname . ' '  . $senderData->lastname . " commented on your post";
        $notification = new Notification;
        $notification->event_id = $postData['event_id'];
        $notification->post_id = $postData['post_id'];
        $notification->comment_id = $postData['comment_id'];
        $notification->user_id =  $getPostOwnerId->user_id;
        $notification->notification_type = $notificationType;
        $notification->sender_id = $postData['sender_id'];
        $notification->notification_message = $notification_message;

        if ($notification->save()) {
            $deviceData = Device::where('user_id', $getPostOwnerId->user_id)->first();
            if (!empty($deviceData)) {
                send_notification_FCM($deviceData->device_token, $notification_message);
            }
        }
    }
    if ($notificationType == 'reply_on_comment_post') {

        $getPostOwnerId = EventPost::where('id', $postData['post_id'])->first();

        $notification_message = $senderData->firstname . ' '  . $senderData->lastname . " replied to your comment";
        $notification = new Notification;
        $notification->event_id = $postData['event_id'];
        $notification->post_id = $postData['post_id'];
        $notification->comment_id = $postData['comment_id'];
        $notification->user_id =  $getPostOwnerId->user_id;
        $notification->notification_type = $notificationType;
        $notification->sender_id = $postData['sender_id'];
        $notification->notification_message = $notification_message;

        if ($notification->save()) {
            $deviceData = Device::where('user_id', $getPostOwnerId->user_id)->first();
            if (!empty($deviceData)) {
                send_notification_FCM($deviceData->device_token, $notification_message);
            }
        }
    }
    if ($notificationType == 'sent_rsvp') {

        $getPostOwnerId = Event::where('id', $postData['event_id'])->first();

        if ($postData['rsvp_status'] == '1') {
            $notification_message = $senderData->firstname . ' '  . $senderData->lastname . " RSVP'd Yes for" . $getPostOwnerId->event_name;
        } elseif ($postData['rsvp_status'] == '0') {
            $notification_message = $senderData->firstname . ' '  . $senderData->lastname . " RSVP'd No for" . $getPostOwnerId->event_name;
        }
        $notification = new Notification;
        $notification->event_id = $postData['event_id'];
        $notification->user_id =  $getPostOwnerId->user_id;
        $notification->notification_type = $notificationType;
        $notification->sender_id = $postData['sender_id'];
        $notification->notification_message = $notification_message;

        if ($notification->save()) {
            $deviceData = Device::where('user_id', $getPostOwnerId->user_id)->first();
            if (!empty($deviceData)) {
                send_notification_FCM($deviceData->device_token, $notification_message);
            }
        }
    }
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

function send_notification_FCM($deviceToken, $message)
{
    $SERVER_API_KEY = 'key=AAAAP6m84T0:APA91bHeuAm2ME_EmPEsOjMe2FatmHn2QU98ADg4Y5UxNMmXGg4MDD4OJQQhvsixNfhV1g2BWbgOCQGEf9_c3ngB8qH_N3MEMsgD7uuAQAq0_IO2GGPqCxjJPuwAME9MVX9ZvWgYbcPh';
    $URL = 'https://fcm.googleapis.com/fcm/send';
    $post_data = '{
            "to" : "' . $deviceToken . '",
            "data" : {
              "body" : "",
              "title" : "Yesvite",
              "type" : "Invite",
              "message" : "' . $message . '",
            },
            "notification" : {
                 "body" : "' . $message . '",
                 "title" : "Yesvite",
                  "type" : "Invite",
                 "message" : "' . $message . '",
                "icon" : "new",
                "sound" : "default"
                },

          }';
    // print_r($post_data);die;

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
