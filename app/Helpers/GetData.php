<?php

use App\Models\EventAddContact;
use App\Models\EventImage;
use App\Models\EventInvitedUser;
use App\Models\EventPostComment;
use App\Models\EventPostCommentReaction;
use App\Models\EventPostImage;
use App\Models\EventPostReaction;
use App\Models\EventPostPhotoReaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\UserEventPollData;
use App\Models\EventType;
use App\Models\User;
use App\Models\Event;
use App\Models\EventPostPoll;
use App\Models\Group;
use App\Models\EventDesignCategory;
use App\Models\EventPostPhotoComment;
use App\Models\EventPhotoCommentReaction;
use App\Models\Social_link;
use App\Models\EventPost;
use App\Models\Notification;
use App\Models\UserPotluckItem;
use App\Models\UserPrivacyPolicy;
use Carbon\Carbon;
use Kreait\Laravel\Firebase\Facades\Firebase;

function getSocialLink(){
    return Social_link::first();
}

function getTotalUnreadMessageCount()
{
    try {
        $userId = auth()->id();
        if ($userId == '' && $userId <= 0) {
            return 0;
        }
        $firebase = Firebase::database();
        $overviewRef = $firebase->getReference('overview/' . $userId);
        $snapshot = $overviewRef->getValue();
        $totalUnreadCount = 0;

        if ($snapshot) {
            foreach ($snapshot as $conversationId => $conversation) {
                if (isset($conversation['unReadCount'])) {
                    $totalUnreadCount += $conversation['unReadCount'];
                }
            }
        }

        return $totalUnreadCount;
    } catch (\Throwable $th) {
        return 0;
    }
}

// function getNotificationList(){
//     $user = Auth::guard('web')->user();
//     // $rawData = $request->getContent();
//     // $input = json_decode($rawData, true);
//     // if ($input == null) {
//     //     return response()->json(['status' => 0, 'message' => "Json invalid"]);
//     // }
//     $final_data=[];
//     $page = '1';
//     $pages = ($page != "") ? $page : 1;
//     $notificationData = Notification::query();
//     $notificationData->with(['user', 'event', 'event.event_settings', 'sender_user', 'post' => function ($query) {
//         $query->with(['post_image', 'event_post_poll'])
//             ->withcount(['event_post_reaction', 'event_post_comment' => function ($query) {
//                 $query->where('parent_comment_id', NULL);
//             }]);
//     }])->orderBy('id', 'DESC')->where(['user_id' => $user->id])->get();
//     // ->where('notification_type', '!=', 'upload_post')->where('notification_type', '!=', 'photos')->where('notification_type', '!=', 'invite')
//     // if (isset($input['filters']) && !empty($input['filters']) && !in_array('all', $input['filters'])) {

//     //     $selectedEvents = $input['filters']['events'];
//     //     $notificationTypes = $input['filters']['notificationTypes'];
//     //     $activityTypes = $input['filters']['activityTypes'];

//     //     $notificationData->where(function ($query) use ($selectedEvents, $notificationTypes, $activityTypes) {
//     //         // Add conditions based on selected events
//     //         if (!empty($selectedEvents)) {
//     //             $query->whereIn('event_id', $selectedEvents);
//     //         }

//     //         // Add conditions based on notification types (read, unread)
//     //         if (!empty($notificationTypes) && in_array('read', $notificationTypes)) {
//     //             $query->orWhere('read', "1");
//     //         }
//     //         if (!empty($notificationTypes) && in_array('unread', $notificationTypes)) {
//     //             $query->orWhere('read', "0");
//     //         }

//     //         // Add conditions based on activity types
//     //         if (!empty($activityTypes)) {

//     //             $query->whereIn('notification_type', $activityTypes);
//     //         }
//     //     });
//     // }
//     $notificationDatacount = $notificationData->count();
//     $total_page = ceil($notificationDatacount / 10);
//     $result = $notificationData->limit(3)->get();

//     $notificationInfo = [];
//         foreach ($result as $values) {
//             if ($values->user_id == $user->id) {
//                 $notificationDetail['event_name'] = $values->event->event_name;
//                 $images = EventImage::where('event_id', $values->event->id)->first();

