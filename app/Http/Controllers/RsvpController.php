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
    EventSetting,
    User,
    UserNotificationType
};
use Auth;
use App\Models\contact_sync;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as Exception;
use Kreait\Laravel\Firebase\Facades\Firebase;

class RsvpController extends BaseController
{
    protected $database;
    protected $chatRoom;
    protected $firebase;
    protected $usersReference;
    public function __construct()
    {
        $this->firebase = Firebase::database();
        $this->usersReference = $this->firebase->getReference('users');
        // $this->database = $database;
        // $this->chatRoom = $this->database->getReference();
    }
    /**
     * 
     * Display a listing of the resource.
     */
    // public function index($userId, $eventId)
    // {
    //     $title = 'RSVP';
    //     $page = 'front.rsvp';
    //     $js = ['rsvp'];



    //     $event_id =  $eventId;
    //     $user_id = $userId;
    //     $event = Event::with(['user', 'event_image', 'event_settings'])->where('id', decrypt($eventId))->first();

    //     if ($event != null) {
    //         $co_hosts = EventInvitedUser::with('user')->where(['event_id' => decrypt($eventId),'is_co_host' => '1'])->first();
    //         $isInvited = EventInvitedUser::where(['event_id' => decrypt($eventId), 'user_id' => decrypt($userId)])->first();
    //         if ($isInvited != null) {

    //             if ($event->event_settings) {
    //                 $eventData = [];

    //                 if ($event->event_settings->allow_for_1_more == '1') {
    //                     $eventData[] = "1+ limit ( limit " . $event->event_settings->allow_limit . ")";
    //                 }
    //                 if ($event->event_settings->adult_only_party == '1') {
    //                     $eventData[] = "Adults Only";
    //                 }
    //                 if ($event->rsvp_by_date_set == '1') {
    //                     $eventData[] = 'RSVP By :- ' . date('F d, Y', strtotime($event->rsvp_by_date));
    //                 }
    //                 if ($event->event_settings->podluck == '1') {
    //                     $eventData[] = "Event Potluck";
    //                 }
    //                 if ($event->event_settings->gift_registry == '1') {
    //                     $eventData[] = "Gift Registry";
    //                 }
    //                 if ($event->event_settings->events_schedule == '1') {
    //                     $eventData[] = "Event has Schedule";
    //                 }

    //                 if ($event->start_date != $event->end_date) {
    //                     $eventData[] = "Multiple Day Event";
    //                 }
    //                 // if (empty($eventData)) {
    //                 //     $eventData[] = date('F d, Y', strtotime($event->start_date));
    //                 //     $numberOfGuest = EventInvitedUser::where('event_id', $event->id)->count();
    //                 //     $eventData[] = "Number of guests : " . $numberOfGuest;
    //                 // }
    //                 $event['event_detail'] = $eventData;
    //             }

    //             $event['profile'] =  ($event->user->profile != null) ? asset('storage/profile/' . $event->user->profile) : "";


    //             $giftRegistryDetails = [];
    //             if ($event->gift_registry_id != null || $event->gift_registry_id != "") {

    //                 if (!empty($event->gift_registry_id)) {
    //                     $giftregistry = explode(',', $event->gift_registry_id);

    //                     $giftregistryData = EventGiftRegistry::whereIn('id', $giftregistry)->get();
    //                     foreach ($giftregistryData as $value) {
    //                         $giftRegistryDetail['id'] = $value->id;
    //                         $giftRegistryDetail['registry_recipient_name'] = $value->registry_recipient_name;
    //                         $giftRegistryDetail['registry_link'] = $value->registry_link;
    //                         $giftRegistryDetails[] = $giftRegistryDetail;
    //                     }
    //                 }
    //             }

    //             $is_podluck = (isset($event->event_settings->adult_only_party) ? $event->event_settings->adult_only_party : "");

    //             $is_adultOnly = (isset($event->event_settings->adult_only_party) ? $event->event_settings->adult_only_party : "");


    //             return view('layout', compact(
    //                 'title',
    //                 'page',
    //                 'js',
    //                 'event',
    //                 'giftRegistryDetails',
    //                 'isInvited',
    //                 'event_id',
    //                 'user_id',
    //                 'is_podluck',
    //                 'is_adultOnly',
    //                 'co_hosts'
    //             ));
    //             // return redirect('home');
    //         }
    //         return redirect('home')->with('error', 'You are not connect with this event');
    //     }
    //     return redirect('home')->with('error', 'You are not inivted in this event');
    // }



