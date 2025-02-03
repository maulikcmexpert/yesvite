<?php

namespace App\Http\Controllers;

use App\Models\{
    Event,
    EventPost,
    EventPostComment,
    EventInvitedUser,
    Notification,
    EventImage,
    EventGiftRegistry,
    EventPostImage,
    EventPotluckCategory,
    EventPotluckCategoryItem,
    EventPostPoll,
    UserPotluckItem,
    PostControl,
    EventPostReaction
};

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as Exception;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class EventPhotoController extends Controller
{
    public function index(String $id)
    {
        $title = 'event photos';
        $page = 'front.event_wall.event_photos';
        $user  = Auth::guard('web')->user();
        $firstname = $user->firstname;
        $lastname = $user->lastname;
        $photos = $user->profile ;

        $js = ['event_photo'];
        // $rawData = $request->getContent();
        // $request = json_decode($rawData, true);
        $event = decrypt($id);
        if ($event == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        try {
            // $selectedFilters = $request->input('filters');
            $getPhotoList = EventPost::query();
            $getPhotoList->with(['user', 'event_post_reaction', 'post_image'])->withCount(['event_post_reaction', 'post_image', 'event_post_comment' => function ($query) {
                $query->where('parent_comment_id', NULL);
            }])->where(['event_id' => $event, 'post_type' => '1']);
            $eventCreator = Event::where('id', $event)->first();

            $getPhotoList->orderBy('id', 'desc');

            $results = $getPhotoList->get();
            // dd($results);
            $postPhotoList = [];
            foreach ($results as $value) {
                $ischeckEventOwner = Event::where(['id' => $event, 'user_id' => $user->id])->first();
                $postControl = PostControl::where(['user_id' => $user->id, 'event_id' => $event, 'event_post_id' => $value->id])->first();
                if ($postControl != null) {

                    if ($postControl->post_control == 'hide_post') {
                        continue;
                    }
                }
                $postPhotoDetail['user_id'] = $value->user->id;
                $postPhotoDetail['is_own_post'] = ($value->user->id == $user->id) ? "1" : "0";
                $postPhotoDetail['is_host'] =  ($ischeckEventOwner != null) ? 1 : 0;
                $postPhotoDetail['firstname'] = $value->user->firstname;
                $postPhotoDetail['lastname'] = $value->user->lastname;
                $postPhotoDetail['profile'] = (!empty($value->user->profile) || $value->user->profile != NULL) ? asset('storage/profile/' . $value->user->profile) : "";
                $selfReaction = EventPostReaction::where(['user_id' => $user->id, 'event_post_id' => $value->id])->first();
                $postPhotoDetail['is_reaction'] = ($selfReaction != NULL) ? '1' : '0';
                $postPhotoDetail['self_reaction'] = ($selfReaction != NULL) ? $selfReaction->reaction : "";
                $postPhotoDetail['event_id'] = $value->event_id;
                $postPhotoDetail['id'] = $value->id;
                $postPhotoDetail['post_message'] = (!empty($value->post_message) || $value->post_message != NULL) ? $value->post_message : "";
                $postPhotoDetail['post_time'] = $this->setpostTime($value->updated_at);
                $postPhotoDetail['is_in_photo_moudle'] = $value->is_in_photo_moudle;
                $photoVideoData = []; // Initialize as an array
                if (!empty($value->post_image)) {
                    $photData = $value->post_image;
                    foreach ($photData as $val) {
                        $photoVideoDetail = []; // Reset for each image
                        $photoVideoDetail['id'] = $val->id;
                        $photoVideoDetail['event_post_id'] = $val->event_post_id;
                        $photoVideoDetail['post_media'] = (!empty($val->post_image) || $val->post_media != NULL) ? asset('storage/post_image/' . $val->post_image) : "";
                        $photoVideoDetail['thumbnail'] = (!empty($val->thumbnail) || $val->thumbnail != NULL) ? asset('storage/thumbnails/' . $val->thumbnail) : "";
                        $photoVideoDetail['type'] = $val->type;

                        // Add to the array of media
                        $photoVideoData[] = $photoVideoDetail;
                    }
                }

                $postPhotoDetail['mediaData'] = $photoVideoData;
                $postPhotoDetail['total_media'] = (count($photoVideoData) > 1) ? "+" . (count($photoVideoData) - 1) : "";
                $getPhotoReaction = getReaction($value->id);
                $reactionList = [];
                foreach ($getPhotoReaction as $values) {
                    $reactionList[] = $values->reaction;
                }
                $postPhotoDetail['reactionList'] = $reactionList;
                $postPhotoDetail['total_likes'] = $value->event_post_reaction_count;
                $postPhotoDetail['total_comments'] = $value->event_post_comment_count;


                $letestComment = EventPostComment::with('user')->withCount('post_comment_reaction', 'replies')
                    ->where(['event_post_id' => $value->id, 'parent_comment_id' => NULL])->get();
                //  ->orderBy('id', 'DESC');
                // ->limit(1)
                // ->first();
                // dd($letestComment);
                $postPhotoDetailcomment=[];
                foreach ($letestComment as $values) {
                    // Setting up the latest comment data
                    $postCommentList = [
                        'id' => $values->id,
                        'event_post_id' => $values->event_post_id,
                        'comment' => $values->comment_text,
                        'media' => (!empty($values->media)) ? asset('storage/comment_media/' . $values->media) : "",
                        'user_id' => $values->user_id,
                        'username' => $values->user->firstname . ' ' . $values->user->lastname,
                        'profile' => (!empty($values->user->profile)) ? asset('storage/profile/' . $values->user->profile) : "",
                        'comment_total_likes' => $values->post_comment_reaction_count,
                        'location' => $values->user->city . ($values->user->state ? ', ' . $values->user->state : ''),
                        'is_like' => 0, // Adjust based on the user's like status
                        'created_at' => $values->created_at,
                        'total_replies' => $values->replies_count,
                        'posttime' => setpostTime($values->created_at),
                        'comment_replies' => []
                    ];

                    $postPhotoDetailcomment[] = $postCommentList;
                }
                $postPhotoDetail['latest_comment'] = $postPhotoDetailcomment;
                $postPhotoList[] = $postPhotoDetail;
            }
            // if (!empty($postPhotoList)) {
            //     return compact('postPhotoList');
            //     // return response()->json(['status' => 1, 'data' => $postPhotoList, 'message' => "Photo List"]);
            // } else {
            //     $postPhotoList="";
            //     return compact('postPhotoList');
            //     // return response()->json(['status' => 0, 'data' => $postPhotoList, 'message' => "Photo not found"]);
            // }
            $eventDetail = Event::with(['user', 'event_image', 'event_schedule', 'event_settings' => function ($query) {
                $query->select('event_id', 'podluck', 'allow_limit', 'adult_only_party');
            },  'event_invited_user' => function ($query) {
                $query->where('is_co_host', '1')->with('user');
            }])->where('id', $event)->first();
            $guestView = [];
            $eventDetails['id'] = $eventDetail->id;
            $eventDetails['event_images'] = [];
            if (count($eventDetail->event_image) != 0) {
                foreach ($eventDetail->event_image as $values) {
                    $eventDetails['event_images'][] = asset('storage/event_images/' . $values->image);
                }
            }
            $eventDetails['user_profile'] = empty($eventDetail->user->profile) ? "" : asset('storage/profile/' . $eventDetail->user->profile);
            $eventDetails['event_name'] = $eventDetail->event_name;
            $eventDetails['hosted_by'] = $eventDetail->hosted_by;
            $eventDetails['is_host'] = ($eventDetail->user_id == $user->id) ? 1 : 0;
            $eventDetails['podluck'] = $eventDetail->event_settings->podluck;
            $rsvp_status = "";
            $checkUserrsvp = EventInvitedUser::whereHas('user', function ($query) {
                // $query->where('app_user', '1');
            })->where(['user_id' => $user->id, 'event_id' => $event])->first();
            // dd($checkUserrsvp);
            // if ($value->rsvp_by_date >= date('Y-m-d')) {

            if ($checkUserrsvp != null) {

                if ($checkUserrsvp->rsvp_status == '1') {
                    $rsvp_status = '1'; // rsvp you'r going
                } else if ($checkUserrsvp->rsvp_status == '0') {
                    $rsvp_status = '0'; // rsvp you'r not going
                }

            }

            $eventDetails['rsvp_status'] = $rsvp_status;
            $eventDetails['allow_limit'] = $eventDetail->event_settings->allow_limit;
            $eventDetails['adult_only_party'] = $eventDetail->event_settings->adult_only_party;
            $eventDetails['event_date'] = $eventDetail->start_date;
            $eventDetails['event_time'] = $eventDetail->rsvp_start_time;
            // if ($eventDetail->event_schedule->isNotEmpty()) {

            //     $eventDetails['event_time'] = $eventDetail->event_schedule->first()->start_time . ' to ' . $eventDetail->event_schedule->last()->end_time;
            // }
            $eventDetails['rsvp_by'] = (!empty($eventDetail->rsvp_by_date) || $eventDetail->rsvp_by_date != NULL) ? $eventDetail->rsvp_by_date : date('Y-m-d', strtotime($eventDetail->created_at));
            $current_date = date('Y-m-d');
            $eventDate = $eventDetail->start_date;
            $datetime1 = Carbon::parse($eventDate);
            $datetime2 =  Carbon::parse($current_date);
            $till_days = strval($datetime1->diff($datetime2)->days);

            if ($eventDate >= $current_date) {
                if ($till_days == 0) {
                    $till_days = "Today";
                }
                if ($till_days == 1) {
                    $till_days = "Tomorrow";
                }
            } else {
                $eventEndDate = $eventDetail->end_date;
                $till_days = "On going";
                if ($eventEndDate < $current_date) {
                    $till_days = "Past";
                }
            }
            $event_comments = EventPostComment::where(['event_id' => $eventDetail->id])->count();
            $eventDetails['total_event_comments']=$event_comments;
            $eventDetail['is_past'] = ($eventDetail->end_date < date('Y-m-d')) ? true : false;
            $eventDetails['days_till_event'] = $till_days;
            $eventDetails['event_created_timestamp'] = Carbon::parse($eventDate)->timestamp;
            $eventDetails['message_to_guests'] = $eventDetail->message_to_guests;

            $coHosts = [];
            foreach ($eventDetail->event_invited_user as $hostValues) {
                $coHostDetail['id'] = $hostValues->user_id;
                $coHostDetail['profile'] = (empty($hostValues->user->profile) || $hostValues->user->profile == NULL) ? "" : asset('storage/profile/' . $hostValues->user->profile);
                $fullName = trim(($hostValues->user->firstname ?? '') . ' ' . ($hostValues->user->lastname ?? ''));
                $coHostDetail['name'] = !empty($fullName) ? $fullName : null;
                $coHostDetail['email'] = (empty($hostValues->user->email) || $hostValues->user->email == NULL) ? "" : $hostValues->user->email;
                $coHostDetail['phone_number'] = (empty($hostValues->user->phone_number) || $hostValues->user->phone_number == NULL) ? "" : $hostValues->user->phone_number;
                $coHosts[] = $coHostDetail;
            }
            $eventDetails['co_hosts'] = $coHosts;
            $eventDetails['event_location_name'] = $eventDetail->event_location_name;
            $eventDetails['address_1'] = $eventDetail->address_1;
            $eventDetails['address_2'] = $eventDetail->address_2;
            $eventDetails['state'] = $eventDetail->state;
            $eventDetails['zip_code'] = $eventDetail->zip_code;
            $eventDetails['city'] = $eventDetail->city;
            $eventDetails['latitude'] = (!empty($eventDetail->latitude) || $eventDetail->latitude != null) ? $eventDetail->latitude : "";
            $eventDetails['logitude'] = (!empty($eventDetail->logitude) || $eventDetail->logitude != null) ? $eventDetail->logitude : "";

            $eventsScheduleList = [];
            foreach ($eventDetail->event_schedule as $key => $value) {
                $event_name =  $value->activity_title;
                if ($value->type == '1') {
                    $event_name = "Start Event";
                } elseif ($value->type == '3') {
                    $event_name = "End Event";
                }
                $scheduleDetail['id'] = $value->id;
                $scheduleDetail['activity_title'] = $event_name;
                $scheduleDetail['start_time'] = ($value->start_time != null) ? $value->start_time : "";
                $scheduleDetail['end_time'] = ($value->end_time != null) ? $value->end_time : "";
                $scheduleDetail['type'] = $value->type;
                $eventsScheduleList[] = $scheduleDetail;
            }
            $eventDetails['event_schedule'] = $eventsScheduleList;

            $eventDetails['gift_registry'] = [];
            if (!empty($eventDetail->gift_registry_id)) {
                $giftregistry = explode(',', $eventDetail->gift_registry_id);
                $giftregistryData = EventGiftRegistry::whereIn('id', $giftregistry)->get();
                foreach ($giftregistryData as $value) {
                    $giftRegistryDetail['id'] = $value->id;
                    $giftRegistryDetail['registry_recipient_name'] = $value->registry_recipient_name;
                    $giftRegistryDetail['registry_link'] = $value->registry_link;
                    $eventDetails['gift_registry'][] = $giftRegistryDetail;
                }
            }
            $eventDetails['event_detail'] = "";
            if ($eventDetail->event_settings) {
                $eventData = [];
                if ($eventDetail->event_settings->allow_for_1_more == '1') {
                    $eventData[] = "Can Bring Guests ( limit " . $eventDetail->event_settings->allow_limit . ")";
                }
                if ($eventDetail->event_settings->adult_only_party == '1') {
                    $eventData[] = "Adults Only";
                }
                if ($eventDetail->rsvp_by_date_set == '1') {
                    $eventData[] = date('F d, Y', strtotime($eventDetail->rsvp_by_date));
                }
                if ($eventDetail->event_settings->podluck == '1') {
                    $eventData[] = "Event Potluck";
                }
                if ($eventDetail->event_settings->gift_registry == '1') {
                    $eventData[] = "Gift Registry";
                }
                if ($eventDetail->event_settings->events_schedule == '1') {
                    $eventData[] = "Event has Schedule";
                }
                if ($eventDetail->start_date != $eventDetail->end_date) {
                    $eventData[] = "Multiple Day Event";
                }
                if (!empty($eventData) || empty($eventData)) {
                    $eventData[] = date('F d, Y', strtotime($eventDetail->start_date));
                    $numberOfGuest = EventInvitedUser::where('event_id', $eventDetail->id)->count();
                    $eventData[] = "Number of guests : " . $numberOfGuest;
                }
                $eventDetails['event_detail'] = $eventData;
            }
            $eventDetails['total_limit'] = $eventDetail->event_settings->allow_limit;
            $eventInfo['guest_view'] = $eventDetails;
                    ///postlist
        $postList = [];
        $eventCreator = Event::where('id', $event)->first();
        $eventPostList = EventPost::query();
        $eventPostList->with(['user', 'post_image'])
            ->withCount([
                'event_post_comment' => function ($query) {
                    $query->where('parent_comment_id', NULL);
                },
                'event_post_reaction'
            ])
            ->where([
                'event_id' => $event,
                // 'is_in_photo_moudle' => '1'
            ])
            ->whereDoesntHave('post_control', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('post_control', 'hide_post');
            });
        // dd($eventPostList);
        $checkEventOwner = Event::where(['id' => $event, 'user_id' => $user->id])->first();
        // dd($checkEventOwner);
        if ($checkEventOwner == null) {
            // dd(1);
            $eventPostList = $eventPostList->where(function ($query) use ($user, $event) {
                $query->where('user_id', $user->id)
                    ->orWhereHas('event.event_invited_user', function ($subQuery) use ($user, $event) {
                        $subQuery->whereHas('user', function ($userQuery) {
                            $userQuery->where('app_user', '1'); // Only app users
                        })
                            ->where('event_id', $event)
                            ->where('user_id', $user->id)
                            ->where(function ($privacyQuery) {
                                // Various privacy conditions
                                $privacyQuery->where(function ($q) {
                                    $q->where('rsvp_d', '1')
                                        ->where('rsvp_status', '1')
                                        ->where('post_privacy', '2');
                                })
                                    ->orWhere(function ($q) {
                                        $q->where('rsvp_d', '1')
                                            ->where('rsvp_status', '0')
                                            ->where('post_privacy', '3');
                                    })
                                    ->orWhere(function ($q) {
                                        $q->where('rsvp_d', '0')
                                            ->where('post_privacy', '4');
                                    })
                                    ->orWhere(function ($q) {
                                        $q->where('post_privacy', '1');
                                    });
                            });
                    });
            });
        }

        // Execute the query and get the results
        $eventPostList = $eventPostList->orderBy('id', 'DESC')->get();
        // dd($eventPostList);
        // $totalPostWalls = $eventPostList->count();
        // $results = $eventPostList->paginate(10);
        // $total_page_of_eventPosts = ceil($totalPostWalls / $this->perPage);

        // dd($checkEventOwner);
        //if (!empty($checkEventOwner)) {
        //dd(1);
        // if (count($results) != 0) {
        if ($eventPostList != "") {
            foreach ($eventPostList as  $value) {
                $checkUserRsvp = checkUserAttendOrNot($event, $user->id);
                $ischeckEventOwner = Event::where(['id' => $event, 'user_id' => $user->id])->first();
                $postControl = PostControl::where(['user_id' => $user->id, 'event_id' => $event, 'event_post_id' => $value->id])->first();
                // dd($postControl);
                $count_kids_adult = EventInvitedUser::where(['event_id' => $event, 'user_id' => $user->id])
                    ->select('kids', 'adults', 'event_id', 'rsvp_status', 'user_id')
                    ->first();
                if ($postControl != null) {
                    if ($postControl->post_control == 'hide_post') {
                        continue;
                    }
                }
                $checkUserIsReaction = EventPostReaction::where(['event_id' => $event, 'event_post_id' => $value->id, 'user_id' => $user->id])->first();

                if (isset($value->post_type) && $value->post_type == '4' && $value->post_message != '') {
                    $EventPostMessageData = json_decode($value->post_message, true);
                    $rsvpstatus = (isset($value->post_type) && $value->post_type == '4' && $value->post_message != '') ? $value->post_message : $checkUserRsvp;
                    $kids = '0';
                    $adults = '0';
                    if (isset($EventPostMessageData['status'])) {
                        $rsvpstatus = (int)$EventPostMessageData['status'];
                    }
                    if (isset($EventPostMessageData['kids'])) {
                        $kids = (int)$EventPostMessageData["kids"];
                    }
                    if (isset($EventPostMessageData['adults'])) {
                        $adults = (int)$EventPostMessageData["adults"];
                    }
                } else {
                    $kids = isset($count_kids_adult['kids']) ? $count_kids_adult['kids'] : 0;
                    $rsvpstatus = (isset($value->post_type) && $value->post_type == '4' && $value->post_message != '') ? $value->post_message : $checkUserRsvp;
                    $adults = isset($count_kids_adult['adults']) ? $count_kids_adult['adults'] : 0;
                }
                $postsNormalDetail['id'] =  $value->id;
                $postsNormalDetail['user_id'] =  $user->id;
                $postsNormalDetail['is_host'] =  ($user->id == $user->id) ? 1 : 0;
                $postsNormalDetail['username'] =  $user->firstname . ' ' . $user->lastname;
                $postsNormalDetail['profile'] =  empty($user->profile) ? "" : asset('storage/profile/' . $user->profile);
                $postsNormalDetail['post_message'] = (empty($value->post_message) || $value->post_type == '4') ? "" :  $value->post_message;
                $postsNormalDetail['rsvp_status'] = (string)$rsvpstatus ?? "";
                $postsNormalDetail['kids'] = (int)$kids;
                $postsNormalDetail['adults'] = (int)$adults;
                $postsNormalDetail['location'] = $user->city != "" ? trim($user->city) . ($user->state != "" ? ', ' . $user->state : '') : "";
                $postsNormalDetail['post_type'] = $value->post_type;
                $postsNormalDetail['post_privacy'] = $value->post_privacy;
                $postsNormalDetail['created_at'] = $value->created_at;
                $postsNormalDetail['posttime'] = setpostTime($value->created_at);
                $postsNormalDetail['commenting_on_off'] = $value->commenting_on_off;
                $postsNormalDetail['post_image'] = [];
                $totalEvent =  Event::where('user_id', $user->id)->count();
                $totalEventPhotos =  EventPost::where(['user_id' => $user->id, 'post_type' => '1'])->count();
                $comments =  EventPostComment::where('user_id', $user->id)->count();
                $postsNormalDetail['user_profile'] = [
                    'id' => $user->id,
                    'profile' => empty($user->profile) ? "" : asset('storage/profile/' . $user->profile),
                    'bg_profile' => empty($user->bg_profile) ? "" : asset('storage/bg_profile/' . $user->bg_profile),
                    'gender' => ($user->gender != NULL) ? $user->gender : "",
                    'username' => $user->firstname . ' ' . $user->lastname,
                    'location' => ($user->city != NULL) ? $user->city : "",
                    'about_me' => ($user->about_me != NULL) ? $user->about_me : "",
                    'created_at' => empty($user->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($user->created_at))),
                    'total_events' => $totalEvent,
                    'visible' => $user->visible,
                    'total_photos' => $totalEventPhotos,
                    'comments' => $comments,
                    'message_privacy' => $user->message_privacy
                ];

                if ($value->post_type == '1' && !empty($value->post_image)) {
                    foreach ($value->post_image as $imgVal) {
                        $postMedia = [
                            'id' => $imgVal->id,
                            'media_url' => asset('storage/post_image/' . $imgVal->post_image),
                            'type' => $imgVal->type,
                            'thumbnail' => (isset($imgVal->thumbnail) && $imgVal->thumbnail != null) ?  asset('storage/thumbnails/' . $imgVal->thumbnail) : '',
                        ];
                        if ($imgVal->type == 'video' && isset($imgVal->duration) && $imgVal->duration !== "") {
                            $postMedia['video_duration'] = $imgVal->duration;
                        } else {
                            unset($postMedia['video_duration']);
                        }
                        $postsNormalDetail['post_image'][] = $postMedia;
                    }
                }
                $postsNormalDetail['total_poll_vote'] = 0;
                $postsNormalDetail['poll_duration'] = "";
                $postsNormalDetail['is_expired'] = false;
                $postsNormalDetail['poll_id'] = 0;
                $postsNormalDetail['poll_question'] = "";
                $postsNormalDetail['poll_option'] = [];
                if ($value->post_type == '2') {
                    // Poll
                    $polls = EventPostPoll::with('event_poll_option')->withCount('user_poll_data')->where(['event_id' => $event, 'event_post_id' => $value->id])->first();
                    $postsNormalDetail['total_poll_vote'] = $polls->user_poll_data_count;
                    $pollDura = getLeftPollTime($polls->updated_at, $polls->poll_duration);
                    $postsNormalDetail['poll_duration'] = $pollDura;
                    $leftDay = (int) preg_replace('/[^0-9]/', '', $polls->poll_duration);
                    $postsNormalDetail['is_expired'] =  ($pollDura == "") ? true : false;
                    $postsNormalDetail['poll_id'] = $polls->id;
                    $postsNormalDetail['poll_question'] = $polls->poll_question;
                    $postsNormalDetail['total_poll_duration'] = $polls->poll_duration;

                    foreach ($polls->event_poll_option as $optionValue) {
                        $optionData['id'] = $optionValue->id;
                        $optionData['option'] = $optionValue->option;
                        $optionData['total_vote'] =  "0%";
                        if (getOptionAllTotalVote($polls->id) != 0) {
                            $optionData['total_vote'] =  round(getOptionTotalVote($optionValue->id) / getOptionAllTotalVote($polls->id) * 100) . "%";
                        }
                        $optionData['is_poll_selected'] = checkUserGivePoll($user->id, $polls->id, $optionValue->id);
                        $postsNormalDetail['poll_option'][] = $optionData;
                    }
                }
                $postsNormalDetail['post_recording'] = empty($value->post_recording) ? "" : asset('storage/event_post_recording/' . $value->post_recording);
                $reactionList = getOnlyReaction($value->id);
                $postsNormalDetail['reactionList'] = $reactionList;
                $postsNormalDetail['total_comment'] = $value->event_post_comment_count;
                $postsNormalDetail['total_likes'] = $value->event_post_reaction_count;
                $postsNormalDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';
                $postsNormalDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";
                $postsNormalDetail['is_owner_post'] = ($value->user->id == $user->id) ? 1 : 0;
                $postsNormalDetail['is_mute'] =  0;
                if ($postControl != null) {
                    if ($postControl->post_control == 'mute') {
                        $postsNormalDetail['is_mute'] =  1;
                    }
                }
                $postCommentList = [];

                $postComment = getComments($value->id);
                foreach ($postComment as $commentVal) {




                    $commentInfo['id'] = $commentVal->id;

                    $commentInfo['event_post_id'] = $commentVal->event_post_id;

                    $commentInfo['comment'] = $commentVal->comment_text;

                    $commentInfo['user_id'] = $commentVal->user_id;

                    $commentInfo['username'] = $commentVal->user->firstname . ' ' . $commentVal->user->lastname;

                    $commentInfo['profile'] = (!empty($commentVal->user->profile)) ? asset('storage/profile/' . $commentVal->user->profile) : "";
                    // $postsNormalDetail['location'] = $value->user->city != "" ? trim($value->user->city) .($value->user->state != "" ? ', ' . $value->user->state : ''): "";
                    // $commentInfo['location'] = ($commentVal->user->city != NULL) ? $commentVal->user->city : "";
                    $commentInfo['location'] = $commentVal->user->city != "" ? trim($commentVal->user->city) . ($commentVal->user->state != "" ? ', ' . $commentVal->user->state : '') : "";

                    $commentInfo['comment_total_likes'] = $commentVal->post_comment_reaction_count;

                    $commentInfo['is_like'] = checkUserIsLike($commentVal->id, $user->id);

                    $commentInfo['total_replies'] = $commentVal->replies_count;

                    $commentInfo['created_at'] = $commentVal->created_at;
                    $commentInfo['posttime'] = setpostTime($commentVal->created_at);

                    $commentInfo['comment_replies'] = [];

                    foreach ($commentVal->replies as $reply) {
                        $mainParentId = (new EventPostComment())->getMainParentId($reply->parent_comment_id);

                        $replyCommentInfo['id'] = $reply->id;

                        $replyCommentInfo['event_post_id'] = $reply->event_post_id;
                        $replyCommentInfo['main_comment_id'] = $reply->main_parent_comment_id;

                        $replyCommentInfo['comment'] = $reply->comment_text;

                        $replyCommentInfo['user_id'] = $reply->user_id;

                        $replyCommentInfo['username'] = $reply->user->firstname . ' ' . $reply->user->lastname;

                        $replyCommentInfo['profile'] = (!empty($reply->user->profile)) ? asset('storage/profile/' . $reply->user->profile) : "";

                        // $replyCommentInfo['location'] = ($reply->user->city != NULL) ? $reply->user->city : "";
                        $replyCommentInfo['location'] =  $reply->user->city != "" ? trim($reply->user->city) . ($reply->user->state != "" ? ', ' . $reply->user->state : '') : "";

                        $replyCommentInfo['comment_total_likes'] = $reply->post_comment_reaction_count;

                        $replyCommentInfo['is_like'] = checkUserIsLike($reply->id, $user->id);

                        $replyCommentInfo['total_replies'] = $reply->replies_count;

                        $replyCommentInfo['created_at'] = $reply->created_at;
                        $replyCommentInfo['posttime'] = setpostTime($reply->created_at);
                        $commentInfo['comment_replies'][] = $replyCommentInfo;


                        $replyComment =  EventPostComment::with(['user'])->withcount('post_comment_reaction', 'replies')->where(['main_parent_comment_id' => $mainParentId, 'event_post_id' => $reply->event_post_id, 'parent_comment_id' => $reply->id])->orderBy('id', 'DESC')->get();

                        foreach ($replyComment as $childReplyVal) {

                            if ($childReplyVal->parent_comment_id != $childReplyVal->main_parent_comment_id) {

                                $totalReply = EventPostComment::withcount('post_comment_reaction')->where("parent_comment_id", $childReplyVal->id)->count();


                                $commentChildReply['id'] = $childReplyVal->id;

                                $commentChildReply['event_post_id'] = $childReplyVal->event_post_id;
                                $commentChildReply['main_comment_id'] = $childReplyVal->main_parent_comment_id;
                                $commentChildReply['comment'] = $childReplyVal->comment_text;
                                $commentChildReply['user_id'] = $childReplyVal->user_id;

                                $commentChildReply['username'] = $childReplyVal->user->firstname . ' ' . $childReplyVal->user->lastname;

                                $commentChildReply['profile'] = (!empty($childReplyVal->user->profile)) ? asset('storage/profile/' . $childReplyVal->user->profile) : "";
                                $commentChildReply['location'] = (!empty($childReplyVal->user->city)) ? $childReplyVal->user->city : "";

                                $commentChildReply['comment_total_likes'] = $childReplyVal->post_comment_reaction_count;

                                $commentChildReply['is_like'] = checkUserIsLike($childReplyVal->id, $user->id);

                                $commentChildReply['total_replies'] = $totalReply;
                                $commentChildReply['posttime'] = setpostTime($childReplyVal->created_at);
                                $commentChildReply['created_at'] = $childReplyVal->created_at;

                                $commentInfo['comment_replies'][] = $commentChildReply;

                                $replyChildComment =  EventPostComment::with(['user'])->withcount('post_comment_reaction', 'replies')->where(['main_parent_comment_id' => $mainParentId, 'event_post_id' => $childReplyVal->event_post_id, 'parent_comment_id' => $childReplyVal->id])->orderBy('id', 'DESC')->get();

                                foreach ($replyChildComment as $childInReplyVal) {

                                    if ($childInReplyVal->parent_comment_id != $childInReplyVal->main_parent_comment_id) {

                                        $totalReply = EventPostComment::withcount('post_comment_reaction')->where("parent_comment_id", $childInReplyVal->id)->count();


                                        $commentChildInReply['id'] = $childInReplyVal->id;

                                        $commentChildInReply['event_post_id'] = $childInReplyVal->event_post_id;
                                        $commentChildInReply['main_comment_id'] = $childInReplyVal->main_parent_comment_id;
                                        $commentChildInReply['comment'] = $childInReplyVal->comment_text;
                                        $commentChildInReply['user_id'] = $childInReplyVal->user_id;

                                        $commentChildInReply['username'] = $childInReplyVal->user->firstname . ' ' . $childInReplyVal->user->lastname;

                                        $commentChildInReply['profile'] = (!empty($childInReplyVal->user->profile)) ? asset('storage/profile/' . $childInReplyVal->user->profile) : "";
                                        $commentChildInReply['location'] = (!empty($childInReplyVal->user->city)) ? $childInReplyVal->user->city : "";

                                        $commentChildInReply['comment_total_likes'] = $childInReplyVal->post_comment_reaction_count;

                                        $commentChildInReply['is_like'] = checkUserIsLike($childInReplyVal->id, $user->id);

                                        $commentChildInReply['total_replies'] = $totalReply;
                                        $commentChildInReply['posttime'] = setpostTime($childInReplyVal->created_at);
                                        $commentChildInReply['created_at'] = $childInReplyVal->created_at;

                                        $commentInfo['comment_replies'][] = $commentChildInReply;
                                    }
                                }
                            }
                        }
                    }


                    $postCommentList[] = $commentInfo;
                }

                $postsNormalDetail['post_comment'] = $postCommentList;
                $postList[] = $postsNormalDetail;
            }
            // dd($postList);
        }
            $current_page = "photos";
            $login_user_id  = $user->id;
            return view('layout', compact('page', 'js','postList' ,'title', 'event', 'login_user_id', 'photos','firstname','lastname','eventDetails', 'postPhotoList', 'current_page')); // return compact('eventInfo');
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['status' => 0, 'message' => 'db error']);
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }



    function setpostTime($dateTime)
    {
        $commentDateTime = $dateTime;
        $commentTime = Carbon::parse($commentDateTime);
        $timeAgo = $commentTime->diffForHumans();
        return $timeAgo;
    }

    public function createEventPost(Request $request)
    {
        // dd($request);
        $user = Auth::guard('web')->user()->id;

        // Create new event post
        $createEventPost = new EventPost();
        $createEventPost->event_id = $request->event_id;
        $createEventPost->user_id = $user;
        $createEventPost->post_message =  $request->postContent; // Placeholder, update as necessary
        $createEventPost->post_privacy = $request->post_privacys; // Example: public post
        $createEventPost->post_type = "1"; // Define post type
        $createEventPost->commenting_on_off =  $request->commenting_on_off;; // Comments allowed
        $createEventPost->is_in_photo_moudle = "1"; // Whether the post contains photos
        $createEventPost->save();

        // Check if files were uploaded
        if ($createEventPost->id && $request->hasFile('files')) {
            $postFiles = $request->file('files'); // Get the uploaded files
            $imageUrls = [];
            $videoCount = 0;
            $imageCount = 0;

            foreach ($postFiles as $key => $postFile) {
                $fileName = time() . $key . '_' . $postFile->getClientOriginalName();

                // Save file to storage/app/public/post_image/
                $postFile->move(public_path('storage/post_image'), $fileName);


                $checkIsImageOrVideo = checkIsImageOrVideo($postFile); // Assuming this is a helper function
                $duration = "";
                $thumbName = "";

                // Process video
                if ($checkIsImageOrVideo == 'video') {
                    $duration = getVideoDuration($postFile); // Assuming this is a helper function
                    $thumbName = genrate_thumbnail($fileName, $createEventPost->id);
                    $postFile->move(public_path('storage/post_image/'), $fileName);
                }

                //     // Process image



                // Count images and videos
                if ($checkIsImageOrVideo == 'video') {
                    $videoCount++;
                } else {
                    $imageCount++;
                }

                // Save post image
                $eventPostImage = new EventPostImage();
                $eventPostImage->event_id = $request->event_id;
                $eventPostImage->event_post_id = $createEventPost->id;
                $eventPostImage->post_image = $fileName;
                $eventPostImage->duration = $duration;
                $eventPostImage->type = $checkIsImageOrVideo;
                $eventPostImage->thumbnail = $thumbName;
                $eventPostImage->save();
            }

            return redirect()->back()->with('success', 'Event post uploded successfully!');
        }

        return redirect()->back()->with('success', 'Event Post created successfully!');
    }

    public function fetchPost(Request $request)
    {
        $user = Auth::guard('web')->user();
        $photoId = $request->id;
        $eventId = $request->event_id;

        // Fetch photo details from the database
        $getPhotoList = EventPost::query();
        $getPhotoList->with(['user', 'event_post_reaction', 'post_image'])
            ->withCount([
                'event_post_reaction',
                'post_image',
                'event_post_comment' => function ($query) {
                    $query->where('parent_comment_id', NULL);
                }
            ])
            ->where(['event_id' => $eventId, 'post_type' => '1', 'id' => $photoId])
            ->orderBy('id', 'desc');

        $results = $getPhotoList->get();

        if ($results->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Photo details not found.'
            ]);
        }

        $postPhotoList = [];

        foreach ($results as $value) {
            $ischeckEventOwner = Event::where(['id' => $eventId, 'user_id' => $user->id])->exists();
            $postControl = PostControl::where([
                'user_id' => $user->id,
                'event_id' => $eventId,
                'event_post_id' => $value->id
            ])->first();

            // Skip hidden posts
            if ($postControl && $postControl->post_control === 'hide_post') {
                continue;
            }

            $postPhotoDetail = [
                'user_id' => $value->user->id,
                'is_own_post' => ($value->user->id == $user->id) ? "1" : "0",
                'is_host' => $ischeckEventOwner ? 1 : 0,
                'firstname' => $value->user->firstname,
                'lastname' => $value->user->lastname,
                'location' => $value->user->city . ', ' . $value->user->state,

                'profile' => (!empty($value->user->profile)) ? asset('storage/profile/' . $value->user->profile) : "",
                'is_reaction' => EventPostReaction::where(['user_id' => $user->id, 'event_post_id' => $value->id])->exists() ? '1' : '0',
                'self_reaction' => EventPostReaction::where(['user_id' => $user->id, 'event_post_id' => $value->id])->value('reaction') ?? "",
                'event_id' => $value->event_id,
                'id' => $value->id,
                'post_message' => $value->post_message ?? "",
                'post_time' => $this->setpostTime($value->updated_at),
                'is_in_photo_moudle' => $value->is_in_photo_moudle,
                'mediaData' => [],
                'total_media' => ($value->post_image_count > 1) ? "+" . ($value->post_image_count - 1) : "",
                'reactionList' => getReaction($value->id)->pluck('reaction')->toArray(),
                'total_likes' => $value->event_post_reaction_count,
                'total_comments' => $value->event_post_comment_count
            ];

            if (!empty($value->post_image)) {
                $photoVideoData = [];
                foreach ($value->post_image as $val) {
                    $photoVideoData[] = [
                        'id' => $val->id,
                        'event_post_id' => $val->event_post_id,
                        'post_media' => (!empty($val->post_image)) ? asset('storage/post_image/' . $val->post_image) : "",
                        'thumbnail' => (!empty($val->thumbnail)) ? asset('storage/thumbnails/' . $val->thumbnail) : "",
                        'type' => $val->type
                    ];
                }
                $postPhotoDetail['mediaData'] = $photoVideoData;
            }
            $letestComment = EventPostComment::with('user')->withCount('post_comment_reaction', 'replies')
            ->where(['event_post_id' => $value->id, 'parent_comment_id' => NULL])->get();
        //  ->orderBy('id', 'DESC');
        // ->limit(1)
        // ->first();
        // dd($letestComment);
        $postPhotoDetailcomment=[];
        foreach ($letestComment as $values) {
            // Setting up the latest comment data
            $postCommentList = [
                'id' => $values->id,
                'event_post_id' => $values->event_post_id,
                'comment' => $values->comment_text,
                'media' => (!empty($values->media)) ? asset('storage/comment_media/' . $values->media) : "",
                'user_id' => $values->user_id,
                'username' => $values->user->firstname . ' ' . $values->user->lastname,
                'profile' => (!empty($values->user->profile)) ? asset('storage/profile/' . $values->user->profile) : "",
                'comment_total_likes' => $values->post_comment_reaction_count,
                'location' => $values->user->city . ($values->user->state ? ', ' . $values->user->state : ''),
                'is_like' => 0, // Adjust based on the user's like status
                'created_at' => $values->created_at,
                'total_replies' => $values->replies_count,
                'posttime' => setpostTime($values->created_at),
                'comment_replies' => []
            ];

            $postPhotoDetailcomment[] = $postCommentList;
        }
        $postPhotoDetail['latest_comment'] = $postPhotoDetailcomment;
            $postPhotoList[] = $postPhotoDetail;
        }

        return response()->json([
            'status' => 'success',
            'data' => $postPhotoList
        ]);
    }

    public function userPostLikeDislike(Request $request)
    {
        $user = Auth::guard('web')->user();

        // Check if user has already reacted to this post
        $checkReaction = EventPostReaction::where([
            'event_id' => $request['event_id'],
            'event_post_id' => $request['event_post_id'],
            'user_id' => $user->id
        ])->first();

        // Convert the emoji reaction to Unicode
        $reaction_unicode = sprintf('\u{%X}', mb_ord($request['reaction'], 'UTF-8'));
        $unicode = strtoupper(bin2hex(mb_convert_encoding($request['reaction'], 'UTF-32', 'UTF-8')));

        if (!$checkReaction) {
            // User has not reacted yet, insert the reaction
            $event_post_reaction = new EventPostReaction;
            $event_post_reaction->event_id = $request['event_id'];
            $event_post_reaction->event_post_id = $request['event_post_id'];
            $event_post_reaction->user_id = $user->id;
            $event_post_reaction->reaction = $reaction_unicode;
            $event_post_reaction->unicode = $unicode;
            $event_post_reaction->save();

            $message = "Post liked by you";
            $isReaction = 1;
        } else {
            // User has already reacted
            if ($checkReaction->unicode != $unicode) {
                // Reaction is different from current, update it
                $checkReaction->reaction = $reaction_unicode;
                $checkReaction->unicode = $unicode;
                $checkReaction->save();
                $message = "Post liked by you";
                $isReaction = 1;
            } else {
                // Same reaction, dislike the post
                $checkReaction->delete();
                $removeNotification = Notification::where([
                    'event_id' => $request['event_id'],
                    'sender_id' => $user->id,
                    'post_id' => $request['event_post_id'],
                    'notification_type' => 'like_post'
                ])->first();

                if ($removeNotification) {
                    $removeNotification->delete();
                }
                $message = "Post Disliked by you";
                $isReaction = 0;
            }
        }

        // Get total count of reactions
        $counts = EventPostReaction::where([
            'event_id' => $request['event_id'],
            'event_post_id' => $request['event_post_id']
        ])->count();

        // Get the top 3 most common reactions
        $total_counts = EventPostReaction::where([
            'event_id' => $request['event_id'],
            'event_post_id' => $request['event_post_id']
        ])
            ->select('reaction', 'unicode', DB::raw('COUNT(*) as count'))
            ->groupBy('reaction', 'unicode')
            ->orderByDesc('count')
            ->take(3)
            ->pluck('reaction')
            ->toArray();

        // Get post reactions with user details
        $postReactions = getReaction($request['event_post_id']);
        $postReaction = [];

        foreach ($postReactions as $reactionVal) {
            $reactionInfo = [
                'id' => $reactionVal->id,
                'event_post_id' => $reactionVal->event_post_id,
                'reaction' => $reactionVal->reaction,
                'user_id' => $reactionVal->user_id,
                'username' => $reactionVal->user->firstname . ' ' . $reactionVal->user->lastname,
                'location' => $reactionVal->user->city ?? "",
                'profile' => !empty($reactionVal->user->profile) ? asset('storage/profile/' . $reactionVal->user->profile) : ""
            ];

            $postReaction[] = $reactionInfo;
        }

        return response()->json([
            'status' => 1,
            'is_reaction' => $isReaction,
            'message' => $message,
            'count' => $counts,
            'post_reaction' => $postReaction,
            'reactionList' => $total_counts
        ]);
    }




    public function deletePost(Request $request)
    {
        $user = Auth::guard('web')->user();

        $id = $request->input('event_post_id');
        $record = EventPost::find($id);

        if ($record) {
            $record->delete();
            return response()->json([
                'success' => true,
                'message' => 'Event post deleted successfully!'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Event post not found or could not be deleted.'
            ]);
        }
    }

    public function userPostComment(Request $request)

    {
        // dd($request);
        $user  = Auth::guard('web')->user();
        $rawData = $request->getContent();




        EventPostComment::where(['event_id' => $request['event_id'], 'event_post_id' => $request['event_post_id'], 'user_id' => $user->id])->count();

        $event_post_comment = new EventPostComment;

        $event_post_comment->event_id = $request['event_id'];

        $event_post_comment->event_post_id = $request['event_post_id'];

        $event_post_comment->user_id = $user->id;

        $event_post_comment->comment_text = $request['comment'];

        if (isset($request['media']) && !empty($request['media'])) {
            $image = $request['media'];

            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('storage/comment_media'), $imageName);
            $event_post_comment->media = $imageName;
            $event_post_comment->type = $request['type'];
        }

        $event_post_comment->save();

        // $notificationParam = [
        //     'sender_id' => $user->id,
        //     'event_id' => $request['event_id'],
        //     'post_id' => $request['event_post_id'],
        //     'comment_id' => $event_post_comment->id
        // ];

        // sendNotification('comment_post', $notificationParam);



        $postComment = getComments($request['event_post_id']);

        $letestComment =  EventPostComment::with('user')->withcount('post_comment_reaction', 'replies')->where(['event_post_id' => $request['event_post_id'], 'parent_comment_id' => NULL])->orderBy('id', 'DESC')->limit(1)->first();



        $postCommentList = [
            'id' => $letestComment->id,

            'event_post_id' => $letestComment->event_post_id,

            'comment' => $letestComment->comment_text,
            'media' => (!empty($letestComment->media) && $letestComment->media != NULL) ? asset('storage/comment_media/' . $letestComment->media) : "",

            'user_id' => $letestComment->user_id,

            'username' => $letestComment->user->firstname . ' ' . $letestComment->user->lastname,

            'profile' => (!empty($letestComment->user->profile)) ? asset('storage/profile/' . $letestComment->user->profile) : "",

            'comment_total_likes' => $letestComment->post_comment_reaction_count,

            'location' => $letestComment->user->city != "" ? trim($letestComment->user->city) . ($letestComment->user->state != "" ? ', ' . $letestComment->user->state : '') : "",
            // 'is_like' => checkUserPhotoIsLike($letestComment->id, $user->id),
            'is_like' => 0,

            'created_at' => $letestComment->created_at,

            'total_replies' => $letestComment->replies_count,

            'posttime' => setpostTime($letestComment->created_at),
            'comment_replies' => []
        ];

        return response()->json(['success' => true, 'total_comments' => count($postComment), 'data' => $postCommentList, 'message' => "Post commented by you"]);
    }
    public function userPostCommentReply(Request $request)

    {
        // dd(1);
        $user  = Auth::guard('web')->user();



            $parentCommentId =  $request['parent_comment_id'];
            $mainParentId = (new EventPostComment())->getMainParentId($parentCommentId);

            $event_post_comment = new EventPostComment;
            $event_post_comment->event_id = $request['event_id'];
            $event_post_comment->event_post_id = $request['event_post_id'];
            $event_post_comment->user_id = $user->id;
            $event_post_comment->parent_comment_id = $request['parent_comment_id'];
            $event_post_comment->main_parent_comment_id = $mainParentId;
            $event_post_comment->comment_text = $request['comment'];
            if (isset($request['media']) && !empty($request['media'])) {
                $image = $request['media'];
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('storage/comment_media'), $imageName);
                $event_post_comment->media = $imageName;
                $event_post_comment->type = $request['type'];
            }
            $event_post_comment->save();


            // $notificationParam = [
            //     'sender_id' => $user->id,
            //     'event_id' => $request['event_id'],
            //     'post_id' => $request['event_post_id'],
            //     'comment_id' => $event_post_comment->id
            // ];
            // sendNotification('reply_on_comment_post', $notificationParam);

            $replyList =   EventPostComment::with(['user', 'replies' => function ($query) {
                $query->withcount('post_comment_reaction', 'replies')->orderBy('id', 'DESC');
            }])->withcount('post_comment_reaction', 'replies')->where(['id' => $mainParentId, 'event_post_id' => $request['event_post_id']])->orderBy('id', 'DESC')->first();

            $commentInfo['id'] = $replyList->id;
            $commentInfo['event_post_id'] = $replyList->event_post_id;
            $commentInfo['comment'] = $replyList->comment_text;
            $commentInfo['user_id'] = $replyList->user_id;
            $commentInfo['username'] = $replyList->user->firstname . ' ' . $replyList->user->lastname;
            $commentInfo['location'] = $replyList->user->city != "" ? trim($replyList->user->city) . ($replyList->user->state != "" ? ', ' . $replyList->user->state : '') : "";
            $commentInfo['profile'] = (!empty($replyList->user->profile)) ? asset('storage/profile/' . $replyList->user->profile) : "";
            $commentInfo['comment_total_likes'] = $replyList->post_comment_reaction_count;
            $commentInfo['is_like'] = checkUserIsLike($replyList->id, $user->id);
            $commentInfo['created_at'] = $replyList->created_at;
            $commentInfo['total_replies'] = $replyList->replies_count;
            $commentInfo['posttime'] = setpostTime($replyList->created_at);
            $commentInfo['comment_replies'] = [];

            if (!empty($replyList->replies)) {
                foreach ($replyList->replies as $replyVal) {
                    $totalReply = EventPostComment::withcount('post_comment_reaction')->where("parent_comment_id", $replyVal->id)->count();
                    $commentReply['id'] = $replyVal->id;
                    $commentReply['event_post_id'] = $replyVal->event_post_id;
                    $commentReply['comment'] = $replyVal->comment_text;
                    $commentReply['user_id'] = $replyVal->user_id;
                    $commentReply['username'] = $replyVal->user->firstname . ' ' . $replyVal->user->lastname;
                    $commentReply['profile'] = (!empty($replyVal->user->profile)) ? asset('storage/profile/' . $replyVal->user->profile) : "";
                    // $commentReply['location'] = (!empty($replyVal->user->city)) ? $replyVal->user->city : "";
                    $commentReply['location'] = $replyVal->user->city != "" ? trim($replyVal->user->city) . ($replyVal->user->state != "" ? ', ' . $replyVal->user->state : '') : "";
                    $commentReply['comment_total_likes'] = $replyVal->post_comment_reaction_count;
                    $commentReply['main_comment_id'] = $replyVal->main_parent_comment_id;
                    $commentReply['is_like'] = checkUserIsLike($replyVal->id, $user->id);
                    $commentReply['total_replies'] = $totalReply;
                    $commentReply['posttime'] = setpostTime($replyVal->created_at);
                    $commentReply['created_at'] = $replyVal->created_at;
                    $commentInfo['comment_replies'][] = $commentReply;
                    $replyComment =  EventPostComment::with(['user'])->withcount('post_comment_reaction', 'replies')->where(['main_parent_comment_id' => $mainParentId, 'event_post_id' => $request['event_post_id'], 'parent_comment_id' => $replyVal->id])->orderBy('id', 'DESC')->get();

                    foreach ($replyComment as $childReplyVal) {
                        if ($childReplyVal->parent_comment_id != $childReplyVal->main_parent_comment_id) {
                            $totalReply = EventPostComment::withcount('post_comment_reaction')->where("parent_comment_id", $childReplyVal->id)->count();
                            $commentChildReply['id'] = $childReplyVal->id;
                            $commentChildReply['event_post_id'] = $childReplyVal->event_post_id;
                            $commentChildReply['comment'] = $childReplyVal->comment_text;
                            $commentChildReply['user_id'] = $childReplyVal->user_id;
                            $commentChildReply['username'] = $childReplyVal->user->firstname . ' ' . $childReplyVal->user->lastname;
                            $commentChildReply['profile'] = (!empty($childReplyVal->user->profile)) ? asset('storage/profile/' . $childReplyVal->user->profile) : "";
                            $commentChildReply['location'] = $childReplyVal->user->city != "" ? trim($childReplyVal->user->city) . ($childReplyVal->user->state != "" ? ', ' . $childReplyVal->user->state : '') : "";
                            $commentChildReply['comment_total_likes'] = $childReplyVal->post_comment_reaction_count;
                            $commentReply['main_comment_id'] = $childReplyVal->main_parent_comment_id;
                            $commentChildReply['is_like'] = checkUserIsLike($childReplyVal->id, $user->id);
                            $commentChildReply['total_replies'] = $totalReply;
                            $commentChildReply['posttime'] = setpostTime($childReplyVal->created_at);
                            $commentChildReply['created_at'] = $childReplyVal->created_at;
                            $commentInfo['comment_replies'][] = $commentChildReply;
                            $replyChildComment =  EventPostComment::with(['user'])->withcount('post_comment_reaction', 'replies')->where(['main_parent_comment_id' => $mainParentId, 'event_post_id' => $request['event_post_id'], 'parent_comment_id' => $childReplyVal->id])->orderBy('id', 'DESC')->get();

                            foreach ($replyChildComment as $childInReplyVal) {
                                if ($childInReplyVal->parent_comment_id != $childInReplyVal->main_parent_comment_id) {
                                    $totalReply = EventPostComment::withcount('post_comment_reaction')->where("parent_comment_id", $childInReplyVal->id)->count();
                                    $commentChildInReply['id'] = $childInReplyVal->id;
                                    $commentChildInReply['event_post_id'] = $childInReplyVal->event_post_id;
                                    $commentChildInReply['comment'] = $childInReplyVal->comment_text;
                                    $commentChildInReply['user_id'] = $childInReplyVal->user_id;
                                    $commentChildInReply['username'] = $childInReplyVal->user->firstname . ' ' . $childInReplyVal->user->lastname;
                                    $commentChildInReply['profile'] = (!empty($childInReplyVal->user->profile)) ? asset('storage/profile/' . $childInReplyVal->user->profile) : "";
                                    $commentChildInReply['location'] = (!empty($childInReplyVal->user->city)) ? $childInReplyVal->user->city : "";
                                    $commentChildInReply['comment_total_likes'] = $childInReplyVal->post_comment_reaction_count;
                                    $commentReply['main_comment_id'] = $childInReplyVal->main_parent_comment_id;
                                    $commentChildInReply['is_like'] = checkUserIsLike($childInReplyVal->id, $user->id);
                                    $commentChildInReply['total_replies'] = $totalReply;
                                    $commentChildInReply['posttime'] = setpostTime($childInReplyVal->created_at);
                                    $commentChildInReply['created_at'] = $childInReplyVal->created_at;
                                    $commentInfo['comment_replies'][] = $commentChildInReply;
                                }
                            }
                        }
                    }
                }
            }

            return response()->json(['success' => true, 'total_comments' => 0, 'data' => $commentInfo, 'message' => "Post comment replied by you"]);

    }

    public function postControl(Request $request)
    {

        $user  = Auth::guard('api')->user();
        $rawData = $request->getContent();
        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        try {

            DB::beginTransaction();

            $checkIsPostControl = PostControl::where(['event_id' => $input['event_id'], 'user_id' => $user->id, 'event_post_id' => $input['event_post_id']])->first();
            if ($checkIsPostControl == null) {
                $setPostControl = new PostControl;

                $setPostControl->event_id = $input['event_id'];
                $setPostControl->user_id = $user->id;
                $setPostControl->event_post_id = $input['event_post_id'];
                $setPostControl->post_control = $input['post_control'];
                $setPostControl->save();
            } else {
                $checkIsPostControl->post_control = $input['post_control'];
                $checkIsPostControl->save();
            }
            DB::commit();
            $message = "";
            if ($input['post_control'] == 'hide_post') {
                $message = "Post is hide from your wall";
            } else if ($input['post_control'] == 'unhide_post') {
                $message = "Post is unhide";
            } else if ($input['post_control'] == 'mute') {
                $message = "Mute every post from this user will post";
            } else if ($input['post_control'] == 'unmute') {
                $message = "Unmuted every post from this user will post";
            } else if ($input['post_control'] == 'report') {
                $reportCreate = new UserReportToPost;
                $reportCreate->event_id = $input['event_id'];
                $reportCreate->user_id =  $user->id;
                $reportCreate->report_type = $input['report_type'];
                $reportCreate->report_description = $input['report_description'];
                $reportCreate->event_post_id = $input['event_post_id'];
                $reportCreate->save();

                $savedReportId =  $reportCreate->id;
                $createdAt = $reportCreate->created_at;
                $message = "Reported to admin for this post";

                $support_email = env('SUPPORT_MAIL');

                $getName = UserReportToPost::with(['users', 'events'])->where('id', $savedReportId)->first();
                $data = [
                    'reporter_username' => $getName->users->firstname . ' ' . $getName->users->lastname,
                    'event_name' => $getName->events->event_name,
                    'report_type' => $getName->report_type,
                    'report_description' => ($getName->report_description != "") ? $getName->report_description : "",
                    'report_time' => Carbon::parse($createdAt)->format('Y-m-d h:i A'),
                    'report_from' => "post"
                ];

                Mail::send('emails.reportEmail', ['userdata' => $data], function ($messages) use ($support_email) {
                    $messages->to($support_email)
                        ->subject('Post Report Mail');
                });
            }
            return response()->json(['status' => 1, 'type' => $input['post_control'], 'message' => $message]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        }
        // catch (\Exception $e) {

        //     return response()->json(['status' => 0, 'message' => "something went wrong"]);
        // }
    }
}