//                 $notificationDetail['event_image'] = ($images != null) ? asset('storage/event_images/' . $images->image) : "";
//                 $formattedDate = Carbon::parse($values->event->created_at)->format('F d, Y');
//                 $notificationDetail['event_date'] = $formattedDate;
//                 $notificationDetail['notification_id'] = $values->id;
//                 $notificationDetail['notification_type'] = $values->notification_type;
//                 $notificationDetail['user_id'] = $values->sender_id;
//                 $notificationDetail['profile'] = (!empty($values->sender_user->profile) || $values->sender_user->profile != null) ? asset('storage/profile/' . $values->sender_user->profile) : "";
//                 $notificationDetail['email'] = $values->sender_user->email;
//                 $notificationDetail['first_name'] = $values->sender_user->firstname;
//                 $notificationDetail['last_name'] = ($values->sender_user->lastname != null) ? $values->sender_user->lastname : "";
//                 $notificationDetail['event_id'] = ($values->event_id != null) ? $values->event_id : 0;
//                 $notificationDetail['post_id'] = ($values->post_id != null) ? $values->post_id : 0;
//                 $notificationDetail['comment_id'] = ($values->comment_id != null) ? $values->comment_id : 0;
//                 $comment_reply_id = EventPostComment::where('parent_comment_id', $values->comment_id)->orderBy('id', 'DESC')->select('id')->first();
//                 $notificationDetail['reply_comment_id'] = 0;
//                 $postCommentDetail =  EventPostComment::where(['id' => $values->comment_id])->first();
//                 if (isset($postCommentDetail->main_parent_comment_id) && $postCommentDetail->main_parent_comment_id != null) {
//                     $commentText = EventPostComment::where('id', $postCommentDetail->main_parent_comment_id)->first();
//                     $notificationDetail['comment'] = ($commentText != null) ? $commentText->comment_text : "";
//                     $notificationDetail['reply_comment_id'] = $values->comment_id;
//                     $notificationDetail['comment_id'] = isset($postCommentDetail->main_parent_comment_id) ? $postCommentDetail->main_parent_comment_id : 0;
//                     $notificationDetail['comment_reply'] = ($postCommentDetail != null) ? $postCommentDetail->comment_text : "";
//                 } else {
//                     $notificationDetail['comment'] = ($postCommentDetail != null) ? $postCommentDetail->comment_text : "";
//                     $notificationDetail['comment_reply'] = "";
//                 }
//                 $notificationDetail['video'] = ($postCommentDetail != null && $postCommentDetail->video != null) ? asset('storage/comment_video' . $postCommentDetail->video) : "";
//                 $checkIsSefRect = EventPostCommentReaction::where(['user_id' => $values->user_id, 'event_post_comment_id' => $values->comment_id])->first();
//                 $notificationDetail['is_self_reaction'] = ($checkIsSefRect != null) ? 1 : 0;
//                 $notificationDetail['message_to_host'] = "";
//                 $notificationDetail['rsvp_attempt'] = "";
//                 $notificationDetail['is_co_host'] = "";
//                 $notificationDetail['accept_as_co_host'] = "";
//                 $notificationDetail['from_addr'] = ($values->from_addr != null || $values->from_addr != "") ? $values->from_addr : "";
//                 $notificationDetail['to_addr'] = ($values->to_addr != null || $values->to_addr != "") ? $values->to_addr : "";
//                 $notificationDetail['from_time'] = ($values->from_time != null || $values->from_time != "") ? $values->from_time : "";
//                 $notificationDetail['to_time'] = ($values->to_time != null || $values->to_time != "") ? $values->to_time : "";
//                 $notificationDetail['old_start_end_date'] = ($values->old_start_end_date != null || $values->old_start_end_date != "") ? $values->old_start_end_date : "";
//                 $notificationDetail['new_start_end_date'] = ($values->new_start_end_date != null || $values->new_start_end_date != "") ? $values->new_start_end_date : "";
//                 $notificationDetail['event_wall'] = $values->event->event_settings->event_wall;
//                 $notificationDetail['guest_list_visible_to_guests'] = $values->event->event_settings->guest_list_visible_to_guests;
//                 $notificationDetail['event_potluck'] = $values->event->event_settings->podluck;
//                 $notificationDetail['guest_pending_count'] = getGuestRsvpPendingCount($values->event->id);
//                 $notificationDetail['is_event_owner'] = ($values->event->user_id == $user->id) ? 1 : 0;
//                 if ($values->notification_type == 'invite') {
//                     $checkIsCoHost =  EventInvitedUser::where(['user_id' => $values->user_id, 'event_id' => $values->event_id])->first();
//                     if ($checkIsCoHost != null) {
//                         $notificationDetail['is_co_host'] = $checkIsCoHost->is_co_host;
//                         $notificationDetail['accept_as_co_host'] = $checkIsCoHost->accept_as_co_host;
//                     }
//                 }
//                 $notificationDetail['potluck_item'] = "";
//                 $notificationDetail['count'] = "";
//                 if ($values->notification_type == 'potluck_bring') {
//                     $getUserPotluckItem = UserPotluckItem::with('event_potluck_category_items')->where('id', $values->user_potluck_item_id)->first();
//                     $notificationDetail['potluck_item'] = $getUserPotluckItem->event_potluck_category_items->description;
//                     $notificationDetail['count'] = $values->user_potluck_item_count;
//                 }
//                 if ($values->notification_type == 'sent_rsvp') {
//                     $notificationDetail['message_to_host'] = ($values->rsvp_message != null && $values->rsvp_message != "") ? $values->rsvp_message : "";
//                     $notificationDetail['rsvp_attempt'] = $values->rsvp_attempt;
//                     $notificationDetail['video'] = ($values->rsvp_video != null && $values->rsvp_video != null) ? asset('storage/rsvp_video/' . $values->rsvp_video) : "";
//                 }
//                 if (isset($values->post->post_type) && $values->post->post_type == '1') {
//                     if (isset($values->post->post_image[0]->type) && $values->post->post_image[0]->type == 'video') {
//                         $notificationDetail['video'] = asset('storage/post_image/' . $values->post->post_image[0]->post_image);
//                         $notificationDetail['media_type'] = $values->post->post_image[0]->type;
//                     }
//                 }
//                 // if ($values->notification_type == 'reply_on_comment_post') {
//                 //     $comment_reply_id = EventPostComment::where('parent_comment_id', $values->comment_id)->orderBy('id', 'DESC')->select('id')->first();
//                 //     $notificationDetail['reply_comment_id'] = (isset($comment_reply_id->id) && $comment_reply_id->id != null) ? $comment_reply_id->id : 0;
//                 // }
//                 $notificationDetail['total_likes'] = (!empty($values->post->event_post_reaction_count)) ? $values->post->event_post_reaction_count : 0;
//                 $notificationDetail['total_comments'] = (!empty($values->post->event_post_comment_count)) ? $values->post->event_post_comment_count : 0;
//                 $postreplyCommentDetail =  EventPostComment::where(['user_id' => $values->sender_id, 'parent_comment_id' => $values->comment_id])->first();
//                 // $notificationDetail['comment_reply'] = ($values->notification_type == 'reply_on_comment_post' && $postreplyCommentDetail != null) ? $postreplyCommentDetail->comment_text : "";
//                 $notificationDetail['post_image'] = "";
//                 $notificationDetail['media_type'] = "";
//                 $notificationDetail['is_post_by_host'] = 0;
//                 if (isset($values->post->user_id) && isset($values->event->user_id)) {
//                     if ($values->post->user_id == $values->event->user_id) {
//                         $notificationDetail['is_post_by_host'] = 1;
//                     }
//                 }
//                 if (isset($values->post->post_type) && $values->post->post_type == '1' && isset($values->post->post_image[0]->type)) {
//                     $notificationDetail['post_image'] = asset('storage/post_image/' . $values->post->post_image[0]->post_image);
//                     if (isset($values->post->post_image[0]->type) &&  $values->post->post_image[0]->type == 'image') {
//                         $notificationDetail['media_type'] = 'photo';
//                     } elseif (isset($values->post->post_image[0]->type) && $values->post->post_image[0]->type == 'video') {
//                         $notificationDetail['media_type'] = (isset($values->post->post_image[0]->type) && $values->post->post_image[0]->type != '') ? $values->post->post_image[0]->type : '';
//                     }
//                 }
//                 $notificationDetail['post_type'] = "";
//                 if (isset($values->post->post_type)) {
//                     $notificationDetail['post_type'] = $values->post->post_type;
//                 }
//                 $notificationDetail['post_message'] = (!empty($values->post->post_message)) ? $values->post->post_message : "";
//                 $notificationDetail['notification_message'] = $values->notification_message;
//                 $notificationDetail['read'] = $values->read;
//                 $notificationDetail['post_time'] = setposttTime($values->created_at);
//                 $notificationDetail['created_at'] = $values->created_at;
//                 $checkrsvp =  EventInvitedUser::where(['user_id' => $values->user_id, 'event_id' => $values->event_id])->first();
//                 if (!empty($checkrsvp)) {
//                     $notificationDetail['rsvp_status'] =  (isset($checkrsvp->rsvp_status) || $checkrsvp->rsvp_status != null) ? $checkrsvp->rsvp_status : "";
//                 } else {
//                     $notificationDetail['rsvp_status'] = '';
//                 }
//                 $rsvpData['rsvpd_status'] = (!empty($values->rsvp_status) || $values->rsvp_status != null) ? $values->rsvp_status : "";
//                 $rsvpData['Adults'] = (!empty($values->adults) || $values->adults != null) ? $values->adults : 0;
//                 $rsvpData['Kids']  =  (!empty($values->kids) || $values->kids != null) ? $values->kids : 0;

//                 $notificationDetail['notification_id'] = $values->id;
//                 $notificationDetail['notification_type'] = $values->notification_type;
//                 $notificationDetail['user_id'] = $values->user_id;
//                 $notificationDetail['sender_id'] = $values->sender_id;
//                 $notificationDetail['notification_message'] = $values->notification_message;
//                 $notificationDetail['read'] = $values->read;
//                 $notificationDetail['rsvp_detail'] = $rsvpData;
//                 $totalEvent =  Event::where('user_id', $values->sender_user->id)->count();
//                 $totalEventPhotos =  EventPost::where(['user_id' => $values->sender_user->id, 'post_type' => '1'])->count();
//                 $comments =  EventPostComment::where('user_id', $values->sender_user->id)->count();
//                 $notificationDetail['user_profile'] = [
//                     'id' => $values->sender_user->id,
//                     'profile' => empty($values->sender_user->profile) ? "" : asset('storage/profile/' . $values->sender_user->profile),
//                     'bg_profile' => empty($values->sender_user->bg_profile) ? "" : asset('storage/bg_profile/' . $values->sender_user->bg_profile),
//                     'gender' => ($values->sender_user->gender != NULL) ? $values->sender_user->gender : "",
//                     'username' => $values->sender_user->firstname . ' ' . $values->sender_user->lastname,
//                     'location' => ($values->sender_user->city != NULL) ? $values->sender_user->city : "",
//                     'about_me' => ($values->sender_user->about_me != NULL) ? $values->sender_user->about_me : "",
//                     'created_at' => empty($values->sender_user->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($values->sender_user->created_at))),
//                     'total_events' => $totalEvent,
//                     'visible' => $values->sender_user->visible,
//                     'comments' => $comments,
//                 ];
//                 $notificationInfo[$values->event_id] = $notificationDetail;
//             }


//                 foreach($notificationInfo as $notify_data){
//                     if ($values->event_id === $notify_data['event_id']) {
//                         $final_data[$values->event->event_name][] = $notify_data; 
//                     }
//                 }
            
//         }
//     $unreadCount = Notification::where(['user_id' => $user->id, 'read' => '0'])->count();
    
//     return $final_data;
// }