    public function index($event_invited_user_id, $eventId)
    {
        $title = 'RSVP';
        $page = 'front.rsvp';
        $js = ['rsvp'];
        $css = 'message.css';

        $event_id =  decrypt($eventId);
        $event_invited_user_id = decrypt($event_invited_user_id);
        // dd($event_invited_user_id);
        // $user_id = decrypt($event_invited_user_id);

        $user_id= EventInvitedUser::where('id',$event_invited_user_id)->first()->user_id;
        // dd($user_id);
        $sync_id="";
        if($user_id==null || $user_id==""){
            $sync_id= EventInvitedUser::where('id',$event_invited_user_id)->first()->sync_id;
        }
        try {
            $eventDetail = Event::with(['user', 'event_image', 'event_schedule', 'event_settings', 'event_invited_user' => function ($query) {
                $query->where('is_co_host', '1')->with('user');
            }])->where('id', $event_id)->first();

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
            $eventDetails['host_first_name'] = $eventDetail->user->firstname;
            $eventDetails['host_last_name'] = $eventDetail->user->lastname;
            $eventDetails['is_host'] = ($eventDetail->user_id == $user_id) ? 1 : 0;
            $eventDetails['event_date'] = $eventDetail->start_date;
            $eventDetails['event_end_date'] = ($eventDetail->end_date != "") ? $eventDetail->end_date : "";
            $eventDetails['event_time'] = $eventDetail->rsvp_start_time;
            $eventDetails['host_id'] = $eventDetail->user_id;
            $eventDetails['event_end_time'] = ($eventDetail->rsvp_end_time != "") ? $eventDetail->rsvp_end_time : "";

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
                $coHostDetail['name'] = $hostValues->user->firstname . ' ' . $hostValues->user->lastname;
                $coHostDetail['first_name'] = $hostValues->user->firstname;
                $coHostDetail['last_name'] = $hostValues->user->lastname;
                $coHostDetail['message'] = $hostValues->message;
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
            $eventDetails['logitude'] = (!empty($eventDetail->longitude) || $eventDetail->longitude != null) ? $eventDetail->longitude : "";





            $eventsScheduleList = [];
            $event_time = [];

            foreach ($eventDetail->event_schedule as $key => $value) {
                $totalTime = "";
                $event_name =  $value->activity_title;

                if ($value->type == '1') {
                    $nextval = $eventDetail->event_schedule[$key + 1];

                    $event_name = "Start Event";
                    $stattim = $value->start_time;
                    $event_time['start'] = $value->start_time;

                    $endtim = "";
                    if ($nextval->type == '2') {
                        $endtim =  $nextval->start_time;
                    } else if ($nextval->type == '3') {
                        $endtim =  $nextval->end_time;
                    }
                    if (
                        !empty($stattim) &&
                        !empty($endtim)
                    ) {

                        $totalTime =  getDeferentBetweenTime($stattim, $endtim);
                    }
                } elseif ($value->type == '2') {
                    $stattim = $value->start_time;
                    $endtim = $value->end_time;;
                    $totalTime =  getDeferentBetweenTime($stattim, $endtim);
                } elseif ($value->type == '3') {
                    $event_name = "End Event";
                    $prevval = $eventDetail->event_schedule[$key - 1];
                    $event_time['end'] = $value->end_time;


                    $endtim = $value->end_time;
                    $stattim = "";
                    if ($prevval->type == '2') {
                        $stattim =  $prevval->end_time;
                    } else if ($prevval->type == '1') {
                        $stattim =  $prevval->start_time;
                    }

                    if (
                        $stattim != "" &&
                        $endtim != ""
                    ) {

                        $totalTime =  getDeferentBetweenTime($stattim, $endtim);
                    }
                }
                $scheduleDetail['id'] = $value->id;
                $scheduleDetail['activity_title'] = $event_name;
                $scheduleDetail['start_time'] = ($value->start_time != null) ? $value->start_time : "";
                $scheduleDetail['end_time'] = ($value->end_time != null) ? $value->end_time : "";
                $scheduleDetail['total_time'] = $totalTime;
                $scheduleDetail['type'] = $value->type;

                $eventsScheduleList[] = $scheduleDetail;
            }

            $eventDetails['event_schedule'] = $eventsScheduleList;
            $eventDetails['event_timings'] = $event_time;
            $eventDetails['event_potluck'] = EventSetting::where('event_id', $event_id)->pluck('podluck')->first();
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
                    // $eventData[] = "Can Bring Guests ( limit " . $eventDetail->event_settings->allow_limit . ")";
                    $eventData[] = "+1 Limit (" . $eventDetail->event_settings->allow_limit . ")";
                }
                if ($eventDetail->event_settings->adult_only_party == '1') {
                    $eventData[] = "Adults Only";
                }
                if ($eventDetail->rsvp_by_date_set == '1') {
                    $eventData[] = 'RSVP By :- ' . date('F d, Y', strtotime($eventDetail->rsvp_by_date));
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
                // if(!empty($eventDetails['co_host_list'])){
                if ($coHosts != NULL) {
                    $eventData[] = "Co-Host";
                }

                $eventDetails['event_detail'] = $eventData;
            }
            $eventDetails['total_limit'] = $eventDetail->event_settings->allow_limit;
            $eventInfo['guest_view'] = $eventDetails;

            //  Host view //
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
            $eventAboutHost['comment'] = EventPostComment::where(['event_id' => $eventDetail->id, 'user_id' => $user_id])->count();
            $total_photos = EventPostImage::where(['event_id' => $eventDetail->id])->count();
            $eventAboutHost['photo_uploaded'] = $total_photos;
            $eventAboutHost['total_invite'] =  count(getEventInvitedUser($event_id));
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

            $getInvitedusers = getInvitedUsersList($event_id);

            $sync_contact_user_id = "";
            $user_email="";
            // dd($user_id);
            if($user_id!=null){
                $user_email = User::where('id', $user_id)->first();
            }
            if ($user_email == "") {
                $user_sync_email = contact_sync::where('id', $sync_id)->first();
                $email = $user_sync_email->email;
                $sync_contact_user_id = $user_id;
                $user_firstname = ($user_sync_email->firstName != "" || $user_sync_email->firstName != null) ? $user_sync_email->firstName : "";
                $user_lastname = ($user_sync_email->lastName != "" || $user_sync_email->lastName != null) ? $user_sync_email->lastName : "";
                $user_id = User::where('email', $email)->first()->id;
            } else {
                $email = $user_email->email;
                $user_firstname = ($user_email->firstname != "" || $user_email->firstname != null) ? $user_email->firstname : "";
                $user_lastname = ($user_email->lastname != "" || $user_email->lastname != null) ? $user_email->lastname : "";
            }

            $is_host = "";
            if ($user_id == $eventDetail->user_id) {
                $is_host = "1";
            }

            $rsvp_status = "";
            if ($user_id != $eventDetail->user_id) {
                $rsvp_status = EventInvitedUser::where(['event_id' => $event_id, 'user_id' => $user_id, 'is_co_host' => '0'])->first()->rsvp_status;
            }
            $rsvp = "";
            if ($rsvp_status != "" || !empty($rsvp_status)) {
                $rsvp = "1";
            }
            $messages = [];
            $userName = "";
            if (auth()->id()) {
                $userId = auth()->id();
                $userData = User::findOrFail($userId);
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
                    'userStatus' => 'Online',
                    'userTypingStatus' => 'Not typing...'
                ];

                // Create a new user node with the userId
                $userRef = $this->usersReference->getChild((string)$userId);
                $userSnapshot = $userRef->getValue();
                $updateFirebase = false;

                if ($userSnapshot) {
                    if ($userSnapshot['userName'] != $userData->firstname . ' ' . $userData->lastname || $userSnapshot['userProfile'] != url('/public/storage/profile/' . $userData->profile)) {
                        $updateFirebase = true;
                    }
                    // User exists, update the existing data
                    $userRef->update($updateData);
                } else {
                    // User does not exist, create a new user node
                    $userRef->set($updateData);
                }

                $reference = $this->firebase->getReference('overview/' . $userId);
                $messages = $reference->getValue();
                $updateData = [
                    'contactName' => $userName,
                    'receiverProfile' => url('/public/storage/profile/' . $userData->profile)
                ];
                $updateGroupData = [
                    'name' => $userName,
                    'image' => url('/public/storage/profile/' . $userData->profile)
                ];
                if ($updateFirebase == true) {
                    if (!empty($messages)) {

                        foreach ($messages as $message) {
                            if (isset($message['group'])  && ($message['group'] == "true" || $message['group'] == true)) {
                                $reference = $this->firebase->getReference('Groups/' . $message['conversationId'] . '/groupInfo/profiles');
                                $profiles = $reference->getValue();
                                if ($profiles) {

                                    foreach ($profiles as $key => $profile) {
                                        if ($profile['id'] == $userId) {
                                            $reference = $this->firebase->getReference('Groups/' . $message['conversationId'] . '/groupInfo/profiles/' . $key);
                                            $reference->update($updateGroupData);
                                            break;
                                        }
                                    }
                                }
                            } else {
                                if (isset($message['contactId'])) {
                                    $reference = $this->firebase->getReference('overview/' . $message['contactId'] . '/' . $message['conversationId']);
                                    if ($reference) {
                                        $reference->update($updateData);
                                    }
                                }
                            }
                        }
                    }
                }
                if ($messages) {
                    uasort($messages, function ($a, $b) {
                        // Check if either of the items has 'isPin' set to '1'
                        $isPinA = isset($a['isPin']) && $a['isPin'] == '1';
                        $isPinB = isset($b['isPin']) && $b['isPin'] == '1';

                        // If both have the same 'isPin' status, sort by 'timeStamp'
                        if ($isPinA == $isPinB) {
                            $timeStampA = isset($a['timeStamp']) ? $a['timeStamp'] : PHP_INT_MAX;
                            $timeStampB = isset($b['timeStamp']) ? $b['timeStamp'] : PHP_INT_MAX;
                            return $timeStampB <=> $timeStampA;
                        }

                        // Otherwise, prioritize the item with 'isPin' set to '1'
                        return $isPinB <=> $isPinA;
                    });
                }
                if ($messages == null) {
                    $messages = [];
                }
                // dd($messages);
                // $title = 'Home';
                // $page = 'front.chat.messages';
                // $css = 'message.css';
                // $css1 = 'audio.css';
            }
            return view('layout', compact(
                'title',
                'page',
                'js',
                'css',
                'eventInfo',
                'event_id',
                'user_id',
                'sync_contact_user_id',
                'email',
                'getInvitedusers',
                'rsvp_status',
                'messages',
                'userName',
                'user_firstname',
                'user_lastname',
                'is_host',
                'event_invited_user_id'
            ));
            // return response()->json(['status' => 1, 'data' => $eventInfo, 'message' => "About event"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {
            dd($e);
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request);

        $userId = decrypt($request->user_id);
        $eventId = decrypt($request->event_id);
        if ($request->input('sync_id') != "") {
            $sync_id = decrypt($request->input('sync_id'));
        } else {
            $sync_id = "";
        }


        $kids = (isset($request->kids) && $request->kids) ? (int)$request->kids : 0;
        $adults = (isset($request->adults) && $request->adults) ? (int)$request->adults : 0;
        // dd($kids,$adults);

        try {
            $checkEvent = Event::where(['id' => $eventId])->first();
            if ($checkEvent->end_date < date('Y-m-d')) {
                return redirect('rsvp/' . $request->event_invited_user_id . '/' . $request->event_id)->with('error', "Event is past , you can't attempt RSVP");
            }
            DB::beginTransaction();
            if ($sync_id != "" || $sync_id != null) {
                $rsvpSent = EventInvitedUser::whereHas('user', function ($query) {})->where(['user_id' => $userId, 'sync_id' => $sync_id, 'is_co_host' => '0', 'event_id' => $eventId])->first();
            } else {
                $rsvpSent = EventInvitedUser::whereHas('user', function ($query) {
                    $query->where('app_user', '1');
                })->where(['user_id' => $userId, 'is_co_host' => '0', 'event_id' => $eventId])->first();
            }

            // dd($rsvpSent);
            $rsvpSentAttempt = $rsvpSent ? $rsvpSent->rsvp_status : "";
            if ($rsvpSent != null) {
                $rsvp_attempt = "";
                if ($rsvpSentAttempt == NULL) {
                    $rsvp_attempt =  'first';
                } else if ($rsvpSentAttempt == '0' && $request->rsvp_status == '1') {
                    $rsvp_attempt =  'no_to_yes';
                } else if ($rsvpSentAttempt == '1' && $request->rsvp_status == '0') {
                    $rsvp_attempt =  'yes_to_no';
                }

                $rsvpSent->event_id = $eventId;

                $rsvpSent->user_id = $userId;

                $rsvpSent->rsvp_status = $request->rsvp_status;

                $rsvpSent->adults = $adults;

                $rsvpSent->kids = $kids;

                $rsvpSent->message_to_host = $request->message_to_host;
                $rsvpSent->rsvp_attempt = $rsvp_attempt;

                $rsvpSent->read = '1';
                $rsvpSent->rsvp_d = '1';

                $rsvpSent->event_view_date = date('Y-m-d');

                $rsvpSent->save();

                if ($rsvpSent->save()) {
                    EventPost::where('event_id', $eventId)
                        ->where('user_id', $userId)
                        ->where('post_type', '4')->delete();
                    $postMessage = [];
                    $postMessage = [
                        'status' => ($request->rsvp_status == '0') ? '2' : '1',
                        'adults' =>  $adults,
                        'kids' => $kids,
                    ];
                    $creatEventPost = new EventPost();
                    $creatEventPost->event_id = $eventId;
                    $creatEventPost->user_id =  $userId;
                    $creatEventPost->post_message = json_encode($postMessage);
                    $creatEventPost->post_privacy = "1";
                    $creatEventPost->post_type = "4";
                    $creatEventPost->commenting_on_off = "0";
                    $creatEventPost->is_in_photo_moudle = "0";
                    $creatEventPost->save();
                    // dd($creatEventPost);
                }
                //     if(!empty($request->input('notifications')) &&$request->input('notifications')[0]=="1"){

                // }

                // if (empty($request->input('notifications'))) {
                //     if ($sync_id != "" || $sync_id != null) {
                //         $rsvpSent = EventInvitedUser::whereHas('user', function ($query) {})->where(['user_id' => $userId, 'sync_id' => $sync_id, 'is_co_host' => '0', 'event_id' => $eventId])->first();
                //     } else {
                //         $rsvpSent = EventInvitedUser::whereHas('user', function ($query) {
                //             $query->where('app_user', '1');
                //         })->where(['user_id' => $userId, 'is_co_host' => '0', 'event_id' => $eventId])->first();


                //     }

                //     // $updateUser = EventInvitedUser::where(['event_id' => $input['event_id'], 'user_id' => $user->id])->first();
                //     if ($rsvpSent != null) {
                //         $rsvpSent->notification_on_off = '0';
                //         $rsvpSent->save();
                //     }
                // }

                if ($userId != "" || $userId != null) {
                    if (!empty($request->input('notifications'))) {
                        foreach ($request->input('notifications') as $value) {
                            if ($value == "wall_post") {
                                $updateNotification = UserNotificationType::where(['type' => 'wall_post', 'user_id' => $userId]);
                                if ($updateNotification) {
                                    $updateNotification->update(['push' => '1']);
                                    $updateNotifications = UserNotificationType::where('user_id', $userId)->whereNot('type', 'wall_post');
                                    $updateNotifications->update(['push' => '0']);
                                }
                            } elseif ($value == "guest_rsvp") {
                                $updateNotification = UserNotificationType::where(['type' => 'guest_rsvp', 'user_id' => $userId]);
                                if ($updateNotification) {
                                    $updateNotification->update(['push' => '1']);
                                    $updateNotification = UserNotificationType::where('user_id', $userId)->whereNot('type', 'guest_rsvp');
                                    $updateNotification->update(['push' => '0']);
                                }
                            } elseif ($value == "1") {
                                $updateNotifications = UserNotificationType::where('user_id', $userId);
                                if ($updateNotifications) {
                                    $updateNotifications->update(['push' => '1']);
                                }
                            }
                        }
                    } else {
                        $updateUser = EventInvitedUser::where(['user_id' => $userId, 'is_co_host' => '0', 'event_id' => $eventId])->first();
                        if ($updateUser != null) {
                            $updateUser->notification_on_off = "0";
                            $updateUser->save();
                        }
                    }
                }
                if ($request->rsvp_status == "0") {
                    if ($sync_id != "" || $sync_id != null) {
                        $updateUser = EventInvitedUser::where(['user_id' => $userId, 'sync_id' => $sync_id, 'is_co_host' => '0', 'event_id' => $eventId])->first();
                        if ($updateUser != null) {
                            $updateUser->notification_on_off = "0";
                            $updateUser->save();
                        }
                    } else {
                        $updateUser = EventInvitedUser::where(['user_id' => $userId, 'is_co_host' => '0', 'event_id' => $eventId])->first();
                        if ($updateUser != null) {
                            $updateUser->notification_on_off = "0";
                            $updateUser->save();
                        }
                    }
                }
                if ($request->rsvp_status == "1") {
                    if ($sync_id != "" || $sync_id != null) {
                        $updateUser = EventInvitedUser::where(['user_id' => $userId, 'sync_id' => $sync_id, 'is_co_host' => '0', 'event_id' => $eventId])->first();
                        if ($updateUser != null) {
                            $updateUser->notification_on_off = "1";
                            $updateUser->save();
                        }
                    } else {
                        $updateUser = EventInvitedUser::where(['user_id' => $userId, 'is_co_host' => '0', 'event_id' => $eventId])->first();
                        if ($updateUser != null) {
                            $updateUser->notification_on_off = "1";
                            $updateUser->save();
                        }
                    }
                }

                $notificationParam = [

                    'sender_id' => $userId,
                    'event_id' => $eventId,
                    'rsvp_status' => $request->rsvp_status,
                    'kids' =>  $kids,
                    'adults' => $adults,
                    'rsvp_video' => "",
                    'rsvp_message' => $request->message_to_host,
                    'post_id' => "",
                    'rsvp_attempt' => $rsvp_attempt
                ];

                DB::commit();

                sendNotification('sent_rsvp', $notificationParam);

                // return  redirect()->route('front.home')->with('success', 'Rsvp sent Successfully');
                if ($request->rsvp_status == "1") {
                    return redirect('rsvp/' . $request->event_invited_user_id . '/' . $request->event_id)->with('msg', 'You are going to this event');
                } elseif ($request->rsvp_status == "0") {
                    return redirect('rsvp/' . $request->event_invited_user_id . '/' . $request->event_id)->with('msg', 'You declined to go to this event');
                }
            }
            return redirect('rsvp/' . $request->event_invited_user_id . '/' . $request->event_id)->with('error', 'Rsvp not sent');
        } catch (QueryException $e) {
            return redirect('rsvp/' . $request->event_invited_user_id . '/' . $request->event_id)->with('error', 'DB error');
            DB::rollBack();
        } catch (\Exception $e) {
            dd($e);
            return redirect('rsvp/' . $request->event_invited_user_id . '/' . $request->event_id)->with('error', 'Something went wrong');
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function CheckRsvpStatus(Request $request)
    {
        $user_id = decrypt($request->input('user_id'));
        $event_id = decrypt($request->input('event_id'));

        if ($request->input('sync_id') != "") {
            $sync_id = decrypt($request->input('sync_id'));
        } else {
            $sync_id = "";
        }

        if ($sync_id != "") {
            $rsvp = EventInvitedUser::whereHas('user', function ($query) {
                // $query->where('app_user', '1');
            })->where(['user_id' => $user_id, 'sync_id' => $sync_id, 'event_id' => $event_id, 'is_co_host' => "0"])->first();
        } else {

            $rsvp = EventInvitedUser::whereHas('user', function ($query) {
                $query->where('app_user', '1');
            })->where(['user_id' => $user_id, 'event_id' => $event_id, 'is_co_host' => "0"])->first();
        }


        if ($rsvp == "" || $rsvp == null) {
            $rsvpStatus = "cohost";
        } else {
            $rsvpStatus = $rsvp->rsvp_status;
        }

        return response()->json(['status' => 1, 'rsvp_status' => $rsvpStatus]);
    }
}
