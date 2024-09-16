<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\{
    User,
    Event,
    EventInvitedUser,
    EventSetting,
    EventGreeting,
    EventCoHost,
    EventGuestCoHost,
    EventGiftRegistry,
    EventImage,
    EventUserRsvp,
    EventSchedule,
    EventPotluckCategory,
    EventPotluckCategoryItem,
    Notification,
    EventPostComment,
    EventPost,
    EventPostCommentReaction,
    EventPostImage,
    EventPostPoll,
    EventPostPollOption,
    EventAddContact,
    EventPostReaction,
    EventUserStory,
    UserEventPollData,
    EventPostPhoto,
    EventPostPhotoReaction,
    EventPostPhotoComment,
    EventPhotoCommentReaction,
    EventPostPhotoData,
    EventDesign,
    EventDesignCategory,
    EventDesignSubCategory,
    EventDesignColor,
    EventDesignStyle,
    UserEventStory,
    PostControl,
    UserPotluckItem,
    Device,
    EventType,
    UserReportToPost,
    Group,
    GroupMember,
    UserNotificationType,
    UserProfilePrivacy,
    UserSeenStory,
    UserSubscription,
    VersionSetting
};
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Jobs\SendInvitationMailJob as sendInvitation;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use stdClass;
use Illuminate\Foundation\Exceptions\Handler as Exception;
use function PHPUnit\Framework\isFalse;

class EventController extends Controller
{
    public function index()
    {

        $title = 'Create Event';
        $page = 'front.create_event';
        $id = Auth::guard('web')->user()->id;

        $js = ['create_event'];

        $user = User::withCount(

            [
                'event' => function ($query) {
                    $query->where('is_draft_save', '0');
                },
                'event_post' => function ($query) {
                    $query->where('post_type', '1');
                },
                'event_post_comment',
            ]
        )->findOrFail($id);
        $inviteduser = "";


        $event_type =   EventType::get();
        $yesvite_user = User::select('id', 'firstname', 'lastname', 'phone_number', 'email', 'profile')
            ->where('app_user', '1')
            ->orderBy('firstname')
            ->limit(1)
            ->get();
        $user['profile'] = ($user->profile != null) ? asset('storage/profile/' . $user->profile) : "";
        $user['bg_profile'] = ($user->bg_profile != null) ? asset('storage/bg_profile/' . $user->bg_profile) : asset('assets/front/image/Frame 1000005835.png');
        $date = Carbon::parse($user->created_at);
        $formatted_date = $date->format('F, Y');
        $user['join_date'] = $formatted_date;

        // $groups = Group::whereHas('groupMembers',function($query) use ($id){
        //     $query->where('user_id',$id);
        // })
        // ->withCount('groupMembers')
        // ->get();

        $groups = Group::withCount('groupMembers')
            ->orderByDesc('group_members_count')
            ->where('user_id', $id)
            ->get();





        return view('event_layout', compact(
            'title',
            'page',
            'js',
            'user',
            'event_type',
            'yesvite_user',
            'groups',
        ));
    }