function getNotificationList($filter = []){
    $user = Auth::guard('web')->user();
        // $rawData = $request->getContent();
        // $input = json_decode($rawData, true);
        // if ($input == null) {
        //     return response()->json(['status' => 0, 'message' => "Json invalid"]);
        // }
        $page = '1';
        $final_data=[];
        $pages = ($page != "") ? $page : 1;
        $notificationData = Notification::query();
        $notificationData->with(['user', 'event', 'event.event_settings', 'sender_user', 'post' => function ($query) {
            $query->with(['post_image', 'event_post_poll'])
                ->withcount(['event_post_reaction', 'event_post_comment' => function ($query) {
                    $query->where('parent_comment_id', NULL);
                }]);
        }])->orderBy('id', 'DESC')->where(['user_id' => $user->id]);

        if (!empty($filter) && !in_array('all', $filter)) {

            $selectedEvents = $filter['selectedEvents'];
            $notificationTypes = $filter['notificationTypes'];
            $activityTypes = $filter['activityTypes'];

            $notificationData->where(function ($query) use ($selectedEvents, $notificationTypes, $activityTypes) {
                if (!empty($selectedEvents)) {
                    $query->whereIn('event_id', $selectedEvents);
                }
                if (!empty($notificationTypes) && in_array('read', $notificationTypes)) {
                    $query->orWhere('read', "1");
                }
                if (!empty($notificationTypes) && in_array('unread', $notificationTypes)) {
                    $query->orWhere('read', "0");
                }
                if (!empty($activityTypes)) {
                    $query->whereIn('notification_type', $activityTypes);
                }
            });
        }

        $notificationDatacount = $notificationData->count();
        $total_page = ceil($notificationDatacount / 10);
        // $result = $notificationData->get();
        $result = $notificationData->get();
        $notificationInfo = [];
            foreach ($result as $values) {
                if ($values->user_id == $user->id) {
                    $notificationDetail['event_name'] = $values->event->event_name;
                    $images = EventImage::where('event_id', $values->event->id)->first();

                    $notificationDetail['event_image'] = ($images != null) ? asset('storage/event_images/' . $images->image) : "";
                    // $eventDetail['event_images'] = ($images != null) ? asset('storage/event_images/' . $images->image) : "";

                    $notificationDetail['notification_id'] = $values->id;
                    $notificationDetail['notification_type'] = $values->notification_type;
                    $notificationDetail['user_id'] = $values->sender_id;
                    $formattedDate = Carbon::parse($values->event->created_at)->format('F d, Y');
                                    $notificationDetail['event_date'] = $formattedDate;
                    $notificationDetail['profile'] = (!empty($values->sender_user->profile) || $values->sender_user->profile != null) ? asset('storage/profile/' . $values->sender_user->profile) : "";
                    $notificationDetail['email'] = $values->sender_user->email;
                    $notificationDetail['first_name'] = $values->sender_user->firstname;
                    $notificationDetail['last_name'] = ($values->sender_user->lastname != null) ? $values->sender_user->lastname : "";
                    $notificationDetail['event_id'] = ($values->event_id != null) ? $values->event_id : 0;
                    $notificationDetail['post_id'] = ($values->post_id != null) ? $values->post_id : 0;
                    $notificationDetail['comment_id'] = ($values->comment_id != null) ? $values->comment_id : 0;
                    $comment_reply_id = EventPostComment::where('parent_comment_id', $values->comment_id)->orderBy('id', 'DESC')->select('id')->first();
                    $notificationDetail['reply_comment_id'] = 0;
                    $postCommentDetail =  EventPostComment::where(['id' => $values->comment_id])->first();
                    if (isset($postCommentDetail->main_parent_comment_id) && $postCommentDetail->main_parent_comment_id != null) {
                        $commentText = EventPostComment::where('id', $postCommentDetail->main_parent_comment_id)->first();
                        $notificationDetail['comment'] = ($commentText != null) ? $commentText->comment_text : "";
                        $notificationDetail['reply_comment_id'] = $values->comment_id;
                        $notificationDetail['comment_id'] = isset($postCommentDetail->main_parent_comment_id) ? $postCommentDetail->main_parent_comment_id : 0;
                        $notificationDetail['comment_reply'] = ($postCommentDetail != null) ? $postCommentDetail->comment_text : "";
                    } else {
                        $notificationDetail['comment'] = ($postCommentDetail != null) ? $postCommentDetail->comment_text : "";
                        $notificationDetail['comment_reply'] = "";
                    }
                    $notificationDetail['video'] = ($postCommentDetail != null && $postCommentDetail->video != null) ? asset('storage/comment_video' . $postCommentDetail->video) : "";
                    $checkIsSefRect = EventPostCommentReaction::where(['user_id' => $values->user_id, 'event_post_comment_id' => $values->comment_id])->first();
                    $notificationDetail['is_self_reaction'] = ($checkIsSefRect != null) ? 1 : 0;
                    $notificationDetail['message_to_host'] = "";
                    $notificationDetail['rsvp_attempt'] = "";
                    $notificationDetail['is_co_host'] = "";
                    $notificationDetail['accept_as_co_host'] = "";
                    $notificationDetail['from_addr'] = ($values->from_addr != null || $values->from_addr != "") ? $values->from_addr : "";
                    $notificationDetail['to_addr'] = ($values->to_addr != null || $values->to_addr != "") ? $values->to_addr : "";
                    $notificationDetail['from_time'] = ($values->from_time != null || $values->from_time != "") ? $values->from_time : "";
                    $notificationDetail['to_time'] = ($values->to_time != null || $values->to_time != "") ? $values->to_time : "";
                    $notificationDetail['old_start_end_date'] = ($values->old_start_end_date != null || $values->old_start_end_date != "") ? $values->old_start_end_date : "";
                    $notificationDetail['new_start_end_date'] = ($values->new_start_end_date != null || $values->new_start_end_date != "") ? $values->new_start_end_date : "";
                    $notificationDetail['event_wall'] = $values->event->event_settings->event_wall;
                    $notificationDetail['guest_list_visible_to_guests'] = $values->event->event_settings->guest_list_visible_to_guests;
                    $notificationDetail['event_potluck'] = $values->event->event_settings->podluck;
                    $notificationDetail['guest_pending_count'] = getGuestRsvpPendingCount($values->event->id);
                    $notificationDetail['is_event_owner'] = ($values->event->user_id == $user->id) ? 1 : 0;
                    if ($values->notification_type == 'invite') {
                        $checkIsCoHost =  EventInvitedUser::where(['user_id' => $values->user_id, 'event_id' => $values->event_id])->first();
                        if ($checkIsCoHost != null) {
                            $notificationDetail['is_co_host'] = $checkIsCoHost->is_co_host;
                            $notificationDetail['accept_as_co_host'] = $checkIsCoHost->accept_as_co_host;
                        }
                    }
                    $notificationDetail['potluck_item'] = "";
                    $notificationDetail['count'] = "";
                    if ($values->notification_type == 'potluck_bring') {
                        $getUserPotluckItem = UserPotluckItem::with('event_potluck_category_items')->where('id', $values->user_potluck_item_id)->first();
                        $notificationDetail['potluck_item'] = $getUserPotluckItem->event_potluck_category_items->description;
                        $notificationDetail['count'] = $values->user_potluck_item_count;
                    }
                    if ($values->notification_type == 'sent_rsvp') {
                        $notificationDetail['message_to_host'] = ($values->rsvp_message != null && $values->rsvp_message != "") ? $values->rsvp_message : "";
                        $notificationDetail['rsvp_attempt'] = $values->rsvp_attempt;
                        $notificationDetail['video'] = ($values->rsvp_video != null && $values->rsvp_video != null) ? asset('storage/rsvp_video/' . $values->rsvp_video) : "";
                    }
                    if (isset($values->post->post_type) && $values->post->post_type == '1') {
                        if (isset($values->post->post_image[0]->type) && $values->post->post_image[0]->type == 'video') {
                            $notificationDetail['video'] = asset('storage/post_image/' . $values->post->post_image[0]->post_image);
                            $notificationDetail['media_type'] = $values->post->post_image[0]->type;
                        }
                    }
                    // if ($values->notification_type == 'reply_on_comment_post') {
                    //     $comment_reply_id = EventPostComment::where('parent_comment_id', $values->comment_id)->orderBy('id', 'DESC')->select('id')->first();
                    //     $notificationDetail['reply_comment_id'] = (isset($comment_reply_id->id) && $comment_reply_id->id != null) ? $comment_reply_id->id : 0;
                    // }
                    $notificationDetail['total_likes'] = (!empty($values->post->event_post_reaction_count)) ? $values->post->event_post_reaction_count : 0;
                    $notificationDetail['total_comments'] = (!empty($values->post->event_post_comment_count)) ? $values->post->event_post_comment_count : 0;
                    $postreplyCommentDetail =  EventPostComment::where(['user_id' => $values->sender_id, 'parent_comment_id' => $values->comment_id])->first();
                    // $notificationDetail['comment_reply'] = ($values->notification_type == 'reply_on_comment_post' && $postreplyCommentDetail != null) ? $postreplyCommentDetail->comment_text : "";
                    $notificationDetail['post_image'] = "";
                    $notificationDetail['media_type'] = "";
                    $notificationDetail['is_post_by_host'] = 0;
                    if (isset($values->post->user_id) && isset($values->event->user_id)) {
                        if ($values->post->user_id == $values->event->user_id) {
                            $notificationDetail['is_post_by_host'] = 1;
                        }
                    }
                    if (isset($values->post->post_type) && $values->post->post_type == '1' && isset($values->post->post_image[0]->type)) {
                        $notificationDetail['post_image'] = asset('storage/post_image/' . $values->post->post_image[0]->post_image);
                        if (isset($values->post->post_image[0]->type) &&  $values->post->post_image[0]->type == 'image') {
                            $notificationDetail['media_type'] = 'photo';
                        } elseif (isset($values->post->post_image[0]->type) && $values->post->post_image[0]->type == 'video') {
                            $notificationDetail['media_type'] = (isset($values->post->post_image[0]->type) && $values->post->post_image[0]->type != '') ? $values->post->post_image[0]->type : '';
                        }
                    }
                    $notificationDetail['post_type'] = "";
                    if (isset($values->post->post_type)) {
                        $notificationDetail['post_type'] = $values->post->post_type;
                    }
                    $notificationDetail['post_message'] = (!empty($values->post->post_message)) ? $values->post->post_message : "";
                    $notificationDetail['notification_message'] = $values->notification_message;
                    $notificationDetail['read'] = $values->read;
                    $notificationDetail['post_time'] = setposttTime($values->created_at);
                    $notificationDetail['created_at'] = $values->created_at;
                    $checkrsvp =  EventInvitedUser::where(['user_id' => $values->user_id, 'event_id' => $values->event_id])->first();
                    if (!empty($checkrsvp)) {
                        $notificationDetail['rsvp_status'] =  (isset($checkrsvp->rsvp_status) || $checkrsvp->rsvp_status != null) ? $checkrsvp->rsvp_status : "";
                    } else {
                        $notificationDetail['rsvp_status'] = '';
                    }
                    $rsvpData['rsvpd_status'] = (!empty($values->rsvp_status) || $values->rsvp_status != null) ? $values->rsvp_status : "";
                    $rsvpData['Adults'] = (!empty($values->adults) || $values->adults != null) ? $values->adults : 0;
                    $rsvpData['Kids']  =  (!empty($values->kids) || $values->kids != null) ? $values->kids : 0;

                    $notificationDetail['notification_id'] = $values->id;
                    $notificationDetail['notification_type'] = $values->notification_type;
                    $notificationDetail['user_id'] = $values->user_id;
                    $notificationDetail['sender_id'] = $values->sender_id;
                    $notificationDetail['notification_message'] = $values->notification_message;
                    $notificationDetail['read'] = $values->read;
                    $notificationDetail['rsvp_detail'] = $rsvpData;
                    $totalEvent =  Event::where('user_id', $values->sender_user->id)->count();
                    $totalEventPhotos =  EventPost::where(['user_id' => $values->sender_user->id, 'post_type' => '1'])->count();
                    $comments =  EventPostComment::where('user_id', $values->sender_user->id)->count();
                    $notificationDetail['user_profile'] = [
                        'id' => $values->sender_user->id,
                        'profile' => empty($values->sender_user->profile) ? "" : asset('storage/profile/' . $values->sender_user->profile),
                        'bg_profile' => empty($values->sender_user->bg_profile) ? "" : asset('storage/bg_profile/' . $values->sender_user->bg_profile),
                        'gender' => ($values->sender_user->gender != NULL) ? $values->sender_user->gender : "",
                        'username' => $values->sender_user->firstname . ' ' . $values->sender_user->lastname,
                        'location' => ($values->sender_user->city != NULL) ? $values->sender_user->city : "",
                        'about_me' => ($values->sender_user->about_me != NULL) ? $values->sender_user->about_me : "",
                        'created_at' => empty($values->sender_user->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($values->sender_user->created_at))),
                        'total_events' => $totalEvent,
                        'visible' => $values->sender_user->visible,
                        'comments' => $comments,
                    ];
                    $notificationInfo[$values->event_id] = $notificationDetail;
                }

                $i=0;
                foreach($notificationInfo as $notify_data){
                    $i++;
                    // if ($i <= 30) {
                    //     continue; // Skip until $i reaches 5
                    // }
                    
                    if ($values->event_id === $notify_data['event_id']) {
                        $final_data[$values->event->event_name][] = $notify_data; 
                    }
                }
            }
        $unreadCount = Notification::where(['user_id' => $user->id, 'read' => '0'])->count();
        // dd($notificationInfo);
        return $final_data;
}
function setposttTime($dateTime)
{
    $commentDateTime = $dateTime; 
    $commentTime = Carbon::parse($commentDateTime);
    $timeAgo = $commentTime->diffForHumans();
    return $timeAgo;
}
function getUser($id)
{
    return User::where('id', $id)->first();
}
function getEventType()
{
    //     $result = EventDesignCategory::all();
    //     $eventType = [];
    //     foreach ($result as $value) {
    //         $eventData['id'] = $value->id;
    //         $eventData['event_type'] = $value->category_name;
    //         $eventType[] = $eventData;
    //     }
    //     return  $eventType;
    return  EventType::all();
}
function getGuestRsvpPendingCount($eventId)
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
        where(['event_id' => $eventId,'rsvp_status' => '1', 'rsvp_d' => '1'])->sum('kids');
    // $eventDetail['guest_pending_count'] = getGuestRsvpPendingCount($value->id, 1);
    return $adults + $kids;
    
    // return  EventInvitedUser::whereHas('user', function ($query) {

    //     $query->where('app_user', '1');
    // })->where(['event_id' => $eventId, 'rsvp_status' => '1'])->count();
}
function upcomingEventsCount($userId)
{
    $usercreatedList = Event::with(['user', 'event_settings', 'event_schedule'])->where('start_date', '>=', date('Y-m-d'))

        ->where('user_id', $userId)
        ->where('is_draft_save', '0')
        ->orderBy('start_date', 'ASC')

        ->get();

    $invitedEvents = EventInvitedUser::whereHas('user', function ($query) {

        $query->where('app_user', '1');
    })->where('user_id', $userId)->get()->pluck('event_id');



    $invitedEventsList = Event::with(['event_image', 'user', 'event_settings', 'event_schedule'])

        ->whereIn('id', $invitedEvents)->where('start_date', '>=', date('Y-m-d'))
        ->where('is_draft_save', '0')
        ->orderBy('start_date', 'ASC')
        ->get();

    $allEvents = $usercreatedList->merge($invitedEventsList)->sortBy('start_date');

    return count($allEvents);
}
function draftEventsCount(){
    $user = Auth::guard('web')->user();
    $draftEvents = Event::where(['user_id' => $user->id, 'is_draft_save' => '1'])->count();
    return $draftEvents;
}
function pendingRsvpCount($userId)
{
    $total_need_rsvp_event_count = EventInvitedUser::whereHas('event', function ($query) {
        $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'));
    })->where(['user_id' => $userId, 'rsvp_status' => NULL,'is_co_host'=>'0'])->count();
    $PendingRsvpEventId = "";
    if ($total_need_rsvp_event_count == 1) {
        $res = EventInvitedUser::select('event_id')->whereHas('event', function ($query) {
            $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'));
        })->where(['user_id' => $userId, 'rsvp_status' => NULL,'is_co_host'=>'0'])->first();
        $PendingRsvpEventId = $res->event_id;
    }
    return compact('total_need_rsvp_event_count', 'PendingRsvpEventId');
}
function hostingCount($userId)
{

    // $totalHosting = Event::where(['is_draft_save' => '0', 'user_id' => $userId])->where('start_date', '>=', date('Y-m-d'))->count();
    $totalHosting = Event::where(['is_draft_save' => '0', 'user_id' => $userId])->count();
    return $totalHosting;
}
function profileHostingCount($userId)
{

    $totalHosting = Event::where(['is_draft_save' => '0', 'user_id' => $userId])->where('start_date', '>=', date('Y-m-d'))->count();
    // $totalHosting = Event::where(['is_draft_save' => '0', 'user_id' => $userId])->count();
    return $totalHosting;
}
function hostingCountCurrentMonth($userId)
{
    $totalHosting = Event::where(['is_draft_save' => '0', 'user_id' => $userId])->where('start_date', '>=', date('Y-m-d'))
    ->whereYear('start_date', date('Y'))   
    ->whereMonth('start_date', date('m'))
    ->count();
    return $totalHosting;
}
function invitedToCount($userId)
{
    $totalInvited = EventInvitedUser::whereHas('event', function ($query) {
        // $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'));
        $query->where('is_draft_save', '0');
    })->where('user_id', $userId)->count();
    return $totalInvited;
}
function profileInvitedToCount($userId)
{
    $totalInvited = EventInvitedUser::whereHas('event', function ($query) {
        $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'));
    })->where(['user_id'=>$userId,'is_co_host'=>'0'])->count();
    return $totalInvited;
}
function invitedToCountCurrentMonth($userId)
{
    $totalInvited = EventInvitedUser::whereHas('event', function ($query) {
        $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'))
        ->whereYear('start_date', date('Y'))   
        ->whereMonth('start_date', date('m'));
    })->where('user_id', $userId)
  
    ->count();
    return $totalInvited;
}
function getParentCommentUserData($parent_comment_id)
{

    return EventPostComment::with('user')->where('id', $parent_comment_id)->first();
}
function getPhotoParentCommentUserData($parent_comment_id)
{
    return EventPostPhotoComment::with('user')->where('id', $parent_comment_id)->first();
}
function getGroupList($id)
{
    $groupList = Group::withCount('groupMembers')
        ->orderByDesc('group_members_count')
        ->where('user_id', $id)
        ->take(2)
        ->get();


    $groupListArr = [];

    foreach ($groupList as $value) {
        $group['id'] = $value->id;
        $group['name'] = $value->name;
        $group['member_count'] = $value->group_members_count;
        $groupListArr[] = $group;
    }

    return $groupListArr;
}
function getYesviteContactList($id)
{
    $yesviteRegisteredUser = User::select('id', 'firstname', 'profile', 'lastname', 'email', 'country_code', 'phone_number', 'app_user', 'prefer_by', 'email_verified_at', 'parent_user_phone_contact', 'visible', 'message_privacy')->where('id', '!=', $id)->where(['is_user_phone_contact' => '0'])->orderBy('firstname')
        ->get();
    $yesviteUser = [];
    foreach ($yesviteRegisteredUser as $user) {

        // if ($user->parent_user_phone_contact != $id && $user->app_user == '0') {
        //     echo  $user->id;
        //     continue;
        // }
        if ($user->email_verified_at == NULL && $user->app_user == '1') {
            continue;
        }
        $yesviteUserDetail['id'] = $user->id;
        $yesviteUserDetail['profile'] = empty($user->profile) ? "" : asset('public/storage/profile/' . $user->profile);
        $yesviteUserDetail['first_name'] = (!empty($user->firstname) || $user->firstname != Null) ? $user->firstname : "";;
        $yesviteUserDetail['last_name'] = (!empty($user->lastname) || $user->lastname != Null) ? $user->lastname : "";
        $yesviteUserDetail['email'] = (!empty($user->email) || $user->email != Null) ? $user->email : "";
        $yesviteUserDetail['country_code'] = (!empty($user->country_code) || $user->country_code != Null) ? strval($user->country_code) : "";
        $yesviteUserDetail['phone_number'] = (!empty($user->phone_number) || $user->phone_number != Null) ? $user->phone_number : "";
        $yesviteUserDetail['app_user']  = $user->app_user;
        $yesviteUserDetail['visible'] =  $user->visible;
        $yesviteUserDetail['message_privacy'] =  $user->message_privacy;
        $yesviteUserDetail['prefer_by']  = $user->prefer_by;
        $yesviteUser[] = $yesviteUserDetail;
    }
    return  $yesviteUser;
}
function getYesviteContactListPage($id, $perPage, $page, $search_name)
{
    $yesviteRegisteredUser = User::select('id', 'firstname', 'profile', 'lastname', 'email', 'country_code', 'phone_number', 'app_user', 'prefer_by', 'email_verified_at', 'parent_user_phone_contact', 'visible', 'message_privacy')
        ->where('id', '!=', $id)
        ->where('is_user_phone_contact', '0')
        // ->where(function ($query) use($id) {
        //     $query->whereNull('email_verified_at')
        //         ->where('app_user', '!=', '1')
        //         // ->orWhere('parent_user_phone_contact',$id)
        //         ->orWhereNotNull('email_verified_at');
        // })
        ->where(function ($query) use ($id) {
            $query->where(function ($subQuery) use ($id) {
                $subQuery->whereNull('email_verified_at')
                         ->where('app_user', '!=', '1')
                         ->where('parent_user_phone_contact', $id);
            })
            ->orWhereNotNull('email_verified_at');
        })
        ->where(DB::raw("CONCAT(firstname, ' ', lastname)"), 'like', "%{$search_name}%")
        ->orderBy('firstname')
        ->paginate($perPage, ['*'], 'page', $page);

    $yesviteUser = [];
    foreach ($yesviteRegisteredUser as $user) {

        $yesviteUserDetail['id'] = $user->id;
        $yesviteUserDetail['profile'] = empty($user->profile) ? "" : asset('storage/profile/' . $user->profile);
        $yesviteUserDetail['first_name'] = (!empty($user->firstname) || $user->firstname != Null) ? $user->firstname : "";;
        $yesviteUserDetail['last_name'] = (!empty($user->lastname) || $user->lastname != Null) ? $user->lastname : "";
        $yesviteUserDetail['email'] = (!empty($user->email) || $user->email != Null) ? $user->email : "";
        $yesviteUserDetail['country_code'] = (!empty($user->country_code) || $user->country_code != Null) ? strval($user->country_code) : "";
        $yesviteUserDetail['phone_number'] = (!empty($user->phone_number) || $user->phone_number != Null) ? $user->phone_number : "";
        $yesviteUserDetail['app_user']  = $user->app_user;
        $yesviteUserDetail['visible'] =  $user->visible;
        $yesviteUserDetail['message_privacy'] =  $user->message_privacy;
        $yesviteUserDetail['prefer_by']  = $user->prefer_by;
        $yesviteUser[] = $yesviteUserDetail;
    }

    return  $yesviteUser;
}
function getEventInvitedUser($event_id, $rsvp_d = null)
{

    return  EventInvitedUser::
    // whereHas('user', function ($query) use ($rsvp_d) {
    //     $query->when($rsvp_d == null, function ($q) {
    //         $q->where('app_user', '1');
    //     })->when($rsvp_d != null, function ($q) {
    //         $q->where('rsvp_d', '1')
    //             ->where('app_user', '1');
    //     });
    // })->
    where(['event_id'=>$event_id,'is_co_host'=>'0'])->get();
}
function checkUserGivePoll($user_id, $post_poll_id, $option_id)
{
    return UserEventPollData::where(['user_id' => $user_id, 'event_post_poll_id' => $post_poll_id, 'event_poll_option_id' => $option_id])->exists();
}
function getTotalEventInvitedUser($event_id)
{
    $total =   EventInvitedUser::whereHas('user', function ($query) {
        $query->where('app_user', '1');
    })->where('event_id', $event_id)->count();
    return $total + 1;
}
function getEventImages($event_id)
{
    return  EventImage::where('event_id', $event_id)->get();
}
function getPostImages($event_post_id)
{
    return  EventPostImage::where('event_post_id', $event_post_id)->get();
}
function getReaction($event_post_id)
{
    return  EventPostReaction::with('user')->where('event_post_id', $event_post_id)->get();
}
function getOnlyReaction($event_post_id)
{
    $onlyReaction =   EventPostReaction::where([

        'event_post_id' => $event_post_id
    ])
        ->select('reaction', DB::raw('COUNT(*) as count'))
        ->groupBy('reaction', 'unicode')
        ->orderByDesc('count')
        ->take(3)
        ->pluck('reaction')
        ->toArray();

    $reactionList = [];

    foreach ($onlyReaction as $val) {
        // $react['emoji'] = $val;
        $react = $val;
        $reactionList[] = $react;
    }
    return $reactionList;
}
function getPhotoReaction($event_post_photo_id)
{
    return  EventPostPhotoReaction::with('user')->where('event_post_photo_id', $event_post_photo_id)->get();
}
function getPostPhotoComments($event_post_photo_id)
{
    return  EventPostPhotoComment::with(['user', 'replies' => function ($query) {
        $query->withcount('post_photo_comment_reaction', 'replies')->orderBy('id', 'DESC');
    }])->withcount('post_photo_comment_reaction', 'replies')->where(['event_post_photo_id' => $event_post_photo_id, 'parent_comment_id' => NULl])->get();
}
function getPhotoCommentReaction($event_photo_comment_id)
{
    return  EventPhotoCommentReaction::where(['event_photo_comment_id' => $event_photo_comment_id])->count();
}
function getComments($event_post_id)
{
    return  EventPostComment::with(['user', 'replies' => function ($query) {
        $query->withcount('post_comment_reaction', 'replies')->orderBy('id', 'DESC');
    }])->withcount('post_comment_reaction', 'replies')->where(['event_post_id' => $event_post_id, 'parent_comment_id' => NULl])->orderBy('id', 'DESC')->get();
}
function checkUserIsLike($event_post_comment_id, $user_id)
{
    $checkUserRection = EventPostCommentReaction::where(['event_post_comment_id' => $event_post_comment_id, 'user_id' => $user_id])->count();
    if ($checkUserRection != 0) {
        return 1;
    } else {
        return 0;
    }
}
function checkUserPhotoIsLike($event_photo_comment_id, $user_id)
{
    $checkUserRection = EventPhotoCommentReaction::where(['event_photo_comment_id' => $event_photo_comment_id, 'user_id' => $user_id])->count();
    if ($checkUserRection != 0) {
        return 1;
    } else {
        return 0;
    }
}
function getOptionTotalVote($event_poll_option_id)
{
    return UserEventPollData::where('event_poll_option_id', $event_poll_option_id)->count();
}
function getOptionAllTotalVote($id)
{
    return UserEventPollData::where('event_post_poll_id', $id)->count();
}
function checkUserAttendOrNOt($event_id, $user_id)
{
    $checkEventOwner = Event::where(['user_id' => $user_id, "id" => $event_id])->exists();
    if ($checkEventOwner) {
        return 3; // owner
    }
    $userAcceptStatus =  EventInvitedUser::where(['event_id' => $event_id, 'user_id' => $user_id])->first();
    if ($userAcceptStatus != null) {

        if ($userAcceptStatus->rsvp_status == '1'  &&  $userAcceptStatus->rsvp_d == '1') {
            return 1; // comming
        }

        if ($userAcceptStatus->rsvp_status == '0'  &&  $userAcceptStatus->rsvp_d == '1') {
            return 2; // not coming
        }
        if ($userAcceptStatus->rsvp_status == '0'  && $userAcceptStatus->read == '1' &&  $userAcceptStatus->rsvp_d == '0') {

            return 0; // No  Reply
        }
    }
    return 0;
}
function getPollData($event_id, $post_id)
{

    $user  = Auth::guard('api')->user();
    $eventPollData =   EventPostPoll::with('event_poll_option')->withCount('user_poll_data')->where(['event_id' => $event_id, 'event_post_id' => $post_id])->first();


    $postsPollDetail['total_poll_vote'] = $eventPollData->user_poll_data_count;



    $postsPollDetail['poll_id'] = $eventPollData->id;

    $postsPollDetail['poll_question'] = $eventPollData->poll_question;

    $postsPollDetail['poll_option'] = [];

    foreach ($eventPollData->event_poll_option as $optionValue) {

        $optionData['id'] = $optionValue->id;

        $optionData['option'] = $optionValue->option;

        $optionData['total_vote'] =  round(getOptionTotalVote($optionValue->id) * 100 / getTotalEventInvitedUser($event_id)) . "%";
        $optionData['is_poll_selected'] = checkUserGivePoll($user->id, $eventPollData->id, $optionValue->id);


        $postsPollDetail['poll_option'][] = $optionData;
    }
    return $postsPollDetail;
}
function getInvitedUsers($eventId)
{
    $eventDetail['invited_guests'] = [];
    $eventDetail['invited_user_id'] = [];

    $invitedUsers = EventInvitedUser::query();
    $invitedUsers->with(['event', 'user','contact_sync'])->where(['event_id' => $eventId,'is_co_host'=>'0']);
    $result = $invitedUsers->get();

    if (!empty($result)) {
        foreach ($result as $guestVal) {

            if ($guestVal->sync_id != '') {
                $invitedGuestDetail['first_name'] = (!empty($guestVal->contact_sync->firstName) && $guestVal->contact_sync->firstName != NULL) ? $guestVal->contact_sync->firstName : "";
                $invitedGuestDetail['last_name'] = (!empty($guestVal->contact_sync->lastName) && $guestVal->contact_sync->lastName != NULL) ? $guestVal->contact_sync->lastName : "";
                $invitedGuestDetail['email'] = (!empty($guestVal->contact_sync->email) && $guestVal->contact_sync->email != NULL) ? $guestVal->contact_sync->email : "";
                $invitedGuestDetail['country_code'] = "";
                $invitedGuestDetail['phone_number'] = (!empty($guestVal->contact_sync->phoneWithCode) && $guestVal->contact_sync->phoneWithCode != NULL) ? $guestVal->contact_sync->phoneWithCode : "";
                $invitedGuestDetail['prefer_by'] = (!empty($guestVal->prefer_by) && $guestVal->prefer_by != NULL) ? $guestVal->prefer_by : "";
                $invitedGuestDetail['id'] = (!empty($guestVal->sync_id) && $guestVal->sync_id != NULL) ? $guestVal->sync_id : "";
                $invitedGuestDetail['app_user'] = (!empty($guestVal->contact_sync->isAppUser) && $guestVal->contact_sync->isAppUser != NULL) ? (int)$guestVal->contact_sync->isAppUser : 0;
                $invitedGuestDetail['visible'] = (!empty($guestVal->contact_sync->visible) && $guestVal->contact_sync->visible != NULL) ? (int)$guestVal->contact_sync->visible : 0;
                $invitedGuestDetail['profile'] = (!empty($guestVal->contact_sync->photo) && $guestVal->contact_sync->photo != NULL) ? $guestVal->contact_sync->photo : "";
                $eventDetail['invited_guests'][] = $invitedGuestDetail;
            } elseif ($guestVal->user->app_user == '1') {
                $invitedUserIdDetail['first_name'] = (!empty($guestVal->user->firstname) && $guestVal->user->firstname != NULL) ? $guestVal->user->firstname : "";
                $invitedUserIdDetail['last_name'] = (!empty($guestVal->user->lastname) && $guestVal->user->lastname != NULL) ? $guestVal->user->lastname : "";
                $invitedUserIdDetail['email'] = (!empty($guestVal->user->email) && $guestVal->user->email != NULL) ? $guestVal->user->email : "";
                $invitedUserIdDetail['country_code'] = (!empty($guestVal->user->country_code) && $guestVal->user->country_code != NULL) ? strval($guestVal->user->country_code) : "";
                $invitedUserIdDetail['phone_number'] = (!empty($guestVal->user->phone_number) && $guestVal->user->phone_number != NULL) ? $guestVal->user->phone_number : "";
                $invitedUserIdDetail['prefer_by'] = (!empty($guestVal->prefer_by) && $guestVal->prefer_by != NULL) ? $guestVal->prefer_by : "";
                $invitedUserIdDetail['id'] = (!empty($guestVal->user_id) && $guestVal->user_id != NULL) ? $guestVal->user_id : "";
                $invitedUserIdDetail['app_user'] = (!empty($guestVal->user->app_user) && $guestVal->user->app_user != NULL) ? (int)$guestVal->user->app_user : 0;
                $invitedUserIdDetail['visible'] = (!empty($guestVal->user->visible) && $guestVal->user->visible != NULL) ? (int)$guestVal->user->visible : 0;
                // $invitedUserIdDetail['profile'] = (!empty($guestVal->user->profile) && $guestVal->user->profile != NULL) ? asset('storage/profile/').$guestVal->user->profile : "";
                $invitedUserIdDetail['profile'] = (!empty($guestVal->user->profile) && $guestVal->user->profile != NULL) ? asset('storage/profile/' . $guestVal->user->profile) : "";
                
                $eventDetail['invited_user_id'][] = $invitedUserIdDetail;
            }
        }
    }
    return $eventDetail;
}

