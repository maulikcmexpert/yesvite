<?php

namespace App\Http\Controllers;

use App\Models\{
    Event,
    User,
    EventPost,
    EventPostComment,
    EventInvitedUser,
    Notification,
    EventImage,
    EventGiftRegistry,
    EventPostImage,
    EventPotluckCategory,
    EventPotluckCategoryItem,
    UserPotluckItem,
    PostControl,
    EventPostReaction
};

use App\Models\contact_sync;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as Exception;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventGuestController extends Controller
{
    public function index(string $id)
    {
        $title = 'event guest';
        $page = 'front.event_wall.event_guest';
        $user  = Auth::guard('web')->user();
        $js = ['event_guest','post_like_comment','guest_rsvp'];
        $event = decrypt($id);
        if ($event == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        try {
            $eventDetail = Event::with(['user', 'event_image', 'event_schedule', 'event_settings' => function ($query) {
                $query->select('event_id', 'podluck', 'allow_limit', 'adult_only_party');
            }, 'event_invited_user' => function ($query) {
                $query->where('is_co_host', '0')->with('user');
            }])->where('id', $event)->first();
        //   {{  dd($eventDetail);}}
            $guestView = [];
            $eventDetails['id'] = $eventDetail->id;
            $eventDetails['event_images'] = [];
            if (count($eventDetail->event_image) != 0) {
                foreach ($eventDetail->event_image as $values) {
                    $eventDetails['event_images'][] = asset('storage/event_images/' . $values->image);
                }
            }
            $event_comments = EventPostComment::where(['event_id' => $eventDetail->id])->count();
            $eventDetails['total_event_comments']=$event_comments;
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
            $eventDetails['host_id'] = $eventDetail->user_id;
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
            $sendFaildInvites = EventInvitedUser::where(['event_id' => $event, 'invitation_sent' => '9'])->get();

            $faildInviteList = [];
            foreach ($sendFaildInvites as $value) {
                $userDetail = [];
                if ($value->user_id != '') {
                    $userDetail['id'] = $value->user->id;
                    $userDetail['first_name'] = (!empty($value->user->firstname) || $value->user->firstname != NULL) ? $value->user->firstname : "";
                    $userDetail['last_name'] = (!empty($value->user->lastname) || $value->user->lastname != NULL) ? $value->user->lastname : "";
                    $userDetail['profile'] = (!empty($value->user->profile) || $value->user->profile != NULL) ? asset('storage/profile/' . $value->user->profile) : "";
                    $userDetail['email'] = (!empty($value->user->email)) ? $value->user->email : "";
                    $userDetail['country_code'] = (string)$value->user->country_code;
                    $userDetail['phone_number'] = (!empty($value->user->phone_number)) ? $value->user->phone_number : "";
                    $userDetail['app_user'] = $value->user->app_user;
                    $userDetail['prefer_by'] = $value->prefer_by;
                } else if ($value->sync_id != '') {
                    $userDetail['id'] = $value->contact_sync->id;
                    $userDetail['first_name'] = (!empty($value->contact_sync->firstName) || $value->contact_sync->firstName != NULL) ? $value->contact_sync->firstName : "";
                    $userDetail['last_name'] = (!empty($value->contact_sync->lastName) || $value->contact_sync->lastName != NULL) ? $value->contact_sync->lastName : "";
                    $userDetail['profile'] = (!empty($value->contact_sync->photo) || $value->contact_sync->photo != NULL) ? $value->contact_sync->photo : "";
                    $userDetail['email'] = (!empty($value->contact_sync->email)) ? $value->contact_sync->email : "";
                    $userDetail['country_code'] = '';
                    $userDetail['phone_number'] = (!empty($value->contact_sync->phoneWithCode)) ? $value->contact_sync->phoneWithCode : "";
                    $userDetail['app_user'] = $value->contact_sync->isAppUser;
                    $userDetail['prefer_by'] = $value->prefer_by;
                }
                $faildInviteList[] = $userDetail;
            }

            // Add the failed invitation list to the event details
            $eventDetails['failed_invites'] = $faildInviteList;  // Add this line
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
                // dd(1);
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
                    // $guestData = EventInvitedUser::with('user') // Eager load the related 'user' model
                    //     ->where(['event_id'=>$eventDetail->id,'is_co_host'=>'0'])
                    //     ->get();

                    $guestData=getInvitedUsersList($eventDetail->id);



                    $eventData[] = "Number of guests : " . $numberOfGuest;
                    $eventData['guests'] = $guestData;
                }
                $eventDetails['event_detail'] = $eventData;

            }
            //  dd($eventDetails['event_detail']);

            $eventDetails['total_limit'] = $eventDetail->event_settings->allow_limit;
            $eventInfo['guest_view'] = $eventDetails;


            
            $eventattending = EventInvitedUser::
                // whereHas('user', function ($query) {
                //     $query->where('app_user', '1');
                // })->
                where(['rsvp_status' => '1', 'event_id' => $eventDetail->id, 'is_co_host' => '0'])->count();

            $totalEnvitedUser = EventInvitedUser::
            // whereHas('user', function ($query) {

            //     // $query->where('app_user', '1');
            // })->
            where(['event_id' => $eventDetail->id, 'is_co_host' => '0'])->count();

            // $eventattending = EventInvitedUser::whereHas('user', function ($query) {

            //     $query->where('app_user', '1');
            // })->where(['rsvp_status' => '1', 'event_id' => $eventDetail->id])->count();

            // $eventNotComing = EventInvitedUser::whereHas('user', function ($query) {

            //     $query->where('app_user', '1');
            // })->where(['rsvp_status' => '0', 'event_id' => $eventDetail->id])->count();



            $eventNotComing = EventInvitedUser::
            // whereHas('user', function ($query) {
            //     $query->where('app_user', '1');
            // })->
            where(['rsvp_d' => '1', 'is_co_host' => '0', 'rsvp_status' => '0', 'event_id' => $eventDetail->id])->count();



            $todayrsvprate = EventInvitedUser::
            // whereHas('user', function ($query) {

            //     // $query->where('app_user', '1');
            // })->
            where(['rsvp_status' => '1','is_co_host' => '0', 'event_id' => $eventDetail->id])

                ->whereDate('created_at', '=', date('Y-m-d'))

                ->count();



            // $pendingUser = EventInvitedUser::whereHas('user', function ($query) {

            //     // $query->where('app_user', '1');
            // })->where(['event_id' => $eventDetail->id, 'rsvp_d' => '0', 'is_co_host' => '0'])->count();
            // // where(['event_id' => $eventDetail->id, 'rsvp_d' => '0', 'is_co_host' => '0'])->count();

            $pendingUser = EventInvitedUser::
            // whereHas('user', function ($query) {
            //     $query->where('app_user', '1');
            // })->
            where(['event_id' => $eventDetail->id, 'rsvp_d' => '0', 'is_co_host' => '0'])->count();



            $adults = EventInvitedUser::
            // whereHas('user', function ($query) {

            //     // $query->where('app_user', '1');
            // })->
            where(['event_id' => $eventDetail->id,'is_co_host' => '0', 'rsvp_status' => '1'])->sum('adults');

            $kids = EventInvitedUser::
            // whereHas('user', function ($query) {

            //     // $query->where('app_user', '1');
            // })->
            where(['event_id' => $eventDetail->id,'is_co_host' => '0', 'rsvp_status' => '1'])->sum('kids');


            $eventAboutHost['attending'] = $adults + $kids;



            $eventAboutHost['adults'] = (int)$adults;

            $eventAboutHost['kids'] = (int)$kids;



            $eventAboutHost['not_attending'] = $eventNotComing;

            $eventAboutHost['pending'] = $pendingUser;

            $eventAboutHost['comment'] = EventPostComment::where(['event_id' => $eventDetail->id, 'user_id' => $user->id])->count();
            $total_photos = EventPostImage::where(['event_id' => $eventDetail->id])->count();

            $eventAboutHost['photo_uploaded'] = $total_photos;

            $eventAboutHost['total_invite'] =  count(getEventInvitedUser($eventDetail->id));

            $eventAboutHost['invite_view_rate'] = EventInvitedUser::
            where(['event_id' => $eventDetail->id, 'read' => '1', 'is_co_host' => '0'])->count();

            $invite_view_percent = 0;
            if ($totalEnvitedUser != 0) {

                $invite_view_percent = EventInvitedUser::
                // whereHas('user', function ($query) {

                //     // $query->where('app_user', '1');
                // })->
                where(['event_id' => $eventDetail->id, 'read' => '1', 'is_co_host' => '0'])->count() / $totalEnvitedUser * 100;
            }

            $eventAboutHost['invite_view_percent'] = round($invite_view_percent, 2) . "%";

            $today_invite_view_percent = 0;
            if ($totalEnvitedUser != 0) {
                $today_invite_view_percent =   EventInvitedUser::
                // whereHas('user', function ($query) {

                //     // $query->where('app_user', '1');
                // })->
                where(['event_id' => $eventDetail->id, 'read' => '1' ,'is_co_host' => '0', 'event_view_date' => date('Y-m-d')])->count() / $totalEnvitedUser * 100;
            }

            $eventAboutHost['today_invite_view_percent'] = round($today_invite_view_percent, 2)  . "%";

            $eventAboutHost['rsvp_rate'] = $eventattending;

            $eventAboutHost['rsvp_rate_percent'] = ($totalEnvitedUser != 0) ? $eventattending / $totalEnvitedUser * 100 . "%" : 0 . "%";

            $eventAboutHost['today_upstick'] = ($totalEnvitedUser != 0) ? $todayrsvprate / $totalEnvitedUser * 100 . "%" : 0 . "%";

            $eventInfo['host_view'] = $eventAboutHost;

                ///postlist        ///postlist
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
                'is_in_photo_moudle' => '1'
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
                $checkUserRsvp = checkUserAttendOrNot($value->event_id, $value->user->id);
                $ischeckEventOwner = Event::where(['id' => $event, 'user_id' => $user->id])->first();
                $postControl = PostControl::where(['user_id' => $user->id, 'event_id' => $event, 'event_post_id' => $value->id])->first();
                // dd($postControl);
                $count_kids_adult = EventInvitedUser::where(['event_id' => $event, 'user_id' => $value->user->id])
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
                $postsNormalDetail['user_id'] =  $value->user->id;
                $postsNormalDetail['is_host'] =  ($value->user->id == $user->id) ? 1 : 0;
                $postsNormalDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;
                $postsNormalDetail['profile'] =  empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile);
                $postsNormalDetail['post_message'] = (empty($value->post_message) || $value->post_type == '4') ? "" :  $value->post_message;
                $postsNormalDetail['rsvp_status'] = (string)$rsvpstatus ?? "";
                $postsNormalDetail['kids'] = (int)$kids;
                $postsNormalDetail['adults'] = (int)$adults;
                $postsNormalDetail['location'] = $value->user->city != "" ? trim($value->user->city) . ($value->user->state != "" ? ', ' . $value->user->state : '') : "";
                $postsNormalDetail['post_type'] = $value->post_type;
                $postsNormalDetail['post_privacy'] = $value->post_privacy;
                $postsNormalDetail['created_at'] = $value->created_at;
                $postsNormalDetail['posttime'] = setpostTime($value->created_at);
                $postsNormalDetail['commenting_on_off'] = $value->commenting_on_off;
                $postsNormalDetail['post_image'] = [];
                $totalEvent =  Event::where('user_id', $value->user->id)->count();
                $totalEventPhotos =  EventPost::where(['user_id' => $value->user->id, 'post_type' => '1'])->count();
                $comments =  EventPostComment::where('user_id', $value->user->id)->count();
                $postsNormalDetail['user_profile'] = [
                    'id' => $value->user->id,
                    'profile' => empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile),
                    'bg_profile' => empty($value->user->bg_profile) ? "" : asset('storage/bg_profile/' . $value->user->bg_profile),
                    'gender' => ($value->user->gender != NULL) ? $value->user->gender : "",
                    'username' => $value->user->firstname . ' ' . $value->user->lastname,
                    'location' => ($value->user->city != NULL) ? $value->user->city : "",
                    'about_me' => ($value->user->about_me != NULL) ? $value->user->about_me : "",
                    'created_at' => empty($value->user->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($value->user->created_at))),
                    'total_events' => $totalEvent,
                    'visible' => $value->user->visible,
                    'total_photos' => $totalEventPhotos,
                    'comments' => $comments,
                    'message_privacy' => $value->user->message_privacy
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

            $login_user_id  = $user->id;
            $current_page = "guest";

            return view('layout', compact('page', 'title', 'event','postList' ,'js', 'eventDetails', 'postList','eventInfo', 'current_page', 'login_user_id')); // return compact('eventInfo');

        } catch (QueryException $e) {

            return response()->json(['status' => 0, 'message' => "error"]);
        }
    }

    function fetch_guest($id,$is_sync)
    {
        if($is_sync=="1"){
            $guest = EventInvitedUser::with('contact_sync')->findOrFail($id); 
            return response()->json([
                'id' => $guest->id,
                'user_id' => $guest->sync_id,
                'event_id'=> $guest->event_id,
                'firstname' => (!empty($guest->contact_sync->firstName) && $guest->contact_sync->firstName != NULL) ? $guest->contact_sync->firstName : "",
                'lastname' => (!empty($guest->contact_sync->lastName) && $guest->contact_sync->lastName != NULL) ? $guest->contact_sync->lastName : "",
                'email' =>  (!empty($guestVal->contact_sync->email) && $guestVal->contact_sync->email != NULL) ? $guestVal->contact_sync->email : "",
                // 'profile' =>(!empty($guestVal->contact_sync->photo) && $guestVal->contact_sync->photo != NULL) ? asset('storage/profile/' . $guestVal->contact_sync->photo) : "" ,
                'profile' => (!empty($guestVal->contact_sync->photo) && $guestVal->contact_sync->photo != NULL && preg_match('/\.(jpg|jpeg|png)$/i', basename($guestVal->contact_sync->photo))) 
                    ? asset('storage/profile/' . $guestVal->contact_sync->photo) 
                    : "",
                'adults' => $guest->adults,
                'kids' => $guest->kids,
                'rsvp_status' => $guest->rsvp_status,
                'is_sync'=>1
            ]);
        }else{
            $guest = EventInvitedUser::with('user')->findOrFail($id);
            return response()->json([
                'id' => $guest->id,
                'user_id' => $guest->user_id,
                'event_id'=> $guest->event_id,
                'firstname' => $guest->user->firstname!=""? $guest->user->firstname:"",
                'lastname' => $guest->user->lastname!=""?$guest->user->lastname:"",
                'email' => $guest->user->email,
                'profile' => $guest->user->profile ? asset('storage/profile/' . $guest->user->profile) : asset('images/default-profile.png'),
                'adults' => $guest->adults,
                'kids' => $guest->kids,
                'rsvp_status' => $guest->rsvp_status,
                'is_sync'=>0
            ]);
        }
      
    }

    public function updateRsvp(Request $request, $id)
    {
        // Validate the data
        $validated = $request->validate([
            'adults' => 'required|integer|min:0',
            'kids' => 'required|integer|min:0',
            'rsvp_status' => 'nullable',
        ]);

        // Find the existing guest by ID
        $guest = EventInvitedUser::find($id);
        // dd($guest);
        if ($guest) {
            // Update the guest's RSVP details
            $guest->adults = $validated['adults'];
            $guest->kids = $validated['kids'];
            $guest->rsvp_status = $validated['rsvp_status'];
            $guest->read = "1";
            $guest->rsvp_d = "1";


            // Save the updated data
            $guest->save();
            return response()->json([
                'success' => true,
                'message' => 'RSVP updated successfully',
                'adults' => $guest->adults,
                'kids' => $guest->kids,
                'guest_id' => $guest->id,
                'rsvp_status' => $guest->rsvp_status,
                'guest' =>   $guest
            ]);

            // Redirect back or return a success message

        }

        // Handle the case where guest is not found
        return redirect()->back()->with('success', 'RSVP updated successfully.');
    }


    public function removeGuestFromInvite(Request $request)
    {

        $user  = Auth::guard('web')->user();


            $getGuest = EventInvitedUser::where(['event_id' => $request['event_id'], 'user_id' => $request['user_id']])->first();
            if ($getGuest != null) {

                $checkNotificationdata = Notification::where(['event_id' => $request['event_id'], 'user_id' => $request['user_id']])->first();
                if ($checkNotificationdata != null) {
                    $checkNotificationdata->delete();
                }

                $getGuest->delete();
                return response()->json(['success' => true, 'message' => "Guest removed successfully"]);
            }

    }

    public function editContact(Request $request)
    {

        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        if ($input['prefer_by'] == 'email') {

            $validator = Validator::make($input, [

                'id' => ['required'],

                'firstname' => ['required'],


                'email' => ['required', Rule::unique("users")->ignore($input["id"])],

            ]);
        } elseif ($input['prefer_by'] == 'phone') {
            $validator = Validator::make($input, [

                'id' => ['required'],

                'firstname' => ['required'],


                'country_code' => ['required'],

                'phone_number' => ['required', Rule::unique("users")->ignore($input["id"])]

            ]);
        }
        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }
        try {

            DB::beginTransaction();

            $user = User::where('id', $input['id'])->first();

            if ($user != null) {

                $user->firstname = $input['firstname'];
                $user->lastname = $input['lastname'];
                $user->email = $input['email'];
                $user->country_code = ($input['country_code'] != "") ? $input['country_code'] : 0;

                $user->phone_number = $input['phone_number'];
                $user->prefer_by = $input['prefer_by'];
                $user->save();

                DB::commit();
                $updateUser = User::where('id', $input['id'])->select('id', 'firstname', 'lastname', 'profile', 'country_code', 'phone_number', 'email', 'app_user', 'prefer_by')->first();
                $useData = [
                    'id' =>  $updateUser->id,
                    'first_name' =>  $updateUser->firstname,
                    'last_name' =>  $updateUser->lastname,
                    'profile' => (isset($updateUser->profile) && $updateUser->profile != NULL) ? asset('storage/profile/' . $updateUser->profile) : "",
                    'country_code' =>  (string)$updateUser->country_code,
                    'phone_number' =>  $updateUser->phone_number,
                    'email' =>  $updateUser->email,
                    'app_user' =>  $updateUser->app_user,
                    'prefer_by' => $updateUser->prefer_by
                ];

                return response()->json(['status' => 1, 'data' => $useData, 'message' => "Contact updated sucessfully"]);
            } else {
                return response()->json(['status' => 0, 'message' => "user not found"]);
            }
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => 'db error']);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }

    public function deleteContact(Request $request)
    {
        $user  = Auth::guard('web')->user();



            $deleteUser = User::where(['id' => $request['user_id']])->first();
            if ($deleteUser != null) {

                $deleteUser->delete();
                return response()->json(['status' => 1, 'message' => "User deleted successfully"]);
            } else {
                return response()->json(['status' => 0, 'message' => "User is not removed"]);
            }

    }
  
    public function see_all_invite_yesvite(Request $request){
    
        $yesvite_all_invite=getInvitedUsersList($request->event_id);
        $new_added_user=session()->get('add_guest_user_id');
        $yesvite_users_data = [];
        $yesvite_phone_data = [];
        $is_phone=$request->is_phone;
        // dd($new_added_user);
        if(!empty($new_added_user)){
        foreach ($new_added_user as $sesionuser) {
            // Try fetching the user from the User table
            $user = User::find($sesionuser['user_id']);
            $prefer_by=$sesionuser['prefer_by'];

            if ($user && $is_phone==0) {
                // If the user exists, add data to the $users_data array
                $yesvite_users_data[] = [
                    'user_id' => $user->id,
                    'first_name' => (!empty($user->firstname) && $user->firstname != NULL) ? $user->firstname : "",
                    'last_name' => (!empty($user->lastname) && $user->lastname != NULL) ? $user->lastname : "",
                    'email' => (!empty($user->email) && $user->email != NULL) ? $user->email : "",
                    'phone_number'=>((!empty($user->phone_number) && $user->phone_number != NULL) ? $user->phone_number : ""),
                    'profile' => (!empty($user->profile) && $user->profile != NULL && preg_match('/\.(jpg|jpeg|png)$/i', basename($user->profile))) 
                                ? asset('storage/profile/' . $user->profile) 
                                : "",
                    'prefer_by'=>$prefer_by
                ];
            } else {
                $contact_sync = contact_sync::find($sesionuser['user_id']);
                
                if ($contact_sync) {
                    $yesvite_phone_data[] = [
                        'user_id' => $contact_sync->id,
                        'first_name' => (!empty($contact_sync->firstName) && $contact_sync->firstName != NULL) ? $contact_sync->firstName : "",
                        'last_name' => (!empty($contact_sync->lastName) && $contact_sync->lastName != NULL) ? $contact_sync->lastName : "",
                        'email' => (!empty($contact_sync->email) && $contact_sync->email != NULL) ? $contact_sync->email : "",
                        'profile' => (!empty($contact_sync->photo) && $contact_sync->photo != NULL && preg_match('/\.(jpg|jpeg|png)$/i', basename($contact_sync->photo))) 
                                    ? asset('storage/profile/' . $contact_sync->photo) 
                                    : "",
                        'phone_number'=>((!empty($contact_sync->phone) && $contact_sync->phone != NULL) ? $contact_sync->phone : ""),
                        'prefer_by'=>$prefer_by
           
                    ];
                }
            }        
            
        }

                // dd($yesvite_phone_data);

    }
        return response()->json(['view' => view( 'front.event_wall.see_invite', compact('yesvite_all_invite','yesvite_users_data','yesvite_phone_data','is_phone'))->render()]);

    }


}