    public function store(Request $request)
    {

        // $potluck = session('category');
        // dd(session()->get('gift_registry_data'));
        // dd($request); 
        $user_id =  Auth::guard('web')->user()->id;
        $dateString = (isset($request->event_date)) ? $request->event_date : "";

        if (strpos($dateString, 'To ') !== false) {
            list($startDate, $endDate) = explode('To ', $dateString);
        } else {
            $startDate = $dateString;
            $endDate = null;
        }
        $event_creation = Event::create([
            'step' => (isset($request->step) && $request->step != '') ? $request->step : 0,
            'event_type_id' => (isset($request->event_type) && $request->event_type != "") ? (int)$request->event_type : "",
            'event_name' => (isset($request->event_name) && $request->event_name != "") ? $request->event_name : "",
            'user_id' => $user_id,
            'hosted_by' => (isset($request->hosted_by) && $request->hosted_by) ? $request->hosted_by : "",
            // 'latitude' => "",
            // 'longitude' => "",
            'start_date' => (isset($startDate) && $startDate != "") ? $startDate : null,
            'end_date' => (isset($endDate) && $endDate != "") ? $endDate : null,
            'rsvp_by_date_set' => '0',
            'rsvp_by_date' => (isset($request->rsvp_by_date) && $request->rsvp_by_date != "") ? $request->rsvp_by_date : null,
            'rsvp_start_time' => (isset($request->start_time) && $request->start_time != "") ? $request->start_time : "",
            'rsvp_start_timezone' => (isset($request->time_zone) && $request->time_zone != "") ? $request->time_zone : "",
            // 'greeting_card_id' => "",
            // 'gift_registry_id' => "",
            // 'rsvp_end_time_set' => "",
            'rsvp_end_time' => (isset($request->event_end_time) && $request->event_end_time != "") ? $request->event_end_time : "",
            'rsvp_end_timezone' => (isset($request->end_time_zone) && $request->end_time_zone != "") ? $request->end_time_zone : "",
            'event_location_name' => (isset($request->event_location) && $request->event_location) ? $request->event_location : "",
            'address_1' => (isset($request->address) && $request->address) ? $request->address : "",
            // 'address_2' => "",
            'state' => (isset($request->state) && $request->state != "") ? $request->state : "",
            'zip_code' => (isset($request->zipcode) && $request->zipcode) ? $request->zipcod : "",
            'city' => (isset($request->city) && $request->city != "") ? $request->city : "",
            'message_to_guests' => (isset($request->message) && $request->message != "") ? $request->message : "",
            // 'subscription_plan_name' => "",
            // 'subscription_invite_count' => "",
            'is_draft_save' => (isset($request->isdraft) && $request->isdraft != "") ? $request->isdraft : "0",
        ]);
        $eventId = $event_creation->id;
        if ($eventId != "") {
            $invitedUsers = $request->email_invite;
            $invitedusersession = session('user_ids');
            if (isset($invitedusersession) && !empty($invitedusersession)) {
                foreach ($invitedusersession as $key => $value) {
                    $is_cohost = '0';
                    $invited_user = $value['id'];
                    $prefer_by =  $value['prefer_by'];

                    EventInvitedUser::create([
                        'event_id' => $eventId,
                        'prefer_by' => $prefer_by,
                        'user_id' => $invited_user,
                        'is_co_host' => $is_cohost,
                    ]);
                    $invitedusers = Event::with(['user'])->whereHas('user', function ($query) {})->where('user_id', $user_id)->where('id', $eventId)->get();
                    foreach ($invitedusers as $event_detail) {
                        $eventData = [
                            'event_name' => $event_detail->event_name,
                            'hosted_by' => $event_detail->user->firstname . ' ' . $event_detail->user->lastname,
                            'profileUser' => ($event_detail->user->profile != NULL || $event_detail->user->profile != "") ? $event_detail->user->profile : "no_profile.png",
                            'event_image' => "no_image.png",
                            'date' =>   date('l - M jS, Y', strtotime($event_detail->start_date)),
                            'time' => $event_detail->rsvp_start_time,
                            'address' => $event_detail->event_location_name . ' ' . $event_detail->address_1 . ' ' . $event_detail->state . ' ' . $event_detail->city . ' - ' . $event_detail->zip_code,
                        ];
                    }
                    // if(isset($request->isdraft) && $request->isdraft == '0'){
                    //     $user = User::find($value['id']);
                    //     if ($user) {
                    //         if ($user->email != "") {
                    //             $emailCheck = dispatch(new sendInvitation(array($user->email, $eventData)));
                    //             Session::forget('user_ids');
                    //         }
                    //     }
                    // }
                }
            }


            if (isset($request->co_host) && $request->co_host != '' && isset($request->co_host_prefer_by)) {
                $is_cohost = '1';
                $invited_user = $request->co_host;
                $prefer_by = $request->co_host_prefer_by;

                EventInvitedUser::create([
                    'event_id' => $eventId,
                    'prefer_by' => $prefer_by,
                    'user_id' => $invited_user,
                    'is_co_host' => $is_cohost,
                ]);
                $invitedusers = Event::with(['user'])->whereHas('user', function ($query) {})->where('id', $eventId)->get();
                foreach ($invitedusers as $event_detail) {
                    $eventData = [
                        'event_name' => $event_detail->event_name,
                        'hosted_by' => $event_detail->user->firstname . ' ' . $event_detail->user->lastname,
                        'profileUser' => ($event_detail->user->profile != NULL || $event_detail->user->profile != "") ? $event_detail->user->profile : "no_profile.png",
                        'event_image' => "no_image.png",
                        'date' =>   date('l - M jS, Y', strtotime($event_detail->start_date)),
                        'time' => $event_detail->rsvp_start_time,
                        'address' => $event_detail->event_location_name . ' ' . $event_detail->address_1 . ' ' . $event_detail->state . ' ' . $event_detail->city . ' - ' . $event_detail->zip_code,
                    ];
                }
            }

            if (isset($request->eventSetting)) {
                EventSetting::create([
                    'event_id' => $eventId,
                    'allow_for_1_more' => (isset($request->allow_for_1_more)) ? $request->allow_for_1_more : "0",
                    'allow_limit' => (isset($request->allow_limit)) ? $request->allow_limit : 0,
                    'adult_only_party' => (isset($request->only_adults)) ? $request->only_adults : "0",
                    'thank_you_cards' => (isset($request->thankyou_message)) ? $request->thankyou_message : "0",
                    'add_co_host' => (isset($request->add_co_host)) ? $request->add_co_host : "0",
                    'gift_registry' => (isset($request->gift_registry)) ? $request->gift_registry : "0",
                    'events_schedule' => (isset($request->events_schedule)) ? $request->events_schedule : "0",
                    'event_wall' => (isset($request->event_wall)) ? $request->event_wall : "0",
                    'guest_list_visible_to_guests' => (isset($request->guest_list_visible_to_guest)) ? $request->guest_list_visible_to_guest : "0",
                    'podluck' => (isset($request->potluck)) ? $request->potluck : "0",
                    'rsvp_updates' => (isset($request->rsvp_update)) ? $request->rsvp_update : "0",
                    'event_wall_post' => (isset($request->event_wall_post)) ? $request->event_wall_post : "0",
                    'send_event_dater_reminders' => (isset($request->rsvp_remainder)) ? $request->rsvp_remainder : "0",
                    'request_event_photos_from_guests' => (isset($request->request_photo)) ? $request->request_photo : "0",
                ]);
            }
            if ($request->potluck == "1") {
                $potluck = session('category');
                if (isset($potluck) && !empty($potluck)) {
                    foreach ($potluck as $category) {
                        $eventPodluck = EventPotluckCategory::create([
                            'event_id' => $eventId,
                            'user_id' => $user_id,
                            'category' => $category['category_name'],
                            'quantity' => $category['category_quantity'],
                        ]);
                        if (isset($category['item'])) {
                            foreach ($category['item'] as $item) {
                                $eventPodluckitem = EventPotluckCategoryItem::create([
                                    'event_id' => $eventId,
                                    'user_id' => $user_id,
                                    'event_potluck_category_id' => $eventPodluck->id,
                                    'self_bring_item' =>  $item['self_bring'],
                                    'description' => $item['name'],
                                    'quantity' => $item['quantity'],
                                ]);
                                if (isset($item['self_bring']) && $item['self_bring'] == '1') {
                                    UserPotluckItem::Create([
                                        'event_id' => $eventId,
                                        'user_id' => $user_id,
                                        'event_potluck_category_id' => $eventPodluck->id,
                                        'event_potluck_item_id' => $eventPodluckitem->id,
                                        'quantity' => (isset($item['self_bring_qty']) && @$item['self_bring_qty'] != "") ? $item['self_bring_qty'] : $item['quantity']
                                    ]);
                                }
                            }
                        }
                    }
                }
            }

            if (isset($request->activity) && !empty($request->activity)) {
                $activities = $request->activity;
                // dd($request);
                $addStartschedule =  new EventSchedule();

                $addStartschedule->event_id = $eventId;
                $addStartschedule->start_time = isset($request->start_time) ? $request->start_time : '';
                $addStartschedule->event_date = isset($startDate) ? $startDate : '';
                $addStartschedule->type = '1';
                $addStartschedule->save();

                foreach ($activities as $date => $activityList) {
                    foreach ($activityList as $activity) {
                        $activity_data[] = [
                            'event_id' => $eventId,
                            'activity_title' => $activity['activity'],
                            'start_time' => $activity['start-time'],
                            'end_time' => $activity['end-time'],
                            'event_date' => $date,
                            'type' => '2'
                        ];
                    }
                }

                EventSchedule::insert($activity_data);

                $addEndschedule =  new EventSchedule();
                $addEndschedule->event_id = $eventId;
                $addEndschedule->end_time = isset($request->end_time)  ? $request->end_time : '';
                $addEndschedule->event_date = isset($endDate) ? $endDate : '';
                $addEndschedule->type = '3';
                $addEndschedule->save();
            }
            $gift = "0";
            if ($request->gift_registry == "1") {
                $gift_registry = $request->gift_registry_data;
                if (isset($gift_registry) && !empty($gift_registry)) {
                    $gift = "1";
                    foreach ($gift_registry as $data) {
                        $gift_registry_data[] = [
                            'user_id' => $user_id,
                            'registry_recipient_name' => $data['registry_name'],
                            'registry_link' => $data['registry_link'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    EventGiftRegistry::insert($gift_registry_data);
                }
            }

            if (isset($request->desgin_selected) && $request->desgin_selected != "") {
                EventImage::create([
                    'event_id' => $eventId,
                    'image' => $request->desgin_selected
                ]);
            }

            $checkUserInvited = Event::withCount('event_invited_user')->where('id', $eventId)->first();
            if ($request->is_update_event == '0') {
                if ($checkUserInvited->event_invited_user_count != '0' && $checkUserInvited->is_draft_save == '0') {
                    $notificationParam = [
                        'sender_id' => $user_id,
                        'event_id' => $eventId,
                        'post_id' => ""
                    ];

                    sendNotification('invite', $notificationParam);
                }
                if ($checkUserInvited->is_draft_save == '0') {
                    $notificationParam = [
                        'sender_id' => $user_id,
                        'event_id' => $eventId,
                        'post_id' => ""
                    ];
                    sendNotification('owner_notify', $notificationParam);
                }
            }

            // if ($request->thankyou_message == "1") {
            //     $thankyou_card = session('thankyou_card_data');
            //     if (isset($thankyou_card) && !empty($thankyou_card)) {
            //         // dd($gift_registry);
            //         foreach ($thankyou_card as $data) {
            //             $thankyou_card_data[] = [
            //                 
            //             ];
            //         }
            //         EventGiftRegistry::insert($thankyou_card_data);
            //     }
            // }     
            // return  Redirect::to('event')->with('success', 'Event Created successfully');
            session()->forget('desgin');
        }
        if ($event_creation && $request->isdraft == "1") {
            return 1;
        }


        $registry = session('gift_registry_data');

        return response()->json([
            'view' => view('front.event.gift_registry.view_gift_registry', compact('registry'))->render(),
            'success' => true,
            'is_registry' => $gift
        ]);
    }

    public function storeUserId(Request $request)
    {
        $userId = $request->input('user_id');

        $user = User::where('id', $userId)->first();
        // $userimage = asset('storage/profile/' . $user->profile);
        $userimage = $user->profile;
        $useremail = $request->input('email');
        $phone = $request->input('mobile');
        // dd($userProfile);
        $isChecked = $request->input('is_checked');
        $userIds = session()->get('user_ids', []);
        // if ($isChecked == true || $isChecked == "true") {
        //     if (!in_array($userId, $userIds)) {
        //         $userIds[] = ['id' => $userId, 'firstname' => $user->firstname, 'lastname' => $user->lastname, 'phonenumber' => ($user->phone_number != "") ? $user->phone_number : '', 'email' => $useremail, 'profile' => (isset($userimage) && $userimage != '') ? $userimage : ''];
        //         session()->put('user_ids', $userIds);
        //         return response()->json(['success' => true, 'data' => $userIds]);
        //     }
        // }
        if (isset($useremail) && $useremail != "") {
            $user_invited_by = $useremail;
            $prefer_by = "email";
        }
        if (isset($phone) && $phone != "") {
            $user_invited_by = $user->phone_number;
            $prefer_by = "phone";
        }

        $userEntry = [
            'id' => $userId,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            // 'phonenumber' => ($user->phone_number != "") ? $user->phone_number : '',
            // 'email' => $useremail,
            'invited_by' => $user_invited_by,
            'prefer_by' => $prefer_by,
            'profile' => (isset($userimage) && $userimage != '') ? $userimage : ''
        ];

        // if ($isChecked == true || $isChecked == "true") {
        //     // Remove any existing entry with the same user ID
        //     $userIds = array_filter($userIds, function ($entry) use ($userId) {
        //         return $entry['id'] !== $userId;
        //     });

        //     // Add the latest user entry
        //     $userIds[] = $userEntry;

        //     // Save updated array to session
        //     session()->put('user_ids', $userIds);


        //     // Return only the latest entry
        //     return response()->json(['success' => true, 'data' => $userEntry]);
        // }
        // dd($userIds);

        if ($isChecked == true || $isChecked == "true") {
            $userExists = array_filter($userIds, function ($entry) use ($userId) {
                return $entry['id'] === $userId;
            });

            $userIds = array_filter($userIds, function ($entry) use ($userId) {
                return $entry['id'] !== $userId;
            });
            $userIds[] = $userEntry;
            session()->put('user_ids', $userIds);

            if (!empty($userExists)) {
                // return response()->json(['success' => false, 'data' => $userEntry, 'is_duplicate' => 1]);
                $data[] = ['userdata' => $userEntry, 'is_duplicate' => 1];
                return response()->json(['view' => view('front.event.guest.addGuest', compact('data'))->render(), 'is_duplicate' => 1]);
            }
            $data[] = ['userdata' => $userEntry, 'is_duplicate' => 0];
            return response()->json(['view' => view('front.event.guest.addGuest', compact('data'))->render(), 'success' => true, 'data' => $userEntry]);
        }
    }

    public function getAllInvitedGuest(Request $request)
    {
        $selected_user = session('user_ids');

        $alreadyselectedUser =  collect($selected_user)->pluck('id')->toArray();


        $users = User::select('id', 'firstname', 'lastname', 'phone_number', 'email', 'profile')
            ->whereNotIn('id', $alreadyselectedUser)
            ->get();



        return  view('front.event.guest.allGuestList', compact('users'));
    }

    public function removeUserId(Request $request)
    {
        $userIds = session()->get('user_ids');
        $userId = $request->input('user_id');
        foreach ($userIds as $key => $value) {
            if ($value['id'] == $userId) {
                unset($userIds[$key]);
            }
        }
        $users = $userIds;
        session()->put('user_ids', $users);
        return response()->json(['success' => true]);
    }

    public function deleteSession(Request $request)
    {
        // dd($request->input());
        $sessionKeys = $request->input('session_key');
        $image = Session::get('desgin');

        if (isset($image) && $image != "" || $image != NULL) {
            if (file_exists(public_path('storage/event_design_template/') . $image)) {
                $imagePath = public_path('storage/event_design_template/') . $image;
                unlink($imagePath);
                session()->forget('desgin');
            }
        }

        if (is_array($sessionKeys)) {
            foreach ($sessionKeys as $key) {
                $request->session()->forget($key);
            }

            return response()->json(['success' => true, 'message' => 'Sessions deleted successfully']);
        }
        return response()->json(['success' => true, 'message' => 'Session deleted successfully']);
    }


    public function storeCategoryitemSession(Request $request)
    {
        $user = Auth::guard('web')->user();
        $name = $user->firstname . ' ' . $user->lastname;
        $categoryName = $request->input('category_name');
        $category_quantity = $request->input('category_quantity');
        $itemName = $request->input('itemName');
        $selfBring = $request->input('selfbring');
        $selfBringQuantity = $request->input('self_bringQuantity');
        $itemQuantity = $request->input('itemQuantity');
        $category_index = $request->input('category_index');

        $categories_item = Session::get('category_item', []);
        $itemData = [
            'item' => $itemName,
            'self_bring' => $selfBring,
            'quantity' => $itemQuantity
        ];
        if (isset($categories_item[$categoryName])) {
            $categories_item[$categoryName][] = $itemData;
        } else {
            $categories_item[$categoryName] = [$itemData];
        }


        $categories = Session::get('category', []);

        if (isset($categories[$category_index])) {
            if (!isset($categories[$category_index]['item'])) {
                $categories[$category_index]['item'] = [];
            }

            $categories[$category_index]['item'][] = [
                'name' => $itemName,
                'self_bring' => $selfBring,
                'self_bring_qty' => $selfBringQuantity,
                'quantity' => $itemQuantity,
            ];
        } else {
            $categories[$category_index] = [
                'category_name' => $categoryName,
                'category_quantity' => $category_quantity,
                'item' => [
                    [
                        'name' => $itemName,
                        'quantity' => $itemQuantity,
                    ]
                ]
            ];
        }

        Session::put('category', $categories);

        Session::put('category_item', $categories_item);
        $categories = Session::get('category', []);

        $category_quantity = $categories[$category_index]['category_quantity'];
        $category_item = count($categories[$category_index]['item']);
        $total_item = 0;
        $total_quantity = 0;
        if (isset($categories[$category_index]['item']) && !empty($categories[$category_index]['item'])) {
            foreach ($categories[$category_index]['item'] as $key => $value) {
                $total_item = $total_item + $value['quantity'];
                if (isset($value['self_bring']) && isset($value['self_bring_qty']) && $value['self_bring'] == 1) {
                    $total_quantity = $total_quantity + $value['self_bring_qty'];
                }
            }
        }
        $total_item = $total_item - $total_quantity;
        $qty = 0;
        if ($category_quantity == $category_item) {
            $qty = 1;
        } else {
            $qty = 0;
        }
        $data = [
            "itemdata" => $itemData,
            'user' => $name,
            'category' => $categoryName,
            'self_bring' => $selfBring,
            'self_bring_qty' => $selfBringQuantity,
            'category_index' => $category_index,
            'category_item' => --$category_item,
        ];

        // return view('front.event.potluck.potluckCategoryItem', $data);

        return response()->json(['view' => view('front.event.potluck.potluckCategoryItem', $data)->render(), 'qty' => $qty, 'total_item' => $total_item]);
    }

    public function storeCategorySession(Request $request)
    {
        $categoryName = $request->input('category_name');
        $categoryQuantity = $request->input('categoryQuantity');
        $potluckkey = $request->input('potluckkey');
        $categories = session()->get('category', []);
        // dd($categories);
        $categoryNames =  collect($categories)->pluck('category_name')->toArray();
        if (in_array($categoryName, $categoryNames)) {
            return response()->json(['view' => '', 'status' => '0']);
        } else {
            $categories[] = ['category_name' => $categoryName, 'category_quantity' => $categoryQuantity];
        }
        session()->put('category', $categories);

        return response()->json(['view' => view('front.event.potluck.potluckCategory', ['categoryName' => $categoryName, 'categoryQuantity' => $categoryQuantity, 'potluckkey' => $potluckkey])->render(), 'status' => '1']);
        // return $categoryName;
    }


    public function addActivity(Request $request)
    {
        $dataid = $request->input('dataid');
        $newClass = $request->input('newClass');
        $count = $request->input('count');
        $id = $request->input('id');
        return view('front.event.activity.addActivity', ['dataid' => $dataid, 'newClass' => $newClass, 'count' => $count, 'id' => $id]);
    }

    public function deletePotluckCategory(Request $request)
    {

        $delete_potluck_id = $request->input('potluck_delete_id');

        $category = session()->get('category');

        if (isset($category[$delete_potluck_id])) {
            unset($category[$delete_potluck_id]);
        }
        session()->put('category', $category);
        // $event_data_display = Session::get('event_date_display');
        // unset($event_data_display[$index]);
        // Session::set('event_data_display', $event_data_display);

        return true;
    }

    public function addNewGiftRegistry(Request $request)
    {

        $recipient_name = $request->input('recipient_name');
        $registry_link = $request->input('registry_link');
        $registry_item = $request->input('registry_item');
        $when_to_send = $request->input('when_to_send');

        $giftRegistryData = session()->get('gift_registry_data', []);

        if (array_key_exists($registry_item, $giftRegistryData) != null || array_key_exists($registry_item, $giftRegistryData) != "") {
            $giftRegistryData[$registry_item]['recipient_name'] = $recipient_name;
            $giftRegistryData[$registry_item]['registry_link'] = $registry_link;

            session(['gift_registry_data' => $giftRegistryData]);
            return response()->json(['message' => "registry updated", 'status' => '1']);
        } else {
            $giftRegistryData[$registry_item] = [
                'recipient_name' => $recipient_name,
                'registry_link' => $registry_link,
            ];
        }
        session(['gift_registry_data' => $giftRegistryData]);

        $data = ['recipient_name' => $recipient_name, 'registry_link' => $registry_link, 'registry_item' => $registry_item];
        return response()->json(['view' => view('front.event.gift_registry.add_gift_registry', $data)->render()]);
    }

    public function removeGiftRegistry(Request $request)
    {
        $registry_item = $request->input('registry_item');
        $giftRegistryData = session()->get('gift_registry_data', []);
        if (array_key_exists($registry_item, $giftRegistryData)) {
            unset($giftRegistryData[$registry_item]);
        }
        session(['gift_registry_data' => $giftRegistryData]);

        return response()->json(['message' => 'Gift registry item removed successfully.']);
    }

    public function addNewThankyouCard(Request $request)
    {
        $template_name = $request->input('template_name');
        $when_to_send = $request->input('when_to_send');
        $thankyou_message = $request->input('thankyou_message');
        $thankyou_template_id = $request->input('thankyou_template_id');

        $thankyouCard = session()->get('thankyou_card_data', []);


        if (array_key_exists($thankyou_template_id, $thankyouCard) != null || array_key_exists($thankyou_template_id, $thankyouCard) != "") {
            $thankyouCard[$thankyou_template_id]['name'] = $template_name;
            $thankyouCard[$thankyou_template_id]['when_to_send'] = $when_to_send;
            $thankyouCard[$thankyou_template_id]['message'] = $thankyou_message;
            session(['thankyou_card_data' => $thankyouCard]);
            return response()->json(['message' => "thankyou card updated", 'status' => '1']);
        } else {
            $thankyouCard[$thankyou_template_id] = [
                'name' => $template_name,
                'when_to_send' => $when_to_send,
                'message' => $thankyou_message,
            ];
        }

        session(['thankyou_card_data' => $thankyouCard]);


        $data = ['name' => $template_name, 'when_to_send' => $when_to_send, 'message' => $thankyou_message, 'thankyou_template_id' => $thankyou_template_id];
        return response()->json(['view' => view('front.event.thankyou_template.add_thankyou_template', $data)->render()]);
    }

    public function removeThankyouCard(Request $request)
    {
        $thank_you_card_id = $request->input('thank_you_card_id');
        $thankyouCard = session()->get('thankyou_card_data', []);
        if (array_key_exists($thank_you_card_id, $thankyouCard)) {
            unset($thankyouCard[$thank_you_card_id]);
        }
        session(['thankyou_card_data' => $thankyouCard]);
        return response()->json(['message' => 'Thankyou Card removed successfully.']);
    }

    public function updateSelfBring(Request $request)
    {
        $categoryItemKey = $request->categoryItemKey;
        $categoryIndexKey = $request->categoryIndexKey;
        $quantity = (string)$request->quantity;
        $categories = session()->get('category', []);
        $categories[$categoryIndexKey]['item'][$categoryItemKey]['self_bring'] = ($quantity == 0) ? '0' : '1';
        $categories[$categoryIndexKey]['item'][$categoryItemKey]['self_bring_qty'] = $quantity;
        session()->put('category', $categories);
        $categories = session()->get('category', []);
        $total_item = 0;
        $total_quantity = 0;
        if (isset($categories[$categoryIndexKey]['item']) && !empty($categories[$categoryIndexKey]['item'])) {
            foreach ($categories[$categoryIndexKey]['item'] as $key => $value) {
                $total_item = $total_item + $value['quantity'];
                if (isset($value['self_bring']) && isset($value['self_bring_qty']) && $value['self_bring'] == 1) {
                    $total_quantity = $total_quantity + $value['self_bring_qty'];
                }
            }
        }
        $total_item = $total_item - $total_quantity;
        return $total_item;
    }

    public function saveTempDesign(Request $request)
    {

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $fileName = time() . '-' . $file->getClientOriginalName();
            $path = $file->move(public_path('storage/event_images'), $fileName);
            session(['desgin' => $fileName]);
            return response()->json(['status' => 'Image saved successfully', 'image' => $fileName]);
        }

        return response()->json(['status' => 'No image uploaded'], 400);
    }

    public function addNewGroup(Request $request)
    {
        $groupname = $request->input('groupname');
        $member = $request->input('groupmember');
        $id = Auth::guard('web')->user()->id;

        // dd($member);
        if ($groupname != "" && !empty($member) && $groupname != null) {
            $createGroup = new Group();
            $createGroup->user_id = $id;
            $createGroup->name = $groupname;
            $createGroup->save();

            foreach ($member as $members) {
                $groupmember[] = [
                    'group_id' => $createGroup->id,
                    'user_id' => $members['id'],
                    'prefer_by' => $members['prefer_by'],
                    'created_at' => now(),
                    'updated_at' => now(),

                ];
            }
            // dd($groupmember);
            GroupMember::insert($groupmember);

            $count = count($member);

            $data = ['groupname' => $groupname, 'group_id' => $createGroup->id, "member_count" => $count];

            return response()->json(['view' => view('front.event.guest.add_group', compact('data'))->render(), "status" => "1"]);
        }
    }

    public function deleteGroup(Request $request)
    {
        $group = $request->input('group_id');

        $deleteGroup = Group::where(['id' => $group])->first();
        if ($deleteGroup != null) {

            $deleteGroup->delete();

            return response()->json(['status' => 1, 'message' => 'Group deleted successfully']);
        }
    }

    public function listGroupMember(Request $request)
    {
        $group_id = $request->input('group_id');

        $groups = GroupMember::with(['user' => function ($query) {
            $query->select('id', 'lastname', 'firstname', 'email', 'phone_number');
        }])
            ->where('group_id', $group_id)
            ->orderBy('id', 'DESC')
            ->get();

        return response()->json(['view' => view('front.event.guest.list_group_member', ['groups' =>  $groups])->render(), "status" => "1"]);
    }

    public function getUserAjax(Request $request)
    {
        $search_user = $request->search_user;
        $id = Auth::guard('web')->user()->id;
        $type = $request->type;




        $yesvite_user = User::select('id', 'firstname', 'lastname', 'phone_number', 'email', 'profile')
            ->where('app_user', '1')
            ->where('id', '!=', $id)
            ->orderBy('firstname')
            ->limit($request->limit)
            ->skip($request->offset)
            ->when($request->search_user != '', function ($query) use ($search_user) {
                $query->where(function ($q) use ($search_user) {
                    $q->where('firstname', 'LIKE', '%' . $search_user . '%')
                        ->orWhere('lastname', 'LIKE', '%' . $search_user . '%');
                });
            })
            ->get();

        return response()->json(view('front.event.guest.get_user', compact('yesvite_user', 'type'))->render());
    }

    // public function searchUserAjax(Request $request){
    //     $id = Auth::guard('web')->user()->id;
    //     $searchName = $request->search_name;

    //     if ($request->ajax()) {
    //         $query = User::where('id', '!=', $id)->where(['is_user_phone_contact' => '0'])->orderBy('firstname');

    //         if ($searchName) {
    //             $query->where(function ($q) use ($searchName) {
    //                 $q->where('firstname', 'LIKE', '%' . $searchName . '%')
    //                     ->orWhere('lastname', 'LIKE', '%' . $searchName . '%');
    //             });
    //         }

    //         $yesvite_user = $query->paginate(10);
    //         return view('front.event.guest.get_user', compact('yesvite_user'))->render();
    //     }
    // }


    public function inviteByGroup(Request $request)
    {

        $users = $request->users;
        // dd($users);

        $userIds = session()->get('user_ids', []);
        foreach ($users as $value) {
            $id = $value['id'];
            $user_detail = User::where('id', $value['id'])->first();
            // dd($user_detail->profile);
            $userimage = ($user_detail->profile);
            // $useremail = $user_detail->input('email');
            // $phone = $user_detail->input('mobile');

            if ($user_detail) {


                $userEntry = [
                    'id' => $value['id'],
                    'firstname' => $user_detail->firstname,
                    'lastname' => $user_detail->lastname,
                    'invited_by' => $value['invited_by'],
                    'prefer_by' => $value['preferby'],
                    'profile' => (isset($userimage) && $userimage != '') ? $userimage : ''
                ];

                $userExists = array_filter($userIds, function ($entry) use ($id) {
                    return $entry['id'] === $id;
                });

                $userIds = array_filter($userIds, function ($entry) use ($id) {
                    return $entry['id'] !== $id;
                });

                $userIds[] = $userEntry;

                if (!empty($userExists)) {
                    $data[] = ['userdata' => $userEntry, 'is_duplicate' => 1];
                } else {
                    $data[] = ['userdata' => $userEntry, 'is_duplicate' => 0];
                }
            }
        }
        session()->put('user_ids', $userIds);

        // Prepare the view and send the response
        return response()->json([
            'view' => view('front.event.guest.addGuest', compact('data'))->render(),
            'success' => true,
            'data' => $data
        ]);
    }

    public function editEvent(Request $request)
    {
        $userid = Auth::guard('web')->user()->id;

        try {
            $getEventData = Event::with('event_schedule')->where('id', $request->event_id)->first();
            if ($getEventData != null) {
                $eventDetail['id'] = (!empty($getEventData->id) && $getEventData->id != NULL) ? $getEventData->id : "";
                $eventDetail['event_type_id'] = (!empty($getEventData->event_type_id) && $getEventData->event_type_id != NULL) ? $getEventData->event_type_id : "";
                $eventDetail['event_name'] = (!empty($getEventData->event_name) && $getEventData->event_name != NULL) ? $getEventData->event_name : "";
                $eventDetail['hosted_by'] = (!empty($getEventData->hosted_by) && $getEventData->hosted_by != NULL) ? $getEventData->hosted_by : "";
                $eventDetail['start_date'] = (!empty($getEventData->start_date) && $getEventData->start_date != NULL) ? $getEventData->start_date : "";
                $eventDetail['end_date'] = (!empty($getEventData->end_date) && $getEventData->end_date != NULL) ? $getEventData->end_date : "";
                $eventDetail['rsvp_by_date_set'] =  $getEventData->rsvp_by_date_set;
                $eventDetail['rsvp_by_date'] = (!empty($getEventData->rsvp_by_date) && $getEventData->rsvp_by_date != NULL) ? $getEventData->rsvp_by_date : "";
                $eventDetail['rsvp_start_time'] = (!empty($getEventData->rsvp_start_time) && $getEventData->rsvp_start_time != NULL) ? $getEventData->rsvp_start_time : "";
                $eventDetail['rsvp_start_timezone'] = (!empty($getEventData->rsvp_start_timezone) && $getEventData->rsvp_start_timezone != NULL) ? $getEventData->rsvp_start_timezone : "";
                $eventDetail['rsvp_end_time_set'] = $getEventData->rsvp_end_time_set;
                $eventDetail['rsvp_end_time'] = (!empty($getEventData->rsvp_end_time) && $getEventData->rsvp_end_time != NULL) ? $getEventData->rsvp_end_time : "";
                $eventDetail['rsvp_end_timezone'] = (!empty($getEventData->rsvp_end_timezone) & $getEventData->rsvp_end_timezone != NULL) ? $getEventData->rsvp_end_timezone : "";
                $eventDetail['event_location_name'] = (!empty($getEventData->event_location_name) & $getEventData->event_location_name != NULL) ? $getEventData->event_location_name : "";
                $eventDetail['latitude'] = (!empty($getEventData->latitude) & $getEventData->latitude != NULL) ? $getEventData->latitude : "";
                $eventDetail['longitude'] = (!empty($getEventData->longitude) & $getEventData->longitude != NULL) ? $getEventData->longitude : "";
                $eventDetail['address_1'] = (!empty($getEventData->address_1) & $getEventData->address_1 != NULL) ? $getEventData->address_1 : "";
                $eventDetail['address_2'] = (!empty($getEventData->address_2) & $getEventData->address_2 != NULL) ? $getEventData->address_2 : "";
                $eventDetail['state'] = (!empty($getEventData->state) & $getEventData->state != NULL) ? $getEventData->state : "";
                $eventDetail['zip_code'] = (!empty($getEventData->zip_code) & $getEventData->zip_code != NULL) ? $getEventData->zip_code : "";
                $eventDetail['city'] = (!empty($getEventData->city) & $getEventData->city != NULL) ? $getEventData->city : "";
                $eventDetail['message_to_guests'] = (!empty($getEventData->message_to_guests) & $getEventData->message_to_guests != NULL) ? $getEventData->message_to_guests : "";
                $eventDetail['is_draft_save'] = $getEventData->is_draft_save;
                $eventDetail['step'] = ($getEventData->step != NULL) ? $getEventData->step : 0;
                $eventDetail['subscription_plan_name'] = ($getEventData->subscription_plan_name != NULL) ? $getEventData->subscription_plan_name : "";
                $eventDetail['subscription_invite_count'] = ($getEventData->subscription_invite_count != NULL) ? $getEventData->subscription_invite_count : 0;
                $eventDetail['event_images'] = [];
                $getEventImages = EventImage::where('event_id', $getEventData->id)->get();
                if (!empty($getEventImages)) {
                    foreach ($getEventImages as $imgVal) {
                        $eventImageData['id'] = $imgVal->id;
                        $eventImageData['image'] = asset('public/storage/event_images/' . $imgVal->image);
                        $eventDetail['event_images'][] = $eventImageData;
                    }
                }
                $eventDetail['invited_user_id'] = [];
                $eventDetail['co_host_list'] = [];
                $eventDetail['invited_guests'] = [];
                $eventDetail['guest_co_host_list'] = [];

                $invitedUser = EventInvitedUser::with('user')->where(['event_id' => $getEventData->id])->get();

                if (!empty($invitedUser)) {
                    foreach ($invitedUser as $guestVal) {
                        if ($guestVal->is_co_host == '0') {
                            if ($guestVal->user->is_user_phone_contact == '1') {
                                $invitedGuestDetail['first_name'] = (!empty($guestVal->user->firstname) && $guestVal->user->firstname != NULL) ? $guestVal->user->firstname : "";
                                $invitedGuestDetail['last_name'] = (!empty($guestVal->user->lastname) && $guestVal->user->lastname != NULL) ? $guestVal->user->lastname : "";
                                $invitedGuestDetail['email'] = (!empty($guestVal->user->email) && $guestVal->user->email != NULL) ? $guestVal->user->email : "";
                                $invitedGuestDetail['country_code'] = (!empty($guestVal->user->country_code) && $guestVal->user->country_code != NULL) ? strval($guestVal->user->country_code) : "";
                                $invitedGuestDetail['phone_number'] = (!empty($guestVal->user->phone_number) && $guestVal->user->phone_number != NULL) ? $guestVal->user->phone_number : "";
                                $invitedGuestDetail['prefer_by'] = (!empty($guestVal->prefer_by) && $guestVal->prefer_by != NULL) ? $guestVal->prefer_by : "";
                                $eventDetail['invited_guests'][] = $invitedGuestDetail;
                            } elseif ($guestVal->user->is_user_phone_contact == '0') {
                                $invitedUserIdDetail['user_id'] = (!empty($guestVal->user_id) && $guestVal->user_id != NULL) ? $guestVal->user_id : "";
                                $invitedUserIdDetail['prefer_by'] = (!empty($guestVal->prefer_by) && $guestVal->prefer_by != NULL) ? $guestVal->prefer_by : "";
                                $eventDetail['invited_user_id'][] = $invitedUserIdDetail;
                            }
                        } else if ($guestVal->is_co_host == '1') {
                            if ($guestVal->user->is_user_phone_contact == '1') {
                                $guestCoHostDetail['first_name'] = (!empty($guestVal->user->firstname) && $guestVal->user->firstname != NULL) ? $guestVal->user->firstname : "";
                                $guestCoHostDetail['last_name'] = (!empty($guestVal->user->lastname) && $guestVal->user->lastname != NULL) ? $guestVal->user->lastname : "";
                                $guestCoHostDetail['email'] = (!empty($guestVal->user->email) && $guestVal->user->email != NULL) ? $guestVal->user->email : "";
                                $guestCoHostDetail['country_code'] = (!empty($guestVal->user->country_code) && $guestVal->user->country_code != NULL) ? strval($guestVal->user->country_code) : "";
                                $guestCoHostDetail['phone_number'] = (!empty($guestVal->user->phone_number) && $guestVal->user->phone_number != NULL) ? $guestVal->user->phone_number : "";
                                $guestCoHostDetail['prefer_by'] = (!empty($guestVal->prefer_by) && $guestVal->prefer_by != NULL) ? $guestVal->prefer_by : "";
                                $eventDetail['guest_co_host_list'][] = $guestCoHostDetail;
                            } elseif ($guestVal->user->is_user_phone_contact == '0') {
                                $coHostDetail['user_id'] = (!empty($guestVal->user_id) && $guestVal->user_id != NULL) ? $guestVal->user_id : "";
                                $coHostDetail['prefer_by'] = (!empty($guestVal->prefer_by) && $guestVal->prefer_by != NULL) ? $guestVal->prefer_by : "";
                                $eventDetail['co_host_list'][] = $coHostDetail;
                            }
                        }
                    }
                    $eventDetail['remaining_invite_count'] = ($getEventData->subscription_invite_count != NULL) ? ($getEventData->subscription_invite_count - (count($eventDetail['invited_user_id']) + count($eventDetail['invited_guests']))) : 0;
                }
                // $eventDetail['events_schedule_list'] = [];
                $eventDetail['events_schedule_list'] = null;
                if ($getEventData->event_schedule->isNotEmpty()) {

                    $eventDetail['events_schedule_list'] = new stdClass();
                    if ($getEventData->event_schedule->first()->type == '1') {


                        $eventDetail['events_schedule_list']->start_time =  ($getEventData->event_schedule->first()->start_time != NULL) ? $getEventData->event_schedule->first()->start_time : "";

                        $eventDetail['events_schedule_list']->event_start_date = ($getEventData->event_schedule->first()->event_date != null) ? $getEventData->event_schedule->first()->event_date : "";
                    }

                    $eventDetail['events_schedule_list']->data = [];
                    foreach ($getEventData->event_schedule as $eventsScheduleVal) {
                        if ($eventsScheduleVal->type == '2') {

                            $eventscheduleData["id"] = $eventsScheduleVal->id;
                            $eventscheduleData["activity_title"] = $eventsScheduleVal->activity_title;
                            $eventscheduleData["start_time"] = ($eventsScheduleVal->start_time !== null) ? $eventsScheduleVal->start_time : "";
                            $eventscheduleData["end_time"] = ($eventsScheduleVal->end_time !== null) ? $eventsScheduleVal->end_time : "";
                            $eventscheduleData['event_date'] = ($eventsScheduleVal->event_date != null) ? $eventsScheduleVal->event_date : "";
                            $eventscheduleData["type"] = $eventsScheduleVal->type;
                            $eventDetail['events_schedule_list']->data[] = $eventscheduleData;
                        }
                    }
                    if ($getEventData->event_schedule->last()->type == '3') {

                        $eventDetail['events_schedule_list']->end_time =  ($getEventData->event_schedule->last()->end_time !== null) ? $getEventData->event_schedule->last()->end_time : "";
                        $eventDetail['events_schedule_list']->event_end_date = ($getEventData->event_schedule->last()->event_date != null) ? $getEventData->event_schedule->last()->event_date : "";
                    }
                }
                $eventDetail['greeting_card_list'] = [];
                if (!empty($getEventData->greeting_card_id) && $getEventData->greeting_card_id != NULL) {


                    $greeting_card_ids = array_map('intval', explode(',', $getEventData->greeting_card_id));

                    $eventDetail['greeting_card_list'] = $greeting_card_ids;
                }

                $eventDetail['gift_registry_list'] = [];
                if (!empty($getEventData->gift_registry_id) && $getEventData->gift_registry_id != NULL) {

                    $gift_registry_ids = array_map('intval', explode(',', $getEventData->gift_registry_id));

                    $eventDetail['gift_registry_list'] = $gift_registry_ids;
                }

                $eventDetail['event_setting'] = "";

                $eventSettings = EventSetting::where('event_id', $getEventData->id)->first();

                if ($eventSettings != NULL) {
                    $eventDetail['event_setting'] = [

                        "allow_for_1_more" => $eventSettings->allow_for_1_more,
                        "allow_limit" => strval($eventSettings->allow_limit),
                        "adult_only_party" => $eventSettings->adult_only_party,

                        "rsvp_by_date" => $getEventData->rsvp_by_date,
                        "thank_you_cards" => $eventSettings->thank_you_cards,
                        "add_co_host" => $eventSettings->add_co_host,
                        "gift_registry" => $eventSettings->gift_registry,
                        "events_schedule" => $eventSettings->events_schedule,
                        "event_wall" => $eventSettings->event_wall,
                        "guest_list_visible_to_guests" => $eventSettings->guest_list_visible_to_guests,
                        "podluck" => $eventSettings->podluck,
                        "rsvp_updates" => $eventSettings->rsvp_updates,
                        "event_wall_post" => $eventSettings->event_wall_post,
                        "send_event_dater_reminders" => $eventSettings->send_event_dater_reminders,
                        "request_event_photos_from_guests" => $eventSettings->request_event_photos_from_guests
                    ];
                }


                $eventDetail['podluck_category_list'] = [];



                $eventpotluckData =  EventPotluckCategory::with(['users', 'event_potluck_category_item' => function ($query) {
                    $query->with(['users', 'user_potluck_items' => function ($subquery) {
                        $subquery->with('users')->sum('quantity');
                    }]);
                }])->withCount('event_potluck_category_item')->where('event_id', $getEventData->id)->get();

                if (!empty($eventpotluckData)) {
                    $potluckCategoryData = [];
                    $potluckDetail['total_potluck_item'] = EventPotluckCategoryItem::where('event_id', $getEventData->id)->count();

                    foreach ($eventpotluckData as $value) {
                        $potluckCategory['id'] = $value->id;
                        $potluckCategory['category'] = $value->category;
                        $potluckCategory['created_by'] = $value->users->firstname . ' ' . $value->users->lastname;
                        $potluckCategory['quantity'] = $value->quantity;
                        $potluckCategory['items'] = [];
                        if (!empty($value->event_potluck_category_item) || $value->event_potluck_category_item != null) {

                            foreach ($value->event_potluck_category_item as $itemValue) {

                                $potluckItem['id'] =  $itemValue->id;
                                $potluckItem['description'] =  $itemValue->description;
                                $potluckItem['is_host'] = ($itemValue->user_id == $userid) ? 1 : 0;
                                $potluckItem['requested_by'] =  $itemValue->users->firstname . ' ' . $itemValue->users->lastname;
                                $potluckItem['quantity'] =  $itemValue->quantity;
                                $potluckItem['self_bring_item'] =  $itemValue->self_bring_item;
                                $spoken_for = UserPotluckItem::where('event_potluck_item_id', $itemValue->id)->sum('quantity');
                                $potluckItem['spoken_quantity'] =  $spoken_for;
                                $potluckItem['item_carry_users'] = [];

                                foreach ($itemValue->user_potluck_items as $itemcarryUser) {
                                    $userPotluckItem['id'] = $itemcarryUser->id;
                                    $userPotluckItem['user_id'] = $itemcarryUser->user_id;
                                    $userPotluckItem['is_host'] = ($itemcarryUser->user_id == $userid) ? 1 : 0;
                                    $userPotluckItem['profile'] =  empty($itemcarryUser->users->profile) ?  "" : asset('public/storage/profile/' . $itemcarryUser->users->profile);
                                    $userPotluckItem['first_name'] = $itemcarryUser->users->firstname;
                                    $userPotluckItem['quantity'] = (!empty($itemcarryUser->quantity) || $itemcarryUser->quantity != NULL) ? $itemcarryUser->quantity : "0";
                                    $userPotluckItem['last_name'] = $itemcarryUser->users->lastname;
                                    $potluckItem['item_carry_users'][] = $userPotluckItem;
                                }
                                $potluckCategory['items'][] = $potluckItem;
                            }
                        }
                        $eventDetail['podluck_category_list'][] = $potluckCategory;
                    }
                }



                return response()->json(['status' => 1, 'message' => "Event data", "data" => $eventDetail]);
            } else {
                return response()->json(['status' => 0, 'message' => "data not found"]);
            }
        } catch (QueryException $e) {
            return response()->json(['status' => 0, 'message' => 'Db error']);
        } catch (Exception $e) {

            return response()->json(['status' => 0, 'message' => 'Something went wrong']);
        }
    }
}