function getInvitedUsersList($eventId)
{
    $eventDetail['invited_guests'] = [];
    $eventDetail['invited_user_id'] = [];

    $invitedUsers = EventInvitedUser::query();
    $invitedUsers->with(['event', 'user','contact_sync'])->where(['event_id' => $eventId,'is_co_host'=>'0']);
    $result = $invitedUsers->get();

    dd($result);
    if (!empty($result)) {
        foreach ($result as $guestVal) {

            if ($guestVal->user_id==""||$guestVal->sync_id != '') {
                $invitedGuestDetail['first_name'] = (!empty($guestVal->contact_sync->firstName) && $guestVal->contact_sync->firstName != NULL) ? $guestVal->contact_sync->firstName : "";
                $invitedGuestDetail['last_name'] = (!empty($guestVal->contact_sync->lastName) && $guestVal->contact_sync->lastName != NULL) ? $guestVal->contact_sync->lastName : "";
                $invitedGuestDetail['email'] = (!empty($guestVal->contact_sync->email) && $guestVal->contact_sync->email != NULL) ? $guestVal->contact_sync->email : "";
                $invitedGuestDetail['country_code'] = "";
                $invitedGuestDetail['phone_number'] = (!empty($guestVal->contact_sync->phoneWithCode) && $guestVal->contact_sync->phoneWithCode != NULL) ? $guestVal->contact_sync->phoneWithCode : "";
                $invitedGuestDetail['prefer_by'] = (!empty($guestVal->prefer_by) && $guestVal->prefer_by != NULL) ? $guestVal->prefer_by : "";
                $invitedGuestDetail['id'] = (!empty($guestVal->sync_id) && $guestVal->sync_id != NULL) ? $guestVal->sync_id : "";
                $invitedGuestDetail['app_user'] = (!empty($guestVal->contact_sync->isAppUser) && $guestVal->contact_sync->isAppUser != NULL) ? (int)$guestVal->contact_sync->isAppUser : 0;
                $invitedGuestDetail['visible'] = (!empty($guestVal->contact_sync->visible) && $guestVal->contact_sync->visible != NULL) ? (int)$guestVal->contact_sync->visible : 0;
                // $invitedGuestDetail['profile'] = (!empty($guestVal->contact_sync->photo) && $guestVal->contact_sync->photo != NULL) ? $guestVal->contact_sync->photo : "";
                $invitedGuestDetail['profile'] = (!empty($guestVal->contact_sync->photo) && $guestVal->contact_sync->photo != NULL) ? asset('storage/profile/' . $guestVal->contact_sync->photo) : "";

                $invitedGuestDetail['rsvp_status']= $guestVal->rsvp_status;
                $invitedGuestDetail['kids']= $guestVal->kids;
                $invitedGuestDetail['adults']= $guestVal->adults;
                $invitedGuestDetail['read']= $guestVal->read;
                $invitedGuestDetail['rsvp_d']= $guestVal->rsvp_d;  
                $invitedGuestDetail['message_to_host']= $guestVal->message_to_host;
                $eventDetail['invited_guests'][] = $invitedGuestDetail;
            } elseif ($guestVal->user->app_user == '1') {
                $invitedUserIdDetail['first_name'] = (!empty($guestVal->user->firstname) && $guestVal->user->firstname != NULL) ? $guestVal->user->firstname : "";
                $invitedUserIdDetail['last_name'] = (!empty($guestVal->user->lastname) && $guestVal->user->lastname != NULL) ? $guestVal->user->lastname : "";
                $invitedUserIdDetail['email'] = (!empty($guestVal->user->email) && $guestVal->user->email != NULL) ? $guestVal->user->email : "";
                $invitedUserIdDetail['country_code'] = (!empty($guestVal->user->country_code) && $guestVal->user->country_code != NULL) ? strval($guestVal->user->country_code) : "";
                $invitedUserIdDetail['phone_number'] = (!empty($guestVal->user->phone_number) && $guestVal->user->phone_number != NULL) ? $guestVal->user->phone_number : "";
                $invitedUserIdDetail['prefer_by'] = (!empty($guestVal->prefer_by) && $guestVal->prefer_by != NULL) ? $guestVal->prefer_by : "";
                $invitedUserIdDetail['id'] = (!empty($guestVal->user_id) && $guestVal->user_id != NULL) ? $guestVal->user_id : "";
                $invitedUserIdDetail['app_user'] = (!empty($guestVal->user->app_user) && $guestVal->user->app_user != NULL) ? (int)$guestVal->user->app_user : 0;
                $invitedUserIdDetail['visible'] = (!empty($guestVal->user->visible) && $guestVal->user->visible != NULL) ? (int)$guestVal->user->visible : 0;
                // $invitedUserIdDetail['profile'] = (!empty($guestVal->user->profile) && $guestVal->user->profile != NULL) ? asset('storage/profile/').$guestVal->user->profile : "";
                $invitedUserIdDetail['profile'] = (!empty($guestVal->user->profile) && $guestVal->user->profile != NULL) ? asset('storage/profile/' . $guestVal->user->profile) : "";
                $invitedUserIdDetail['rsvp_status']= $guestVal->rsvp_status;
                $invitedUserIdDetail['kids']= $guestVal->kids;
                $invitedUserIdDetail['adults']= $guestVal->adults;
                $invitedUserIdDetail['read']= $guestVal->read;
                $invitedUserIdDetail['rsvp_d']= $guestVal->rsvp_d;
                $invitedUserIdDetail['message_to_host']= $guestVal->message_to_host;
                $eventDetail['invited_user_id'][] = $invitedUserIdDetail;
            }
        }
    }
    $eventDetail['all_invited_users'] = array_merge($eventDetail['invited_guests'], $eventDetail['invited_user_id']);

    return $eventDetail;
}

