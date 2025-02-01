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
    EventPostPoll,
    EventPotluckCategory,
    EventPotluckCategoryItem,
    UserPotluckItem,
    User
};

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as Exception;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventPotluckController extends Controller
{
    public function index(String $id)
    {
        $title = 'event potluck';
        $page = 'front.event_wall.event_potluck';
        $user  = Auth::guard('web')->user();
        $event = decrypt($id);
        $js = ['event_potluck','guest_rsvp','post_like_comment'];
        if ($event == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        try {
            $eventpotluckData =  EventPotluckCategory::with(['users', 'event_potluck_category_item' => function ($query) {
                $query->with(['users', 'user_potluck_items' => function ($subquery) {
                    $subquery->with('users')->sum('quantity');
                }]);
            }])->withCount('event_potluck_category_item')->where('event_id', $event)->get();
            $totalItems = EventPotluckCategoryItem::where('event_id', $event)->sum('quantity');
            $spoken_for = UserPotluckItem::where('event_id', $event)->sum('quantity');
            $checkEventOwner = Event::FindOrFail($event);
            $potluckDetail['total_potluck_categories'] = count($eventpotluckData);
            $potluckDetail['is_event_owner'] = ($checkEventOwner->user_id == $user->id) ? 1 : 0;
            $potluckDetail['is_past'] = ($checkEventOwner['end_date'] < date('Y-m-d')) ? true : false;
            $potluckDetail['potluck_items'] = $totalItems;
            $potluckDetail['spoken_for'] = $spoken_for;
            $potluckDetail['left'] = $totalItems - $spoken_for;
            $potluckDetail['item'] = $totalItems;
            $potluckDetail['available'] = $totalItems;
            if (!empty($eventpotluckData)) {
                $potluckCategoryData = [];
                $potluckItemsSummury = [];
                //   dd($eventpotluckData);
                foreach ($eventpotluckData as $value) {
                    $itempotluckCategory['id'] = $value->id;
                    $itempotluckCategory['category'] = $value->category;
                    $itempotluckCategory['total_items'] =  $value->event_potluck_category_item_count;

                    $i = 0;
                    $totalSpoken = 0;
                    foreach ($value->event_potluck_category_item as  $checkItem) {
                        $mainQty = $checkItem->quantity;
                        $spokenFor = UserPotluckItem::where('event_potluck_item_id', $checkItem->id)->sum('quantity');
                        if ($mainQty <= $spokenFor) {
                            $totalSpoken += 1;
                        }
                    }
                    $itempotluckCategory['spoken_items'] = $totalSpoken;
                    $potluckItemsSummury[] = $itempotluckCategory;
                }
                //    {{     dd($eventpotluckData);}}
                $potluckDetail['item_summary'] = $potluckItemsSummury;
                foreach ($eventpotluckData as $value) {
                    $potluckCategory['id'] = $value->id;
                    $potluckCategory['category'] = $value->category;
                    $potluckCategory['created_by'] = $value->users->firstname . ' ' . $value->users->lastname;
                    $potluckCategory['quantity'] = $value->quantity;
                    $potluckCategory['items'] = [];
                    if (!empty($value->event_potluck_category_item) || $value->event_potluck_category_item != null) {
                        foreach ($value->event_potluck_category_item as $itemValue) {
                            // dd($itemValue);
                            $potluckItem['id'] =  $itemValue->id;
                            $potluckItem['description'] =  $itemValue->description;
                            $potluckItem['is_host'] = ($checkEventOwner->user_id == $itemValue->user_id) ? 1 : 0;
                            $potluckItem['requested_by'] =  $itemValue->users->firstname . ' ' . $itemValue->users->lastname;
                            $potluckItem['quantity'] =  $itemValue->quantity;
                            $spoken_for = UserPotluckItem::where('event_potluck_item_id', $itemValue->id)->sum('quantity');
                            $potluckItem['spoken_quantity'] =  $spoken_for;
                            $missing_quantity = $itemValue->quantity - $spoken_for;
                            $over_quantity = $spoken_for > $itemValue->quantity ? $spoken_for - $itemValue->quantity : 0;
                            $potluckItem['missing_quantity'] = $missing_quantity > 0 ? $missing_quantity : 0;
                            $potluckItem['over_quantity'] = $over_quantity > 0 ? $over_quantity : 0;
                            $potluckItem['item_carry_users'] = [];

                            foreach ($itemValue->user_potluck_items as $itemcarryUser) {
                                $userPotluckItem['id'] = $itemcarryUser->id;
                                $userPotluckItem['user_id'] = $itemcarryUser->user_id;
                                $userPotluckItem['is_host'] = ($checkEventOwner->user_id == $itemValue->user_id) ? 1 : 0;
                                $userPotluckItem['profile'] =  empty($itemcarryUser->users->profile) ?  "" : asset('storage/profile/' . $itemcarryUser->users->profile);
                                $userPotluckItem['first_name'] = $itemcarryUser->users->firstname;
                                $userPotluckItem['quantity'] = (!empty($itemcarryUser->quantity) || $itemcarryUser->quantity != NULL) ? $itemcarryUser->quantity : "0";
                                $userPotluckItem['last_name'] = $itemcarryUser->users->lastname;
                                $potluckItem['item_carry_users'][] = $userPotluckItem;
                            }
                            $potluckCategory['items'][] = $potluckItem;
                        }
                    }
                    $potluckCategoryData[] = $potluckCategory;
                }

                $potluckDetail['podluck_category_list'] = $potluckCategoryData;

                $eventDetail = Event::with(['user', 'event_image', 'event_schedule', 'event_settings' => function ($query) {
                    $query->select('event_id', 'podluck', 'allow_limit', 'adult_only_party');
                },  'event_invited_user' => function ($query) {
                    $query->where('is_co_host', '0')->with('user');
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
                $eventDetails['allow_limit'] = $eventDetail->event_settings->allow_limit;
                $eventDetails['adult_only_party'] = $eventDetail->event_settings->adult_only_party;
                $eventDetails['is_host'] = ($eventDetail->user_id == $user->id) ? 1 : 0;
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
                        $guestData = EventInvitedUser::with('user') // Eager load the related 'user' model
                        ->where(['event_id'=>$eventDetail->id,'is_co_host'=>"0"])

                            ->get();



                        $eventData[] = "Number of guests : " . $numberOfGuest;
                        $eventData['guests'] = $guestData;
                    }
                    $eventDetails['event_detail'] = $eventData;
                }
                $eventDetails['total_limit'] = $eventDetail->event_settings->allow_limit;
                $eventInfo['guest_view'] = $eventDetails;
                $totalEnvitedUser = EventInvitedUser::whereHas('user', function ($query) {

                    $query->where('app_user', '1');
                })->where(['event_id' => $eventDetail->id])->count();

                $eventattending = EventInvitedUser::whereHas('user', function ($query) {

                    $query->where('app_user', '1');
                })->where(['rsvp_status' => '1', 'event_id' => $eventDetail->id])->count();

                $eventNotComing = EventInvitedUser::whereHas('user', function ($query) {

                    $query->where('app_user', '1');
                })->where(['rsvp_d' => '1', 'rsvp_status' => '0', 'event_id' => $eventDetail->id])->count();



                $todayrsvprate = EventInvitedUser::whereHas('user', function ($query) {

                    $query->where('app_user', '1');
                })->where(['rsvp_status' => '1', 'event_id' => $eventDetail->id])

                    ->whereDate('created_at', '=', date('Y-m-d'))

                    ->count();



                $pendingUser = EventInvitedUser::whereHas('user', function ($query) {

                    $query->where('app_user', '1');
                })->where(['event_id' => $eventDetail->id, 'rsvp_d' => '0'])->count();



                $adults = EventInvitedUser::whereHas('user', function ($query) {

                    $query->where('app_user', '1');
                })->where(['event_id' => $eventDetail->id, 'rsvp_status' => '1'])->sum('adults');

                $kids = EventInvitedUser::whereHas('user', function ($query) {

                    $query->where('app_user', '1');
                })->where(['event_id' => $eventDetail->id, 'rsvp_status' => '1'])->sum('kids');


                $eventAboutHost['attending'] = $adults + $kids;



                $eventAboutHost['adults'] = (int)$adults;

                $eventAboutHost['kids'] = (int)$kids;



                $eventAboutHost['not_attending'] = $eventNotComing;

                $eventAboutHost['pending'] = $pendingUser;

                $eventAboutHost['comment'] = EventPostComment::where(['event_id' => $eventDetail->id, 'user_id' => $user->id])->count();
                $total_photos = EventPostImage::where(['event_id' => $eventDetail->id])->count();

                $eventAboutHost['photo_uploaded'] = $total_photos;

                $eventAboutHost['total_invite'] =  count(getEventInvitedUser($event));

                $eventAboutHost['invite_view_rate'] = EventInvitedUser::whereHas('user', function ($query) {

                    $query->where('app_user', '1');
                })->where(['event_id' => $eventDetail->id, 'read' => '1'])->count();

                $invite_view_percent = 0;
                if ($totalEnvitedUser != 0) {

                    $invite_view_percent = EventInvitedUser::whereHas('user', function ($query) {

                        $query->where('app_user', '1');
                    })->where(['event_id' => $eventDetail->id, 'read' => '1'])->count() / $totalEnvitedUser * 100;
                }

                $eventAboutHost['invite_view_percent'] = round($invite_view_percent, 2) . "%";

                $today_invite_view_percent = 0;
                if ($totalEnvitedUser != 0) {
                    $today_invite_view_percent =   EventInvitedUser::whereHas('user', function ($query) {

                        $query->where('app_user', '1');
                    })->where(['event_id' => $eventDetail->id, 'read' => '1', 'event_view_date' => date('Y-m-d')])->count() / $totalEnvitedUser * 100;
                }

                $eventAboutHost['today_invite_view_percent'] = round($today_invite_view_percent, 2)  . "%";

                $eventAboutHost['rsvp_rate'] = $eventattending;

                $eventAboutHost['rsvp_rate_percent'] = ($totalEnvitedUser != 0) ? $eventattending / $totalEnvitedUser * 100 . "%" : 0 . "%";

                $eventAboutHost['today_upstick'] = ($totalEnvitedUser != 0) ? $todayrsvprate / $totalEnvitedUser * 100 . "%" : 0 . "%";

                $eventInfo['host_view'] = $eventAboutHost;
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
                $current_page = "potluck";
                $login_user_id  = $user->id;
                return view('layout', compact('page', 'title', 'event', 'js', 'login_user_id','postList', 'eventDetails', 'eventInfo','potluckDetail', 'current_page')); // return compact('eventInfo');
                // return compact('potluckDetail');
                // return response()->json(['status' => 1, 'data' => $potluckDetail, 'message' => " Potluck data"]);
            } else {

                return response()->json(['status' => 0, 'message' => "No data in potluck"]);
            }
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception $e) {

            return response()->json(['status' => 0, 'message' => "Something went wrong"]);
        }
    }


    public function addPotluckCategory(Request $request)
    {
        $user  = Auth::guard('web')->user();



        // $input = json_decode($rawData, true);
        // if ($input == null) {
        //     return response()->json(['status' => 0, 'message' => "Json invalid"]);
        //}



        EventPotluckCategory::Create([
            'event_id' => $request->event_id,
            'user_id' => $user->id,
            'category' => $request->category,
            'quantity' => $request->category_quantity
        ]);

        return redirect()->back()->with('Potluck category created');
    }
    public function getCategory($id)
    {
        $category = EventPotluckCategory::findOrFail($id);
        return response()->json($category);
    }

    public function updateCategory(Request $request, $id)
    {

        $request->validate([
            'category' => 'required|string|max:30',
            'quantity' => 'required|integer|min:0',
        ]);

        $category = EventPotluckCategory::findOrFail($id);
        $category->category = $request->input('category');
        $category->quantity = $request->input('quantity');
        $category->save();

        return redirect()->back()->with('success', 'Category updated successfully!');
    }
    public function deleteCategory(Request $request)
    {
        $categoryId = $request->input('category_id');
        $eventId = $request->input('event_id');

        // Find the category by ID
        $category = EventPotluckCategory::find($categoryId);

        if ($category) {
            // Delete related items if necessary (e.g., deleting items under the category)
            // For example, assuming you have a relation called items() on the Category model
            // $category->items()->delete(); // Delete items related to the category
            $category->delete(); // Delete the category itself

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Category not found']);
    }
    public function addPotluckCategoryItem(Request $request)
    {
        // dd($request);
        $user = Auth::guard('web')->user();
        $login_user_id = $user->id;

        // Create the event potluck category item
        $eventPotluckItem = EventPotluckCategoryItem::create([
            'event_id' => $request['event_id'],
            'event_potluck_category_id' => $request['category_id'],
            'self_bring_item' => $request['self_bring_item'],
            'user_id' => $user->id,
            'description' => $request['description'],
            'quantity' => $request['quantity'],
        ]);

        // If 'self_bring_item' is 1, create the UserPotluckItem
        if ($request['self_bring_item'] == '1' || $request['self_bring_item'] == '0') {
            // Use the 'self_quantity' if provided, otherwise use the 'quantity'
            $selfQuantity = $request->has('self_quantity') ? $request['self_quantity'] : $request['quantity'];

            UserPotluckItem::create([
                'event_id' => $request['event_id'],
                'user_id' => $user->id,
                'event_potluck_category_id' => $request['category_id'],
                'event_potluck_item_id' => $eventPotluckItem->id,
                'quantity' => $selfQuantity,
            ]);
        }
        // $spoken_for = UserPotluckItem::where('event_potluck_item_id', $itemValue->id)->sum('quantity');
        // $potluckItem['spoken_quantity'] =  $spoken_for;
        $accordionItemHtml = view('front.event_wall.potluck_item', [
            'category_id' => $eventPotluckItem->event_potluck_category_id,
            'item_id' => $eventPotluckItem->id,
            'description' => $request['description'],

            'quantity' => $request['quantity'],
            'self_bring_item' => $request['self_bring_item'],
            'login_user_id' => $login_user_id,
            'user' => [
                'first_name' => $user->firstname,
                'last_name' => $user->lastname,
                'profile' => $user->profile,
                'user_id' => $eventPotluckItem['user_id'] // Assuming profile_image is the field storing the image
            ],
        ])->render();

        return response()->json([
            'status' => 'success',
            'data' => $accordionItemHtml,
        ]);
    }

    public function editPotluckCategoryItem(Request $request)
    {
        $user  = Auth::guard('web')->user();

        $eventPotluckItem = EventPotluckCategoryItem::where('id', $request['category_item_id'])->first();

        if ($eventPotluckItem != "") {
            // $eventPotluckItem->event_id = $request['event_id'];
            $eventPotluckItem->event_potluck_category_id = $request['category_id'];
            // $eventPotluckItem->description = $request['description'];
            // $eventPotluckItem->self_bring_item = $request['self_bring_item'];

            $eventPotluckItem->quantity = $request['quantity'];
            $eventPotluckItem->save();
        }



        return redirect()->back()->with('success', "Potluck category item updated");
    }

    public function editUserPotluckItem(Request $request)
    {
        $user  = Auth::guard('web')->user();

        $checkCarryQty = UserPotluckItem::where(['event_potluck_category_id' => $request['category_id'], 'event_id' => $request['event_id'], 'event_potluck_item_id' => $request['category_item_id']])->first();

        // if ($input['quantity'] <= $checkQty) {
        $checkIsExist = UserPotluckItem::where([
            'id' => $checkCarryQty['id']
        ])->first();
        if ($checkIsExist != null) {
            $checkIsExist->quantity = $request['quantity'];
            $checkIsExist->save();
        }

        $getUserItemData = UserPotluckItem::with('users')->where(['id' => $checkCarryQty['id']])->first();
        $spoken_for = UserPotluckItem::where(['event_potluck_item_id' => $request['category_item_id']])->sum('quantity');

        $getCarryUser =  [
            "id" => $getUserItemData->id,
            "user_id" => $getUserItemData->user_id,
            "is_host" => ($getUserItemData->user_id == $user->id) ? 1 : 0,
            "profile" => empty($getUserItemData->users->profile) ?  "" : asset('storage/profile/' . $getUserItemData->users->profile),
            "first_name" => $getUserItemData->users->firstname,
            "quantity" => (!empty($getUserItemData->quantity) || $getUserItemData->quantity != NULL) ? $getUserItemData->quantity : "0",

            "last_name" =>  $getUserItemData->users->lastname
        ];

        return response()->json(['status' => 1, "spoken_for" => $spoken_for, 'data' => $getCarryUser, 'message' => "Potluck item updated"]);
    }
    public function fetchUserDetails(Request $request)
    {
        $categoryId = $request->category_id;
        $itemId = $request->item_id;
        $userProfile = $request->user_profile;
        $loginUserId = $request->login_user_id;
        $quantity = $request->quantity;
        $user = Auth::guard('web')->user();


        // Check if the item quantity is available
        $checkQty = EventPotluckCategoryItem::where('id', $itemId)->value('quantity');
        $checkCarryQty = intval(UserPotluckItem::where([
            'event_potluck_category_id' => $categoryId,
            'event_potluck_item_id' => $itemId
        ])->sum('quantity'));

        // Check if there's enough quantity available
        if ($checkCarryQty < $checkQty) {
            // Check if the user has already added this item
            $checkIsExist = UserPotluckItem::where([
                'event_id' => $request->event_id,
                'user_id' => $user->id,
                'event_potluck_category_id' => $categoryId,
                'event_potluck_item_id' => $itemId
            ])->first();

            // If the user has not added the item, create a new entry
            if (!$checkIsExist) {
                $newUserItem = UserPotluckItem::create([
                    'event_id' => $request->event_id,
                    'user_id' => $user->id,
                    'event_potluck_category_id' => $categoryId,
                    'event_potluck_item_id' => $itemId,
                    'quantity' => $quantity
                ]);
                $checkIsExist = $newUserItem;
            } else {
                // If the item already exists, update the quantity
                $checkIsExist->quantity = $quantity;
                $checkIsExist->save();
            }



            // Fetch the user item data
            $getUserItemData = UserPotluckItem::with('users')->where('id', $checkIsExist->id)->first();
            $spokenFor = UserPotluckItem::where('event_potluck_item_id', $itemId)->sum('quantity');
            // dd($getUserItemData);
            // Prepare the view to be sent in the response
            $getCarryUser = view('front.event_wall.potluck_user_categoryItem', [
                'id' => $getUserItemData->id,
                'user_id' => $getUserItemData->user_id,
                'is_host' => ($getUserItemData->user_id == $user->id) ? 1 : 0,
                'profile' => $getUserItemData->users->profile ? asset('storage/profile/' . $getUserItemData->users->profile) : '',
                'first_name' => $getUserItemData->users->firstname,
                'quantity' => $getUserItemData->quantity ?? '0',
                'last_name' => $getUserItemData->users->lastname,
                'category_id' => $categoryId,
                'item_id' => $itemId,
                'spoken_for' => $spokenFor,
            ])->render();

            // Return the response
            return response()->json([
                'status' => 'success',
                'data' => $getCarryUser,


            ]);
        } else {
            // If the potluck is full, return a message
            return response()->json([
                'status' => 1,
                'message' => "Potluck is full!!!"
            ]);
        }
    }
    public function deleteUserPotluckItem(Request $request)
    {
        $user  = Auth::guard('web')->user();
        $checkCarryQty = UserPotluckItem::where(['event_potluck_category_id' => $request['category_id'], 'event_id' => $request['event_id'], 'event_potluck_item_id' => $request['category_item_id']])->first();
        $checkIsExist = UserPotluckItem::where([
            'id' =>  $checkCarryQty['id']
        ])->first();

        $event_potluck_item_id = $checkIsExist->event_potluck_item_id;
        if ($checkIsExist != null) {

            $checkIsExist->delete();
        }


        $spoken_for = UserPotluckItem::where(['event_potluck_item_id' => $event_potluck_item_id])->sum('quantity');
        return response()->json([
            'success' => true,
            'spoken_for' => $spoken_for,
            'message' => 'Potluck item deleted successfully!',
            'redirect_url' => route('event.event_potluck',  encrypt($request['event_id']))  // Optionally send a redirect URL back
        ]);
    }
}