function getInvitedCohostList($eventId)
{
 

    $invitedUsers = EventInvitedUser::query();
    $invitedUsers->with(['event', 'user','contact_sync'])->where(['event_id' => $eventId,'is_co_host'=>'1']);
    $result = $invitedUsers->get();
    if (!empty($result)) {
        foreach ($result as $guestVal) {

            if ($guestVal->sync_id != '' &&  $guestVal->user_id == null) {
                $invitedGuestDetail['first_name'] = (!empty($guestVal->contact_sync->firstName) && $guestVal->contact_sync->firstName != NULL) ? $guestVal->contact_sync->firstName : "";
                $invitedGuestDetail['last_name'] = (!empty($guestVal->contact_sync->lastName) && $guestVal->contact_sync->lastName != NULL) ? $guestVal->contact_sync->lastName : "";
                $invitedGuestDetail['email'] = (!empty($guestVal->contact_sync->email) && $guestVal->contact_sync->email != NULL) ? $guestVal->contact_sync->email : "";
                $invitedGuestDetail['country_code'] = "";
                $invitedGuestDetail['phone_number'] = (!empty($guestVal->contact_sync->phoneWithCode) && $guestVal->contact_sync->phoneWithCode != NULL) ? $guestVal->contact_sync->phoneWithCode : "";
                $invitedGuestDetail['prefer_by'] = (!empty($guestVal->prefer_by) && $guestVal->prefer_by != NULL) ? $guestVal->prefer_by : "";
                $invitedGuestDetail['id'] = (!empty($guestVal->sync_id) && $guestVal->sync_id != NULL) ? $guestVal->sync_id : "";
                $invitedGuestDetail['app_user'] = (!empty($guestVal->contact_sync->isAppUser) && $guestVal->contact_sync->isAppUser != NULL) ? (int)$guestVal->contact_sync->isAppUser : 0;
                $invitedGuestDetail['visible'] = (!empty($guestVal->contact_sync->visible) && $guestVal->contact_sync->visible != NULL) ? (int)$guestVal->contact_sync->visible : 0;
                $invitedGuestDetail['profile'] = (!empty($guestVal->contact_sync->photo) && $guestVal->contact_sync->photo != NULL) ? $guestVal->contact_sync->photo : "";
                $invitedGuestDetail['rsvp_status']= $guestVal->rsvp_status;
                $invitedGuestDetail['kids']= $guestVal->kids;
                $invitedGuestDetail['adults']= $guestVal->adults;
                $invitedGuestDetail['read']= $guestVal->read;
                $invitedGuestDetail['rsvp_d']= $guestVal->rsvp_d;  
                $invitedGuestDetail['message_to_host']= $guestVal->message_to_host;
                $eventDetail[] = $invitedGuestDetail;
                return $eventDetail;
            } elseif ($guestVal->user->app_user == '1') {
                $invitedUserIdDetail['first_name'] = (!empty($guestVal->user->firstname) && $guestVal->user->firstname != NULL) ? $guestVal->user->firstname : "";
                $invitedUserIdDetail['last_name'] = (!empty($guestVal->user->lastname) && $guestVal->user->lastname != NULL) ? $guestVal->user->lastname : "";
                $invitedUserIdDetail['email'] = (!empty($guestVal->user->email) && $guestVal->user->email != NULL) ? $guestVal->user->email : "";
                $invitedUserIdDetail['country_code'] = (!empty($guestVal->user->country_code) && $guestVal->user->country_code != NULL) ? strval($guestVal->user->country_code) : "";
                $invitedUserIdDetail['phone_number'] = (!empty($guestVal->user->phone_number) && $guestVal->user->phone_number != NULL) ? $guestVal->user->phone_number : "";
                $invitedUserIdDetail['prefer_by'] = (!empty($guestVal->prefer_by) && $guestVal->prefer_by != NULL) ? $guestVal->prefer_by : "";
                $invitedUserIdDetail['id'] = (!empty($guestVal->user_id) && $guestVal->user_id != NULL) ? $guestVal->user_id : "";
                $invitedUserIdDetail['app_user'] = (!empty($guestVal->user->app_user) && $guestVal->user->app_user != NULL) ? (int)$guestVal->user->app_user : 0;
                $invitedUserIdDetail['visible'] = (!empty($guestVal->user->visible) && $guestVal->user->visible != NULL) ? (int)$guestVal->user->visible : 0;
                // $invitedUserIdDetail['profile'] = (!empty($guestVal->user->profile) && $guestVal->user->profile != NULL) ? asset('storage/profile/').$guestVal->user->profile : "";
                $invitedUserIdDetail['profile'] = (!empty($guestVal->user->profile) && $guestVal->user->profile != NULL) ? asset('storage/profile/' . $guestVal->user->profile) : "";
                $invitedUserIdDetail['rsvp_status']= $guestVal->rsvp_status;
                $invitedUserIdDetail['kids']= $guestVal->kids;
                $invitedUserIdDetail['adults']= $guestVal->adults;
                $invitedUserIdDetail['read']= $guestVal->read;
                $invitedUserIdDetail['rsvp_d']= $guestVal->rsvp_d;
                $invitedUserIdDetail['message_to_host']= $guestVal->message_to_host;
                $eventDetail[] = $invitedUserIdDetail;
                return $eventDetail;
            }
        }
    }
}
function getDeferentBetweenTime($time1, $time2)
{
    // Convert time strings to Carbon objects
    $carbonTime1 = Carbon::createFromFormat('h:i A', $time1);
    $carbonTime2 = Carbon::createFromFormat('h:i A', $time2);

    // Find the difference in minutes
    $diffInMinutes = $carbonTime1->diffInMinutes($carbonTime2);

    // Convert minutes to hours and minutes
    $hours = floor($diffInMinutes / 60);
    $minutes = $diffInMinutes % 60;

    // Format response
    $response = '';
    if ($hours > 0) {
        $response .= "$hours h";
    }
    if ($minutes > 0) {
        $response .= " $minutes m";
    }
    return $response;
}
function getLeftPollTime($createdDate, $pollDuration)
{


    // Assuming you have the create date as a string
    $createDate = $createdDate;
    $pollDuration = $pollDuration;

    // Extract the number of hours from the poll duration
    preg_match('/(\d+)\s*Hour/i', $pollDuration, $matches);
    $hours = isset($matches[1]) ? (int)$matches[1] : 0;
    preg_match('/(\d+)\s*Day/i', $pollDuration, $matches);
    $days = isset($matches[1]) ? (int)$matches[1] : 0;

    preg_match('/(\d+)\s*Week/i', $pollDuration, $matches);
    $weeks = isset($matches[1]) ? (int)$matches[1] : 0;

    preg_match('/(\d+)\s*Month/i', $pollDuration, $matches);
    $months = isset($matches[1]) ? (int)$matches[1] : 0;
    // Convert the create date to a Carbon instance
    $createDateTime = Carbon::parse($createDate);

    // Add hours to the create date
    if ($hours != 0) {

        $endTime = $createDateTime->addHours($hours);
    }
    if ($days != 0) {

        $endTime = $createDateTime->addDays($days);
    }
    if ($weeks != 0) {

        $endTime = $createDateTime->addWeeks($weeks);
    }

    if ($months != 0) {

        $endTime = $createDateTime->addMonths($months);
    }


    // Get the current time
    $currentTime = Carbon::now();

    // Calculate the remaining time
    $remainingTime = "0";
    if ($endTime > $currentTime) {

        $remainingTime = $endTime->diff($currentTime);
    }

    // Format the remaining time
    $formattedRemainingTime = '';
    if ($remainingTime != "0") {

        if ($remainingTime->d > 0) {
            $formattedRemainingTime .= $remainingTime->d . ' d';
            return trim($formattedRemainingTime);
        }
        if ($remainingTime->h > 0) {
            $formattedRemainingTime .= $remainingTime->h . 'h ';
            return trim($formattedRemainingTime);
        }
        if ($remainingTime->i > 0) {
            $formattedRemainingTime .= $remainingTime->i . 'm ';
            return trim($formattedRemainingTime);
        }
        if ($remainingTime->s > 0) {
            $formattedRemainingTime .= $remainingTime->s . 's ';
            return trim($formattedRemainingTime);
        }
    }

    return $formattedRemainingTime;
    // Output the formatted remaining time



}
function getYesviteSelectedUserPage($id, $perPage, $page, $eventId)
{
    $yesviteEvents = Event::where('id', '=', $eventId)->first();
    dd($yesviteEvents);
    $yesviteRegisteredUser = User::select('id', 'firstname', 'profile', 'lastname', 'email', 'country_code', 'phone_number', 'app_user', 'prefer_by', 'email_verified_at', 'parent_user_phone_contact', 'visible', 'message_privacy')
        ->where('id', '!=', $id)
        ->where('is_user_phone_contact', '0')
        ->with(['event' => function ($query) use ($eventId) {
            $query->where('id', $eventId);
        }])
        ->where(function ($query) {
            $query->whereNull('email_verified_at')
                ->where('app_user', '!=', '1')
                ->orWhereNotNull('email_verified_at');
        })
        ->orderBy('firstname')
        ->paginate($perPage, ['*'], 'page', $page);
    dd($yesviteRegisteredUser);
    $yesviteUser = [];
    foreach ($yesviteRegisteredUser as $user) {

        $yesviteUserDetail['id'] = $user->id;
        $yesviteUserDetail['profile'] = empty($user->profile) ? "" : asset('storage/profile/' . $user->profile);
        $yesviteUserDetail['first_name'] = (!empty($user->firstname) || $user->firstname != Null) ? $user->firstname : "";;
        $yesviteUserDetail['last_name'] = (!empty($user->lastname) || $user->lastname != Null) ? $user->lastname : "";
        $yesviteUserDetail['email'] = (!empty($user->email) || $user->email != Null) ? $user->email : "";
        $yesviteUserDetail['country_code'] = (!empty($user->country_code) || $user->country_code != Null) ? strval($user->country_code) : "";
        $yesviteUserDetail['phone_number'] = (!empty($user->phone_number) || $user->phone_number != Null) ? $user->phone_number : "";
        $yesviteUserDetail['app_user']  = $user->app_user;
        $yesviteUserDetail['visible'] =  $user->visible;
        $yesviteUserDetail['message_privacy'] =  $user->message_privacy;
        $yesviteUserDetail['prefer_by']  = $user->prefer_by;
        $yesviteUser[] = $yesviteUserDetail;
    }
    return  $yesviteUser;
}
function getProfileCounter(){

    $user  = Auth::guard('web')->user();
    
    $totalEvent =  Event::where('user_id', $user->id)->count();
    $totalEventPhotos =  EventPost::where(['user_id' => $user->id, 'post_type' => '1'])->count();
    $comments =  EventPostComment::where('user_id', $user->id)->count();
    $counter=['events'=>$totalEvent,'photos'=>$totalEventPhotos,'comments'=>$comments];

    return $counter;
}

function getTotalUnreadNotification($user_id){
    $total=Notification::where(['user_id' => $user_id, 'read' => '0'])->count();
    return $total;
}

function getAllEventList($user_id){

    $eventData = EventInvitedUser::where(['user_id' => $user_id])->get();

    $eventList = [];

    foreach ($eventData as $val) {

        $eventDatas =   Event::select('id', 'event_name')->where('id', $val->event_id)->get();
        foreach ($eventDatas as $vals) {
            $eventDetail['id'] = $vals->id;
            $eventDetail['event_name'] = $vals->event_name;
            $eventList[] = $eventDetail;
        }
    }
    $ownerEvent =    Event::select('id', 'event_name')->where(['user_id' => $user_id, 'is_draft_save' => '0'])->get();

    foreach ($ownerEvent as $ownerEvent) {
        $eventOwnDetail['id'] = $ownerEvent->id;
        $eventOwnDetail['event_name'] = $ownerEvent->event_name;
        $eventList[] = $eventOwnDetail;
    }

    return $eventList;
}

function totalEventOfCurrentMonth($user_id){
    $eventData = Event::where(['user_id' => $user_id, 'is_draft_save' => '0'])
    ->whereYear('start_date', date('Y'))
    ->whereMonth('start_date', date('m'));
    $invitedEvents = EventInvitedUser::where('user_id', $user_id)->get()->pluck('event_id');
    $invitedEventsList = Event::whereIn('id', $invitedEvents)
        ->where('is_draft_save', '0')
        ->whereYear('start_date', date('Y'))
        ->whereMonth('start_date', date('m'));
    
    $totalEventOfCurrentMonth = $eventData->union($invitedEventsList)->get();
    return count($totalEventOfCurrentMonth);
}
function totalEventOfCurrentYear($user_id){
    $eventData = Event::where(['user_id' => $user_id, 'is_draft_save' => '0'])
    ->whereYear('start_date', date('Y'))
    ->whereNull('deleted_at');
    $invitedEvents = EventInvitedUser::where('user_id', $user_id)->get()->pluck('event_id');
    $invitedEventsList = Event::whereIn('id', $invitedEvents)
        ->where('is_draft_save', '0')
        ->whereYear('start_date', date('Y'))
        ->whereNull('deleted_at');
// ;
    
    $totalEventOfCurrentYear = $eventData->union($invitedEventsList)->get(); 
    return count($totalEventOfCurrentYear);
}