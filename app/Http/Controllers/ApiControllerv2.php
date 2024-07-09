<?php



namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;

use Location;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Audio\Mp3;
use FFMpeg\Exception\RuntimeException;

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
    UserReportToPost,
    Group,
    GroupMember,
    UserNotificationType,
    UserProfilePrivacy,
    UserSeenStory,
    UserSubscription,
    VersionSetting
};
use Illuminate\Support\Facades\Http;
// Rules //
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Rules\CheckUserEvent;
use App\Rules\checkUserEventPost;
use App\Rules\checkUserGreetingId;
use App\Rules\checkIsUserEvent;
use App\Rules\checkUserGiftregistryId;

use App\Rules\checkInvitedUser;
use Illuminate\Support\Facades\Hash;
// Rules //
use Illuminate\Support\Collection;

use Illuminate\Support\Facades\Validator;

use DateTime;
// use Validator;
use Laravel\Passport\Token;

use Illuminate\Support\Facades\Storage;

use Illuminate\Validation\Rule;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as Exception;
use Throwable;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use App\Mail\InvitationEmail;
use DateException;
use Exception as GlobalException;
use FFI\Exception as FFIException;
use Illuminate\Support\Facades\Mail;
use LogicException;
use Illuminate\Database\Query\Builder;
use App\Jobs\SendInvitationMailJob as sendInvitation;
use App\Services\GooglePlayService;
use Illuminate\Support\Facades\Session;
use stdClass;
use App\Services\GooglePlayServices;

class ApiControllerv2 extends Controller


{
    protected $perPage;
    protected  $upcomingEventCount;

    protected $user;
    protected $pendingRsvpCount;
    protected $hostingCount;
    protected $invitedToCount;



    public function __construct(GooglePlayService $GooglePlayService)
    {

        $this->user = Auth::guard('api')->user();

        $this->perPage = 5;
        if ($this->user != null) {

            $this->upcomingEventCount = upcomingEventsCount($this->user->id);
            $this->pendingRsvpCount = pendingRsvpCount($this->user->id);

            $this->hostingCount = hostingCount($this->user->id);
            $this->invitedToCount = invitedToCount($this->user->id);
        }
    }





    public function sendThanks()
    {
        $commentDateTime = date('Y-m-d'); // Replace this with your actual timestamp

        // Convert the timestamp to a Carbon instance
        $commentTime = Carbon::parse($commentDateTime);

        // Calculate the time difference
        $timeAgo = $commentTime->diff(now()); // This will give the time ago format


        // Display the time ago
        if ($timeAgo->y > 0) {
            return $timeAgo->y . 'y';
        } elseif ($timeAgo->m > 0) {
            return $timeAgo->m . 'm';
        } elseif ($timeAgo->d > 0) {
            return $timeAgo->d . 'd';
        } elseif ($timeAgo->h > 0) {
            return $timeAgo->h . 'h';
        } elseif ($timeAgo->i > 0) {
            return $timeAgo->i . 'm';
        } elseif ($timeAgo->s > 0) {
            return $timeAgo->s . 's';
        } else {
            return 'just now';
        }
    }

    public function createProfessionalAccount(Request $request)

    {

        $user = Auth::guard('api')->user();

        $rawData = $request->getContent();


        $input = json_decode($rawData, true);

        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [

            'firstname' => 'required',

            'lastname' => 'required',

            'email' => 'required|email|unique:users',

            'password' => 'required|min:8',

            'account_type' => 'required|in:0,1',
            'company_name' => 'required'
        ]);

        $customMessages = [
            'account_type.in' => 'The account_type field must be 0 or 1',
        ];
        $validator->setCustomMessages($customMessages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorKeys = $errors->keys();
            $firstErrorKey = $errorKeys[0];
            $status = 0;
            if ($firstErrorKey == 'email') {
                $status = 2;
            }
            return response()->json(
                [
                    'status' => $status,
                    'message' => $validator->errors()->first()

                ],
            );
        }

        try {

            DB::beginTransaction();



            $randomString = Str::random(30);



            $proffesionalAccount = User::create([
                'firstname' => $input['firstname'],
                'lastname' => $input['lastname'],
                'email' => $input['email'],
                'account_type' => $input['account_type'],
                'company_name' => ($input['account_type'] == '1') ? $input['company_name'] : "",
                'password' => Hash::make($input['password']),
                'password_updated_date' => date('Y-m-d'),
                'remember_token' =>  $randomString,
                'user_parent_id' => $user->id
            ]);


            //event(new \App\Events\UserRegistered($proffesionalAccount));
            DB::commit();

            $userDetails = User::where('id', $proffesionalAccount->id)->first();

            $userData = [
                'username' => $userDetails->firstname . ' ' . $userDetails->lastname,
                'email' => $userDetails->email,
                'token' => $randomString
            ];
            Mail::send('emails.emailVerificationEmail', ['userData' => $userData], function ($message) use ($input) {
                $message->to($input['email']);
                $message->subject('Email Verification Mail');
            });


            return response()->json(['status' => 1, 'message' => "Your Proffesional account is registered sucessfully , Please verify your email"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'message' => "Something went wrong"]);
        }
    }



    public function changePassword(Request $request)
    {

        $rawData = $request->getContent();
        $user = Auth::guard('api')->user();
        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [
            'old_password' => ['required', 'min:8'],
            'password' => ['required', 'min:8'],
        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 0,
                    'message' => $validator->errors()->first()
                ],
            );
        }


        $user = User::where('id', $user->id)->first();

        if (Hash::check($input['old_password'], $user->password)) {
            $user->password = Hash::make($input['password']);
            $user->password_updated_date = date('Y-m-d');
            if ($user->save()) {

                return response()->json(['status' => 1, 'message' => 'Password changed successful']);
            } else {
                return response()->json(['status' => 0, 'message' => 'Password not updated please try again']);
            }
        } else {
            return response()->json(['status' => 0, 'message' => 'Old password does not match']);
        }
        // Delete the token record
    }

    public function notificationOnOff(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }


        $validator = Validator::make($input, [
            'event_id' => 'required',
            'is_owner' => ['required', 'in:0,1'],
            'status' => ['required', 'in:0,1'],

        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),

            ]);
        }

        try {

            DB::beginTransaction();


            if ($input['is_owner'] == '1') {


                $updateNotification = Event::where('id', $input['event_id'])->first();
                $updatedDate = $updateNotification->updated_at;
                if ($updateNotification != null) {
                    $updateNotification->timestamps = false;  // Disable timestamps
                    $updateNotification->notification_on_off = $input['status'];


                    $updateNotification->save();
                    $updateNotification->timestamps = true;  // Disable timestamps
                }
            } else {
                $updateUser = EventInvitedUser::where(['event_id' => $input['event_id'], 'user_id' => $user->id])->first();
                if ($updateUser != null) {
                    $updateUser->notification_on_off = $input['status'];
                    $updateUser->save();
                }
            }
            DB::commit();
            $message = "";
            if ($input['status'] == '0') {
                $message = "Notification is off successfully";
            } else {
                $message = "Notification is on successfully";
            }
            return response()->json(['status' => 1, 'message' => $message]);
        } catch (QueryException $e) {
            DB::Rollback();
            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }

    public function home(Request $request)
    {
        try {
            $user  = Auth::guard('api')->user();

            if ($user->is_first_login == '1') {
                $userIsLogin = User::where('id', $user->id)->first();
                $userIsLogin->is_first_login = '0';
                $userIsLogin->save();
            }
            $usercreatedList = Event::with(['user', 'event_settings', 'event_schedule'])->where('start_date', '>', date('Y-m-d'))
                ->where('user_id', $user->id)
                ->where('is_draft_save', '0')
                ->orderBy('start_date', 'ASC')
                ->get();

            $invitedEvents = EventInvitedUser::whereHas('user', function ($query) {

                $query->where('app_user', '1');
            })->where('user_id', $user->id)->get()->pluck('event_id');

            $invitedEventsList = Event::with(['event_image', 'user', 'event_settings', 'event_schedule'])

                ->whereIn('id', $invitedEvents)->where('start_date', '>', date('Y-m-d'))
                ->where('is_draft_save', '0')
                ->orderBy('start_date', 'ASC')
                ->get();

            $allEvents = $usercreatedList->merge($invitedEventsList)->sortBy('start_date');
            $page = $request->input('page');
            $pages = ($page != "") ? $page : 1;

            $offset = ($pages - 1) * $this->perPage;
            $total_page =  ceil(count($allEvents) / $this->perPage);

            // Get paginated data using offset and take
            $paginatedEvents = $allEvents->slice($offset)->take($this->perPage);
            $eventList = [];

            if (count($paginatedEvents) != 0) {

                foreach ($paginatedEvents as $value) {


                    $eventDetail['id'] = $value->id;

                    $eventDetail['event_name'] = $value->event_name;
                    $eventDetail['is_event_owner'] = ($value->user->id == $user->id) ? 1 : 0;
                    $isCoHost =     EventInvitedUser::where(['event_id' => $value->id, 'user_id' => $user->id])->first();
                    $eventDetail['is_notification_on_off']  = "";
                    if ($value->user->id == $user->id) {

                        $eventDetail['is_notification_on_off'] =  $value->notification_on_off;
                    } else {


                        $eventDetail['is_notification_on_off'] =  $isCoHost->notification_on_off;
                    }
                    $eventDetail['is_co_host'] = "0";
                    if ($isCoHost != null) {
                        $eventDetail['is_co_host'] = $isCoHost->is_co_host;
                    }
                    $eventDetail['message_to_guests'] = $value->message_to_guests;
                    $eventDetail['event_wall'] = $value->event_settings->event_wall;
                    $eventDetail['guest_list_visible_to_guests'] = $value->event_settings->guest_list_visible_to_guests;
                    $eventDetail['event_potluck'] = $value->event_settings->podluck;
                    $eventDetail['guest_pending_count'] = getGuestRsvpPendingCount($value->id, 1);
                    $eventDetail['adult_only_party'] = $value->event_settings->adult_only_party;
                    $eventDetail['post_time'] =  $this->setpostTime($value->updated_at);


                    $rsvp_status = "";
                    $checkUserrsvp = EventInvitedUser::whereHas('user', function ($query) {

                        $query->where('app_user', '1');
                    })->where(['user_id' => $user->id, 'event_id' => $value->id])->first();

                    // if ($value->rsvp_by_date >= date('Y-m-d')) {

                    $rsvp_status = "";

                    if ($checkUserrsvp != null) {
                        if ($checkUserrsvp->rsvp_status == '1') {

                            $rsvp_status = '1'; // rsvp you'r going

                        } else if ($checkUserrsvp->rsvp_status == '0') {
                            $rsvp_status = '2'; // rsvp you'r not going
                        }
                        if ($checkUserrsvp->rsvp_status == NULL) {

                            $rsvp_status = '0'; // rsvp button//

                        }
                    }
                    // }


                    $eventDetail['rsvp_status'] = $rsvp_status;

                    $eventDetail['user_id'] = $value->user->id;

                    $eventDetail['host_profile'] = empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                    $eventDetail['host_name'] = $value->hosted_by;

                    $eventDetail['kids'] = 0;
                    $eventDetail['adults'] = 0;

                    $checkRsvpDone = EventInvitedUser::where(['event_id' => $value->id, 'user_id' => $user->id, 'rsvp_status' => '1'])->first();
                    if ($checkRsvpDone != null) {
                        $eventDetail['kids'] = $checkRsvpDone->kids;
                        $eventDetail['adults'] = $checkRsvpDone->adults;
                    }

                    $images = EventImage::where('event_id', $value->id)->first();



                    $eventDetail['event_images'] = ($images != null) ? asset('public/storage/event_images/' . $images->image) : "";



                    $eventDetail['event_date'] = $value->start_date;


                    $event_time = "-";
                    if ($value->event_schedule->isNotEmpty()) {

                        $event_time =  $value->event_schedule->first()->start_time;
                    }

                    $eventDetail['start_time'] =  $value->rsvp_start_time;

                    $eventDetail['rsvp_start_timezone'] = $value->rsvp_start_timezone;

                    $total_accept_event_user = EventInvitedUser::where(['event_id' => $value->id, 'rsvp_status' => '1'])->count();

                    $eventDetail['total_accept_event_user'] = $total_accept_event_user;



                    $total_invited_user = EventInvitedUser::whereHas('user', function ($query) {

                        $query->where('app_user', '1');
                    })->where(['event_id' => $value->id])->count();

                    $eventDetail['total_invited_user'] = $total_invited_user;


                    $total_refuse_event_user = EventInvitedUser::where(['event_id' => $value->id, 'rsvp_status' => '0'])->count();

                    $eventDetail['total_refuse_event_user'] = $total_refuse_event_user;



                    $total_notification = Notification::where(['event_id' => $value->id, 'user_id' => $user->id, 'read' => '0'])->count();

                    $eventDetail['total_notification'] = $total_notification;
                    $eventDetail['event_detail'] = [];
                    if ($value->event_settings) {
                        $eventData = [];

                        if ($value->event_settings->allow_for_1_more == '1') {
                            $eventData[] = "Can Bring Guests ( limit " . $value->event_settings->allow_limit . ")";
                        }
                        if ($value->event_settings->adult_only_party == '1') {
                            $eventData[] = "Adults Only";
                        }
                        if ($value->rsvp_by_date_set == '1') {
                            $eventData[] = date('F d, Y', strtotime($value->rsvp_by_date));
                        }
                        if ($value->event_settings->podluck == '1') {
                            $eventData[] = "Event Potluck";
                        }
                        if ($value->event_settings->gift_registry == '1') {
                            $eventData[] = "Gift Registry";
                        }
                        if (empty($eventData)) {
                            $eventData[] = date('F d, Y', strtotime($value->start_date));
                            $numberOfGuest = EventInvitedUser::where('event_id', $value->id)->count();
                            $eventData[] = "Number of guests : " . $numberOfGuest;
                        }
                        $eventDetail['event_detail'] = $eventData;
                    }
                    $eventDetail['allow_limit'] = $value->event_settings->allow_limit;
                    $totalEvent =  Event::where('user_id', $value->user->id)->count();
                    $totalEventPhotos =  EventPost::where(['user_id' => $value->user->id, 'post_type' => '1'])->count();
                    $comments =  EventPostComment::where('user_id', $value->user->id)->count();

                    $eventDetail['user_profile'] = [
                        'id' => $value->user->id,
                        'profile' => empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile),
                        'bg_profile' => empty($value->user->bg_profile) ? "" : asset('public/storage/bg_profile/' . $value->user->bg_profile),
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

                    $eventList[] = $eventDetail;
                }

                return response()->json(['status' => 1, 'count' => count($allEvents), 'total_page' => $total_page, 'data' => $eventList, 'message' => "Events Data"]);
            } else {

                return response()->json(['status' => 0, 'data' => $eventList, 'message' => "No upcoming events found"]);
            }
        } catch (QueryException $e) {

            return response()->json(['status' => 0, 'message' => "Db error"]);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'Something went wrong']);
        }
    }

    public function EventList(Request $request)

    {

        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();
        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [
            'event_date' => ['present', 'date'],

            'end_event_date' => ['present'],

        ]);


        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }


        try {
            // All  //
            $event_date = "";

            $end_event_date = "";
            if (isset($input['event_date']) && !empty($input['event_date'])) {
                $event_date = $input['event_date'];
                $end_event_date = $input['end_event_date'];
                if (empty($end_event_date)) {
                    $end_event_date = $event_date;
                }
            }




            $page = $request->input('page');

            $pages = ($page != "") ? $page : 1;
            $totalCounts = 0;
            $createdEventList = [];
            $total_allEvent_page = 0;

            $eventList = [];
            if (($input['hosting'] == '0' && $input['invited_to'] == '0' && $input['past_event'] == '0' && $input['need_rsvp_to'] == '0')) {
                $usercreatedAllEventList = Event::query();
                $usercreatedAllEventList->with(['event_image', 'event_settings', 'user', 'event_schedule'])
                    ->where('user_id', $user->id)
                    ->where('is_draft_save', '0');
                if ($event_date != "" || $end_event_date != "") {
                    $usercreatedAllEventList->when($event_date, function ($query) use ($event_date, $end_event_date) {
                        return $query->whereBetween('start_date', [$event_date, $end_event_date]);
                    });
                } else {
                    $usercreatedAllEventList->where('start_date', ">=", date('Y-m-d'));
                }
                if (isset($input['search_event']) && !empty($input['search_event'])) {
                    $search = $input['search_event'];
                    $usercreatedAllEventList->when($search, function ($query) use ($search) {
                        return   $query->where('event_name', 'like', "%$search%");
                    });
                }
                if (isset($input['month_wise_search']) && !empty($input['month_wise_search'])) {
                    $monthWiseSearch = $input['month_wise_search'];

                    $month_wise_search = Carbon::createFromFormat('F, Y', $monthWiseSearch);
                    $month = $month_wise_search->month;
                    $year = $month_wise_search->year;
                    $usercreatedAllEventList->when($month_wise_search, function ($query) use ($month, $year) {
                        return   $query->whereMonth('start_date', $month)->whereYear('start_date', $year);
                    });
                }

                $usercreatedAllEventList->orderBy('start_date', 'ASC');

                $invitedEvents = EventInvitedUser::whereHas('user', function ($query) {
                    $query->where('app_user', '1');
                })->where('user_id', $user->id)->get()->pluck('event_id');

                $invitedEventsList = Event::query();
                $invitedEventsList->with(['event_image', 'event_settings', 'user', 'event_schedule'])
                    ->whereIn('id', $invitedEvents);


                if ($event_date != "" || $end_event_date != "") {
                    $invitedEventsList->when($event_date, function ($query) use ($event_date, $end_event_date) {
                        return $query->whereBetween('start_date', [$event_date, $end_event_date]);
                    });
                } else {
                    $invitedEventsList->where('start_date', ">=", date('Y-m-d'));
                }

                if (isset($input['search_event']) && !empty($input['search_event'])) {
                    $search = $input['search_event'];

                    $invitedEventsList->when($search, function ($query) use ($search) {
                        return    $query->where('event_name', 'like', "%$search%");
                    });
                }

                if (isset($input['month_wise_search']) && !empty($input['month_wise_search'])) {
                    $monthWiseSearch = $input['month_wise_search'];
                    $month_wise_search = Carbon::createFromFormat('F, Y', $monthWiseSearch);
                    $month = $month_wise_search->month;
                    $year = $month_wise_search->year;
                    $invitedEventsList->when($month_wise_search, function ($query) use ($month, $year) {
                        return   $query->whereMonth('start_date', $month)->whereYear('start_date', $year);
                    });
                }

                $invitedEventsList->where('is_draft_save', '0')
                    ->orderBy('start_date', 'ASC');

                // Use union to combine the results of the two queries
                $allEvent = $usercreatedAllEventList->union($invitedEventsList)->get();

                $totalCounts += count($allEvent);
                // Calculate offset based on current page and perPage
                $offset = ($pages - 1) * $this->perPage;



                // $paginatedEvents =  collect($allEvent)->sortBy('start_date')->forPage($page, $this->perPage);
                $paginatedEvents =  collect($allEvent)->sortBy('start_date');


                if (count($paginatedEvents) != 0) {


                    foreach ($paginatedEvents as $value) {


                        $eventDetail['id'] = $value->id;
                        $eventDetail['event_name'] = $value->event_name;
                        $eventDetail['is_event_owner'] = ($value->user->id == $user->id) ? 1 : 0;
                        $isCoHost = EventInvitedUser::where(['event_id' => $value->id, 'user_id' => $user->id])->first();
                        $eventDetail['is_notification_on_off']  = "";
                        if ($value->user->id == $user->id) {

                            $eventDetail['is_notification_on_off'] =  $value->notification_on_off;
                        } else {


                            $eventDetail['is_notification_on_off'] =  $isCoHost->notification_on_off;
                        }
                        $eventDetail['is_co_host'] = "0";
                        if ($isCoHost != null) {
                            $eventDetail['is_co_host'] = $isCoHost->is_co_host;
                        }
                        $eventDetail['user_id'] = $value->user->id;
                        $eventDetail['host_profile'] = empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);
                        $eventDetail['message_to_guests'] = $value->message_to_guests;
                        $eventDetail['event_wall'] = $value->event_settings->event_wall;
                        $eventDetail["guest_list_visible_to_guests"] = $value->event_settings->guest_list_visible_to_guests;
                        $eventDetail['event_potluck'] = $value->event_settings->podluck;
                        $eventDetail['guest_pending_count'] = getGuestRsvpPendingCount($value->id);
                        $eventDetail['adult_only_party'] = $value->event_settings->adult_only_party;
                        $eventDetail['host_name'] = $value->hosted_by;
                        $eventDetail['allow_limit'] = $value->event_settings->allow_limit;
                        $eventDetail['is_past'] = ($value->end_date < date('Y-m-d')) ? true : false;
                        $eventDetail['is_gone_time'] = $this->evenGoneTime($value->end_date);
                        $eventDetail['post_time'] =  $this->setpostTime($value->updated_at);
                        $eventDetail['kids'] = 0;
                        $eventDetail['adults'] = 0;

                        $checkRsvpDone = EventInvitedUser::where(['event_id' => $value->id, 'user_id' => $user->id])->first();
                        if ($checkRsvpDone != null) {
                            $eventDetail['kids'] = $checkRsvpDone->kids;
                            $eventDetail['adults'] = $checkRsvpDone->adults;
                        }


                        $images = EventImage::where('event_id', $value->id)->first();

                        $eventDetail['event_images'] = ($images != null) ? asset('public/storage/event_images/' . $images->image) : "";

                        $eventDetail['event_date'] = $value->start_date;


                        $event_time = "-";
                        if ($value->event_schedule->isNotEmpty()) {

                            $event_time =  $value->event_schedule->first()->start_time;
                        }

                        $eventDetail['start_time'] =  $value->rsvp_start_time;



                        $eventDetail['rsvp_start_timezone'] = $value->rsvp_start_timezone;


                        $rsvp_status = "";





                        $checkUserrsvp = EventInvitedUser::whereHas('user', function ($query) {

                            $query->where('app_user', '1');
                        })->where(['user_id' => $user->id, 'event_id' => $value->id])->first();
                        if ($checkUserrsvp != null) {
                            if ($checkUserrsvp->rsvp_status == '1') {

                                $rsvp_status = '1'; // rsvp you'r going

                            } else if ($checkUserrsvp->rsvp_status == '0') {
                                $rsvp_status = '2'; // rsvp you'r not going
                            }
                            if ($checkUserrsvp->rsvp_status == NULL) {


                                $rsvp_status = '0'; // rsvp button//

                            }
                        }


                        $eventDetail['rsvp_status'] = $rsvp_status;

                        $total_accept_event_user = EventInvitedUser::whereHas('user', function ($query) {

                            $query->where('app_user', '1');
                        })->where(['event_id' => $value->id, 'rsvp_status' => '1', 'rsvp_d' => '1'])->count();

                        $eventDetail['total_accept_event_user'] = $total_accept_event_user;





                        $total_invited_user = EventInvitedUser::whereHas('user', function ($query) {

                            $query->where('app_user', '1');
                        })->where(['event_id' => $value->id])->count();



                        $eventDetail['total_invited_user'] = $total_invited_user;



                        $total_refuse_event_user = EventInvitedUser::whereHas('user', function ($query) {

                            $query->where('app_user', '1');
                        })->where(['event_id' => $value->id, 'rsvp_status' => '0', 'rsvp_d' => '1'])->count();

                        $eventDetail['total_refuse_event_user'] = $total_refuse_event_user;

                        $total_notification = Notification::where(['event_id' => $value->id, 'user_id' => $user->id, 'read' => '0'])->count();

                        $eventDetail['total_notification'] = $total_notification;
                        $eventDetail['event_detail'] = [];
                        if ($value->event_settings) {
                            $eventData = [];

                            if ($value->event_settings->allow_for_1_more == '1') {
                                $eventData[] = "Can Bring Guests ( limit " . $value->event_settings->allow_limit . ")";
                            }
                            if ($value->event_settings->adult_only_party == '1') {
                                $eventData[] = "Adults Only";
                            }
                            if ($value->rsvp_by_date_set == '1') {
                                $eventData[] = date('F d, Y', strtotime($value->rsvp_by_date));
                            }
                            if ($value->event_settings->podluck == '1') {
                                $eventData[] = "Event Potluck";
                            }
                            if ($value->event_settings->gift_registry == '1') {
                                $eventData[] = "Gift Registry";
                            }
                            if (empty($eventData)) {
                                $eventData[] = date('F d, Y', strtotime($value->start_date));
                                $numberOfGuest = EventInvitedUser::where('event_id', $value->id)->count();
                                $eventData[] = "Number of guests : " . $numberOfGuest;
                            }
                            $eventDetail['event_detail'] = $eventData;
                        }
                        $totalEvent =  Event::where('user_id', $value->user->id)->count();
                        $totalEventPhotos =  EventPost::where(['user_id' => $value->user->id, 'post_type' => '1'])->count();
                        $comments =  EventPostComment::where('user_id', $value->user->id)->count();

                        $eventDetail['user_profile'] = [
                            'id' => $value->user->id,
                            'profile' => empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile),
                            'bg_profile' => empty($value->user->bg_profile) ? "" : asset('public/storage/bg_profile/' . $value->user->bg_profile),
                            'gender' => ($value->user->gender != NULL) ? $value->user->gender : "",
                            'username' => $value->user->firstname . ' ' . $value->user->lastname,
                            'location' => ($value->user->city != NULL) ? $value->user->city : "",
                            'about_me' => ($value->user->about_me != NULL) ? $value->user->about_me : "",
                            'created_at' => empty($value->user->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($value->user->created_at))),
                            'total_events' => $totalEvent,
                            'total_photos' => $totalEventPhotos,
                            'visible' =>  $value->user->visible,
                            'comments' => $comments,
                        ];

                        $eventList[] = $eventDetail;
                    }
                }
            }


            // All  //

            // Invited To //
            $totalInvited = EventInvitedUser::whereHas('event', function ($query) {
                $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'));
            })->where('user_id', $user->id)->count();


            if ($input['invited_to'] == '1') {
                $search = "";

                if (isset($input['search_event']) && !empty($input['search_event'])) {
                    $search = $input['search_event'];
                }
                $month = "";
                $year = "";
                if (isset($input['month_wise_search']) && !empty($input['month_wise_search'])) {
                    $monthWiseSearch = $input['month_wise_search'];

                    $month_wise_search = Carbon::createFromFormat('F, Y', $monthWiseSearch);
                    $month = $month_wise_search->month;
                    $year = $month_wise_search->year;
                }

                $totalCounts += EventInvitedUser::whereHas('event', function ($query) use ($event_date, $end_event_date, $search, $month, $year) {
                    $query->where('is_draft_save', '0');
                    $query->with(['event_image', 'event_settings', 'user', 'event_schedule'])->where('start_date', '>=', date('Y-m-d'))->orderBy('id', 'DESC');

                    $query->when($event_date || $end_event_date, function ($query) use ($event_date, $end_event_date) {
                        return $query->whereBetween('start_date', [$event_date, $end_event_date]);
                    });

                    $query->when($search != "", function ($query) use ($search) {
                        return $query->where('event_name', 'like', "%$search%");
                    });
                    $query->when($month && $year, function ($query) use ($month, $year) {
                        return   $query->whereMonth('start_date', $month)->whereYear('start_date', $year);
                    });
                })->whereHas('user', function ($query) {
                    $query->where('app_user', '1');
                })->where('user_id', $user->id)->count();

                // Make sure to handle the retrieved $userInvitedEventList accordingly




                $userInvitedEventList = EventInvitedUser::whereHas('event', function ($query) use ($event_date, $end_event_date, $search, $month, $year) {
                    $query->where('is_draft_save', '0');
                    $query->with(['event_image', 'event_settings', 'user', 'event_schedule'])->where('start_date', '>=', date('Y-m-d'))->orderBy('id', 'DESC');

                    $query->when($event_date || $end_event_date, function ($query) use ($event_date, $end_event_date) {
                        return $query->whereBetween('start_date', [$event_date, $end_event_date]);
                    });

                    $query->when($search != "", function ($query) use ($search) {
                        return $query->where('event_name', 'like', "%$search%");
                    });
                    $query->when($month && $year, function ($query) use ($month, $year) {
                        return   $query->whereMonth('start_date', $month)->whereYear('start_date', $year);
                    });
                })->whereHas('user', function ($query) {
                    $query->where('app_user', '1');
                })->where('user_id', $user->id)->get();
                // ->paginate($this->perPage, ['*'], 'page', $page);


                // Make sure to handle the retrieved $userInvitedEventList accordingly



                if (count($userInvitedEventList) != 0) {



                    foreach ($userInvitedEventList as $value) {



                        $eventDetail['id'] = $value->event->id;
                        $eventDetail['user_id'] = $value->event->user->id;
                        $eventDetail['event_name'] = $value->event->event_name;
                        $eventDetail['is_event_owner'] = ($value->event->user->id == $user->id) ? 1 : 0;

                        $eventDetail['is_co_host'] = $value->is_co_host;
                        $eventDetail['is_notification_on_off'] =  $value->notification_on_off;
                        $eventDetail['message_to_guests'] = $value->event->message_to_guests;
                        $eventDetail['host_profile'] = empty($value->event->user->profile) ? "" : asset('public/storage/profile/' . $value->event->user->profile);
                        $eventDetail['event_wall'] = $value->event->event_settings->event_wall;
                        $eventDetail["guest_list_visible_to_guests"] = $value->event->event_settings->guest_list_visible_to_guests;


                        $eventDetail['guest_pending_count'] = getGuestRsvpPendingCount($value->event->id);
                        $eventDetail['event_potluck'] = $value->event->event_settings->podluck;
                        $eventDetail['adult_only_party'] = $value->event->event_settings->adult_only_party;
                        $eventDetail['host_name'] = $value->event->hosted_by;
                        $eventDetail['is_past'] = ($value->event->end_date < date('Y-m-d')) ? true : false;
                        $eventDetail['post_time'] =  $this->setpostTime($value->event->updated_at);
                        $eventDetail['is_gone_time'] = $this->evenGoneTime($value->event->end_date);
                        $eventDetail['allow_limit'] = $value->event->event_settings->allow_limit;
                        $images = EventImage::where('event_id', $value->event->id)->first();

                        $eventDetail['event_images'] = "";

                        if (!empty($images)) {

                            $eventDetail['event_images'] = asset('public/storage/event_images/' . $images->image);
                        }

                        $eventDetail['kids'] = 0;
                        $eventDetail['adults'] = 0;

                        $checkRsvpDone = EventInvitedUser::where(['event_id' => $value->event->id, 'user_id' => $user->id])->first();
                        if ($checkRsvpDone != null) {
                            $eventDetail['kids'] = $checkRsvpDone->kids;
                            $eventDetail['adults'] = $checkRsvpDone->adults;
                        }

                        $eventDetail['event_date'] = $value->event->start_date;
                        $event_time = "-";
                        if ($value->event->event_schedule->isNotEmpty()) {

                            $event_time =  $value->event->event_schedule->first()->start_time;
                        }

                        $eventDetail['start_time'] =  $value->event->rsvp_start_time;


                        $eventDetail['rsvp_start_timezone'] = $value->event->rsvp_start_timezone;



                        $rsvp_status = "";




                        $checkUserrsvp = EventInvitedUser::whereHas('user', function ($query) {

                            $query->where('app_user', '1');
                        })->where(['user_id' => $user->id, 'event_id' => $value->event->id])->first();
                        if ($checkUserrsvp != null) {
                            if ($checkUserrsvp->rsvp_status == '1') {

                                $rsvp_status = '1'; // rsvp you'r going

                            } else if ($checkUserrsvp->rsvp_status == '0') {
                                $rsvp_status = '2'; // rsvp you'r not going
                            }
                            if ($checkUserrsvp->rsvp_status == '0') {


                                $rsvp_status = '0'; // rsvp button//

                            }
                        }


                        $eventDetail['rsvp_status'] = $rsvp_status;

                        $total_notification = Notification::where(['event_id' => $value->event->id, 'user_id' => $user->id, 'read' => '0'])->count();

                        $eventDetail['total_notification'] = $total_notification;
                        $eventDetail['event_detail'] = [];
                        if ($value->event_settings) {
                            $eventData = [];

                            if ($value->event->event_settings->allow_for_1_more == '1') {
                                $eventData[] = "Can Bring Guests ( limit " . $value->event->event_settings->allow_limit . ")";
                            }
                            if ($value->event->event_settings->adult_only_party == '1') {
                                $eventData[] = "Adults Only";
                            }
                            if ($value->event->rsvp_by_date_set == '1') {
                                $eventData[] = date('F d, Y', strtotime($value->event->rsvp_by_date));
                            }
                            if ($value->event->event_settings->podluck == '1') {
                                $eventData[] = "Event Potluck";
                            }
                            if ($value->event->event_settings->gift_registry == '1') {
                                $eventData[] = "Gift Registry";
                            }
                            if (empty($eventData)) {
                                $eventData[] = date('F d, Y', strtotime($value->event->start_date));
                                $numberOfGuest = EventInvitedUser::where('event_id', $value->event->id)->count();
                                $eventData[] = "Number of guests : " . $numberOfGuest;
                            }
                            $eventDetail['event_detail'] = $eventData;
                        }
                        $total_accept_event_user = EventInvitedUser::whereHas('user', function ($query) {

                            $query->where('app_user', '1');
                        })->where(['event_id' => $value->event->id, 'rsvp_status' => '1', 'rsvp_d' => '1'])->count();

                        $eventDetail['total_accept_event_user'] = $total_accept_event_user;





                        $total_invited_user = EventInvitedUser::whereHas('user', function ($query) {

                            $query->where('app_user', '1');
                        })->where(['event_id' => $value->event->id])->count();



                        $eventDetail['total_invited_user'] = $total_invited_user;



                        $total_refuse_event_user = EventInvitedUser::whereHas('user', function ($query) {

                            $query->where('app_user', '1');
                        })->where(['event_id' => $value->event->id, 'rsvp_status' => '0', 'rsvp_d' => '1'])->count();

                        $eventDetail['total_refuse_event_user'] = $total_refuse_event_user;

                        $totalEvent =  Event::where('user_id', $value->event->user->id)->count();
                        $totalEventPhotos =  EventPost::where(['user_id' => $value->event->user->id, 'post_type' => '1'])->count();
                        $comments =  EventPostComment::where('user_id', $value->event->user->id)->count();

                        $eventDetail['user_profile'] = [
                            'id' => $value->event->user->id,
                            'profile' => empty($value->event->user->profile) ? "" : asset('public/storage/profile/' . $value->event->user->profile),
                            'bg_profile' => empty($value->event->user->bg_profile) ? "" : asset('public/storage/bg_profile/' . $value->event->user->bg_profile),
                            'gender' => ($value->event->user->gender != NULL) ? $value->event->user->gender : "",
                            'username' => $value->event->user->firstname . ' ' . $value->event->user->lastname,
                            'location' => ($value->event->user->city != NULL) ? $value->event->user->city : "",
                            'about_me' => ($value->event->user->about_me != NULL) ? $value->event->user->about_me : "",
                            'created_at' => empty($value->event->user->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($value->event->user->created_at))),
                            'total_events' => $totalEvent,
                            'total_photos' => $totalEventPhotos,
                            'visible' =>  $value->event->user->visible,
                            'comments' => $comments
                        ];
                        $eventList[] = $eventDetail;
                    }
                }
            }


            // Invited To //



            // Hosting//
            $totalHosting = Event::where(['is_draft_save' => '0', 'user_id' => $user->id])->where('start_date', '>=', date('Y-m-d'))->count();



            if ($input['hosting'] == '1') {
                $search = "";

                if (isset($input['search_event']) && !empty($input['search_event'])) {
                    $search = $input['search_event'];
                }
                $month = "";
                $year = "";
                if (isset($input['month_wise_search']) && !empty($input['month_wise_search'])) {
                    $monthWiseSearch = $input['month_wise_search'];

                    $month_wise_search = Carbon::createFromFormat('F, Y', $monthWiseSearch);
                    $month = $month_wise_search->month;
                    $year = $month_wise_search->year;
                }




                $totalCounts += Event::with(['event_image', 'event_settings', 'user', 'event_schedule'])->where(['is_draft_save' => '0', 'user_id' => $user->id])
                    ->where('start_date', '>=', date('Y-m-d'))
                    ->when($event_date || $end_event_date, function ($query) use ($event_date, $end_event_date) {
                        return $query->whereBetween('start_date', [$event_date, $end_event_date]);
                    })->when($search != "", function ($query) use ($search) {
                        return $query->where('event_name', 'like', "%$search%");
                    })->when($month && $year, function ($query) use ($month, $year) {
                        return   $query->whereMonth('start_date', $month)->whereYear('start_date', $year);
                    })->orderBy('start_date', 'ASC')->count();

                // Make sure to handle the retrieved $userInvitedEventList accordingly





                $hostingEvents =  Event::with(['event_image', 'event_settings', 'user', 'event_schedule'])->where(['is_draft_save' => '0', 'user_id' => $user->id])
                    ->where('start_date', '>=', date('Y-m-d'))
                    ->when($event_date || $end_event_date, function ($query) use ($event_date, $end_event_date) {
                        return $query->whereBetween('start_date', [$event_date, $end_event_date]);
                    })->when($search != "", function ($query) use ($search) {
                        return $query->where('event_name', 'like', "%$search%");
                    })->when($month && $year, function ($query) use ($month, $year) {
                        return   $query->whereMonth('start_date', $month)->whereYear('start_date', $year);
                    })->orderBy('start_date', 'ASC')->get();
                // ->paginate($this->perPage, ['*'], 'page', $page);
                // Make sure to handle the retrieved $userInvitedEventList accordingly



                if (count($hostingEvents) != 0) {



                    foreach ($hostingEvents as $value) {


                        $eventDetail['id'] = $value->id;
                        $eventDetail['event_name'] = $value->event_name;
                        $eventDetail['is_event_owner'] = ($value->user->id == $user->id) ? 1 : 0;
                        $isCoHost = EventInvitedUser::where(['event_id' => $value->id, 'user_id' => $user->id])->first();
                        if ($isCoHost != null) {
                            $eventDetail['is_co_host'] = $isCoHost->is_co_host;
                        }

                        $eventDetail['is_notification_on_off']  = "";
                        if ($value->user->id == $user->id) {

                            $eventDetail['is_notification_on_off'] =  $value->notification_on_off;
                        } else {


                            $eventDetail['is_notification_on_off'] =  $isCoHost->notification_on_off;
                        }
                        $eventDetail['message_to_guests'] = $value->message_to_guests;
                        $eventDetail['user_id'] = $value->user->id;
                        $eventDetail['host_profile'] = empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);
                        $eventDetail['event_wall'] = $value->event_settings->event_wall;
                        $eventDetail["guest_list_visible_to_guests"] = $value->event_settings->guest_list_visible_to_guests;
                        $eventDetail['guest_pending_count'] = getGuestRsvpPendingCount($value->id);
                        $eventDetail['event_potluck'] = $value->event_settings->podluck;
                        $eventDetail['adult_only_party'] = $value->event_settings->adult_only_party;
                        $eventDetail['host_name'] = $value->hosted_by;
                        $eventDetail['is_past'] = ($value->end_date < date('Y-m-d')) ? true : false;
                        $eventDetail['post_time'] =  $this->setpostTime($value->updated_at);
                        $eventDetail['is_gone_time'] = $this->evenGoneTime($value->end_date);
                        $eventDetail['allow_limit'] = $value->event_settings->allow_limit;
                        $eventDetail['kids'] = 0;
                        $eventDetail['adults'] = 0;

                        $checkRsvpDone = EventInvitedUser::where(['event_id' => $value->id, 'user_id' => $user->id])->first();
                        if ($checkRsvpDone != null) {
                            $eventDetail['kids'] = $checkRsvpDone->kids;
                            $eventDetail['adults'] = $checkRsvpDone->adults;
                        }


                        $images = EventImage::where('event_id', $value->id)->first();

                        $eventDetail['event_images'] = ($images != null) ? asset('public/storage/event_images/' . $images->image) : "";

                        $eventDetail['event_date'] = $value->start_date;
                        $event_time = "-";
                        if ($value->event_schedule->isNotEmpty()) {

                            $event_time =  $value->event_schedule->first()->start_time;
                        }

                        $eventDetail['start_time'] =  $value->rsvp_start_time;

                        $eventDetail['rsvp_start_timezone'] = $value->rsvp_start_timezone;


                        $rsvp_status = "";





                        $checkUserrsvp = EventInvitedUser::whereHas('user', function ($query) {

                            $query->where('app_user', '1');
                        })->where(['user_id' => $user->id, 'event_id' => $value->id])->first();
                        if ($checkUserrsvp != null) {
                            if ($checkUserrsvp->rsvp_status == '1') {

                                $rsvp_status = '1'; // rsvp you'r going

                            } else if ($checkUserrsvp->rsvp_status == '0') {
                                $rsvp_status = '2'; // rsvp you'r not going
                            }
                            if ($checkUserrsvp->rsvp_status == '0') {


                                $rsvp_status = '0'; // rsvp button//

                            }
                        }


                        $eventDetail['rsvp_status'] = $rsvp_status;

                        $total_accept_event_user = EventInvitedUser::whereHas('user', function ($query) {

                            $query->where('app_user', '1');
                        })->where(['event_id' => $value->id, 'rsvp_status' => '1', 'rsvp_d' => '1'])->count();

                        $eventDetail['total_accept_event_user'] = $total_accept_event_user;





                        $total_invited_user = EventInvitedUser::whereHas('user', function ($query) {

                            $query->where('app_user', '1');
                        })->where(['event_id' => $value->id])->count();



                        $eventDetail['total_invited_user'] = $total_invited_user;



                        $total_refuse_event_user = EventInvitedUser::whereHas('user', function ($query) {

                            $query->where('app_user', '1');
                        })->where(['event_id' => $value->id, 'rsvp_status' => '0', 'rsvp_d' => '1'])->count();

                        $eventDetail['total_refuse_event_user'] = $total_refuse_event_user;

                        $total_notification = Notification::where(['event_id' => $value->id, 'user_id' => $user->id, 'read' => '0'])->count();

                        $eventDetail['total_notification'] = $total_notification;
                        $eventDetail['event_detail'] = [];
                        if ($value->event_settings) {
                            $eventData = [];

                            if ($value->event_settings->allow_for_1_more == '1') {
                                $eventData[] = "Can Bring Guests ( limit " . $value->event_settings->allow_limit . ")";
                            }
                            if ($value->event_settings->adult_only_party == '1') {
                                $eventData[] = "Adults Only";
                            }
                            if ($value->rsvp_by_date_set == '1') {
                                $eventData[] = date('F d, Y', strtotime($value->rsvp_by_date));
                            }
                            if ($value->event_settings->podluck == '1') {
                                $eventData[] = "Event Potluck";
                            }
                            if ($value->event_settings->gift_registry == '1') {
                                $eventData[] = "Gift Registry";
                            }
                            if (empty($eventData)) {
                                $eventData[] = date('F d, Y', strtotime($value->start_date));
                                $numberOfGuest = EventInvitedUser::where('event_id', $value->id)->count();
                                $eventData[] = "Number of guests : " . $numberOfGuest;
                            }
                            $eventDetail['event_detail'] = $eventData;
                        }
                        $totalEvent =  Event::where('user_id', $value->user->id)->count();
                        $totalEventPhotos =  EventPost::where(['user_id' => $value->user->id, 'post_type' => '1'])->count();
                        $comments =  EventPostComment::where('user_id', $value->user->id)->count();

                        $eventDetail['user_profile'] = [
                            'id' => $value->user->id,
                            'profile' => empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile),
                            'bg_profile' => empty($value->user->bg_profile) ? "" : asset('public/storage/bg_profile/' . $value->user->bg_profile),
                            'gender' => ($value->user->gender != NULL) ? $value->user->gender : "",
                            'username' => $value->user->firstname . ' ' . $value->user->lastname,
                            'location' => ($value->user->city != NULL) ? $value->user->city : "",
                            'about_me' => ($value->user->about_me != NULL) ? $value->user->about_me : "",
                            'created_at' => empty($value->user->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($value->user->created_at))),
                            'total_events' => $totalEvent,
                            'total_photos' => $totalEventPhotos,
                            'visible' =>  $value->user->visible,
                            'comments' => $comments,
                        ];

                        $eventList[] = $eventDetail;
                    }
                }
            }


            // Hosting//



            // Past Event // 

            $usercreatedAllPastEventCount = Event::where(['is_draft_save' => '0', 'user_id' => $user->id])->where('end_date', '<', date('Y-m-d'));

            $invitedPastEvents = EventInvitedUser::whereHas('user', function ($query) {
                $query->where('app_user', '1');
            })->where('user_id', $user->id)->get()->pluck('event_id');

            $total_past_event = Event::where('end_date', '<', date('Y-m-d'))->whereIn('id', $invitedPastEvents)->where('is_draft_save', '0');
            $allPastEventC = $usercreatedAllPastEventCount->union($total_past_event)->orderByDesc('id')->get();
            $totalPastEventCount = count($allPastEventC);

            if ($input['past_event'] == '1') {

                $usercreatedAllPastEventList = Event::query();
                $usercreatedAllPastEventList->with(['event_image', 'event_settings', 'user', 'event_schedule'])->where(['user_id' => $user->id]);
                if ($event_date != "" || $end_event_date != "") {
                    $usercreatedAllPastEventList->when($event_date, function ($query, $event_date, $end_event_date) {
                        return $query->whereBetween('start_date', [$event_date, $end_event_date]);
                    });
                } else {

                    $usercreatedAllPastEventList->where('end_date', '<', date('Y-m-d'));
                }

                if (isset($input['search_event']) && !empty($input['search_event'])) {
                    $search = $input['search_event'];
                    $usercreatedAllPastEventList->where('event_name', 'like', "%$search%");
                }

                if (isset($input['month_wise_search']) && !empty($input['month_wise_search'])) {
                    $monthWiseSearch = $input['month_wise_search'];

                    $month_wise_search = Carbon::createFromFormat('F, Y', $monthWiseSearch);
                    $month = $month_wise_search->month;
                    $year = $month_wise_search->year;
                    $usercreatedAllPastEventList->when($month_wise_search, function ($query) use ($month, $year) {
                        return   $query->whereMonth('start_date', $month)->whereYear('start_date', $year);
                    });
                }
                $usercreatedAllPastEventList->where('is_draft_save', '0');

                $invitedPastEvents = EventInvitedUser::whereHas('user', function ($query) {
                    $query->where('app_user', '1');
                })->where('user_id', $user->id)->get()->pluck('event_id');

                $invitedPastEventsList = Event::query();
                $invitedPastEventsList->with(['event_image', 'event_settings', 'user', 'event_schedule'])->whereIn('id', $invitedPastEvents)->where('is_draft_save', '0');
                if ($event_date != "" || $end_event_date != "") {
                    $invitedPastEventsList->when($event_date, function ($query, $event_date, $end_event_date) {
                        return $query->whereBetween('start_date', [$event_date, $end_event_date]);
                    });
                } else {

                    $invitedPastEventsList->where('end_date', '<', date('Y-m-d'));
                }
                if (isset($input['search_event']) && !empty($input['search_event'])) {
                    $search = $input['search_event'];
                    $invitedPastEventsList->where('event_name', 'like', "%$search%");
                }
                if (isset($input['month_wise_search']) && !empty($input['month_wise_search'])) {
                    $monthWiseSearch = $input['month_wise_search'];

                    $month_wise_search = Carbon::createFromFormat('F, Y', $monthWiseSearch);
                    $month = $month_wise_search->month;
                    $year = $month_wise_search->year;
                    $invitedPastEventsList->when($month_wise_search, function ($query) use ($month, $year) {
                        return   $query->whereMonth('start_date', $month)->whereYear('start_date', $year);
                    });
                }
                $invitedPastEventsList->where('is_draft_save', '0');

                $invitedPastEventsList->orderBy('start_date', 'ASC');

                // Use union to combine the two query results
                $allPastEvent = $usercreatedAllPastEventList->union($invitedPastEventsList)->orderByDesc('id')->get();

                $totalCounts += count($allPastEvent);


                $allPastEvents =  collect($allPastEvent)->sortBy('start_date');
                // ->forPage($page, $this->perPage);


                if (count($allPastEvents) != 0) {


                    foreach ($allPastEvents as $value) {


                        $eventDetail['id'] = $value->id;
                        $eventDetail['event_name'] = $value->event_name;
                        $eventDetail['user_id'] = $value->user->id;
                        $eventDetail['is_event_owner'] = ($value->user->id == $user->id) ? 1 : 0;
                        $isCoHost =     EventInvitedUser::where(['event_id' => $value->id, 'user_id' => $user->id])->first();
                        if ($isCoHost != null) {
                            $eventDetail['is_co_host'] = $isCoHost->is_co_host;
                        }
                        $eventDetail['is_notification_on_off']  = "";
                        if ($value->user->id == $user->id) {

                            $eventDetail['is_notification_on_off'] =  $value->notification_on_off;
                        } else {


                            $eventDetail['is_notification_on_off'] =  $isCoHost->notification_on_off;
                        }
                        $eventDetail['message_to_guests'] = $value->message_to_guests;
                        $eventDetail['host_profile'] = empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);
                        $eventDetail['event_wall'] = $value->event_settings->event_wall;
                        $eventDetail["guest_list_visible_to_guests"] = $value->event_settings->guest_list_visible_to_guests;
                        $eventDetail['guest_pending_count'] = getGuestRsvpPendingCount($value->id);
                        $eventDetail['event_potluck'] = $value->event_settings->podluck;
                        $eventDetail['adult_only_party'] = $value->event_settings->adult_only_party;
                        $eventDetail['host_name'] = $value->hosted_by;
                        $eventDetail['is_past'] = true;
                        $eventDetail['post_time'] =  $this->setpostTime($value->updated_at);
                        $eventDetail['is_gone_time'] = $this->evenGoneTime($value->end_date);
                        $eventDetail['allow_limit'] = $value->event_settings->allow_limit;
                        $eventDetail['kids'] = 0;
                        $eventDetail['adults'] = 0;

                        $checkRsvpDone = EventInvitedUser::where(['event_id' => $value->id, 'user_id' => $user->id])->first();
                        if ($checkRsvpDone != null) {
                            $eventDetail['kids'] = $checkRsvpDone->kids;
                            $eventDetail['adults'] = $checkRsvpDone->adults;
                        }

                        $images = EventImage::where('event_id', $value->id)->first();

                        $eventDetail['event_images'] = ($images != null) ? asset('public/storage/event_images/' . $images->image) : "";

                        $eventDetail['event_date'] = $value->start_date;

                        $event_time = "-";
                        if ($value->event_schedule->isNotEmpty()) {

                            $event_time =  $value->event_schedule->first()->start_time;
                        }

                        $eventDetail['start_time'] =  $value->rsvp_start_time;

                        $eventDetail['rsvp_start_timezone'] = $value->rsvp_start_timezone;

                        $rsvp_status = "";





                        $checkUserrsvp = EventInvitedUser::whereHas('user', function ($query) {

                            $query->where('app_user', '1');
                        })->where(['user_id' => $user->id, 'event_id' => $value->id])->first();
                        if ($checkUserrsvp != null) {
                            if ($checkUserrsvp->rsvp_status == '1') {

                                $rsvp_status = '1'; // rsvp you'r going

                            } else if ($checkUserrsvp->rsvp_status == '0') {
                                $rsvp_status = '2'; // rsvp you'r not going
                            }
                            if ($checkUserrsvp->rsvp_status == '0') {


                                $rsvp_status = '0'; // rsvp button//

                            }
                        }


                        $eventDetail['rsvp_status'] = $rsvp_status;
                        $total_accept_event_user = EventInvitedUser::whereHas('user', function ($query) {

                            $query->where('app_user', '1');
                        })->where(['event_id' => $value->id, 'rsvp_status' => '1', 'rsvp_d' => '1'])->count();

                        $eventDetail['total_accept_event_user'] = $total_accept_event_user;





                        $total_invited_user = EventInvitedUser::whereHas('user', function ($query) {

                            $query->where('app_user', '1');
                        })->where(['event_id' => $value->id])->count();



                        $eventDetail['total_invited_user'] = $total_invited_user;



                        $total_refuse_event_user = EventInvitedUser::whereHas('user', function ($query) {

                            $query->where('app_user', '1');
                        })->where(['event_id' => $value->id, 'rsvp_status' => '0', 'rsvp_d' => '1'])->count();

                        $eventDetail['total_refuse_event_user'] = $total_refuse_event_user;



                        $total_notification = Notification::where(['event_id' => $value->id, 'user_id' => $user->id, 'read' => '0'])->count();

                        $eventDetail['total_notification'] = $total_notification;
                        $eventDetail['event_detail'] = [];
                        if ($value->event_settings) {
                            $eventData = [];

                            if ($value->event_settings->allow_for_1_more == '1') {
                                $eventData[] = "Can Bring Guests ( limit " . $value->event_settings->allow_limit . ")";
                            }
                            if ($value->event_settings->adult_only_party == '1') {
                                $eventData[] = "Adults Only";
                            }
                            if ($value->rsvp_by_date_set == '1') {
                                $eventData[] = date('F d, Y', strtotime($value->rsvp_by_date));
                            }
                            if ($value->event_settings->podluck == '1') {
                                $eventData[] = "Event Potluck";
                            }
                            if ($value->event_settings->gift_registry == '1') {
                                $eventData[] = "Gift Registry";
                            }
                            if (empty($eventData)) {
                                $eventData[] = date('F d, Y', strtotime($value->start_date));
                                $numberOfGuest = EventInvitedUser::where('event_id', $value->id)->count();
                                $eventData[] = "Number of guests : " . $numberOfGuest;
                            }
                            $eventDetail['event_detail'] = $eventData;
                        }
                        $totalEvent =  Event::where('user_id', $value->user->id)->count();
                        $totalEventPhotos =  EventPost::where(['user_id' => $value->user->id, 'post_type' => '1'])->count();
                        $comments =  EventPostComment::where('user_id', $value->user->id)->count();

                        $eventDetail['user_profile'] = [
                            'id' => $value->user->id,
                            'profile' => empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile),
                            'bg_profile' => empty($value->user->bg_profile) ? "" : asset('public/storage/bg_profile/' . $value->user->bg_profile),
                            'gender' => ($value->user->gender != NULL) ? $value->user->gender : "",
                            'username' => $value->user->firstname . ' ' . $value->user->lastname,
                            'location' => ($value->user->city != NULL) ? $value->user->city : "",
                            'about_me' => ($value->user->about_me != NULL) ? $value->user->about_me : "",
                            'created_at' => empty($value->user->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($value->user->created_at))),
                            'total_events' => $totalEvent,
                            'total_photos' => $totalEventPhotos,
                            'visible' =>  $value->user->visible,
                            'comments' => $comments,
                        ];

                        $eventList[] = $eventDetail;
                    }
                }
            }


            // Past Event // 



            // Need RSVP To //
            $total_need_rsvp_event_count = EventInvitedUser::whereHas('event', function ($query) {
                $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'));
            })->where(['user_id' => $user->id, 'rsvp_status' => NULL])->count();



            if ($input['need_rsvp_to'] == '1') {
                $search = "";

                if (isset($input['search_event']) && !empty($input['search_event'])) {
                    $search = $input['search_event'];
                }
                $month = "";
                $year = "";
                if (isset($input['month_wise_search']) && !empty($input['month_wise_search'])) {
                    $monthWiseSearch = $input['month_wise_search'];

                    $month_wise_search = Carbon::createFromFormat('F, Y', $monthWiseSearch);
                    $month = $month_wise_search->month;
                    $year = $month_wise_search->year;
                }

                $totalCounts += EventInvitedUser::whereHas('event', function ($query) use ($event_date, $end_event_date, $search, $month, $year) {
                    $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'))

                        ->with(['event_image', 'event_settings', 'user', 'event_schedule'])
                        ->orderBy('id', 'DESC');

                    $query->when($event_date || $end_event_date, function ($query) use ($event_date, $end_event_date) {
                        return $query->whereBetween('start_date', [$event_date, $end_event_date]);
                    });

                    $query->when($search != "", function ($query) use ($search) {
                        return $query->where('event_name', 'like', "%$search%");
                    });
                    $query->when($month && $year, function ($query) use ($month, $year) {
                        return   $query->whereMonth('start_date', $month)->whereYear('start_date', $year);
                    });
                })->whereHas('user', function ($query) {
                    $query->where('app_user', '1');
                })->where(['user_id' => $user->id, 'rsvp_status' => NULL])->count();

                // Make sure to handle the retrieved $userInvitedEventList accordingly




                $userNeedRsvpEventList = EventInvitedUser::whereHas('event', function ($query) use ($event_date, $end_event_date, $search, $month, $year) {
                    $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'))

                        ->with(['event_image', 'event_settings', 'user', 'event_schedule'])
                        ->orderBy('id', 'DESC');

                    $query->when($event_date || $end_event_date, function ($query) use ($event_date, $end_event_date) {
                        return $query->whereBetween('start_date', [$event_date, $end_event_date]);
                    });

                    $query->when($search != "", function ($query) use ($search) {
                        return $query->where('event_name', 'like', "%$search%");
                    });
                    $query->when($month && $year, function ($query) use ($month, $year) {
                        return   $query->whereMonth('start_date', $month)->whereYear('start_date', $year);
                    });
                })->whereHas('user', function ($query) {
                    $query->where('app_user', '1');
                })->where(['user_id' => $user->id, 'rsvp_status' => NULL])->get();
                // ->paginate($this->perPage, ['*'], 'page', $page);


                // Make sure to handle the retrieved $userNeedRsvpEventList accordingly



                if (count($userNeedRsvpEventList) != 0) {



                    foreach ($userNeedRsvpEventList as $value) {



                        $eventDetail['id'] = $value->event->id;
                        $eventDetail['user_id'] = $value->event->user->id;
                        $eventDetail['event_name'] = $value->event->event_name;
                        $eventDetail['is_event_owner'] = ($value->event->user->id == $user->id) ? 1 : 0;
                        $isCoHost =     EventInvitedUser::where(['event_id' =>  $value->event->id, 'user_id' => $user->id])->first();
                        if ($isCoHost != null) {
                            $eventDetail['is_co_host'] = $isCoHost->is_co_host;
                        }
                        $eventDetail['is_notification_on_off']  = "";
                        if ($value->user->id == $user->id) {

                            $eventDetail['is_notification_on_off'] =  $value->notification_on_off;
                        } else {


                            $eventDetail['is_notification_on_off'] =  $isCoHost->notification_on_off;
                        }
                        $eventDetail['message_to_guests'] = $value->event->message_to_guests;
                        $eventDetail['host_profile'] = empty($value->event->user->profile) ? "" : asset('public/storage/profile/' . $value->event->user->profile);
                        $eventDetail['event_wall'] = $value->event->event_settings->event_wall;
                        $eventDetail["guest_list_visible_to_guests"] = $value->event->event_settings->guest_list_visible_to_guests;


                        $eventDetail['guest_pending_count'] = getGuestRsvpPendingCount($value->event->id);
                        $eventDetail['event_potluck'] = $value->event->event_settings->podluck;
                        $eventDetail['adult_only_party'] = $value->event->event_settings->adult_only_party;
                        $eventDetail['host_name'] = $value->event->hosted_by;
                        $eventDetail['is_past'] = ($value->event->end_date < date('Y-m-d')) ? true : false;
                        $eventDetail['post_time'] =  $this->setpostTime($value->event->updated_at);
                        $eventDetail['is_gone_time'] = $this->evenGoneTime($value->event->end_date);
                        $eventDetail['allow_limit'] = $value->event->event_settings->allow_limit;
                        $images = EventImage::where('event_id', $value->event->id)->first();

                        $eventDetail['event_images'] = "";

                        if (!empty($images)) {

                            $eventDetail['event_images'] = asset('public/storage/event_images/' . $images->image);
                        }

                        $eventDetail['kids'] = 0;
                        $eventDetail['adults'] = 0;

                        $checkRsvpDone = EventInvitedUser::where(['event_id' => $value->event->id, 'user_id' => $user->id])->first();
                        if ($checkRsvpDone != null) {
                            $eventDetail['kids'] = $checkRsvpDone->kids;
                            $eventDetail['adults'] = $checkRsvpDone->adults;
                        }

                        $eventDetail['event_date'] = $value->event->start_date;
                        $event_time = "-";
                        if ($value->event->event_schedule->isNotEmpty()) {

                            $event_time =  $value->event->event_schedule->first()->start_time;
                        }

                        $eventDetail['start_time'] =  $value->event->rsvp_start_time;


                        $eventDetail['rsvp_start_timezone'] = $value->event->rsvp_start_timezone;



                        $rsvp_status = "";




                        $checkUserrsvp = EventInvitedUser::whereHas('user', function ($query) {

                            $query->where('app_user', '1');
                        })->where(['user_id' => $user->id, 'event_id' => $value->event->id])->first();
                        if ($checkUserrsvp != null) {
                            if ($checkUserrsvp->rsvp_status == '1') {

                                $rsvp_status = '1'; // rsvp you'r going

                            } else if ($checkUserrsvp->rsvp_status == '0') {
                                $rsvp_status = '2'; // rsvp you'r not going
                            }
                            if ($checkUserrsvp->rsvp_status == '0') {


                                $rsvp_status = '0'; // rsvp button//

                            }
                        }


                        $eventDetail['rsvp_status'] = $rsvp_status;

                        $total_notification = Notification::where(['event_id' => $value->event->id, 'user_id' => $user->id, 'read' => '0'])->count();

                        $eventDetail['total_notification'] = $total_notification;
                        $eventDetail['event_detail'] = [];
                        if ($value->event_settings) {
                            $eventData = [];

                            if ($value->event->event_settings->allow_for_1_more == '1') {
                                $eventData[] = "Can Bring Guests ( limit " . $value->event->event_settings->allow_limit . ")";
                            }
                            if ($value->event->event_settings->adult_only_party == '1') {
                                $eventData[] = "Adults Only";
                            }
                            if ($value->event->rsvp_by_date_sets == '1') {
                                $eventData[] = date('F d, Y', strtotime($value->event->rsvp_by_date));
                            }
                            if ($value->event->event_settings->podluck == '1') {
                                $eventData[] = "Event Potluck";
                            }
                            if ($value->event->event_settings->gift_registry == '1') {
                                $eventData[] = "Gift Registry";
                            }
                            if (empty($eventData)) {
                                $eventData[] = date('F d, Y', strtotime($value->event->start_date));
                                $numberOfGuest = EventInvitedUser::where('event_id', $value->event->id)->count();
                                $eventData[] = "Number of guests : " . $numberOfGuest;
                            }
                            $eventDetail['event_detail'] = $eventData;
                        }
                        $total_accept_event_user = EventInvitedUser::whereHas('user', function ($query) {

                            $query->where('app_user', '1');
                        })->where(['event_id' => $value->event->id, 'rsvp_status' => '1', 'rsvp_d' => '1'])->count();

                        $eventDetail['total_accept_event_user'] = $total_accept_event_user;





                        $total_invited_user = EventInvitedUser::whereHas('user', function ($query) {

                            $query->where('app_user', '1');
                        })->where(['event_id' => $value->event->id])->count();



                        $eventDetail['total_invited_user'] = $total_invited_user;



                        $total_refuse_event_user = EventInvitedUser::whereHas('user', function ($query) {

                            $query->where('app_user', '1');
                        })->where(['event_id' => $value->event->id, 'rsvp_status' => '0', 'rsvp_d' => '1'])->count();

                        $eventDetail['total_refuse_event_user'] = $total_refuse_event_user;

                        $totalEvent =  Event::where('user_id', $value->event->user->id)->count();
                        $totalEventPhotos =  EventPost::where(['user_id' => $value->event->user->id, 'post_type' => '1'])->count();
                        $comments =  EventPostComment::where('user_id', $value->event->user->id)->count();

                        $eventDetail['user_profile'] = [
                            'id' => $value->event->user->id,
                            'profile' => empty($value->event->user->profile) ? "" : asset('public/storage/profile/' . $value->event->user->profile),
                            'bg_profile' => empty($value->event->user->bg_profile) ? "" : asset('public/storage/bg_profile/' . $value->event->user->bg_profile),
                            'gender' => ($value->event->user->gender != NULL) ? $value->event->user->gender : "",
                            'username' => $value->event->user->firstname . ' ' . $value->event->user->lastname,
                            'location' => ($value->event->user->city != NULL) ? $value->event->user->city : "",
                            'about_me' => ($value->event->user->about_me != NULL) ? $value->event->user->about_me : "",
                            'created_at' => empty($value->event->user->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($value->event->user->created_at))),
                            'total_events' => $totalEvent,
                            'total_photos' => $totalEventPhotos,
                            'visible' =>  $value->event->user->visible,
                            'comments' => $comments
                        ];
                        $eventList[] = $eventDetail;
                    }
                }
            }


            // Invited To //
            $collection = collect($eventList);

            // Use unique() method on the collection to make it unique based on a specific key
            $uniqueCollection = $collection->unique('id');

            // Convert the unique collection back to a plain array
            $uniqueArray = $uniqueCollection->values()->all();
            usort($uniqueArray, function ($a, $b) {
                return strtotime($a['event_date']) - strtotime($b['event_date']);
            });
            // $total_allEvent_page = ceil($totalCounts / $this->perPage);
            $total_allEvent_page = ceil(count($uniqueArray) / $this->perPage);
            $offset = ($page - 1) * $this->perPage;

            $paginatedArray = array_slice($uniqueArray, $offset, $this->perPage);

            if (!empty($paginatedArray)) {
                return response()->json(['status' => 1, "total_invited" => $totalInvited, "total_hosting" => $totalHosting, 'total_past_event_count' => $totalPastEventCount, 'total_need_rsvp_event_count' => $total_need_rsvp_event_count, "count" => $totalCounts, 'total_allEvent_page' => $total_allEvent_page, 'data' => $paginatedArray, 'message' => "All events"]);
            } else {
                return response()->json(['status' => 0, "total_invited" => $totalInvited, "total_hosting" => $totalHosting, 'total_past_event_count' => $totalPastEventCount, 'total_need_rsvp_event_count' => $total_need_rsvp_event_count, "count" => $totalCounts, 'total_allEvent_page' => $total_allEvent_page, 'data' => $paginatedArray, 'message' => "Events not found"]);
            }
        }


        // catch (QueryException $e) {
        //     return response()->json(['status' => 0, 'message' => "db error"]);
        // }
        catch (Exception $e) {
            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }

    function sortByDate($a, $b)
    {
        return strtotime($a['event_date']) - strtotime($b['event_date']);
    }

    public function updateProfile(Request $request)

    {


        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        if ($input['account_type'] == '1') {
            $validator = Validator::make($input, [
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => ['required', Rule::unique('users')->ignore($user->id)],
                "address" => "required",
                "city" => "required",
                "state" => "required",
                "zip_code" => "required"
            ]);
        } else {

            $validator = Validator::make($input, [
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => ['required', Rule::unique('users')->ignore($user->id)],
                "zip_code" => "present"
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
            $userUpdate = User::where('id', $user->id)->first();

            $userUpdate->firstname = $input['firstname'];

            $userUpdate->lastname = $input['lastname'];
            $userUpdate->gender = ($input['gender'] != "") ? $input['gender'] : $userUpdate->gender;
            $userUpdate->birth_date = ($input['birth_date'] != "") ? $input['birth_date'] : $userUpdate->birth_date;
            $userUpdate->country_code = ($input['country_code'] != "") ? $input['country_code'] : $userUpdate->country_code;
            $userUpdate->phone_number = ($input['phone_number'] != "") ? $input['phone_number'] : $userUpdate->phone_number;
            $userUpdate->about_me = ($input['about_me'] != "") ? $input['about_me'] : $userUpdate->about_me;
            $userUpdate->zip_code = ($input['zip_code'] != "") ? $input['zip_code'] : $userUpdate->zip_code;
            if ($userUpdate->account_type == '1') {
                $validator = Validator::make($input, [
                    'company_name' => 'required',
                ]);
                $userUpdate->company_name = $input['company_name'];
            }
            $userUpdate->address = ($input['address'] != "") ? $input['address'] : $userUpdate->address;
            $userUpdate->address_2 = (isset($input['address_2'])  && $input['address_2'] != "") ? $input['address_2'] : $userUpdate->address_2;
            $userUpdate->city = ($input['city'] != "") ? $input['city'] : $userUpdate->city;
            $userUpdate->state = ($input['state'] != "") ? $input['state'] : $userUpdate->state;
            $userUpdate->save();
            DB::commit();

            $details = User::where('id', $user->id)->first();
            if (!empty($details)) {
                $totalEvent =  Event::where('user_id', $details->id)->count();
                $totalEventPhotos = EventPost::where(['user_id' => $details->id, 'post_type' => '1'])->count();
                $postComments =  EventPostComment::where('user_id', $details->id)->count();

                $totalDraftEvent =  Event::where(['user_id' => $details->id, 'is_draft_save' => '1'])->count();
                $getUserPrivacyPolicy = UserProfilePrivacy::select('profile_privacy', 'status')->where('user_id', $details->id)->get();
                // $profileData = [
                //     'id' =>  empty($details->id) ? "" : $details->id,
                //     'profile' =>  empty($details->profile) ?  "" : asset('public/storage/profile/' . $details->profile),
                //     'bg_profile' =>  empty($details->bg_profile) ? "" : asset('public/storage/bg_profile/' . $details->bg_profile),
                //     'firstname' => empty($details->firstname) ? "" : $details->firstname,
                //     'firstname' => empty($details->firstname) ? "" : $details->firstname,
                //     'lastname' => empty($details->lastname) ? "" : $details->lastname,
                //     'birth_date' => empty($details->birth_date) ? "" : $details->birth_date,
                //     'email' => empty($details->email) ? "" : $details->email,
                //     'about_me' => empty($details->about_me) ? "" : $details->about_me,
                //     'created_at' => empty($details->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($details->created_at))),
                //     'total_events' => $totalEvent,
                //     'total_photos' => $totalEventPhotos,
                //     'comments' => $postComments,
                //     'gender' => empty($details->gender) ? "" : $details->gender,
                //     'country_code' => empty($details->country_code) ? "" : strval($details->country_code),
                //     'phone_number' => empty($details->phone_number) ? "" : $details->phone_number,
                //     'visible' =>  $details->visible,
                //     'account_type' =>  $details->account_type,
                //     'company_name' => empty($details->company_name) ? "" : $details->company_name,
                //     'address' => empty($details->address) ? "" : $details->address,
                //     'address_2' => empty($details->address_2) ? "" : $details->address_2,
                //     'city' => empty($details->city) ? "" : $details->city,
                //     'state' => empty($details->state) ? "" : $details->state,
                //     'zip_code' => empty($details->zip_code) ? "" : $details->zip_code
                // ];
                $profileData = [
                    'id' =>  empty($details->id) ? "" : $details->id,
                    'profile' =>  empty($details->profile) ?  "" : asset('public/storage/profile/' . $details->profile),
                    'bg_profile' =>  empty($details->bg_profile) ? "" : asset('public/storage/bg_profile/' . $details->bg_profile),
                    'firstname' => empty($details->firstname) ? "" : $details->firstname,
                    'firstname' => empty($details->firstname) ? "" : $details->firstname,
                    'lastname' => empty($details->lastname) ? "" : $details->lastname,
                    'birth_date' => empty($details->birth_date) ? "" : $details->birth_date,
                    'email' => empty($details->email) ? "" : $details->email,
                    'about_me' => empty($details->about_me) ? "" : $details->about_me,
                    'created_at' => empty($details->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($details->created_at))),
                    // 'created_at' => empty($details->created_at) ? "" :   date('F Y', strtotime($details->created_at)),
                    'total_events' => $totalEvent,
                    'total_draft_events' => $totalDraftEvent,
                    'total_upcoming_events' => $this->upcomingEventCount,
                    'pending_rsvp_count' =>  $this->pendingRsvpCount['total_need_rsvp_event_count'],
                    'Pending_rsvp_event_id' => $this->pendingRsvpCount['PendingRsvpEventId'],
                    'hosting_count' => $this->hostingCount,
                    'invitedTo_count' => $this->invitedToCount,
                    'total_photos' => $totalEventPhotos,
                    'comments' => $postComments,
                    'gender' => empty($details->gender) ? "" : $details->gender,
                    'country_code' => empty($details->country_code) ? "" : strval($details->country_code),
                    'phone_number' => empty($details->phone_number) ? "" : $details->phone_number,
                    'visible' =>  $details->visible,
                    'message_privacy' =>  $details->message_privacy,
                    'photo_via_wifi' =>  $details->photo_via_wifi,
                    'enable_face_id_login' =>  $details->enable_face_id_login,
                    'profile_privacy' =>  $getUserPrivacyPolicy,
                    'account_type' =>  $details->account_type,
                    'company_name' => empty($details->company_name) ? "" : $details->company_name,
                    'address' => empty($details->address) ? "" : $details->address,
                    'address_2' => empty($details->address_2) ? "" : $details->address_2,
                    'city' => empty($details->city) ? "" : $details->city,
                    'state' => empty($details->state) ? "" : $details->state,
                    'zip_code' => empty($details->zip_code) ? "" : $details->zip_code,
                    'password_updated_date' => empty($details->password_updated_date) ? "" : $details->password_updated_date,
                    'total_notification' => Notification::where(['user_id' => $details->id, 'read' => '0'])->count()
                ];

                return response()->json(['status' => 1, 'data' => $profileData, 'message' => "Changes Saved!"]);
            } else {

                return response()->json(['status' => 0, 'message' => "User profile not found", 'message' => "The requested user profile data does not exist."]);
            }
        } catch (QueryException $e) {
            DB::Rollback();
            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }

    public function updateProfileOrBgProfile(Request $request)

    {
        try {

            $input = $request->all();
            $validator = Validator::make($input, [

                'profile' => ['image'],
                'bg_profile' => ['image']
            ]);

            if ($validator->fails()) {

                return response()->json([
                    'status' => 0,
                    'message' => $validator->errors()->first(),



                ]);
            }

            $user = Auth::guard('api')->user();

            if (!empty($request->profile)) {

                if ($user->profile != "" || $user->profile != NULL) {

                    if (file_exists(public_path('storage/profile/') . $user->profile)) {
                        $imagePath = public_path('storage/profile/') . $user->profile;
                        unlink($imagePath);
                    }
                }


                $image = $request->profile;


                $imageName = $user->id . '_profile.' . $image->getClientOriginalExtension();


                $image->move(public_path('storage/profile'), $imageName);
                $user->profile = $imageName;
            }



            if (!empty($request->bg_profile)) {

                if ($user->bg_profile != "" || $user->bg_profile != NULL) {

                    if (file_exists(public_path('storage/bg_profile/') . $user->bg_profile)) {
                        $imagePath = public_path('storage/bg_profile/') . $user->bg_profile;
                        unlink($imagePath);
                    }
                }


                $bgimage = $request->bg_profile;

                $bgimageName = $user->id . '_bg_profile' . $bgimage->getClientOriginalName();

                $bgimage->move(public_path('storage/bg_profile'), $bgimageName);



                $user->bg_profile = $bgimageName;
            }

            $user->save();

            $details = User::where('id', $user->id)->first();




            if (!empty($details)) {
                $totalEvent =  Event::where('user_id', $details->id)->count();

                $totalDraftEvent =  Event::where(['user_id' => $details->id, 'is_draft_save' => '1'])->count();
                $totalEventPhotos = EventPost::where(['user_id' => $details->id, 'post_type' => '1'])->count();

                $postComments =  EventPostComment::where('user_id', $details->id)->count();
                $getUserPrivacyPolicy = UserProfilePrivacy::select('profile_privacy', 'status')->where('user_id', $details->id)->get();
                // $profileData = [
                //     'id' =>  empty($details->id) ? "" : $details->id,
                //     'profile' =>  empty($details->profile) ?  "" : asset('public/storage/profile/' . $details->profile),
                //     'bg_profile' =>  empty($details->bg_profile) ? "" : asset('public/storage/bg_profile/' . $details->bg_profile),
                //     'firstname' => empty($details->firstname) ? "" : $details->firstname,
                //     'firstname' => empty($details->firstname) ? "" : $details->firstname,
                //     'lastname' => empty($details->lastname) ? "" : $details->lastname,
                //     'birth_date' => empty($details->birth_date) ? "" : $details->birth_date,
                //     'email' => empty($details->email) ? "" : $details->email,
                //     'about_me' => empty($details->about_me) ? "" : $details->about_me,

                //     'created_at' => empty($details->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($details->created_at))),
                //     'total_events' => $totalEvent,
                //     'total_photos' => $totalEventPhotos,
                //     'comments' => $postComments,
                //     'gender' => empty($details->gender) ? "" : $details->gender,
                //     'country_code' => empty($details->country_code) ? "" : strval($details->country_code),
                //     'phone_number' => empty($details->phone_number) ? "" : $details->phone_number,
                //     'visible' =>  $details->visible,
                //     'account_type' =>  $details->account_type,
                //     'company_name' => empty($details->company_name) ? "" : $details->company_name,
                //     'address' => empty($details->address) ? "" : $details->address,
                //     'address_2' => empty($details->address_2) ? "" : $details->address_2,
                //     'city' => empty($details->city) ? "" : $details->city,
                //     'state' => empty($details->state) ? "" : $details->state,
                //     'zip_code' => empty($details->zip_code) ? "" : $details->zip_code

                // ];
                $profileData = [
                    'id' =>  empty($details->id) ? "" : $details->id,
                    'profile' =>  empty($details->profile) ?  "" : asset('public/storage/profile/' . $details->profile),
                    'bg_profile' =>  empty($details->bg_profile) ? "" : asset('public/storage/bg_profile/' . $details->bg_profile),
                    'firstname' => empty($details->firstname) ? "" : $details->firstname,
                    'firstname' => empty($details->firstname) ? "" : $details->firstname,
                    'lastname' => empty($details->lastname) ? "" : $details->lastname,
                    'birth_date' => empty($details->birth_date) ? "" : $details->birth_date,
                    'email' => empty($details->email) ? "" : $details->email,
                    'about_me' => empty($details->about_me) ? "" : $details->about_me,
                    'created_at' => empty($details->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($details->created_at))),
                    // 'created_at' => empty($details->created_at) ? "" :   date('F Y', strtotime($details->created_at)),
                    'total_events' => $totalEvent,
                    'total_draft_events' => $totalDraftEvent,
                    'total_upcoming_events' => $this->upcomingEventCount,
                    'pending_rsvp_count' =>  $this->pendingRsvpCount['total_need_rsvp_event_count'],
                    'Pending_rsvp_event_id' => $this->pendingRsvpCount['PendingRsvpEventId'],
                    'hosting_count' => $this->hostingCount,
                    'invitedTo_count' => $this->invitedToCount,
                    'total_photos' => $totalEventPhotos,
                    'comments' => $postComments,
                    'gender' => empty($details->gender) ? "" : $details->gender,
                    'country_code' => empty($details->country_code) ? "" : strval($details->country_code),
                    'phone_number' => empty($details->phone_number) ? "" : $details->phone_number,
                    'visible' =>  $details->visible,
                    'message_privacy' =>  $details->message_privacy,
                    'photo_via_wifi' =>  $details->photo_via_wifi,
                    'enable_face_id_login' =>  $details->enable_face_id_login,
                    'profile_privacy' =>  $getUserPrivacyPolicy,
                    'account_type' =>  $details->account_type,
                    'company_name' => empty($details->company_name) ? "" : $details->company_name,
                    'address' => empty($details->address) ? "" : $details->address,
                    'address_2' => empty($details->address_2) ? "" : $details->address_2,
                    'city' => empty($details->city) ? "" : $details->city,
                    'state' => empty($details->state) ? "" : $details->state,
                    'zip_code' => empty($details->zip_code) ? "" : $details->zip_code,
                    'password_updated_date' => empty($details->password_updated_date) ? "" : $details->password_updated_date,
                    'total_notification' => Notification::where(['user_id' => $details->id, 'read' => '0'])->count()
                ];


                return response()->json(['status' => 1, 'data' => $profileData, 'message' => "Profile updated successfully"]);
            } else {

                return response()->json(['status' => 0, 'message' => "User profile not found", 'message' => "The requested user profile data does not exist."]);
            }
        } catch (QueryException $e) {
            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }

    public function removeProfile(Request $request)
    {
        $user  = Auth::guard('api')->user();


        $rawData = $request->getContent();


        $input = json_decode($rawData, true);

        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [

            'type' => ['required', 'in:profile,bg_profile'],
        ]);



        if ($validator->fails()) {

            return response()->json(
                [
                    'status' => 0,
                    'message' => $validator->errors()->first()

                ],
            );
        }
        if ($input['type'] == 'profile') {

            if ($user->profile != "" || $user->profile != NULL) {

                if (file_exists(public_path('storage/profile/') . $user->profile)) {
                    $imagePath = public_path('storage/profile/') . $user->profile;
                    unlink($imagePath);
                }

                $user->profile = NULL;
                $user->save();
            }
        }
        if ($input['type'] == 'bg_profile') {
            if ($user->bg_profile != "" || $user->bg_profile != NULL) {

                if (file_exists(public_path('storage/bg_profile/') . $user->bg_profile)) {
                    $bgimagePath = public_path('storage/bg_profile/') . $user->bg_profile;
                    unlink($bgimagePath);
                }

                $user->bg_profile = NULL;
                $user->save();
            }
        }
        return response()->json(['status' => 1, 'message' => "Profile removed successfully"]);
    }

    public function myProfile(Request $request)

    {

        try {


            $loginuser  = Auth::guard('api')->user();

            $userId = $loginuser->id;
            $rawData = $request->getContent();

            $input = json_decode($rawData, true);


            if (isset($input['user_id']) && $input['user_id'] != "") {
                $userId = $input['user_id'];
            }

            $user = User::where('id', $userId)->first();

            $totalEvent =  Event::where(['user_id' => $user->id, 'is_draft_save' => '0'])->count();
            $totalDraftEvent =  Event::where(['user_id' => $user->id, 'is_draft_save' => '1'])->count();


            $totalEventPhotos = EventPost::where(['user_id' => $user->id, 'post_type' => '1'])->count();

            $postComments =  EventPostComment::where('user_id', $user->id)->count();

            $getUserPrivacyPolicy = UserProfilePrivacy::select('profile_privacy', 'status')->where('user_id', $user->id)->get();

            if (!empty($user)) {

                $profileData = [
                    'id' =>  empty($user->id) ? "" : $user->id,
                    'profile' =>  empty($user->profile) ?  "" : asset('public/storage/profile/' . $user->profile),
                    'bg_profile' =>  empty($user->bg_profile) ? "" : asset('public/storage/bg_profile/' . $user->bg_profile),
                    'firstname' => empty($user->firstname) ? "" : $user->firstname,
                    'firstname' => empty($user->firstname) ? "" : $user->firstname,
                    'lastname' => empty($user->lastname) ? "" : $user->lastname,
                    'birth_date' => empty($user->birth_date) ? "" : $user->birth_date,
                    'email' => empty($user->email) ? "" : $user->email,
                    'about_me' => empty($user->about_me) ? "" : $user->about_me,
                    'created_at' => empty($user->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($user->created_at))),
                    // 'created_at' => empty($user->created_at) ? "" :   date('F Y', strtotime($user->created_at)),
                    'total_events' => $totalEvent,
                    'total_draft_events' => $totalDraftEvent,
                    'total_upcoming_events' => $this->upcomingEventCount,
                    'pending_rsvp_count' =>  $this->pendingRsvpCount['total_need_rsvp_event_count'],
                    'Pending_rsvp_event_id' => $this->pendingRsvpCount['PendingRsvpEventId'],
                    'hosting_count' => $this->hostingCount,
                    'invitedTo_count' => $this->invitedToCount,
                    'total_photos' => $totalEventPhotos,
                    'comments' => $postComments,
                    'gender' => empty($user->gender) ? "" : $user->gender,
                    'country_code' => empty($user->country_code) ? "" : strval($user->country_code),
                    'phone_number' => empty($user->phone_number) ? "" : $user->phone_number,
                    'visible' =>  $user->visible,
                    'message_privacy' =>  $user->message_privacy,
                    'photo_via_wifi' =>  $user->photo_via_wifi,
                    'enable_face_id_login' =>  $user->enable_face_id_login,
                    'profile_privacy' =>  $getUserPrivacyPolicy,
                    'account_type' =>  $user->account_type,
                    'company_name' => empty($user->company_name) ? "" : $user->company_name,
                    'address' => empty($user->address) ? "" : $user->address,
                    'address_2' => empty($user->address_2) ? "" : $user->address_2,
                    'city' => empty($user->city) ? "" : $user->city,
                    'state' => empty($user->state) ? "" : $user->state,
                    'zip_code' => empty($user->zip_code) ? "" : $user->zip_code,
                    'password_updated_date' => empty($user->password_updated_date) ? "" : $user->password_updated_date,
                    'total_notification' => Notification::where(['user_id' => $user->id, 'read' => '0'])->count()


                ];


                return response()->json(['status' => 1, 'data' => $profileData, 'message' => "My Profile"]);
            } else {

                return response()->json(['status' => 0, 'message' => "User profile not found", 'message' => "The requested user profile data does not exist."]);
            }
        } catch (QueryException $e) {
            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }

    public function privacySetting(Request $request)

    {

        $user  = Auth::guard('api')->user();



        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [

            'privacy_visible' => [

                'required',

                'in:1,2,3'

            ],



        ]);

        $customMessages = [
            'privacy_visible.in' => 'The privacy_visible field must be 1, 2, or 3.',
        ];
        $validator->setCustomMessages($customMessages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),

            ]);
        }

        try {
            DB::beginTransaction();
            $user->visible = $input["privacy_visible"];

            if ($user->save()) {
                if ($input["privacy_visible"] == '1') {
                    $privacyData = UserProfilePrivacy::where('user_id', $user->id)->count();
                    if ($privacyData == 0) {
                        foreach ($input['profile_privacy'] as $value) {
                            $setPrivacyData = new UserProfilePrivacy();
                            $setPrivacyData->profile_privacy = $value['profile_privacy'];
                            $setPrivacyData->status = $value["status"];
                            $setPrivacyData->user_id = $user->id;
                            $setPrivacyData->save();
                        }
                    } else {
                        foreach ($input['profile_privacy'] as $value) {
                            $setUpdatePrivacyData = UserProfilePrivacy::where(['user_id' => $user->id, 'profile_privacy' => $value['profile_privacy']])->first();
                            if ($setUpdatePrivacyData != null) {
                                $setUpdatePrivacyData->status = $value["status"];
                                $setUpdatePrivacyData->save();
                            } else {
                                $setUpdatePrivacyData = new UserProfilePrivacy();
                                $setUpdatePrivacyData->profile_privacy = $value['profile_privacy'];
                                $setUpdatePrivacyData->status = $value["status"];
                                $setUpdatePrivacyData->user_id = $user->id;
                                $setUpdatePrivacyData->save();
                            }
                        }
                    }
                }
            }
            DB::commit();
            return response()->json(['status' => 1, 'message' => "visible changed successfully"]);
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }


    public function MessageprivacySetting(Request $request)

    {

        $user  = Auth::guard('api')->user();



        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [

            'message_privacy' => [

                'required',

                'in:1,2,3'

            ],



        ]);

        $customMessages = [
            'message_privacy.in' => 'The message privacy field must be 1, 2, or 3.',
        ];
        $validator->setCustomMessages($customMessages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),

            ]);
        }

        try {
            DB::beginTransaction();
            $user->message_privacy = $input["message_privacy"];

            if ($user->save()) {
                // if ($input["message_privacy"] == '3') {
                //     $privacyData = UserProfilePrivacy::where('user_id', $user->id)->count();
                //     if ($privacyData == 0) {
                //         foreach ($input['profile_privacy'] as $value) {
                //             $setPrivacyData = new UserProfilePrivacy();
                //             $setPrivacyData->profile_privacy = $value['profile_privacy'];
                //             $setPrivacyData->status = $value["status"];
                //             $setPrivacyData->user_id = $user->id;
                //             $setPrivacyData->save();
                //         }
                //     } else {
                //         foreach ($input['profile_privacy'] as $value) {
                //             $setUpdatePrivacyData = UserProfilePrivacy::where(['user_id' => $user->id, 'profile_privacy' => $value['profile_privacy']])->first();
                //             if ($setUpdatePrivacyData != null) {
                //                 $setUpdatePrivacyData->status = $value["status"];
                //                 $setUpdatePrivacyData->save();
                //             } else {
                //                 $setUpdatePrivacyData = new UserProfilePrivacy();
                //                 $setUpdatePrivacyData->profile_privacy = $value['profile_privacy'];
                //                 $setUpdatePrivacyData->status = $value["status"];
                //                 $setUpdatePrivacyData->user_id = $user->id;
                //                 $setUpdatePrivacyData->save();
                //             }
                //         }
                //     }
                // }
            }
            DB::commit();
            return response()->json(['status' => 1, 'message' => "Message privacy changed successfully"]);
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }

    public function getNotificationSetting()
    {
        $user  = Auth::guard('api')->user();


        $checkNotificationSetting =  UserNotificationType::select('type', 'push', 'email')->where('user_id', $user->id)->get();
        if (count($checkNotificationSetting) != 0) {
            return response()->json(['status' => 1, 'message' => "Notification setting", 'data' => $checkNotificationSetting]);
        }
    }

    public function notificationSetting(Request $request)

    {

        $user  = Auth::guard('api')->user();



        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        try {
            DB::beginTransaction();

            $checkNotificationSetting =  UserNotificationType::where('user_id', $user->id)->count();
            if ($checkNotificationSetting  == 0) {
                foreach ($input['notification_settings'] as $val) {
                    $setNotification = new UserNotificationType();
                    $setNotification->user_id = $user->id;
                    $setNotification->type = $val['type'];
                    $setNotification->push = $val['push'];
                    $setNotification->email = $val['email'];
                    $setNotification->save();
                }
            } else {

                foreach ($input['notification_settings'] as $value) {
                    $updateNotification = UserNotificationType::where(['type' => $value['type'], 'user_id' => $user->id])->first();
                    $updateNotification->push = $value['push'];
                    $updateNotification->email = $value['email'];
                    $updateNotification->save();
                }
            }
            DB::commit();
            return response()->json(['status' => 1, 'message' => "Notification set successfully"]);
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }

    public function generalSetting(Request $request)

    {

        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [

            'photo_via_wifi' => [

                'required',

                'in:0,1'

            ],

            'enable_face_id_login' => [

                'required',

                'in:0,1'

            ]



        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),

            ]);
        }





        try {

            DB::beginTransaction();
            $user->photo_via_wifi = $input["photo_via_wifi"];

            $user->enable_face_id_login = $input["enable_face_id_login"];

            $user->save();

            DB::commit();

            return response()->json(['status' => 1, 'message' => "general changed successfully"]);
        } catch (QueryException $e) {



            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }

    public function deleteAccount()
    {

        $user  = Auth::guard('api')->user();



        try {

            DB::beginTransaction();

            $userDelete = User::find($user->id);

            $userDelete->delete();

            Token::where('user_id', $user->id)->delete();

            DB::commit();



            return response()->json(['status' => 1, 'message' => "Account deleted sucessfully"]);
        } catch (QueryException $e) {



            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }

    public function addContact(Request $request)
    {

        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        if ($input['prefer_by'] == 'email') {

            $validator = Validator::make($input, [
                'firstname' => ['required'],
                'lastname' => ['required'],
                'email' => ['required', 'email:rfc,dns', 'unique:users,email'],
                'prefer_by' => ['required', 'in:email,phone']

            ]);
        } elseif ($input['prefer_by'] == 'phone') {
            $validator = Validator::make($input, [
                'firstname' => ['required'],
                'lastname' => ['required'],
                'country_code' => ['required'],
                'phone_number' => ['required', 'unique:users,phone_number'],
                'email' => ['required', 'email:rfc,dns', 'unique:users,email'],
                'prefer_by' => ['required', 'in:email,phone']

            ]);
        }



        $customMessages = [
            'prefer_by.in' => 'The prefer_by field must be email or phone.',
        ];


        $validator->setCustomMessages($customMessages);
        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),



            ]);
        }
        try {
            DB::beginTransaction();

            $addContact = new User;
            $addContact->parent_user_phone_contact = $user->id;
            $addContact->firstname = $input['firstname'];
            $addContact->lastname = $input['lastname'];
            $addContact->country_code = ($input['country_code'] != "") ? $input['country_code'] : 0;
            $addContact->phone_number = $input['phone_number'];
            $addContact->email = $input['email'];
            $addContact->app_user = '0';
            $addContact->prefer_by = $input['prefer_by'];
            $addContact->save();
            DB::commit();
            // $addedUser =  User::where('id', $addContact->id)->first();
            $addedUser = User::where('id', $addContact->id)->select('id', 'firstname', 'lastname', 'country_code', 'phone_number', 'email', 'app_user', 'prefer_by')->first();
            $useData = [
                'id' =>  $addedUser->id,
                'first_name' =>  $addedUser->firstname,
                'last_name' =>  $addedUser->lastname,
                'profile' => (isset($addedUser->profile) && $addedUser->profile != NULL) ? asset('public/storage/profile/' . $addedUser->profile) : "",
                'country_code' =>  (string)$addedUser->country_code,
                'phone_number' =>  $addedUser->phone_number,
                'email' =>  $addedUser->email,
                'app_user' =>  $addedUser->app_user,
                'prefer_by' => $addedUser->prefer_by
            ];

            return response()->json(['status' => 1, 'data' => $useData, 'message' => "Contact Saved"]);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
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
                    'profile' => (isset($updateUser->profile) && $updateUser->profile != NULL) ? asset('public/storage/profile/' . $updateUser->profile) : "",
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
        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }



        $validator = Validator::make($input, [
            'user_id' => ['required'],

        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }
        try {
            $deleteUser = User::where(['id' => $input['user_id']])->first();
            if ($deleteUser != null) {

                $deleteUser->delete();
                return response()->json(['status' => 1, 'message' => "User deleted successfully"]);
            } else {
                return response()->json(['status' => 0, 'message' => "User is not removed"]);
            }
        } catch (QueryException $e) {

            return response()->json(['status' => 0, 'message' => 'db error']);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }

    public function removeGuestFromInvite(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }



        $validator = Validator::make($input, [

            'user_id' => ['required'],

            'event_id' => ['required'],


        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }
        try {
            $getGuest = EventInvitedUser::where(['event_id' => $input['event_id'], 'user_id' => $input['user_id']])->first();
            if ($getGuest != null) {

                $checkNotificationdata = Notification::where(['event_id' => $input['event_id'], 'user_id' => $input['user_id']])->first();
                if ($checkNotificationdata != null) {
                    $checkNotificationdata->delete();
                }

                $getGuest->delete();
                return response()->json(['status' => 1, 'message' => "Guest removed successfully"]);
            } else {
                return response()->json(['status' => 0, 'message' => "Guest is not removed"]);
            }
        } catch (QueryException $e) {

            return response()->json(['status' => 0, 'message' => 'db error']);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }

    public function getEventType()

    {
        try {
            $eventTypeData = getEventType();
            return response()->json(['status' => 1, 'message' => "Event types", "data" => $eventTypeData]);
        } catch (QueryException $e) {

            return response()->json(['status' => 0, 'message' => 'db error']);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }

    public function getYesviteContactList()
    {

        try {
            $user  = Auth::guard('api')->user();
            $groupList = getGroupList($user->id);
            $yesvitecontactList = getYesviteContactList($user->id);
            return response()->json(['status' => 1, 'message' => "Yesvite contact list", "data" => $yesvitecontactList, 'group' => $groupList]);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }


    public function getYesviteContactListPage(Request $request)
    {

        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        try {
            $page = (isset($input['page']) || $input['page'] != "") ? $input['page'] : "1";
            $search_name = (isset($input['search_name']) || $input['search_name'] != "") ? $input['search_name'] : "";
            $user  = Auth::guard('api')->user();
            $groupList = getGroupList($user->id);
            $yesvitecontactList = getYesviteContactListPage($user->id, "10", $page, $search_name);
            $yesviteRegisteredUser = User::where('id', '!=', $user->id)->where('is_user_phone_contact', '0')->where(function ($query) {
                $query->whereNull('email_verified_at')
                    ->where('app_user', '!=', '1')
                    ->orWhereNotNull('email_verified_at');
            })
                ->orderBy('firstname')
                ->count();
            $total_page = ceil($yesviteRegisteredUser / 10);
            return response()->json(['status' => 1, 'message' => "Yesvite contact list", 'total_page' => $total_page, "data" => $yesvitecontactList, 'group' => $groupList]);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }

    //  event create // 

    public function  getDesignList(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [

            'category_id' => ['required'],


        ]);

        if ($validator->fails()) {

            return response()->json([

                'message' => $validator->errors()->first(),

                'status' => 0,

            ]);
        }
        try {

            $event_design = EventDesign::get();
            if ($input['category_id'] != 0) {

                $event_design = EventDesign::where('event_design_category_id', $input['category_id'])->get();
            }

            $designList = [];
            if (count($event_design) != 0) {
                foreach ($event_design as $val) {
                    $designInfo['id'] = $val->id;
                    $designInfo['image'] = asset('storage/event_design_template/' . $val->image);
                    $designList[] = $designInfo;
                }
                return response()->json(['status' => 1, 'message' => "Event design Data", "data" => $designList]);
            } else {
                return response()->json(['status' => 1, 'message' => "No Data", "data" => $designList]);
            }
        } catch (QueryException $e) {
            return response()->json(['status' => 0, 'message' => 'db error']);
        } catch (\Throwable  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }

    public function  getDesignFilterList(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [

            'category_id' => ['required'],


        ]);

        if ($validator->fails()) {

            return response()->json([

                'message' => $validator->errors()->first(),

                'status' => 0,

            ]);
        }
        try {

            // $selectedFilters = $request->input('filters', ['host_update']);
            $event_design = EventDesign::query();
            if ($input['category_id'] != 0) {

                $event_design->where('event_design_category_id', $input['category_id']);
            }

            // if (!empty($selectedFilters) && !in_array('all', $selectedFilters)) {

            //     foreach ($selectedFilters as $filter) {

            //         switch ($filter) {
            //             case 'host_update':

            //                 $eventPostList->where('user_id', '=', $eventCreator->user_id);

            //                 break;
            //             case 'video_uploads':
            //                 $eventPostList->where('post_type', '1')->with(['post_image' => function ($qury) {
            //                     $qury->where('type', 'video');
            //                 }]);
            //                 break;
            //             case 'photo_uploads':
            //                 $eventPostList->where('post_type', '1')->with(['post_image' => function ($qury) {
            //                     $qury->where('type', 'image');
            //                 }]);
            //                 break;
            //             case 'polls':
            //                 $eventPostList->where('post_type', '2');
            //                 break;
            //             case 'comments':
            //                 $eventPostList->where('post_type', '0');
            //                 break;
            //                 // Add more cases for other filters if needed
            //         }
            //     }
            // }
            $result = $event_design->get();
            $designList = [];
            if (count($result) != 0) {
                foreach ($event_design as $val) {
                    $designInfo['id'] = $val->id;
                    $designInfo['image'] = asset('storage/event_design_template/' . $val->image);
                    $designList[] = $designInfo;
                }
                return response()->json(['status' => 1, 'message' => "Event design Data", "data" => $designList]);
            } else {
                return response()->json(['status' => 1, 'message' => "No Data", "data" => $designList]);
            }
        } catch (QueryException $e) {
            return response()->json(['status' => 0, 'message' => 'db error']);
        } catch (\Throwable  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }

    public function  getDesignStyleOptionDataList()
    {
        try {
            $eventStyle = EventDesignStyle::All();
            $styleList = [];
            foreach ($eventStyle as $value) {

                $styleInfo['id'] = $value->id;
                $styleInfo['design_name'] = $value->design_name;

                $styleList[] =  $styleInfo;
            }
            return response()->json(['status' => 1, 'message' => "Style list", "data" => $styleList]);
        } catch (QueryException $e) {

            return response()->json(['status' => 0, 'message' => 'db error']);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }

    public function getDesignOptionDataList(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }


        try {

            if (isset($input['search_category_name']) && $input['search_category_name'] != "") {
                $catSearch = $input['search_category_name'];

                $eventCategory = EventDesignCategory::with('subcategory')->withCount('subcategory')->where('category_name', 'like', "%$catSearch%")->get();
            } else {


                $eventCategory = EventDesignCategory::with('subcategory')->withCount('subcategory')->get();
            }


            $categoryList = [];
            foreach ($eventCategory as $value) {
                if ($value->subcategory_count != 0) {
                    $categoryInfo['id'] = $value->id;
                    $categoryInfo['category_name'] = $value->category_name;
                    $subcategoryList = [];
                    foreach ($value->subcategory as $subCatval) {

                        $subcategoryInfo['id'] = $subCatval->id;
                        $subcategoryInfo['subcategory_name'] = $subCatval->subcategory_name;
                        $subcategoryList[] = $subcategoryInfo;
                    }
                    $categoryInfo['subcategory'] =  $subcategoryList;
                    $categoryList[] =  $categoryInfo;
                }
            }
            return response()->json(['status' => 1, 'message' => "Category list", "data" => $categoryList]);
        } catch (QueryException $e) {

            return response()->json(['status' => 0, 'message' => 'db error']);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }

    public function draftEventList()
    {
        try {
            $user  = Auth::guard('api')->user();

            $draftEvents = Event::where(['user_id' => $user->id, 'is_draft_save' => '1'])->orderBy('id', 'DESC')->get();
            $draftEventArray = [];
            if (!empty($draftEvents) && count($draftEvents) != 0) {

                foreach ($draftEvents as $value) {
                    $eventDetail['id'] = $value->id;
                    $eventDetail['event_name'] = $value->event_name;
                    $formattedDate = Carbon::createFromFormat('Y-m-d H:i:s', $value->updated_at)->format('F j, Y h:i A');
                    $eventDetail['saved_date'] = $formattedDate;
                    $eventDetail['step'] = ($value->step != NULL) ? $value->step : 0;

                    $draftEventArray[] = $eventDetail;
                }
                return response()->json(['status' => 1, 'message' => "Draft Events", "data" => $draftEventArray]);
            } else {
                return response()->json(['status' => 0, 'message' => "No Draft Events", "data" => $draftEventArray]);
            }
        } catch (QueryException $e) {

            return response()->json(['status' => 0, 'message' => 'db error']);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }

    public function createEvent(Request $request)
    {

        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();

        $eventData = json_decode($rawData, true);

        if ($eventData == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        if ($eventData['is_draft_save'] == '0') {
            $validator = Validator::make($eventData, [

                'event_type_id' => ['required'],
                'event_name' => ['required'],

                'start_date' => ['required'],
                'end_date' => ['required'],
                'rsvp_by_date_set' => ['required', 'in:0,1'],
                'rsvp_by_date' => ['present'],
                'rsvp_start_time' => ['required'],
                'rsvp_start_timezone' => ['required'],
                'rsvp_end_time_set' => ['required', 'in:0,1'],
                'rsvp_end_time' => ['present'],
                'rsvp_end_timezone' => ['present'],
                // 'event_location_name' => ['required'],
                'address_1' => ['required'],
                // 'address_2' => ['required'],
                'state' => ['required'],
                'zip_code' => ['required'],
                'city' => ['required'],
                'latitude' => ['required'],
                'longitude' => ['required'],
                'message_to_guests' => ['present'],
                'invited_user_id' => ['array'],
                'invited_guests' => ['present', 'array'],
                'event_setting' => ['required'],
                'greeting_card_list' => ['array'],
                'co_host_list' => ['array'],
                'guest_co_host_list' => ['array'],
                'gift_registry_list' => ['array'],
                'podluck_category_list' => ['array'],
                'events_schedule_list' => ['array'],
                'is_draft_save' => ['required', 'in:0,1']
            ]);
        } else {

            $validator = Validator::make($eventData, [

                'event_type_id' => ['required'],
                'event_name' => ['required'],
                'start_date' => ['required'],
                'end_date' => ['required'],
                'is_draft_save' => ['required', 'in:0,1']
            ]);
        }


        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }

        // try {
        DB::beginTransaction();



        $rsvp_by_date = date('Y-m-d');
        $rsvp_by_date_set = '0';

        $rsvpEndTime = "";

        if (!empty($eventData['rsvp_by_date'])) {

            $rsvp_by_date = $eventData['rsvp_by_date'];
            $rsvp_by_date_set = '1';
        } else {
            if (!empty($eventData['start_date'])) {

                $start = new DateTime($eventData['start_date']);
                $start->modify('-1 day');
                $rsvp_by_date = $start->format('Y-m-d');
            }
        }




        $greeting_card_id = "";
        if ($eventData['event_setting']['thank_you_cards'] == '1') {

            if (!empty($eventData['greeting_card_list']) && is_int($eventData['greeting_card_list'][0])) {

                $greeting_card_id =  implode(',', $eventData['greeting_card_list']);
            }
        }

        $gift_registry_id = "";
        if ($eventData['event_setting']['gift_registry'] == '1') {
            if (!empty($eventData['gift_registry_list']) && is_int($eventData['gift_registry_list'][0])) {

                $gift_registry_id =  implode(',', $eventData['gift_registry_list']);
            }
        }




        $eventCreation =  Event::create([

            'event_type_id' => (!empty($eventData['event_type_id'])) ? $eventData['event_type_id'] : "",

            'event_name' => (!empty($eventData['event_name'])) ? $eventData['event_name'] : "",

            'user_id' => $user->id,

            'hosted_by' => (!empty($eventData['hosted_by'])) ? $eventData['hosted_by'] : $user->firstname . ' ' . $user->lastname,
            'latitude' => (!empty($eventData['latitude'])) ? $eventData['latitude'] : "",
            'longitude' => (!empty($eventData['longitude'])) ? $eventData['longitude'] : "",
            'start_date' => (!empty($eventData['start_date'])) ? $eventData['start_date'] : NULL,

            'end_date' => (!empty($eventData['end_date'])) ? $eventData['end_date'] : NULL,
            //'rsvp_by_date_set' => $eventData['rsvp_by_date_set'],
            'rsvp_by_date_set' => $rsvp_by_date_set,
            // 'rsvp_by_date' => (!empty($eventData['rsvp_by_date'])) ? $eventData['rsvp_by_date'] : NULL,
            'rsvp_by_date' => $rsvp_by_date,

            'rsvp_start_time' => $eventData['rsvp_start_time'],

            'rsvp_start_timezone' => (!empty($eventData['rsvp_start_timezone'])) ? $eventData['rsvp_start_timezone'] : "",
            'greeting_card_id' => $greeting_card_id,
            'gift_registry_id' => $gift_registry_id,
            'rsvp_end_time_set' => (!empty($eventData['rsvp_end_time_set'])) ? $eventData['rsvp_end_time_set'] : "0",
            'rsvp_end_time' => $eventData['rsvp_end_time'],
            'rsvp_end_timezone' => ($eventData['rsvp_end_time_set'] == '1') ? $eventData['rsvp_end_timezone'] : "",
            'event_location_name' => (!empty($eventData['event_location_name'])) ? $eventData['event_location_name'] : "",
            'address_1' => (!empty($eventData['address_1'])) ? $eventData['address_1'] : "",
            'address_2' => (!empty($eventData['address_2'])) ? $eventData['address_2'] : "",
            'state' => (!empty($eventData['state'])) ? $eventData['state'] : "",
            'zip_code' => (!empty($eventData['zip_code'])) ? $eventData['zip_code'] : "",
            'city' => (!empty($eventData['city'])) ? $eventData['city'] : "",
            'message_to_guests' => (!empty($eventData['message_to_guests'])) ? $eventData['message_to_guests'] : "",
            'subscription_plan_name' => (!empty($eventData['subscription_plan_name'])) ? $eventData['subscription_plan_name'] : "",
            'subscription_invite_count' => (!empty($eventData['subscription_invite_count'])) ? $eventData['subscription_invite_count'] : 0,

            'is_draft_save' => $eventData['is_draft_save']
        ]);



        if ($eventCreation) {

            $eventId = $eventCreation->id;

            if (!empty($eventData['invited_user_id'])) {

                $invitedUsers = $eventData['invited_user_id'];




                foreach ($invitedUsers as $value) {



                    EventInvitedUser::create([

                        'event_id' => $eventId,

                        'prefer_by' => $value['prefer_by'],

                        'user_id' => $value['user_id']

                    ]);
                }
            }
            if (!empty($eventData['invited_guests'])) {

                $invitedGuestUsers = $eventData['invited_guests'];



                foreach ($invitedGuestUsers as $value) {

                    if ($value['prefer_by'] == 'phone') {

                        $checkUserExist = User::where('phone_number', $value['phone_number'])->first();

                        if (empty($checkUserExist)) {

                            $guestUser = User::create([



                                'firstname' => $value['first_name'],

                                'lastname' => $value['last_name'],


                                'country_code' => ($value['country_code'] != "") ? $value['country_code'] : 0,

                                'phone_number' => $value['phone_number'],

                                'app_user' => '0',
                                'is_user_phone_contact' => '1',
                                'parent_user_phone_contact' => $user->id
                            ]);

                            EventInvitedUser::create([

                                'event_id' => $eventId,

                                'prefer_by' => $value['prefer_by'],

                                'user_id' => $guestUser->id

                            ]);
                        } else {
                            $alreadyselectedUser =  collect($eventData['invited_user_id'])->pluck('user_id')->toArray();

                            if (!in_array($checkUserExist->id, $alreadyselectedUser)) {

                                EventInvitedUser::create([

                                    'event_id' => $eventId,

                                    'prefer_by' => (isset($value['prefer_by'])) ? $value['prefer_by'] : "email",

                                    'user_id' => $checkUserExist->id

                                ]);
                            }
                        }
                    } else if ($value['prefer_by'] == 'email') {

                        $checkUserExist = User::where('email', $value['email'])->first();

                        if (empty($checkUserExist)) {

                            $guestUser = User::create([



                                'firstname' => $value['first_name'],

                                'lastname' => $value['last_name'],

                                'email' => $value['email'],

                                'app_user' => '0',
                                'is_user_phone_contact' => '1',
                                'parent_user_phone_contact' => $user->id

                            ]);

                            EventInvitedUser::create([

                                'event_id' => $eventId,

                                'prefer_by' => $value['prefer_by'],

                                'user_id' => $guestUser->id

                            ]);
                        } else {

                            $alreadyselectedUser =  collect($eventData['invited_user_id'])->pluck('user_id')->toArray();

                            if (!in_array($checkUserExist->id, $alreadyselectedUser)) {

                                EventInvitedUser::create([

                                    'event_id' => $eventId,

                                    'prefer_by' => (isset($value['prefer_by'])) ? $value['prefer_by'] : "email",

                                    'user_id' => $checkUserExist->id
                                ]);
                            }
                        }
                    }
                }
            }
            if ($eventData['event_setting']) {
                EventSetting::create([
                    'event_id' => $eventId,
                    'allow_for_1_more' => $eventData['event_setting']['allow_for_1_more'],
                    'allow_limit' => $eventData['event_setting']['allow_limit'],
                    'adult_only_party' => $eventData['event_setting']['adult_only_party'],

                    'thank_you_cards' => $eventData['event_setting']['thank_you_cards'],
                    'add_co_host' => $eventData['event_setting']['add_co_host'],
                    'gift_registry' => $eventData['event_setting']['gift_registry'],
                    'events_schedule' => $eventData['event_setting']['events_schedule'],
                    'event_wall' => $eventData['event_setting']['event_wall'],
                    'guest_list_visible_to_guests' => $eventData['event_setting']['guest_list_visible_to_guests'],
                    'podluck' => $eventData['event_setting']['podluck'],
                    'rsvp_updates' => $eventData['event_setting']['rsvp_updates'],
                    'event_wall_post' => $eventData['event_setting']['event_wall_post'],
                    'send_event_dater_reminders' => $eventData['event_setting']['send_event_dater_reminders'],
                    'request_event_photos_from_guests' => $eventData['event_setting']['request_event_photos_from_guests'],
                ]);
            }


            if ($eventData['event_setting']['add_co_host'] == '1') {

                $coHostList = $eventData['co_host_list'];

                if (!empty($coHostList)) {

                    foreach ($coHostList as $value) {
                        $alreadyselectedUser =  collect($eventData['invited_user_id'])->pluck('user_id')->toArray();
                        if (!in_array($value['user_id'], $alreadyselectedUser)) {
                            EventInvitedUser::create([

                                'event_id' => $eventId,

                                'prefer_by' => $value['prefer_by'],

                                'user_id' => $value['user_id'],
                                'is_co_host' => '1'
                            ]);
                        } else {
                            $updateRecord = EventInvitedUser::where(['user_id' => $value['user_id'], 'event_id' => $eventId])->first();
                            $updateRecord->is_co_host = '1';
                            $updateRecord->save();
                        }
                    }
                }
                $guestcoHostList = $eventData['guest_co_host_list'];

                if (!empty($guestcoHostList)) {

                    foreach ($guestcoHostList as $value) {

                        if ($value['prefer_by'] == 'phone') {

                            $checkUserExist = User::where('phone_number', $value['phone_number'])->first();

                            if (empty($checkUserExist)) {

                                $guestUser = User::create([

                                    'firstname' => $value['first_name'],

                                    'lastname' => $value['last_name'],


                                    'country_code' => ($value['country_code'] != "") ? $value['country_code'] : 0,

                                    'phone_number' => $value['phone_number'],

                                    'app_user' => '0',
                                    'is_user_phone_contact' => '1',
                                    'parent_user_phone_contact' => $user->id

                                ]);

                                EventInvitedUser::create([

                                    'event_id' => $eventId,

                                    'prefer_by' => (isset($value['prefer_by'])) ? $value['prefer_by'] : "phone",

                                    'user_id' => $guestUser->id,
                                    'is_co_host' => '1'
                                ]);
                            } else {
                                $alreadyselectedUser =  collect($eventData['invited_user_id'])->pluck('user_id')->toArray();
                                if (!in_array($checkUserExist->id, $alreadyselectedUser)) {

                                    $alreadyselectedCoHostUser =  collect($eventData['co_host_list'])->pluck('user_id')->toArray();
                                    if (!in_array($checkUserExist->id, $alreadyselectedCoHostUser)) {
                                        EventInvitedUser::create([

                                            'event_id' => $eventId,

                                            'prefer_by' => (isset($value['prefer_by'])) ? $value['prefer_by'] : "phone",

                                            'user_id' => $checkUserExist->id,
                                            'is_co_host' => '1'
                                        ]);
                                    }
                                } else {
                                    $updateRecord = EventInvitedUser::where(['user_id' => $checkUserExist->id, 'event_id' => $eventId])->first();
                                    $updateRecord->is_co_host = '1';
                                    $updateRecord->save();
                                }
                            }
                        } else if ($value['prefer_by'] == 'email') {

                            $checkUserExist = User::where('email', $value['email'])->first();

                            if (empty($checkUserExist)) {

                                $guestUser = User::create([

                                    'firstname' => $value['first_name'],

                                    'lastname' => $value['last_name'],

                                    'email' => $value['email'],

                                    'app_user' => '0',
                                    'is_user_phone_contact' => '1',
                                    'parent_user_phone_contact' => $user->id

                                ]);

                                EventInvitedUser::create([

                                    'event_id' => $eventId,

                                    'prefer_by' => (isset($value['prefer_by'])) ? $value['prefer_by'] : "email",

                                    'user_id' => $guestUser->id,
                                    'is_co_host' => '1'
                                ]);
                            } else {
                                $alreadyselectedUser =  collect($eventData['invited_user_id'])->pluck('user_id')->toArray();

                                if (!in_array($checkUserExist->id, $alreadyselectedUser)) {

                                    $alreadyselectedCoHostUser =  collect($eventData['co_host_list'])->pluck('user_id')->toArray();
                                    if (!in_array($checkUserExist->id, $alreadyselectedCoHostUser)) {
                                        EventInvitedUser::create([

                                            'event_id' => $eventId,

                                            'prefer_by' => (isset($value['prefer_by'])) ? $value['prefer_by'] : "email",

                                            'user_id' => $checkUserExist->id,
                                            'is_co_host' => '1'
                                        ]);
                                    }
                                } else {

                                    $updateRecord = EventInvitedUser::where(['user_id' => $checkUserExist->id, 'event_id' => $eventId])->first();
                                    $updateRecord->is_co_host = '1';
                                    $updateRecord->save();
                                }
                            }
                        }
                    }
                }
            }

            if ($eventData['event_setting']['events_schedule'] == '1') {

                $eventsScheduleList = $eventData['events_schedule_list'];

                if (!empty($eventsScheduleList)) {

                    $addStartschedule =  new EventSchedule();
                    $addStartschedule->event_id = $eventId;
                    $addStartschedule->start_time = $eventsScheduleList['start_time'];
                    $addStartschedule->event_date = $eventsScheduleList['event_start_date'];
                    $addStartschedule->type = '1';
                    $addStartschedule->save();

                    foreach ($eventsScheduleList['data'] as $value) {
                        EventSchedule::create([

                            'event_id' => $eventId,
                            'activity_title' => $value['activity_title'],
                            'start_time' => $value['start_time'],
                            'end_time' => $value['end_time'],
                            'event_date' => $value['event_date'],
                            'type' => '2',

                        ]);
                    }

                    $addEndschedule =  new EventSchedule();
                    $addEndschedule->event_id = $eventId;
                    $addEndschedule->end_time = $eventsScheduleList['end_time'];
                    $addEndschedule->event_date = $eventsScheduleList['event_end_date'];
                    $addEndschedule->type = '3';
                    $addEndschedule->save();
                }
            }


            if (isset($eventData['podluck_category_list']) && is_array($eventData['podluck_category_list']) && $eventData['event_setting']['podluck'] == '1'  && $eventData['is_draft_save'] == '0') {


                $podluckCategoryList = $eventData['podluck_category_list'];

                if (!empty($podluckCategoryList)) {



                    foreach ($podluckCategoryList as $value) {



                        $eventPodluck = EventPotluckCategory::create([

                            'event_id' => $eventId,
                            'user_id' => $user->id,
                            'category' => $value['category'],

                            'quantity' => $value['quantity'],

                        ]);



                        if (!empty($value['items'])) {

                            $items = $value['items'];



                            foreach ($items as $value) {



                                $eventPodluckitem = EventPotluckCategoryItem::create([

                                    'event_id' => $eventId,
                                    'user_id' => $user->id,
                                    'event_potluck_category_id' => $eventPodluck->id,
                                    'self_bring_item' => (isset($value['self_bring_item'])) ? $value['self_bring_item'] : '0',
                                    'description' => $value['description'],
                                    'quantity' => $value['quantity'],
                                ]);

                                if (isset($value['self_bring_item']) && $value['self_bring_item'] == '1') {
                                    UserPotluckItem::Create([
                                        'event_id' => $eventId,
                                        'user_id' => $user->id,
                                        'event_potluck_category_id' => $eventPodluck->id,
                                        'event_potluck_item_id' => $eventPodluckitem->id,
                                        'quantity' => (isset($value['self_quantity']) && @$value['self_quantity'] != "") ? $value['self_quantity'] : $value['quantity']
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }

        DB::commit();
        return response()->json(['status' => 1, 'event_id' => $eventCreation->id, 'event_name' => $eventData['event_name'], 'message' => "Event Created Successfully", 'guest_pending_count' => getGuestRsvpPendingCount($eventCreation->id)]);
        // } catch (QueryException $e) {
        //     DB::rollBack();

        //     return response()->json(['status' => 0, 'message' => 'Db error']);
        // } catch (Exception $e) {
        //     Log::info('API request event create something error successfully');;
        //     return response()->json(['status' => 0, 'message' => 'Something went wrong']);
        // }
    }

    public function createGroup(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $input = $request->getContent();

        $input = json_decode($input, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [
            'name' => ['required']
        ]);



        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }
        try {

            $checkGroup = Group::where(['user_id' => $user->id, 'name' => $input['name']])->count();
            if ($checkGroup == 0) {

                $createGroup = new Group();
                $createGroup->user_id = $user->id;
                $createGroup->name = $input['name'];
                $createGroup->save();

                $GroupData[] = [
                    'id' => $createGroup->id,
                    'name' => $input['name']
                ];
                return response()->json(['status' => 1, 'message' => 'Group Created', 'data' => $GroupData]);
            }
            return response()->json(['status' => 0, 'message' => 'Group name already exists, please choose another name']);
        } catch (QueryException $e) {
            return response()->json(['status' => 0, 'message' => 'Db error']);
        } catch (Exception $e) {

            return response()->json(['status' => 0, 'message' => 'Something went wrong']);
        }
    }

    public function groupList(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $input = $request->getContent();

        $input = json_decode($input, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }


        try {

            $page = isset($input['page']) ? $input['page'] : "1";

            $pages = ($page != "") ? $page : "1";
            $search = "";
            if (isset($input['search'])) {
                $search = $input['search'];
            }

            $groupCount = Group::select('id', 'name')
                ->where('user_id', $user->id)

                ->where('name', 'like', "%$search%")
                ->count();
            $total_page = ceil($groupCount / 10);



            $groupList = Group::select('id', 'name')
                ->withCount('groupMembers as members_count')
                ->where('user_id', $user->id)
                ->where('name', 'like', "%$search%")
                ->paginate(10, ['*'], 'page', $pages);


            $groupListArr = [];
            foreach ($groupList as $value) {
                $group['id'] = $value->id;
                $group['name'] = $value->name;
                $group['member_count'] = $value->members_count;
                $groupListArr[] = $group;
            }
            return response()->json(['status' => 1, 'message' => 'group created successfully', 'total_page' => $total_page, 'data' => $groupListArr]);
        } catch (QueryException $e) {
            return response()->json(['status' => 0, 'message' => 'Db error']);
        } catch (Exception $e) {

            return response()->json(['status' => 0, 'message' => 'Something went wrong']);
        }
    }

    public function deleteGroup(Request $request)
    {


        $input = $request->getContent();

        $input = json_decode($input, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [
            'group_id' => ['required', 'exists:groups,id']
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }

        try {

            $deleteGroup = Group::where(['id' => $input['group_id']])->first();
            if ($deleteGroup != null) {

                $deleteGroup->delete();

                return response()->json(['status' => 1, 'message' => 'Group deleted successfully']);
            }
            return response()->json(['status' => 0, 'message' => 'group is already deleted']);
        } catch (QueryException $e) {
            return response()->json(['status' => 0, 'message' => 'Db error']);
        } catch (Exception $e) {

            return response()->json(['status' => 0, 'message' => 'Something went wrong']);
        }
    }

    public function addGroupMember(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $input = $request->getContent();

        $input = json_decode($input, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [
            'group_id' => ['required'],
            'members_id' => ['array']
        ]);



        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }
        try {

            if (!empty($input['members_id'])) {

                foreach ($input['members_id'] as $val) {
                    $checkIsAlready = GroupMember::where(['user_id' => $val, 'group_id' => $input['group_id']])->first();
                    if ($checkIsAlready == null) {

                        $addGroupMember = new GroupMember();
                        $addGroupMember->user_id = $val;
                        $addGroupMember->group_id = $input['group_id'];
                        $addGroupMember->save();
                    }
                }
            }
            return response()->json(['status' => 1, 'message' => 'Members added in group successfully']);
        } catch (QueryException $e) {
            return response()->json(['status' => 0, 'message' => 'Db error']);
        } catch (Exception $e) {

            return response()->json(['status' => 0, 'message' => 'Something went wrong']);
        }
    }

    public function memberList(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $input = $request->getContent();

        $input = json_decode($input, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [
            'group_id' => ['required'],

        ]);



        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }

        try {

            $page = isset($input['page']);

            $pages = ($page != "") ? $page : 1;


            $groupMember = GroupMember::where('group_id', $input['group_id'])->pluck('user_id');

            $yesviteRegisteredUser = User::select('id', 'firstname', 'profile', 'lastname', 'email', 'country_code', 'phone_number', 'app_user', 'prefer_by', 'email_verified_at', 'parent_user_phone_contact')->where('id', '!=', $user->id)->whereIn('id', $groupMember)->orderBy('firstname')
                ->paginate(10, ['*'], 'page', $page);
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
                $yesviteUserDetail['first_name'] = $user->firstname;
                $yesviteUserDetail['last_name'] = $user->lastname;
                $yesviteUserDetail['email'] = (!empty($user->email) || $user->email != Null) ? $user->email : "";
                $yesviteUserDetail['country_code'] = (!empty($user->country_code) || $user->country_code != Null) ? strval($user->country_code) : "";
                $yesviteUserDetail['phone_number'] = (!empty($user->phone_number) || $user->phone_number != Null) ? $user->phone_number : "";
                $yesviteUserDetail['app_user']  = $user->app_user;
                $yesviteUserDetail['prefer_by']  = $user->prefer_by;
                $yesviteUser[] = $yesviteUserDetail;
            }

            return response()->json(['status' => 1, 'message' => 'group member list', 'data' => $yesviteUser]);
        } catch (QueryException $e) {
            return response()->json(['status' => 0, 'message' => 'Db error']);
        } catch (Exception $e) {

            return response()->json(['status' => 0, 'message' => 'Something went wrong']);
        }
    }

    public function removeUserFromGroup(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $input = $request->getContent();

        $input = json_decode($input, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [
            'user_id' => ['required'],
            'group_id' => ['required']
        ]);



        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }

        try {

            $delete = GroupMember::where(['user_id' => $input['user_id'], 'group_id' => $input['group_id']])->first();
            if ($delete != null) {
                $delete->delete();
            }
            return response()->json(['status' => 1, 'message' => 'Member removed from group']);
        } catch (QueryException $e) {
            return response()->json(['status' => 0, 'message' => 'Db error']);
        } catch (Exception $e) {

            return response()->json(['status' => 0, 'message' => 'Something went wrong']);
        }
    }

    public function getEventData(Request $request)
    {

        $user  = Auth::guard('api')->user();
        $rawData = $request->getContent();
        $eventData = json_decode($rawData, true);
        if ($eventData == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($eventData, [
            'event_id' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }

        try {
            $getEventData = Event::with('event_schedule')->where('id', $eventData['event_id'])->first();
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
                                $potluckItem['is_host'] = ($itemValue->user_id == $user->id) ? 1 : 0;
                                $potluckItem['requested_by'] =  $itemValue->users->firstname . ' ' . $itemValue->users->lastname;
                                $potluckItem['quantity'] =  $itemValue->quantity;
                                $potluckItem['self_bring_item'] =  $itemValue->self_bring_item;
                                $spoken_for = UserPotluckItem::where('event_potluck_item_id', $itemValue->id)->sum('quantity');
                                $potluckItem['spoken_quantity'] =  $spoken_for;
                                $potluckItem['item_carry_users'] = [];

                                foreach ($itemValue->user_potluck_items as $itemcarryUser) {
                                    $userPotluckItem['id'] = $itemcarryUser->id;
                                    $userPotluckItem['user_id'] = $itemcarryUser->user_id;
                                    $userPotluckItem['is_host'] = ($itemcarryUser->user_id == $user->id) ? 1 : 0;
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

    public function editEvent(Request $request)
    {

        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();

        $eventData = json_decode($rawData, true);

        if ($eventData == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        if ($eventData['is_draft_save'] == '0') {
            $validator = Validator::make($eventData, [
                'event_id' => ['required', 'exists:events,id', new checkIsUserEvent],
                'event_type_id' => ['required'],
                'event_name' => ['required'],
                // 'hosted_by' => ['required'],
                'start_date' => ['required'],
                'end_date' => ['required'],
                'rsvp_by_date_set' => ['required', 'in:0,1'],
                'rsvp_by_date' => ['present'],
                'rsvp_start_time' => ['required'],
                'rsvp_start_timezone' => ['required'],
                'rsvp_end_time_set' => ['required', 'in:0,1'],
                'rsvp_end_time' => ['present'],
                'rsvp_end_timezone' => ['present'],
                // 'event_location_name' => ['required'],
                'address_1' => ['required'],
                // 'address_2' => ['required'],
                'state' => ['required'],
                'zip_code' => ['required'],
                'city' => ['required'],
                'latitude' => ['required'],
                'longitude' => ['required'],
                'message_to_guests' => ['present'],
                'invited_user_id' => ['array'],
                'invited_guests' => ['present', 'array'],
                'event_setting' => ['required'],
                'greeting_card_list' => ['array'],
                'co_host_list' => ['array'],
                'guest_co_host_list' => ['array'],
                'gift_registry_list' => ['array'],
                'podluck_category_list' => ['array'],
                'events_schedule_list' => ['array'],
                'is_draft_save' => ['required', 'in:0,1']
            ]);
        } else {
            $validator = Validator::make($eventData, [
                'event_id' => ['required', 'exists:events,id', new checkIsUserEvent],
                'event_type_id' => ['required'],
                'event_name' => ['required'],
                'is_draft_save' => ['required', 'in:0,1'],
                'invited_user_id' => ['present', 'array'],
                'invited_guests' => ['present', 'array'],
                'greeting_card_list' => ['array'],
                'co_host_list' => ['array'],
                'guest_co_host_list' => ['array'],
                'gift_registry_list' => ['array']
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

            $updateEvent = Event::where('id', $eventData['event_id'])->first();
            if ($updateEvent != null) {


                // $rsvp_by_date = date('Y-m-d');
                // $rsvp_by_date_set = '0';

                // $rsvpEndTime = "";


                $greeting_card_id = "";
                if ($eventData['event_setting']['thank_you_cards'] == '1') {

                    if (!empty($eventData['greeting_card_list']) && is_int($eventData['greeting_card_list'][0])) {

                        $greeting_card_id =  implode(',', $eventData['greeting_card_list']);
                    }
                }

                $gift_registry_id = "";
                if ($eventData['event_setting']['gift_registry'] == '1') {
                    if (!empty($eventData['gift_registry_list']) && is_int($eventData['gift_registry_list'][0])) {

                        $gift_registry_id =  implode(',', $eventData['gift_registry_list']);
                    }
                }



                $updateEvent->event_type_id = (!empty($eventData['event_type_id'])) ? $eventData['event_type_id'] : "";
                $updateEvent->event_name = (!empty($eventData['event_name'])) ? $eventData['event_name'] : "";

                $updateEvent->hosted_by = (!empty($eventData['hosted_by'])) ? $eventData['hosted_by'] : $user->firstname . ' ' . $user->lastname;
                $updateEvent->start_date = (!empty($eventData['start_date'])) ? $eventData['start_date'] : NULL;
                $updateEvent->end_date = (!empty($eventData['end_date'])) ? $eventData['end_date'] : NULL;

                $updateEvent->rsvp_by_date_set = $eventData['rsvp_by_date_set'];
                $updateEvent->rsvp_by_date = NULL;
                if (!empty($eventData['rsvp_by_date'])) {
                    $updateEvent->rsvp_by_date = $eventData['rsvp_by_date'];
                }
                $updateEvent->latitude = $eventData['latitude'];
                $updateEvent->longitude = $eventData['longitude'];
                $updateEvent->rsvp_start_time = $eventData['rsvp_start_time'];
                $updateEvent->rsvp_start_timezone = (!empty($eventData['rsvp_start_timezone'])) ? $eventData['rsvp_start_timezone'] : "";
                $updateEvent->greeting_card_id = $greeting_card_id;
                $updateEvent->gift_registry_id = $gift_registry_id;
                $updateEvent->rsvp_end_time_set = (!empty($eventData['rsvp_end_time_set'])) ? $eventData['rsvp_end_time_set'] : "0";
                $updateEvent->rsvp_end_time = $eventData['rsvp_end_time'];;
                $updateEvent->rsvp_end_timezone = ($eventData['rsvp_end_time_set'] == '1') ? $eventData['rsvp_end_timezone'] : "";
                $updateEvent->event_location_name = (!empty($eventData['event_location_name'])) ? $eventData['event_location_name'] : "";
                $updateEvent->address_1 = (!empty($eventData['address_1'])) ? $eventData['address_1'] : "";
                $updateEvent->address_2 = (!empty($eventData['address_2'])) ? $eventData['address_2'] : "";
                $updateEvent->state = (!empty($eventData['state'])) ? $eventData['state'] : "";
                $updateEvent->zip_code = (!empty($eventData['zip_code'])) ? $eventData['zip_code'] : "";
                $updateEvent->city = (!empty($eventData['city'])) ? $eventData['city'] : "";
                $updateEvent->message_to_guests = (!empty($eventData['message_to_guests'])) ? $eventData['message_to_guests'] : "";
                $updateEvent->is_draft_save = $eventData['is_draft_save'];
                $updateEvent->subscription_plan_name = (!empty($eventData['subscription_plan_name'])) ? $eventData['subscription_plan_name'] : "";
                $updateEvent->subscription_invite_count = (!empty($eventData['subscription_invite_count'])) ? $eventData['subscription_invite_count'] : 0;

                if ($updateEvent->save()) {
                    $getalreadyInviteduser =  EventInvitedUser::where('event_id', $eventData['event_id'])->get()->pluck('user_id')->toArray();

                    // EventInvitedUser::where('event_id', $eventData['event_id'])->delete();

                    if (isset($eventData['invited_user_id']) && !empty($eventData['invited_user_id'])) {
                        $invitedUsers = $eventData['invited_user_id'];


                        foreach ($invitedUsers as $value) {

                            if (in_array($value['user_id'], $getalreadyInviteduser)) {
                                continue;
                            }



                            EventInvitedUser::create([

                                'event_id' => $eventData['event_id'],

                                'prefer_by' => $value['prefer_by'],

                                'user_id' => $value['user_id']

                            ]);
                        }

                        $userSelectedGuest =  collect($eventData['invited_user_id'])->pluck('user_id')->toArray();
                        foreach ($getalreadyInviteduser as $value) {

                            if (!in_array($value, $userSelectedGuest)) {
                                EventInvitedUser::where('user_id', $value)->delete();
                            }
                        }
                    } else {
                        EventInvitedUser::where('event_id', $eventData['event_id'])->delete();
                    }



                    if (!empty($eventData['invited_guests'])) {

                        $invitedGuestUsers = $eventData['invited_guests'];

                        $alreadyinvitedUser = EventInvitedUser::where('event_id', $eventData['event_id'])->pluck('user_id')->toArray();

                        foreach ($invitedGuestUsers as $value) {

                            if ($value['prefer_by'] == 'phone') {

                                $checkUserExist = User::where('phone_number', $value['phone_number'])->first();

                                if (empty($checkUserExist)) {

                                    $guestUser = User::create([



                                        'firstname' => $value['first_name'],

                                        'lastname' => $value['last_name'],


                                        'country_code' => $value['country_code'],

                                        'phone_number' => $value['phone_number'],

                                        'app_user' => '0',
                                        'is_user_phone_contact' => '1',
                                        'parent_user_phone_contact' => $user->id

                                    ]);

                                    EventInvitedUser::create([

                                        'event_id' =>  $eventData['event_id'],

                                        'prefer_by' => $value['prefer_by'],

                                        'user_id' => $guestUser->id

                                    ]);
                                } else {

                                    $alreadyselectedUser =  collect($eventData['invited_user_id'])->pluck('user_id')->toArray();


                                    if (!in_array($checkUserExist->id, $alreadyselectedUser)) {
                                        if (!in_array($checkUserExist->id, $alreadyinvitedUser)) {

                                            EventInvitedUser::create([

                                                'event_id' => $eventData['event_id'],

                                                'prefer_by' => (isset($value['prefer_by'])) ? $value['prefer_by'] : "email",

                                                'user_id' => $checkUserExist->id

                                            ]);
                                        }
                                    }
                                }
                            } else if ($value['prefer_by'] == 'email') {

                                $checkUserExist = User::where('email', $value['email'])->first();

                                if (empty($checkUserExist)) {

                                    $guestUser = User::create([



                                        'firstname' => $value['first_name'],

                                        'lastname' => $value['last_name'],

                                        'email' => $value['email'],

                                        'app_user' => '0',
                                        'is_user_phone_contact' => '1',
                                        'parent_user_phone_contact' => $user->id

                                    ]);

                                    EventInvitedUser::create([

                                        'event_id' =>  $eventData['event_id'],

                                        'prefer_by' => $value['prefer_by'],

                                        'user_id' => $guestUser->id

                                    ]);
                                } else {
                                    $alreadyselectedUser =  collect($eventData['invited_user_id'])->pluck('user_id')->toArray();

                                    if (!in_array($checkUserExist->id, $alreadyselectedUser)) {
                                        if (!in_array($checkUserExist->id, $alreadyinvitedUser)) {
                                            EventInvitedUser::create([

                                                'event_id' => $eventData['event_id'],

                                                'prefer_by' => (isset($value['prefer_by'])) ? $value['prefer_by'] : "email",

                                                'user_id' => $checkUserExist->id
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }



                    if ($eventData['event_setting']) {

                        $updateEventSetting = EventSetting::where('event_id', $eventData['event_id'])->first();

                        $updateEventSetting->allow_for_1_more = $eventData['event_setting']['allow_for_1_more'];
                        $updateEventSetting->allow_limit = $eventData['event_setting']['allow_limit'];
                        $updateEventSetting->adult_only_party = $eventData['event_setting']['adult_only_party'];
                        $updateEventSetting->thank_you_cards = $eventData['event_setting']['thank_you_cards'];
                        $updateEventSetting->add_co_host = $eventData['event_setting']['add_co_host'];
                        $updateEventSetting->gift_registry = $eventData['event_setting']['gift_registry'];
                        $updateEventSetting->events_schedule = $eventData['event_setting']['events_schedule'];
                        $updateEventSetting->event_wall = $eventData['event_setting']['event_wall'];
                        $updateEventSetting->guest_list_visible_to_guests = $eventData['event_setting']['guest_list_visible_to_guests'];
                        $updateEventSetting->podluck = $eventData['event_setting']['podluck'];
                        $updateEventSetting->rsvp_updates = $eventData['event_setting']['rsvp_updates'];
                        $updateEventSetting->event_wall_post = $eventData['event_setting']['event_wall_post'];
                        $updateEventSetting->send_event_dater_reminders = $eventData['event_setting']['send_event_dater_reminders'];
                        $updateEventSetting->request_event_photos_from_guests = $eventData['event_setting']['request_event_photos_from_guests'];
                        $updateEventSetting->save();
                    }


                    if ($eventData['event_setting']['add_co_host'] == '1') {

                        if (isset($eventData['co_host_list'])) {

                            $coHostList = $eventData['co_host_list'];

                            if (!empty($coHostList)) {

                                foreach ($coHostList as $value) {

                                    $alreadyselectedUser =  collect($eventData['invited_user_id'])->pluck('user_id')->toArray();
                                    $alreadyselectedasCoUser =  collect($eventData['co_host_list'])->pluck('user_id')->toArray();

                                    if (!in_array($value['user_id'], $alreadyselectedUser) && !in_array($value['user_id'], $getalreadyInviteduser)) {

                                        EventInvitedUser::create([

                                            'event_id' => $eventData['event_id'],

                                            'prefer_by' => $value['prefer_by'],

                                            'user_id' => $value['user_id'],
                                            'is_co_host' => '1'
                                        ]);
                                    }
                                    // else if (!in_array($value['user_id'], $alreadyselectedasCoUser) && !in_array($value['user_id'], $getalreadyInviteduser)) {
                                    //     // remove //
                                    //     EventInvitedUser::where(['user_id' => $value['user_id'], 'event_id' => $eventData['event_id']])->delete();
                                    // }
                                    else {
                                        $updateCohostRecord = EventInvitedUser::where(['user_id' => $value['user_id'], 'event_id' => $eventData['event_id']])->first();
                                        if ($updateCohostRecord) {
                                            $updateCohostRecord->is_co_host = '1';
                                            $updateCohostRecord->save();
                                        }
                                    }
                                }
                            }
                            // else {
                            //     // remove //

                            //     EventInvitedUser::where(['event_id' => $eventData['event_id'], 'is_co_host' => '1'])->delete();
                            // }
                        }
                        if (isset($eventData['guest_co_host_list'])) {
                            $guestcoHostList = $eventData['guest_co_host_list'];
                            $alreadyselectedasguestCoUser =  collect($eventData['guest_co_host_list'])->pluck('user_id')->toArray();
                            if (!empty($guestcoHostList)) {

                                foreach ($guestcoHostList as $value) {

                                    if ($value['prefer_by'] == 'phone') {

                                        $checkUserExist = User::where('phone_number', $value['phone_number'])->first();

                                        if (empty($checkUserExist)) {

                                            $guestUser = User::create([

                                                'firstname' => $value['first_name'],

                                                'lastname' => $value['last_name'],


                                                'country_code' => $value['country_code'],

                                                'phone_number' => $value['phone_number'],

                                                'app_user' => '0',
                                                'is_user_phone_contact' => '1',
                                                'parent_user_phone_contact' => $user->id

                                            ]);

                                            EventInvitedUser::create([

                                                'event_id' => $eventData['event_id'],

                                                'prefer_by' => (isset($value['prefer_by'])) ? $value['prefer_by'] : "phone",

                                                'user_id' => $guestUser->id,
                                                'is_co_host' => '1'
                                            ]);
                                        } else {
                                            $alreadyselectedUser =  collect($eventData['invited_user_id'])->pluck('user_id')->toArray();
                                            if (!in_array($checkUserExist->id, $alreadyselectedUser)) {

                                                $alreadyselectedCoHostUser =  collect($eventData['co_host_list'])->pluck('user_id')->toArray();
                                                if (!in_array($checkUserExist->id, $alreadyselectedCoHostUser)) {
                                                    $checkIsAlreadyInvited = EventInvitedUser::where(['event_id' => $eventData['event_id'], 'user_id' => $checkUserExist->id])->first();
                                                    if ($checkIsAlreadyInvited == null) {

                                                        EventInvitedUser::create([

                                                            'event_id' => $eventData['event_id'],

                                                            'prefer_by' => (isset($value['prefer_by'])) ? $value['prefer_by'] : "phone",

                                                            'user_id' => $checkUserExist->id,
                                                            'is_co_host' => '1'
                                                        ]);
                                                    } else {
                                                        $updateRecord = EventInvitedUser::where(['user_id' => $checkUserExist->id, 'event_id' => $eventData['event_id']])->first();
                                                        $updateRecord->is_co_host = '1';
                                                        $updateRecord->save();
                                                    }
                                                }
                                            }
                                            //  else if (!in_array($value['user_id'], $alreadyselectedasguestCoUser) && !in_array($value['user_id'], $getalreadyInviteduser)) {
                                            //     // remove //
                                            //     EventInvitedUser::where(['user_id' => $value['user_id'], 'event_id' => $eventData['event_id']])->delete();
                                            // }
                                            else {
                                                $updateRecord = EventInvitedUser::where(['user_id' => $checkUserExist->id, 'event_id' => $eventData['event_id']])->first();
                                                $updateRecord->is_co_host = '1';
                                                $updateRecord->save();
                                            }
                                        }
                                    } else if ($value['prefer_by'] == 'email') {

                                        $checkUserExist = User::where('email', $value['email'])->first();

                                        if (empty($checkUserExist)) {

                                            $guestUser = User::create([

                                                'firstname' => $value['first_name'],

                                                'lastname' => $value['last_name'],

                                                'email' => $value['email'],

                                                'app_user' => '0',
                                                'is_user_phone_contact' => '1',
                                                'parent_user_phone_contact' => $user->id

                                            ]);

                                            EventInvitedUser::create([

                                                'event_id' => $eventData['event_id'],

                                                'prefer_by' => (isset($value['prefer_by'])) ? $value['prefer_by'] : "email",

                                                'user_id' => $guestUser->id,
                                                'is_co_host' => '1'
                                            ]);
                                        } else {
                                            $alreadyselectedUser =  collect($eventData['invited_user_id'])->pluck('user_id')->toArray();
                                            if (!in_array($checkUserExist->id, $alreadyselectedUser)) {

                                                $alreadyselectedCoHostUser =  collect($eventData['co_host_list'])->pluck('user_id')->toArray();
                                                if (!in_array($checkUserExist->id, $alreadyselectedCoHostUser)) {
                                                    $checkIsAlreadyInvited = EventInvitedUser::where(['event_id' => $eventData['event_id'], 'user_id' => $checkUserExist->id])->first();
                                                    if ($checkIsAlreadyInvited == null) {
                                                        EventInvitedUser::create([

                                                            'event_id' => $eventData['event_id'],

                                                            'prefer_by' => (isset($value['prefer_by'])) ? $value['prefer_by'] : "email",

                                                            'user_id' => $checkUserExist->id,
                                                            'is_co_host' => '1'
                                                        ]);
                                                    }
                                                }
                                            }
                                            // else if (!in_array($value['user_id'], $alreadyselectedasguestCoUser) && !in_array($value['user_id'], $getalreadyInviteduser)) {
                                            //     // remove //
                                            //     EventInvitedUser::where(['user_id' => $value['user_id'], 'event_id' => $eventData['event_id']])->delete();
                                            // }
                                            else {
                                                $updateRecord = EventInvitedUser::where(['user_id' => $checkUserExist->id, 'event_id' => $eventData['event_id']])->first();
                                                $updateRecord->is_co_host = '1';
                                                $updateRecord->save();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        //  else {
                        //     // remove //
                        //     EventInvitedUser::where(['event_id' => $eventData['event_id'], 'is_co_host' => '1'])->delete();
                        // }
                    }
                    //  else {
                    //     EventInvitedUser::where(['event_id' => $eventData['event_id'], 'is_co_host' => '1'])->delete();
                    // }



                    if ($eventData['event_setting']['events_schedule'] == '1') {

                        $eventsScheduleList = $eventData['events_schedule_list'];


                        if (!empty($eventsScheduleList)) {
                            EventSchedule::where('event_id', $eventData['event_id'])->delete();

                            $addStartschedule =  new EventSchedule();
                            $addStartschedule->event_id = $eventData['event_id'];
                            $addStartschedule->start_time = $eventsScheduleList['start_time'];
                            $addStartschedule->event_date = $eventsScheduleList['event_start_date'];
                            $addStartschedule->type = '1';
                            $addStartschedule->save();

                            foreach ($eventsScheduleList['data'] as $value) {
                                EventSchedule::create([

                                    'event_id' => $eventData['event_id'],
                                    'activity_title' => $value['activity_title'],
                                    'start_time' => $value['start_time'],
                                    'end_time' => $value['end_time'],
                                    'event_date' => $value['event_date'],
                                    'type' => '2',

                                ]);
                            }

                            $addEndschedule =  new EventSchedule();
                            $addEndschedule->event_id = $eventData['event_id'];
                            $addEndschedule->end_time = $eventsScheduleList['end_time'];
                            $addEndschedule->event_date = $eventsScheduleList['event_end_date'];
                            $addEndschedule->type = '3';
                            $addEndschedule->save();
                        }
                    }

                    if ($eventData['is_draft_save'] == '1') {
                        EventPotluckCategory::where('event_id', $eventData['event_id'])->delete();
                    }
                    if ($eventData['event_setting']['podluck'] == '1' && $eventData['is_draft_save'] == '0') {

                        $podluckCategoryList = $eventData['podluck_category_list'];

                        if (!empty($podluckCategoryList)) {

                            EventPotluckCategory::where('event_id', $eventData['event_id'])->delete();


                            foreach ($podluckCategoryList as $value) {



                                $eventPodluck = EventPotluckCategory::create([

                                    'event_id' => $eventData['event_id'],
                                    'user_id' => $user->id,

                                    'category' => $value['category'],

                                    'quantity' => $value['quantity'],

                                ]);



                                if (!empty($value['items'])) {

                                    $items = $value['items'];



                                    foreach ($items as $value) {



                                        $eventPodluckitem =   EventPotluckCategoryItem::create([

                                            'event_id' => $eventData['event_id'],
                                            'user_id' => $user->id,
                                            'event_potluck_category_id' => $eventPodluck->id,
                                            'self_bring_item' => (isset($value['self_bring_item'])) ? $value['self_bring_item'] : '0',
                                            'description' => $value['description'],

                                            'quantity' => $value['quantity'],

                                        ]);

                                        if (isset($value['self_bring_item']) && $value['self_bring_item'] == '1') {
                                            UserPotluckItem::Create([
                                                'event_id' => $eventData['event_id'],
                                                'user_id' => $user->id,
                                                'event_potluck_category_id' => $eventPodluck->id,
                                                'event_potluck_item_id' => $eventPodluckitem->id,
                                                'quantity' => (isset($value['self_quantity']) && @$value['self_quantity'] != "") ? $value['self_quantity'] : $value['quantity']
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }



                if (isset($eventData['addr_change']) && $eventData['addr_change'] == '1') {

                    $notificationParam = [
                        'sender_id' => $user->id,
                        'event_id' => $eventData['event_id'],
                        'from_addr' => $eventData['from_addr'],
                        'to_addr' => $eventData['to_addr'],
                        'newUser' => $eventData['invited_new_guest']
                    ];

                    sendNotification('update_address', $notificationParam);
                }

                if (isset($eventData['time_change']) && $eventData['time_change'] == '1') {

                    $notificationParam = [
                        'sender_id' => $user->id,
                        'event_id' => $eventData['event_id'],
                        'from_time' => $eventData['from_time'],
                        'to_time' => $eventData['to_time'],
                        'newUser' => $eventData['invited_new_guest']
                    ];

                    sendNotification('update_time', $notificationParam);
                }

                if (isset($eventData['update_date']) && $eventData['update_date'] == '1') {

                    $notificationParam = [
                        'sender_id' => $user->id,
                        'event_id' => $eventData['event_id'],
                        'old_start_end_date' => $eventData['old_start_end_date'],
                        'new_start_end_date' => $eventData['new_start_end_date'],
                        'newUser' => $eventData['invited_new_guest']
                    ];

                    sendNotification('update_date', $notificationParam);
                }

                if (isset($eventData['addr_change']) && $eventData['addr_change'] == '0' && isset($eventData['time_change']) && $eventData['time_change'] == '0' && $eventData['update_date'] == '0') {
                    $notificationParam = [
                        'sender_id' => $user->id,
                        'event_id' => $eventData['event_id'],
                        'from_time' => $eventData['from_time'],
                        'to_time' => $eventData['to_time'],
                        'newUser' => $eventData['invited_new_guest']
                    ];

                    sendNotification('update_event', $notificationParam);
                }

                if (isset($eventData['invited_new_guest']) && count($eventData['invited_new_guest']) != 0) {
                    $notificationParam = [

                        'sender_id' => $user->id,

                        'event_id' => $eventData['event_id'],

                        'newUser' => $eventData['invited_new_guest']

                    ];

                    sendNotification('invite', $notificationParam);
                }


                DB::commit();

                return response()->json(['status' => 1, 'event_name' => $eventData['event_name'], 'event_id' => (int)$eventData['event_id'], 'message' => "Event updated Successfully", 'guest_pending_count' => getGuestRsvpPendingCount($eventData['event_id'])]);
            } else {

                return response()->json(['status' => 0, 'message' => 'Event is not found']);
            }
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json(['status' => 0, 'message' => 'Db error']);
        } catch (Exception $e) {

            return response()->json(['status' => 0, 'message' => 'Something went wrong']);
        }
    }

    // Gift Registry //
    public function createGiftregistry(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [
            'registry_recipient_name' => ['required'],
            'registry_link' => ['required', 'url'],
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),


            ]);
        }

        try {

            DB::beginTransaction();

            EventGiftRegistry::Create([
                'user_id' => $user->id,
                'registry_recipient_name' => $input['registry_recipient_name'],
                'registry_link' => $input['registry_link'],
            ]);
            DB::commit();
            return response()->json(['status' => 1, 'message' => "Event gift registry created"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {


            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }

    public function updateGiftregistry(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [

            'gift_registry_id' => ['required', 'exists:event_gift_registries,id'],
            'registry_recipient_name' => ['required'],
            'registry_link' => ['required', 'url']

        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),


            ]);
        }

        try {

            DB::beginTransaction();
            $updateGiftRegistry = EventGiftRegistry::where('id', $input['gift_registry_id'])->first();
            $updateGiftRegistry->user_id = $user->id;
            $updateGiftRegistry->registry_recipient_name = $input['registry_recipient_name'];
            $updateGiftRegistry->registry_link = $input['registry_link'];

            if ($updateGiftRegistry->save()) {

                DB::commit();
                return response()->json(['status' => 1, 'message' => "Gift registry updated"]);
            } else {
                return response()->json(['status' => 0, 'message' => "Gift registry is not updated"]);
            }
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {


            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }

    public function deleteGiftregistry(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [

            'gift_registry_id' => ['required', 'exists:event_gift_registries,id']

        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),


            ]);
        }

        try {
            $deleteGiftRegistry = EventGiftRegistry::where('id', $input['gift_registry_id'])->first();

            if ($deleteGiftRegistry) {
                $deleteGiftRegistry->delete();

                return response()->json(['status' => 1, 'message' => "Gift registry deleted"]);
            } else {
                return response()->json(['status' => 0, 'message' => "Gift registry is not deleted"]);
            }
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {


            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }

    public function getGiftRegistryList()
    {
        $user  = Auth::guard('api')->user();

        try {

            $GiftRegistryList = EventGiftRegistry::where('user_id', $user->id)
                ->select('id', 'user_id', 'registry_recipient_name', 'registry_link')
                ->orderBy('id', 'DESC')
                ->get();

            if (count($GiftRegistryList) != 0) {

                return response()->json(['status' => 1, 'data' => $GiftRegistryList, 'message' => "Gift registry list"]);
            } else {
                return response()->json(['status' => 0, 'message' => "Gift registry list is not available"]);
            }
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {


            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }
    // Gift Registry //

    // Greeting Card //
    public function createGreetingCard(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [
            'template_name' => ['required'],
            'message' => ['required'],
            'custom_hours_after_event' => ['present', 'numeric']
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),


            ]);
        }

        try {

            DB::beginTransaction();

            EventGreeting::Create([
                'user_id' => $user->id,
                'template_name' => $input['template_name'],
                'message' => $input['message'],
                'custom_hours_after_event' => $input['custom_hours_after_event']
            ]);
            DB::commit();
            return response()->json(['status' => 1, 'message' => "Greeting card created"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {


            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }

    public function updateGreetingCard(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [

            'greeting_card_id' => ['required', 'exists:event_greetings,id'],
            'template_name' => ['required'],
            'message' => ['required'],
            'custom_hours_after_event' => ['present', 'numeric']
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),


            ]);
        }

        try {

            DB::beginTransaction();
            $updateGreetingCard = EventGreeting::where('id', $input['greeting_card_id'])->first();
            $updateGreetingCard->user_id = $user->id;
            $updateGreetingCard->template_name = $input['template_name'];
            $updateGreetingCard->message = $input['message'];
            $updateGreetingCard->custom_hours_after_event = $input['custom_hours_after_event'];
            if ($updateGreetingCard->save()) {

                DB::commit();
                return response()->json(['status' => 1, 'message' => "Greeting card updated"]);
            } else {
                return response()->json(['status' => 0, 'message' => "Greeting card is not updated"]);
            }
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {


            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }

    public function deleteGreetingCard(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [

            'greeting_card_id' => ['required', 'exists:event_greetings,id'],

        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),


            ]);
        }

        try {
            $deleteGreetingCard = EventGreeting::where('id', $input['greeting_card_id'])->first();

            if ($deleteGreetingCard) {

                $this->manageEventGreetingCards($deleteGreetingCard);
                $deleteGreetingCard->delete();

                return response()->json(['status' => 1, 'message' => "Greeting card deleted"]);
            } else {
                return response()->json(['status' => 0, 'message' => "Greeting card is not deleted"]);
            }
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        }
        //  catch (\Exception $e) {


        //     return response()->json(['status' => 0, 'message' => "something went wrong"]);
        // }
    }

    public function manageEventGreetingCards($greetingCardData)
    {
        $allEvents = Event::where('user_id', $greetingCardData->user_id)->get();
        foreach ($allEvents as $value) {
            $greetingCards = explode(',', $value->greeting_card_id);
            if (!empty($greetingCards)) {
                $index = array_search($greetingCardData->id, $greetingCards);
                if ($index !== false) {
                    unset($greetingCards[$index]);
                    $eventupdate = Event::where('id', $value->id)->first();
                    $eventupdate->greeting_card_id = implode(',', $greetingCards);
                    $eventupdate->save();
                }
            }
        }
    }

    public function getGreetingCardList()
    {
        $user  = Auth::guard('api')->user();

        try {

            $GreetingCardList = EventGreeting::where('user_id', $user->id)
                ->select('id', 'user_id', 'template_name', 'message', 'message_sent_time', 'custom_hours_after_event')
                ->orderBy('id', 'DESC')
                ->get();

            if (count($GreetingCardList) != 0) {

                return response()->json(['status' => 1, 'data' => $GreetingCardList, 'message' => "Greeting card list"]);
            } else {
                return response()->json(['status' => 0, 'message' => "Greeting card list is not available"]);
            }
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {


            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }
    // Greeting Card //

    public function storeEventImage(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'event_id' => ['required', 'exists:events,id']
            //   'image' => ['required', 'array']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }
        try {
            DB::beginTransaction();
            if (isset($request->image) && !empty($request->image)) {
                $images = $request->image;
                $eventOldImages = EventImage::where('event_id', $request->event_id)->get();
                if (!empty($eventOldImages)) {
                    foreach ($eventOldImages as $oldImages) {
                        if (file_exists(public_path('public/storage/event_images/') . $oldImages->image)) {
                            $imagePath = public_path('public/storage/event_images/') . $oldImages->image;
                            unlink($imagePath);
                        }
                        EventImage::where('id', $oldImages->id)->delete();
                    }
                }
                foreach ($images as $value) {
                    $image = $value;
                    $imageName = time() . '_' . str_replace(' ', '_', $image->getClientOriginalName());
                    $image->move(public_path('storage/event_images'), $imageName);
                    EventImage::create([
                        'event_id' => $request->event_id,
                        'image' => $imageName
                    ]);
                }
            }

            $user  = Auth::guard('api')->user();
            $checkUserInvited = Event::withCount('event_invited_user')->where('id', $input['event_id'])->first();
            DB::commit();
            if ($request->is_update_event == '0') {
                if ($checkUserInvited->event_invited_user_count != '0' && $checkUserInvited->is_draft_save == '0') {
                    $notificationParam = [
                        'sender_id' => $user->id,
                        'event_id' => $input['event_id'],
                        'post_id' => ""
                    ];
                    sendNotification('invite', $notificationParam);
                }
                if ($checkUserInvited->is_draft_save == '0') {
                    $notificationParam = [
                        'sender_id' => $user->id,
                        'event_id' => $input['event_id'],
                        'post_id' => ""
                    ];
                    sendNotification('owner_notify', $notificationParam);
                }
            }
            return response()->json(['status' => 1, 'message' => "Event images stored successfully"]);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {
            // return response()->json(['status' => 1, 'message' => "Event images stored successfully"]);
            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }

    public function deleteEvent(Request $request)
    {

        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();



        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [

            'event_id' => ['required', 'exists:events,id'],
            'reason' => ['required']
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),

            ]);
        }

        try {

            DB::beginTransaction();

            $deleteEvent = Event::where(['id' => $input['event_id'], 'user_id' => $user->id])->first();



            if (!empty($deleteEvent)) {
                Notification::where('event_id', $input['event_id'])->delete();
                $deleteEvent->reason = $input['reason'];
                if ($deleteEvent->save()) {

                    $deleteEvent->delete();
                }

                DB::commit();

                return response()->json(['status' => 1, 'message' => "Event deleted successfully"]);
            } else {

                return response()->json(['status' => 0, 'message' => "data is incorrect"]);
            }
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {


            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }

    //  add item in  existing category //

    public function addPotluckCategory(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [
            // 'event_id' => ['required', 'exists:events,id', new CheckUserEvent],
            'event_id' => ['required', 'exists:events,id'],
            'category' => 'required|unique:event_potluck_categories,category,NULL,id,event_id,' . $input['event_id'],
            'quantity' => ['required',]
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }

        try {
            DB::beginTransaction();
            EventPotluckCategory::Create([
                'event_id' => $input['event_id'],
                'user_id' => $user->id,
                'category' => $input['category'],
                'quantity' => $input['quantity']
            ]);
            DB::commit();
            return response()->json(['status' => 1, 'message' => "Potluck category created"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {


            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }
    // 23-02-2024
    public function editPotluckCategory(Request $request)
    {
        $user = Auth::guard('api')->user();

        $rawData = $request->getContent();
        $input = json_decode($rawData, true);

        if ($input == null) {
            return response()->json(['status' => 0, 'message' => 'Json invalid']);
        }

        $validator = Validator::make($input, [
            // 'event_id' => ['required', 'exists:events,id', new CheckUserEvent],
            'event_potluck_category_id' => 'required',
            'category' => 'required',
            'quantity' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }

        try {
            DB::beginTransaction();

            $potluckCategory = EventPotluckCategory::where('id', $input['event_potluck_category_id'])->first();

            if ($potluckCategory != "") {
                // $potluckCategory->event_id = $input['event_id'];
                $potluckCategory->category = $input['category'];
                $potluckCategory->quantity = $input['quantity'];
                $potluckCategory->save();
            }

            // Check if the user has the right to edit this category (you might need to implement this)
            // For example: if ($user->id !== $potluckCategory->event->user_id) { return response()->json(['status' => 0, 'message' => 'Unauthorized'], 403); }


            DB::commit();

            return response()->json(['status' => 1, 'message' => 'Potluck category updated']);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {


            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }

    public function addPotluckCategoryItem(Request $request)
    {
        $user  = Auth::guard('api')->user();
        $rawData = $request->getContent();
        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [
            'event_id' => ['required', 'exists:events,id'],
            'event_potluck_category_id' => 'required|exists:event_potluck_categories,id',
            'self_bring_item' => ['required'],
            'description' => 'required',
            'quantity' => ['required', 'numeric']
        ]);
        $validator->setAttributeNames($input);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }

        try {

            DB::beginTransaction();

            $eventPotluckItem = EventPotluckCategoryItem::Create([
                'event_id' => $input['event_id'],
                'event_potluck_category_id' => $input['event_potluck_category_id'],
                'self_bring_item' => $input['self_bring_item'],
                'user_id' => $user->id,
                'description' => $input['description'],
                'quantity' => $input['quantity'],

            ]);



            if (isset($input['self_bring_item']) && $input['self_bring_item'] == '1') {
                UserPotluckItem::Create([
                    'event_id' =>  $input['event_id'],
                    'user_id' => $user->id,
                    'event_potluck_category_id' => $input['event_potluck_category_id'],
                    'event_potluck_item_id' => $eventPotluckItem->id,
                    'quantity' => (isset($input['self_quantity']) && @$input['self_quantity'] != "") ? $input['self_quantity'] : $input['quantity']
                ]);
            }
            DB::commit();


            return response()->json(['status' => 1, 'message' => "Potluck category item created"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {

            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }
    // 23-2-2024

    public function editPotluckCategoryItem(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();



        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [

            'event_potluck_category_item_id' => 'required',
            // 'event_id' => ['required', 'exists:events,id'],
            'event_potluck_category_id' => 'required|exists:event_potluck_categories,id',
            'description' => 'required',
            'self_bring_item' => 'required',
            'quantity' => ['required', 'numeric']

        ]);
        $validator->setAttributeNames($input);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }

        try {

            DB::beginTransaction();

            $eventPotluckItem = EventPotluckCategoryItem::where('id', $input['event_potluck_category_item_id'])->first();

            if ($eventPotluckItem != "") {
                // $eventPotluckItem->event_id = $input['event_id'];
                $eventPotluckItem->event_potluck_category_id = $input['event_potluck_category_id'];
                $eventPotluckItem->description = $input['description'];
                $eventPotluckItem->self_bring_item = $input['self_bring_item'];

                $eventPotluckItem->quantity = $input['quantity'];
                $eventPotluckItem->save();
            }



            DB::commit();
            return response()->json(['status' => 1, 'message' => "Potluck category item updated"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {

            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }



    public function EventpotluckCategoryDelete(Request $request)
    {

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [
            'event_id' => ['required', 'exists:events,id'],
            'event_potluck_category_id' => 'required|exists:event_potluck_categories,id',
        ]);


        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),



            ]);
        }

        try {

            DB::beginTransaction();

            EventPotluckCategory::where([
                'event_id' => $input['event_id'],
                'id' => $input['event_potluck_category_id'],

            ])->delete();
            DB::commit();
            return response()->json(['status' => 1, 'message' => "Potluck category deleted"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception $e) {

            return response()->json(['status' => 0, 'message' => "Something went wrong"]);
        }
    }


    public function addUserPotluckItem(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();



        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [

            'event_id' => 'required|exists:events,id',
            'event_potluck_category_id' => 'required|exists:event_potluck_categories,id',
            'event_potluck_item_id' => 'required',
            'quantity' => ['required']
        ]);
        $validator->setAttributeNames($input);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }

        try {

            DB::beginTransaction();
            $checkQty =  EventPotluckCategoryItem::where('id', $input['event_potluck_item_id'])->value('quantity');
            $checkCarryQty = intval(UserPotluckItem::where(['event_potluck_category_id' => $input['event_potluck_category_id'], 'event_potluck_item_id' => $input['event_potluck_item_id']])->sum('quantity'));



            if (strval($checkCarryQty) < $checkQty) {
                $checkIsExist = UserPotluckItem::where([
                    'event_id' => $input['event_id'],
                    'user_id' => $user->id,
                    'event_potluck_category_id' => $input['event_potluck_category_id'],
                    'event_potluck_item_id' => $input['event_potluck_item_id']
                ])->first();
                if ($checkIsExist == null) {
                    $newUserItem =  UserPotluckItem::Create([
                        'event_id' => $input['event_id'],
                        'user_id' => $user->id,
                        'event_potluck_category_id' => $input['event_potluck_category_id'],
                        'event_potluck_item_id' => $input['event_potluck_item_id'],
                        'quantity' => $input['quantity']
                    ]);
                    $checkIsExist = $newUserItem->id;
                } else {
                    $checkIsExist->quantity = $input['quantity'];
                    $checkIsExist->save();
                }


                DB::commit();
            } else {
                return response()->json(['status' => 1, 'message' => "Potluck is full !!!"]);
            }
            $getUserItemData = UserPotluckItem::with('users')->where(['id' => $checkIsExist])->first();
            $spoken_for = UserPotluckItem::where(['event_potluck_item_id' => $input['event_potluck_item_id']])->sum('quantity');
            $getCarryUser =  [
                "id" => $getUserItemData->id,
                "user_id" => $getUserItemData->user_id,
                "is_host" => ($getUserItemData->user_id == $user->id) ? 1 : 0,
                "profile" => empty($getUserItemData->users->profile) ?  "" : asset('public/storage/profile/' . $getUserItemData->users->profile),
                "first_name" => $getUserItemData->users->firstname,
                "quantity" => (!empty($getUserItemData->quantity) || $getUserItemData->quantity != NULL) ? $getUserItemData->quantity : "0",

                "last_name" =>  $getUserItemData->users->lastname
            ];
            return response()->json(['status' => 1, "spoken_for" => $spoken_for, 'data' => $getCarryUser, 'message' => "Potluck item carry by you"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {

            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }


    public function editUserPotluckItem(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();



        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [

            'user_potluck_item_id' => 'required',
            'event_potluck_category_id' => 'required|exists:event_potluck_categories,id',
            'event_potluck_item_id' => 'required',
            'quantity' => ['required']
        ]);
        $validator->setAttributeNames($input);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }

        // try {

        DB::beginTransaction();
        $checkQty =  EventPotluckCategoryItem::where('id', $input['event_potluck_item_id'])->value('quantity');
        // $checkCarryQty = intval(UserPotluckItem::where(['event_potluck_category_id' => $input['event_potluck_category_id'], 'event_potluck_item_id' => $input['event_potluck_item_id']])->sum('quantity'));



        if ($input['quantity'] <= $checkQty) {
            $checkIsExist = UserPotluckItem::where([
                'id' => $input['user_potluck_item_id']
            ])->first();
            if ($checkIsExist != null) {
                $checkIsExist->quantity = $input['quantity'];
                $checkIsExist->save();
            }
            $notificationParam = [

                'sender_id' => $user->id,
                'event_id' => $checkIsExist->event_id,
                'user_potluck_item_id' => $checkIsExist->id,
                'user_potluck_item_count' => $input['quantity']
            ];

            DB::commit();
        } else {
            return response()->json(['status' => 1, 'message' => "Potluck quantity select less then " . $checkQty . " !!!"]);
        }




        sendNotification('potluck_bring', $notificationParam);

        $getUserItemData = UserPotluckItem::with('users')->where(['id' => $input['user_potluck_item_id']])->first();
        $spoken_for = UserPotluckItem::where(['event_potluck_item_id' => $input['event_potluck_item_id']])->sum('quantity');

        $getCarryUser =  [
            "id" => $getUserItemData->id,
            "user_id" => $getUserItemData->user_id,
            "is_host" => ($getUserItemData->user_id == $user->id) ? 1 : 0,
            "profile" => empty($getUserItemData->users->profile) ?  "" : asset('public/storage/profile/' . $getUserItemData->users->profile),
            "first_name" => $getUserItemData->users->firstname,
            "quantity" => (!empty($getUserItemData->quantity) || $getUserItemData->quantity != NULL) ? $getUserItemData->quantity : "0",

            "last_name" =>  $getUserItemData->users->lastname
        ];

        return response()->json(['status' => 1, "spoken_for" => $spoken_for, 'data' => $getCarryUser, 'message' => "Potluck item updated"]);
        // } catch (QueryException $e) {

        //     DB::rollBack();

        //     return response()->json(['status' => 0, 'message' => "db error"]);
        // } catch (\Exception $e) {

        //     return response()->json(['status' => 0, 'message' => "something went wrong"]);
        // }
    }

    public function deleteUserPotluckItem(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();



        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [

            'user_potluck_item_id' => 'required',


        ]);
        $validator->setAttributeNames($input);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }

        try {

            DB::beginTransaction();

            $checkIsExist = UserPotluckItem::where([
                'id' => $input['user_potluck_item_id']
            ])->first();

            $event_potluck_item_id = $checkIsExist->event_potluck_item_id;
            if ($checkIsExist != null) {

                $checkIsExist->delete();
            }

            DB::commit();
            $spoken_for = UserPotluckItem::where(['event_potluck_item_id' => $event_potluck_item_id])->sum('quantity');
            return response()->json(['status' => 1, "spoken_for" => $spoken_for, 'message' => "Potluck item deleted"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        }
        // catch (\Exception $e) {

        //     return response()->json(['status' => 0, 'message' => "something went wrong"]);
        // }
    }

    public function deletePotluck(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }



        $validator = Validator::make($input, [
            'event_id' => ['required'],

        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }
        try {
            $deletePotluck = EventPotluckCategory::where(['event_id' => $input['event_id']])->first();
            if ($deletePotluck != null) {

                EventPotluckCategory::where(['event_id' => $input['event_id']])->delete();
                return response()->json(['status' => 1, 'message' => "Potluck deleted successfully"]);
            } else {
                return response()->json(['status' => 0, 'message' => "Potluck is not removed"]);
            }
        } catch (QueryException $e) {

            return response()->json(['status' => 0, 'message' => 'db error']);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }

    public function eventPotluck(Request $request)
    {
        $user  = Auth::guard('api')->user();
        $rawData = $request->getContent();
        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [
            'event_id' => ['required', 'exists:events,id'],
        ]);
        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }

        try {

            $eventpotluckData =  EventPotluckCategory::with(['users', 'event_potluck_category_item' => function ($query) {
                $query->with(['users', 'user_potluck_items' => function ($subquery) {
                    $subquery->with('users')->sum('quantity');
                }]);
            }])->withCount('event_potluck_category_item')->where('event_id', $input['event_id'])->get();

            $totalItems = EventPotluckCategoryItem::where('event_id', $input['event_id'])->sum('quantity');
            $spoken_for = UserPotluckItem::where('event_id', $input['event_id'])->sum('quantity');

            $checkEventOwner = Event::FindOrFail($input['event_id']);

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
                        if ($mainQty == $spokenFor) {
                            $totalSpoken += 1;
                        }
                    }

                    $itempotluckCategory['spoken_items'] = $totalSpoken;
                    $potluckItemsSummury[] = $itempotluckCategory;
                }
                $potluckDetail['item_summary'] = $potluckItemsSummury;
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
                            $potluckItem['is_host'] = ($checkEventOwner->user_id == $itemValue->user_id) ? 1 : 0;
                            $potluckItem['requested_by'] =  $itemValue->users->firstname . ' ' . $itemValue->users->lastname;
                            $potluckItem['quantity'] =  $itemValue->quantity;
                            $spoken_for = UserPotluckItem::where('event_potluck_item_id', $itemValue->id)->sum('quantity');
                            $potluckItem['spoken_quantity'] =  $spoken_for;
                            $potluckItem['item_carry_users'] = [];

                            foreach ($itemValue->user_potluck_items as $itemcarryUser) {
                                $userPotluckItem['id'] = $itemcarryUser->id;
                                $userPotluckItem['user_id'] = $itemcarryUser->user_id;
                                $userPotluckItem['is_host'] = ($checkEventOwner->user_id == $itemValue->user_id) ? 1 : 0;
                                $userPotluckItem['profile'] =  empty($itemcarryUser->users->profile) ?  "" : asset('public/storage/profile/' . $itemcarryUser->users->profile);
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
                return response()->json(['status' => 1, 'data' => $potluckDetail, 'message' => " Potluck data"]);
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

    public function eventListForCalendar()
    {
        try {
            $user  = Auth::guard('api')->user();

            $userJoinDate = $user->created_at;
            $userJoinDate = Carbon::parse($userJoinDate);

            $dataGetFromDate = $userJoinDate->toDateString();


            // Get the year the user joined
            $currentDate = Carbon::now();

            $yearsSinceJoin = $userJoinDate->diffInYears($currentDate);

            if ($yearsSinceJoin >= 3) {
                $twoYearsAgo = $currentDate->subYears(2)->startOfDay();
                $dataGetFromDate = $twoYearsAgo->toDateString();
            }
            // $currentMonth = Carbon::now()->startOfMonth();

            $eventData = Event::select('id', 'event_name', 'start_date')
                ->where('start_date', '>=', $dataGetFromDate)
                ->where(['user_id' => $user->id, 'is_draft_save' => '0'])
                ->orderBy('start_date', 'ASC');

            $invitedEvents = EventInvitedUser::where('user_id', $user->id)->get()->pluck('event_id');

            $invitedEventsList = Event::select('id', 'event_name', 'start_date')->where('start_date', '>=', $dataGetFromDate)->whereIn('id', $invitedEvents)->where('is_draft_save', '0')->orderBy('start_date', 'ASC');

            $allEvent = $eventData->union($invitedEventsList)->get();
            $allEventData = $allEvent->map(function ($event) {
                return [
                    'id' => $event['id'],
                    'event_name' => $event['event_name'],
                    'start_date' => $event['start_date']
                ];
            });

            // Sort the events by start date
            $sortedEventData = $allEventData->sortBy('start_date')->values();

            return response()->json(['status' => 1, 'message' => "Event List", 'data' => $sortedEventData]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => $e->getMessage()]);
        } catch (\Exception $e) {

            return response()->json(['status' => 0, 'message' => $e->getMessage()]);
        }
    }

    public function evenGoneTime($enddate)
    {

        $eventEndDate = $enddate; // Example end date (without time)

        // Get current date
        $currentDate = Carbon::today();


        // Get end date of the event
        $endDateTime = Carbon::parse($eventEndDate);

        // Calculate the difference in hours
        $hoursElapsed = $endDateTime->diffInHours($currentDate, false); // Passing false for negative value


        return $hoursElapsed;
    }



    public function pendingRsvpEventList()
    {
        $userNeedRsvpEventList = EventInvitedUser::whereHas('event', function ($query) {
            $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'))

                ->with(['event_image', 'event_settings', 'user', 'event_schedule'])
                ->orderBy('id', 'DESC');
        })->whereHas('user', function ($query) {
            $query->where('app_user', '1');
        })->where(['user_id' => $this->user->id, 'rsvp_status' => NULL])->get();

        $needRsvpEventList = [];

        if (count($userNeedRsvpEventList) != 0) {
            foreach ($userNeedRsvpEventList as $value) {
                $eventData['id'] = $value->event_id;
                $images = EventImage::where('event_id', $value->event_id)->first();

                $eventData['event_images'] = "";

                if (!empty($images)) {

                    $eventData['event_images'] = asset('public/storage/event_images/' . $images->image);
                }
                $needRsvpEventList[] = $eventData;
            }
        }
        return response()->json(['status' => 1, 'data' => $needRsvpEventList, 'message' => "Rsvp pending events"]);
    }


    public function acceptRejectCoHost(Request $request)
    {
        $user  = Auth::guard('api')->user();
        $rawData = $request->getContent();



        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [

            'event_id' => ['required', 'exists:events,id'],
            'status' => ['required', 'in:1,2']
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first()
            ]);
        }



        try {
            // 
            DB::beginTransaction();

            $acceptReject = EventInvitedUser::where(['user_id' => $user->id, 'event_id' => $input['event_id']])->first();


            if ($acceptReject != null) {

                $acceptReject->accept_as_co_host = $input['status'];

                if ($input['status'] == '2') {
                    $acceptReject->is_co_host = '0';
                }
                $acceptReject->save();

                $notificationParam = [

                    'sender_id' => $user->id,
                    'event_id' => $input['event_id'],
                    'status' => $input['status']
                ];

                DB::commit();

                sendNotification('accept_reject_co_host', $notificationParam);

                if ($input['status'] == '1') {

                    return response()->json(['status' => 1, 'message' => "Co host request accepted Successfully"]);
                } elseif ($input['status'] == '2') {
                    return response()->json(['status' => 1, 'message' => "Co host request rejected Successfully"]);
                }
            }
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {

            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }

    public function sentRsvp(Request $request)

    {
        $user  = Auth::guard('api')->user();
        $input = $request->all();
        $validator = Validator::make($input, [

            'event_id' => ['required', 'exists:events,id'],

            'rsvp_status' => 'required',

            'adults' => 'required',

            'kids' => 'required',

            // 'message_to_host' => "required",
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),

            ]);
        }



        try {

            $checkEvent = Event::where(['id' => $request->event_id])->first();

            if ($checkEvent->end_date < date('Y-m-d')) {
                return response()->json(['status' => 0, 'message' => "Event is past , you can't attempt RSVP"]);
            }
            DB::beginTransaction();

            $video = "";


            if (!empty($request->message_by_video)) {



                $video = $request->message_by_video;

                $videoName = time() . '_' . $video->getClientOriginalName();
                $video->move(public_path('storage/rsvp_video'), $videoName);


                $video = $videoName;
            }



            $rsvpSent = EventInvitedUser::whereHas('user', function ($query) {

                $query->where('app_user', '1');
            })->where(['user_id' => $user->id, 'event_id' => $request->event_id])->first();
            $rsvpSentAttempt = $rsvpSent->rsvp_status;

            if ($rsvpSent != null) {
                $rsvp_attempt = "";
                if ($rsvpSentAttempt == NULL) {
                    $rsvp_attempt =  'first';
                } else if ($rsvpSentAttempt == '0' && $request->rsvp_status == '1') {
                    $rsvp_attempt =  'no_to_yes';
                } else if ($rsvpSentAttempt == '1' && $request->rsvp_status == '0') {
                    $rsvp_attempt =  'yes_to_no';
                }

                $rsvpSent->event_id = $request->event_id;

                $rsvpSent->user_id = $user->id;

                $rsvpSent->rsvp_status = $request->rsvp_status;

                $rsvpSent->adults = $request->adults;

                $rsvpSent->kids = $request->kids;

                $rsvpSent->message_to_host = $request->message_to_host;
                $rsvpSent->rsvp_attempt = $rsvp_attempt;

                $rsvpSent->message_by_video = $video;

                $rsvpSent->read = '1';
                $rsvpSent->rsvp_d = '1';

                $rsvpSent->event_view_date = date('Y-m-d');

                $rsvpSent->save();

                $notificationParam = [

                    'sender_id' => $user->id,
                    'event_id' => $request->event_id,
                    'rsvp_status' => $request->rsvp_status,
                    'kids' => $request->kids,
                    'adults' => $request->adults,
                    'rsvp_video' => $video,
                    'rsvp_message' => $request->message_to_host,
                    'post_id' => "",
                    'rsvp_attempt' => $rsvp_attempt
                ];

                DB::commit();

                sendNotification('sent_rsvp', $notificationParam);


                return response()->json(['status' => 1, 'message' => "Rsvp sent Successfully"]);
            }
            return response()->json(['status' => 0, 'message' => "Rsvp not sent"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => $e->getMessage()]);
        } catch (\Exception $e) {

            return response()->json(['status' => 0, 'message' => $e->getMessage()]);
        }
    }



    public function eventAbout(Request $request)
    {

        $user  = Auth::guard('api')->user();
        $rawData = $request->getContent();
        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [
            'event_id' => ['required', 'exists:events,id']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        try {
            $eventDetail = Event::with(['user', 'event_image', 'event_schedule', 'event_settings', 'event_invited_user' => function ($query) {

                $query->where('is_co_host', '1')->with('user');
            }])->where('id', $input['event_id'])->first();
            $guestView = [];
            $eventDetails['id'] = $eventDetail->id;
            $eventDetails['event_images'] = [];
            if (count($eventDetail->event_image) != 0) {
                foreach ($eventDetail->event_image as $values) {
                    $eventDetails['event_images'][] = asset('public/storage/event_images/' . $values->image);
                }
            }
            $eventDetails['user_profile'] = empty($eventDetail->user->profile) ? "" : asset('public/storage/profile/' . $eventDetail->user->profile);
            $eventDetails['event_name'] = $eventDetail->event_name;
            $eventDetails['hosted_by'] = $eventDetail->hosted_by;
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
            $eventDetail['is_past'] = ($eventDetail->end_date < date('Y-m-d')) ? true : false;
            $eventDetails['days_till_event'] = $till_days;
            $eventDetails['event_created_timestamp'] = Carbon::parse($eventDate)->timestamp;
            $eventDetails['message_to_guests'] = $eventDetail->message_to_guests;



            $coHosts = [];

            foreach ($eventDetail->event_invited_user as $hostValues) {

                $coHostDetail['id'] = $hostValues->user_id;

                $coHostDetail['profile'] = (empty($hostValues->user->profile) || $hostValues->user->profile == NULL) ? "" : asset('public/storage/profile/' . $hostValues->user->profile);

                $coHostDetail['name'] = $hostValues->user->firstname . ' ' . $hostValues->user->lastname;

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
                if (empty($eventData)) {
                    $eventData[] = date('F d, Y', strtotime($eventDetail->start_date));
                    $numberOfGuest = EventInvitedUser::where('event_id', $eventDetail->id)->count();
                    $eventData[] = "Number of guests : " . $numberOfGuest;
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

            $eventAboutHost['comment'] = EventPostComment::where(['event_id' => $eventDetail->id, 'user_id' => $user->id])->count();
            $total_photos = EventPostImage::where(['event_id' => $eventDetail->id])->count();

            $eventAboutHost['photo_uploaded'] = $total_photos;

            $eventAboutHost['total_invite'] =  count(getEventInvitedUser($input['event_id']));

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

            return response()->json(['status' => 1, 'data' => $eventInfo, 'message' => "About event"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {

            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }


    public function eventAboutv2(Request $request)
    {
        $user  = Auth::guard('api')->user();
        $rawData = $request->getContent();
        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [
            'event_id' => ['required', 'exists:events,id']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        try {
            $eventDetail = Event::with(['user', 'event_image', 'event_schedule', 'event_settings', 'event_invited_user' => function ($query) {

                $query->where('is_co_host', '1')->with('user');
            }])->withCount(['event_invited_user' => function ($query) {
                $query->where('rsvp_status', '1');
            }])->where('id', $input['event_id'])->first();

            $guestView = [];
            $eventDetails['id'] = $eventDetail->id;

            $eventDetails['event_images'] = [];

            if (count($eventDetail->event_image) != 0) {

                foreach ($eventDetail->event_image as $values) {

                    $eventDetails['event_images'][] = asset('public/storage/event_images/' . $values->image);
                }
            }
            $eventDetails['user_profile'] = empty($eventDetail->user->profile) ? "" : asset('public/storage/profile/' . $eventDetail->user->profile);
            $eventDetails['event_name'] = $eventDetail->event_name;
            $eventDetails['hosted_by'] = $eventDetail->hosted_by;
            $eventDetails['is_host'] = ($eventDetail->user_id == $user->id) ? 1 : 0;

            $event_date = $eventDetail->start_date;
            if ($eventDetail->start_date != $eventDetail->end_date) {
                $event_date = $eventDetail->start_date . ' to ' . $event_date = $eventDetail->end_date;
            }
            $eventDetails['event_date'] = $event_date;
            $eventDetails['event_time'] =  $eventDetail->rsvp_start_time;

            if ($eventDetail->event_schedule->isNotEmpty()) {
                if ($eventDetail->event_schedule->last()->end_time != NULL || $eventDetail->event_schedule->last()->end_time != "") {

                    $eventDetails['event_time'] = $eventDetail->event_schedule->first()->start_time . ' to ' . $eventDetail->event_schedule->last()->end_time;
                } else {
                    $eventDetails['event_time'] = $eventDetail->event_schedule->first()->start_time;
                    if ($eventDetail->rsvp_end_time != NULL || $eventDetail->rsvp_end_time != "") {

                        $eventDetails['event_time'] = $eventDetail->event_schedule->first()->start_time . ' to ' . $eventDetail->rsvp_end_time;
                    }
                }
            } else {
                $eventDetails['event_time'] =  $eventDetail->rsvp_start_time;
                if ($eventDetail->rsvp_end_time != NULL || $eventDetail->rsvp_end_time != "") {
                    $eventDetails['event_time'] =  $eventDetail->rsvp_start_time . ' to ' . $eventDetail->rsvp_end_time;
                }
            }


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


            $eventDetails['days_till_event'] = $till_days;
            $eventDetails['is_past'] = ($eventDetail->end_date < date('Y-m-d')) ? true : false;
            $eventDetails['guest_thus_far'] = $eventDetail->event_invited_user_count;
            $eventDetails['event_created_timestamp'] = Carbon::parse($eventDate)->timestamp;
            $eventDetails['message_to_guests'] = $eventDetail->message_to_guests;


            $coHostDetail['id'] = $eventDetail->user_id;

            $coHostDetail['profile'] = (empty($eventDetail->user->profile) || $eventDetail->user->profile == NULL) ? "" : asset('public/storage/profile/' . $eventDetail->user->profile);

            $coHostDetail['name'] = $eventDetail->user->firstname . ' ' . $eventDetail->user->lastname;

            $coHostDetail['email'] = (empty($eventDetail->user->email) || $eventDetail->user->email == NULL) ? "" : $eventDetail->user->email;

            $coHostDetail['phone_number'] = (empty($eventDetail->user->phone_number) || $eventDetail->user->phone_number == NULL) ? "" : $eventDetail->user->phone_number;
            $coHostDetail['message_privacy'] =  $eventDetail->user->message_privacy;

            $eventDetails['co_hosts'] = $coHostDetail;

            $eventDetails['event_location_name'] = $eventDetail->event_location_name;

            $eventDetails['address_1'] = $eventDetail->address_1;

            $eventDetails['address_2'] = $eventDetail->address_2;

            $eventDetails['state'] = $eventDetail->state;

            $eventDetails['zip_code'] = $eventDetail->zip_code;

            $eventDetails['city'] = $eventDetail->city;

            $eventDetails['latitude'] = (!empty($eventDetail->latitude) || $eventDetail->latitude != null) ? $eventDetail->latitude : "";

            $eventDetails['logitude'] = (!empty($eventDetail->longitude) || $eventDetail->longitude != null) ? $eventDetail->longitude : "";

            $eventsScheduleList = [];

            foreach ($eventDetail->event_schedule as $key => $value) {
                $totalTime = "";
                $event_name =  $value->activity_title;

                if ($value->type == '1') {
                    $nextval = $eventDetail->event_schedule[$key + 1];

                    $event_name = "Start Event";
                    $stattim = $value->start_time;
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
                    $eventData[] = 'RSVP By :- ' . date('F d, Y', strtotime($eventDetail->rsvp_by_date));
                }
                if ($eventDetail->event_settings->podluck == '1') {
                    $eventData[] = "Event Potluck";
                }
                if ($eventDetail->event_settings->gift_registry == '1') {
                    $eventData[] = "Gift Registry";
                }
                if (empty($eventData)) {
                    $eventData[] = date('F d, Y', strtotime($eventDetail->start_date));
                    $numberOfGuest = EventInvitedUser::where('event_id', $eventDetail->id)->count();
                    $eventData[] = "Number of guests : " . $numberOfGuest;
                }
                $eventDetails['event_detail'] = $eventData;
            }
            $eventDetails['total_limit'] = $eventDetail->event_settings->allow_limit;
            $rsvp_status = 'rsvp';


            $eventDetails['rsvp_kids'] = 0;
            $eventDetails['rsvp_adults'] = 0;
            $checkUserrsvp = EventInvitedUser::whereHas('user', function ($query) {

                $query->where('app_user', '1');
            })->where(['user_id' => $user->id, 'event_id' => $eventDetail->id])->first();
            if ($checkUserrsvp != null) {
                if ($checkUserrsvp->rsvp_status == '1') {

                    $rsvp_status = 'attending'; // rsvp you'r going

                } else if ($checkUserrsvp->rsvp_status == '0') {
                    $rsvp_status = 'not_attending'; // rsvp you'r not going
                }
                if ($checkUserrsvp->rsvp_status == NULL) {


                    $rsvp_status = 'rsvp'; // rsvp button//

                }
                $eventDetails['rsvp_kids'] = $checkUserrsvp->kids;
                $eventDetails['rsvp_adults'] = $checkUserrsvp->adults;
            }

            $eventDetails['rsvp_status']  = $rsvp_status;




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


            $eventDetails['attending'] = $adults + $kids;



            $eventDetails['adults'] = (int)$adults;

            $eventDetails['kids'] = (int)$kids;



            $eventDetails['not_attending'] = $eventNotComing;

            $eventDetails['pending'] = $pendingUser;

            $eventDetails['comment'] = EventPostComment::where(['event_id' => $eventDetail->id, 'user_id' => $user->id])->count();
            $total_photos = EventPostImage::where(['event_id' => $eventDetail->id])->count();

            $eventDetails['photo_uploaded'] = $total_photos;

            $eventDetails['total_invite'] =  count(getEventInvitedUser($input['event_id']));

            $eventDetails['invite_view_rate'] = EventInvitedUser::whereHas('user', function ($query) {

                $query->where('app_user', '1');
            })->where(['event_id' => $eventDetail->id, 'read' => '1'])->count();

            $invite_view_percent = 0;
            if ($totalEnvitedUser != 0) {

                $invite_view_percent = EventInvitedUser::whereHas('user', function ($query) {

                    $query->where('app_user', '1');
                })->where(['event_id' => $eventDetail->id, 'read' => '1'])->count() / $totalEnvitedUser * 100;
            }

            $eventDetails['invite_view_percent'] = round($invite_view_percent, 2) . "%";

            $today_invite_view_percent = 0;
            if ($totalEnvitedUser != 0) {
                $today_invite_view_percent =   EventInvitedUser::whereHas('user', function ($query) {

                    $query->where('app_user', '1');
                })->where(['event_id' => $eventDetail->id, 'read' => '1', 'event_view_date' => date('Y-m-d')])->count() / $totalEnvitedUser * 100;
            }

            $eventDetails['today_invite_view_percent'] = round($today_invite_view_percent, 2)  . "%";

            $eventDetails['rsvp_rate'] = $eventattending;

            $eventDetails['rsvp_rate_percent'] = ($totalEnvitedUser != 0) ? $eventattending / $totalEnvitedUser * 100 . "%" : 0 . "%";

            $eventDetails['today_upstick'] = ($totalEnvitedUser != 0) ? $todayrsvprate / $totalEnvitedUser * 100 . "%" : 0 . "%";


            return response()->json(['status' => 1, 'data' => $eventDetails, 'message' => "About event"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        }
        // catch (\Exception $e) {

        //     return response()->json(['status' => 0, 'message' => 'something went wrong']);
        // }
    }




    public function eventViewUser($user_id, $event_id)
    {
        $checkViewbyuser = EventInvitedUser::whereHas('user', function ($query) {

            $query->where('app_user', '1');
        })->where(['user_id' => $user_id, 'event_id' => $event_id])->first();
        if ($checkViewbyuser != null) {
            if ($checkViewbyuser->read == '0') {
                $checkViewbyuser->read = '1';
                $checkViewbyuser->event_view_date = date('Y-m-d');
                $checkViewbyuser->save();
                DB::commit();
                return response()->json(['status' => 1, 'message' => "viewed invite"]);
            }
        }
    }



    public function eventWall(Request $request)
    {
        $user  = Auth::guard('api')->user();
        $rawData = $request->getContent();
        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [
            'event_id' => ['required', 'exists:events,id', new checkInvitedUser]
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }
        // try {
        $page = (isset($input['page'])) ? $input['page'] : "1";
        $this->eventViewUser($user->id, $input['event_id']);

        $currentDateTime = Carbon::now();

        $wallData = [];

        $wallData['owner_stories'] = [];

        $eventLoginUserStoriesList = EventUserStory::with(['user', 'user_event_story' => function ($query) use ($currentDateTime) {
            $query->where('created_at', '>', now()->subHours(24));
        }])
            ->where(['event_id' => $input['event_id'], 'user_id' => $user->id])
            ->where('created_at', '>', now()->subHours(24))
            ->first();

        if ($eventLoginUserStoriesList != null) {

            $storiesDetaill['id'] =  $eventLoginUserStoriesList->id;
            $storiesDetaill['user_id'] =  $eventLoginUserStoriesList->user->id;
            $storiesDetaill['username'] =  $eventLoginUserStoriesList->user->firstname . ' ' . $eventLoginUserStoriesList->user->lastname;
            $storiesDetaill['profile'] =  empty($eventLoginUserStoriesList->user->profile) ? "" : asset('public/storage/profile/' . $eventLoginUserStoriesList->user->profile);
            $storiesDetaill['story'] = [];
            foreach ($eventLoginUserStoriesList->user_event_story as $storyVal) {
                $storiesData['id'] = $storyVal->id;
                $storiesData['storyurl'] = empty($storyVal->story) ? "" : asset('public/storage/event_user_stories/' . $storyVal->story);
                $storiesData['type'] = $storyVal->type;
                $storiesData['post_time'] =  $this->setpostTime($storyVal->updated_at);
                $checkISeen = UserSeenStory::where(['user_id' => $user->id, 'user_event_story_id' => $storyVal->id])->count();
                $storiesData['is_seen'] = ($checkISeen != 0) ? "1" : "0";
                if ($storyVal->type == 'video') {
                    $storiesData['video_duration'] = (!empty($storyVal->duration)) ? $storyVal->duration : "";
                }
                $storiesData['created_at'] = $storyVal->updated_at;
                $storiesDetaill['story'][] = $storiesData;
            }
            $wallData['owner_stories'][] = $storiesDetaill;
        }

        $totalStories =  EventUserStory::with(['user', 'user_event_story' => function ($query) use ($currentDateTime) {
            $query->where('created_at', '>', now()->subHours(24));
        }])
            ->where('event_id', $input['event_id'])
            ->where('created_at', '>', now()->subHours(24))
            ->where('user_id', '!=', $user->id)->count();

        if (isset($input['type']) && ($input['type'] == '1' || $input['type'] == '0')) {

            $total_page_of_stories = ceil($totalStories / $this->perPage);
            $eventStoriesList = EventUserStory::with(['user', 'user_event_story' => function ($query) use ($currentDateTime) {
                $query->where('created_at', '>', now()->subHours(24));
            }])
                ->where('created_at', '>', now()->subHours(24))
                ->where('event_id', $input['event_id'])
                ->where('user_id', '!=', $user->id)
                ->paginate($this->perPage, ['*'], 'page', $page);
        } else {
            $total_page_of_stories = ceil($totalStories / $this->perPage);
            $eventStoriesList = EventUserStory::with(['user', 'user_event_story' => function ($query) use ($currentDateTime) {
                $query->where('created_at', '>', now()->subHours(24));
            }])

                ->where('created_at', '>', now()->subHours(24))
                ->where('event_id', $input['event_id'])
                ->where('user_id', '!=', $user->id)->paginate($this->perPage, ['*'], 'page', "1");
        }
        $storiesList = [];
        if (count($eventStoriesList) != 0) {
            foreach ($eventStoriesList as $value) {
                $storiesDetaill['id'] =  $value->id;
                $storiesDetaill['user_id'] =  $value->user->id;
                $storiesDetaill['username'] =  $value->user->firstname . ' ' . $value->user->lastname;
                $storiesDetaill['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);
                $storyAlldata = [];
                foreach ($value->user_event_story as $storyVal) {
                    $storiesData['id'] = $storyVal->id;
                    $storiesData['storyurl'] = empty($storyVal->story) ? "" : asset('public/storage/event_user_stories/' . $storyVal->story);
                    $storiesData['type'] = $storyVal->type;
                    $storiesData['post_time'] =  $this->setpostTime($storyVal->created_at);
                    $checkISeen = UserSeenStory::where(['user_id' => $user->id, 'user_event_story_id' => $storyVal->id])->count();
                    $storiesData['is_seen'] = ($checkISeen != 0) ? "1" : "0";
                    if ($storyVal->type == 'video') {
                        $storiesData['video_duration'] = (!empty($storyVal->duration)) ? $storyVal->duration : "";
                    }
                    $storiesData['created_at'] =  $storyVal->created_at;
                    $storyAlldata[] = $storiesData;
                }
                $storiesDetaill['story'] = $storyAlldata;
                $storiesList[] = $storiesDetaill;
            }
        }
        //  Posts List //
        $selectedFilters = $request->input('filters');
        $eventCreator = Event::where('id', $input['event_id'])->first();
        $eventPostList = EventPost::query();
        $eventPostList->with(['user', 'post_image'])
            ->withCount([
                'event_post_comment' => function ($query) {
                    $query->where('parent_comment_id', NULL);
                },
                'event_post_reaction'
            ])
            ->where([
                'event_id' => $input['event_id'],
                'is_in_photo_moudle' => '0'
            ])
            ->whereDoesntHave('post_control', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('post_control', 'hide_post');
            });
        $checkEventOwner = Event::where(['id' => $input['event_id'], 'user_id' => $user->id])->first();

        if ($checkEventOwner == null) {
            $eventPostList->where(function ($query) use ($user, $input) {
                $query->where('user_id', $user->id)
                    ->orWhereHas('event.event_invited_user', function ($subQuery) use ($user, $input) {
                        $subQuery->whereHas('user', function ($userQuery) {
                            $userQuery->where('app_user', '1');
                        })
                            ->where('event_id', $input['event_id'])
                            ->where('user_id', $user->id)
                            ->where(function ($privacyQuery) {
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
                                        // This block is for post_privacy == 1
                                        $q->where('post_privacy', '1');
                                    });
                            });
                    });
            });
        }
        $eventPostList->orderBy('id', 'desc');
        if (!empty($selectedFilters) && !in_array('all', $selectedFilters)) {
            $eventPostList->where(function ($query) use ($selectedFilters, $eventCreator) {
                foreach ($selectedFilters as $filter) {
                    switch ($filter) {
                        case 'host_update':
                            $query->orWhere('user_id', $eventCreator->user_id);
                            break;
                        case 'video_uploads':
                            $query->orWhere(function ($qury) {
                                $qury->where('post_type', '1')
                                    ->whereHas('post_image', function ($q) {
                                        $q->where('type', 'video');
                                    });
                            });
                            break;
                        case 'photo_uploads':
                            $query->orWhere(function ($qury) {
                                $qury->where('post_type', '1')
                                    ->whereHas('post_image', function ($q) {
                                        $q->where('type', 'image');
                                    });
                            });
                            break;
                        case 'polls':
                            $query->orWhere('post_type', '2');
                            break;
                        case 'comments':
                            $query->orWhere('post_type', '0');
                            break;
                            // Add more cases for other filters if needed
                    }
                }
            });
        }

        $totalPostWalls = $eventPostList->count();
        $results = $eventPostList->paginate($this->perPage, ['*'], 'page', $page);
        $total_page_of_eventPosts = ceil($totalPostWalls / $this->perPage);
        $postList = [];
        // dd($eventPostList);
        if (!empty($checkEventOwner)) {
            if (count($results) != 0) {
                foreach ($results as  $value) {
                    $checkUserRsvp = checkUserAttendOrNot($value->event_id, $value->user->id);
                    $ischeckEventOwner = Event::where(['id' => $input['event_id'], 'user_id' => $value->user->id])->first();
                    $postControl = PostControl::where(['user_id' => $user->id, 'event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();
                    $count_kids_adult = EventInvitedUser::where(['event_id' => $input['event_id'], 'user_id' => $value->user->id])
                        ->select('kids', 'adults', 'event_id', 'rsvp_status', 'user_id')
                        ->first();
                    if ($postControl != null) {
                        if ($postControl->post_control == 'hide_post') {
                            continue;
                        }
                    }
                    $checkUserIsReaction = EventPostReaction::where(['event_id' => $input['event_id'], 'event_post_id' => $value->id, 'user_id' => $user->id])->first();
                    $postsNormalDetail['id'] =  $value->id;
                    $postsNormalDetail['user_id'] =  $value->user->id;
                    $postsNormalDetail['is_host'] =  ($ischeckEventOwner != null) ? 1 : 0;
                    $postsNormalDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;
                    $postsNormalDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);
                    $postsNormalDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;
                    $postsNormalDetail['rsvp_status'] = $checkUserRsvp;
                    $postsNormalDetail['kids'] = isset($count_kids_adult['kids']) ? $count_kids_adult['kids'] : 0;
                    $postsNormalDetail['adults'] = isset($count_kids_adult['adults']) ? $count_kids_adult['adults'] : 0;
                    $postsNormalDetail['location'] = ($value->user->city != NULL) ? $value->user->city : "";
                    $postsNormalDetail['post_type'] = $value->post_type;
                    $postsNormalDetail['created_at'] = $value->created_at;
                    $postsNormalDetail['posttime'] = setpostTime($value->created_at);
                    $postsNormalDetail['post_image'] = [];
                    if ($value->post_type == '1' && !empty($value->post_image)) {
                        foreach ($value->post_image as $imgVal) {
                            $postMedia = [
                                'media_url' => asset('public/storage/post_image/' . $imgVal->post_image),
                                'type' => $imgVal->type,
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
                        $polls = EventPostPoll::with('event_poll_option')->withCount('user_poll_data')->where(['event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();
                        $postsNormalDetail['total_poll_vote'] = $polls->user_poll_data_count;
                        $pollDura = getLeftPollTime($polls->updated_at, $polls->poll_duration);
                        $postsNormalDetail['poll_duration'] = $pollDura;
                        $leftDay = (int) preg_replace('/[^0-9]/', '', $polls->poll_duration);
                        $postsNormalDetail['is_expired'] =  ($pollDura == "") ? true : false;
                        $postsNormalDetail['poll_id'] = $polls->id;
                        $postsNormalDetail['poll_question'] = $polls->poll_question;
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
                    $postsNormalDetail['post_recording'] = empty($value->post_recording) ? "" : asset('public/storage/event_post_recording/' . $value->post_recording);
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
                    $postList[] = $postsNormalDetail;
                }
            }
        } else {
            if (count($results) != 0) {
                foreach ($results as $value) {

                    $checkUserRsvp = checkUserAttendOrNot($value->event_id, $value->user->id);
                    $count_kids_adult = EventInvitedUser::where(['event_id' => $input['event_id'], 'user_id' => $value->user->id])
                        ->select('kids', 'adults', 'event_id', 'rsvp_status', 'user_id')
                        ->first();
                    // dd($count_kids_adult['kids']);
                    $ischeckEventOwner = Event::where(['id' => $input['event_id'], 'user_id' => $value->user->id])->first();

                    $postControl = PostControl::where(['user_id' => $user->id, 'event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                    // if ($postControl != null) {

                    //     if ($postControl->post_control == 'hide_post') {
                    //         continue;
                    //     }
                    // }

                    $checkUserIsReaction = EventPostReaction::where(['event_id' => $input['event_id'], 'event_post_id' => $value->id, 'user_id' => $user->id])->first();

                    // if ($value->post_privacy == '1') {
                    $postsNormalDetail['id'] =  $value->id;

                    $postsNormalDetail['user_id'] =  $value->user->id;

                    $postsNormalDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                    $postsNormalDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                    $postsNormalDetail['is_host'] =  ($ischeckEventOwner != null) ? 1 : 0;
                    $postsNormalDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;

                    $postsNormalDetail['rsvp_status'] = $checkUserRsvp;
                    $postsNormalDetail['kids'] = isset($count_kids_adult['kids']) ? $count_kids_adult['kids'] : 0;
                    $postsNormalDetail['adults'] = isset($count_kids_adult['adults']) ? $count_kids_adult['adults'] : 0;
                    $postsNormalDetail['location'] = ($value->user->city != NULL) ? $value->user->city : "";



                    $postsNormalDetail['post_type'] = $value->post_type;
                    $postsNormalDetail['post_privacy'] = $value->post_privacy;
                    $postsNormalDetail['created_at'] = $value->created_at;
                    $postsNormalDetail['posttime'] = setpostTime($value->created_at);
                    $postsNormalDetail['post_image'] = [];


                    if ($value->post_type == '1' && !empty($value->post_image)) {

                        foreach ($value->post_image as $imgVal) {
                            $postMedia = [
                                'media_url' => asset('public/storage/post_image/' . $imgVal->post_image),
                                'type' => $imgVal->type,
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
                    if ($value->post_type == '2') { // Poll
                        $polls = EventPostPoll::with('event_poll_option')->withCount('user_poll_data')->where(['event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                        $postsNormalDetail['total_poll_vote'] = $polls->user_poll_data_count;
                        $pollDura = getLeftPollTime($polls->updated_at, $polls->poll_duration);
                        $postsNormalDetail['poll_duration'] = $pollDura;
                        // $postsNormalDetail['poll_duration'] =  empty($polls->poll_duration) ? "" :  $polls->poll_duration;
                        $leftDay = (int) preg_replace('/[^0-9]/', '', $polls->poll_duration);

                        $postsNormalDetail['is_expired'] =  ($pollDura == "") ? true : false;
                        $postsNormalDetail['poll_id'] = $polls->id;

                        $postsNormalDetail['poll_question'] = $polls->poll_question;


                        foreach ($polls->event_poll_option as $optionValue) {

                            $optionData['id'] = $optionValue->id;

                            $optionData['option'] = $optionValue->option;
                            $optionData['total_vote'] = "0%";
                            if (getOptionAllTotalVote($polls->id) != 0) {
                                $optionData['total_vote'] =   round(getOptionTotalVote($optionValue->id) / getOptionAllTotalVote($polls->id) * 100) . "%";
                            }
                            $optionData['is_poll_selected'] = checkUserGivePoll($user->id, $polls->id, $optionValue->id);


                            $postsNormalDetail['poll_option'][] = $optionData;
                        }
                    }

                    $postsNormalDetail['post_recording'] = empty($value->post_recording) ? "" : asset('public/storage/event_post_recording/' . $value->post_recording);
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
                    $postList[] = $postsNormalDetail;
                    // }

                    //  reply by user and  RSVP



                    // $checkUserTypeForPost = EventInvitedUser::whereHas('user', function ($query) {

                    //     $query->where('app_user', '1');
                    // })->where(['event_id' => $input['event_id'], 'user_id' => $user->id])->first();



                    // if ($checkUserTypeForPost->rsvp_d == '1' && $checkUserTypeForPost->rsvp_status == '1'  && $value->post_privacy == '2') {
                    //     $postsNormalDetail['id'] =  $value->id;

                    //     $postsNormalDetail['user_id'] =  $value->user->id;

                    //     $postsNormalDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;
                    //     $postsNormalDetail['is_host'] =  ($ischeckEventOwner != null) ? 1 : 0;
                    //     $postsNormalDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                    //     $postsNormalDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;

                    //     $postsNormalDetail['rsvp_status'] = $checkUserRsvp;
                    //     $postsNormalDetail['location'] = ($value->user->city != NULL) ? $value->user->city : "";



                    //     $postsNormalDetail['post_type'] = $value->post_type;

                    //     $postsNormalDetail['created_at'] = $value->created_at;
                    //     $postsNormalDetail['posttime'] = setpostTime($value->created_at);



                    //     $postsNormalDetail['post_image'] = [];
                    //     if ($value->post_type == '1' && !empty($value->post_image)) {
                    //         foreach ($value->post_image as $imgVal) {
                    //             $postMedia = [
                    //                 'media_url' => asset('public/storage/post_image/' . $imgVal->post_image),
                    //                 'type' => $imgVal->type,
                    //             ];

                    //             if ($imgVal->type == 'video' && isset($imgVal->duration) && $imgVal->duration !== "") {
                    //                 $postMedia['video_duration'] = $imgVal->duration;
                    //             } else {
                    //                 unset($postMedia['video_duration']);
                    //             }

                    //             $postsNormalDetail['post_image'][] = $postMedia;
                    //         }
                    //     }
                    //     $postsNormalDetail['total_poll_vote'] = 0;
                    //     $postsNormalDetail['poll_duration'] = "";
                    //     $postsNormalDetail['is_expired'] = false;
                    //     $postsNormalDetail['poll_id'] = 0;
                    //     $postsNormalDetail['poll_question'] = "";
                    //     $postsNormalDetail['poll_option'] = [];
                    //     if ($value->post_type == '2') { // Poll

                    //         $polls = EventPostPoll::with('event_poll_option')->withCount('user_poll_data')->where(['event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                    //         $postsNormalDetail['total_poll_vote'] = $polls->user_poll_data_count;

                    //         $postsNormalDetail['poll_duration'] =  empty($polls->poll_duration) ? "" :  $polls->poll_duration;
                    //         $leftDay = (int) preg_replace('/[^0-9]/', '', $polls->poll_duration);
                    //         $postsNormalDetail['is_expired'] =  (dateDiffer($polls->created_at) > $leftDay) ? true : false;

                    //         $postsNormalDetail['poll_id'] = $polls->id;

                    //         $postsNormalDetail['poll_question'] = $polls->poll_question;


                    //         foreach ($polls->event_poll_option as $optionValue) {

                    //             $optionData['id'] = $optionValue->id;

                    //             $optionData['option'] = $optionValue->option;
                    //             $optionData['total_vote'] =  "0%";
                    //             if (getOptionAllTotalVote($polls->id) != 0) {

                    //                 $optionData['total_vote'] =  round(getOptionTotalVote($optionValue->id) / getOptionAllTotalVote($polls->id) * 100) . "%";
                    //             }
                    //             $optionData['is_poll_selected'] = checkUserGivePoll($user->id, $polls->id, $optionValue->id);


                    //             $postsNormalDetail['poll_option'][] = $optionData;
                    //         }
                    //     }
                    //     $postsNormalDetail['post_recording'] = empty($value->post_recording) ? "" : asset('public/storage/event_post_recording/' . $value->post_recording);
                    //     $reactionList = getOnlyReaction($value->id);

                    //     $postsNormalDetail['reactionList'] = $reactionList;

                    //     $postsNormalDetail['total_comment'] = $value->event_post_comment_count;

                    //     $postsNormalDetail['total_likes'] = $value->event_post_reaction_count;

                    //     $postsNormalDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                    //     $postsNormalDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";

                    //     $postsNormalDetail['is_owner_post'] = ($value->user->id == $user->id) ? 1 : 0;
                    //     $postsNormalDetail['is_mute'] =  0;
                    //     if ($postControl != null) {

                    //         if ($postControl->post_control == 'mute') {
                    //             $postsNormalDetail['is_mute'] =  1;
                    //         }
                    //     }
                    //     $postList[] = $postsNormalDetail;
                    // }



                    // if ($checkUserTypeForPost->rsvp_d == '1' && $checkUserTypeForPost->rsvp_status == '0' && $value->post_privacy == '3') {

                    //     $postsNormalDetail['id'] =  $value->id;

                    //     $postsNormalDetail['user_id'] =  $value->user->id;

                    //     $postsNormalDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;
                    //     $postsNormalDetail['is_host'] =  ($ischeckEventOwner != null) ? 1 : 0;
                    //     $postsNormalDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                    //     $postsNormalDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;

                    //     $postsNormalDetail['rsvp_status'] = $checkUserRsvp;
                    //     $postsNormalDetail['location'] = ($value->user->city != NULL) ? $value->user->city : "";



                    //     $postsNormalDetail['post_type'] = $value->post_type;

                    //     $postsNormalDetail['created_at'] = $value->created_at;
                    //     $postsNormalDetail['posttime'] = setpostTime($value->created_at);


                    //     $postsNormalDetail['post_image'] = [];
                    //     if ($value->post_type == '1' && !empty($value->post_image)) {
                    //         foreach ($value->post_image as $imgVal) {
                    //             $postMedia = [
                    //                 'media_url' => asset('public/storage/post_image/' . $imgVal->post_image),
                    //                 'type' => $imgVal->type,
                    //             ];

                    //             if ($imgVal->type == 'video' && isset($imgVal->duration) && $imgVal->duration !== "") {
                    //                 $postMedia['video_duration'] = $imgVal->duration;
                    //             } else {
                    //                 unset($postMedia['video_duration']);
                    //             }

                    //             $postsNormalDetail['post_image'][] = $postMedia;
                    //         }
                    //     }

                    //     $postsNormalDetail['total_poll_vote'] = 0;
                    //     $postsNormalDetail['poll_duration'] = "";
                    //     $postsNormalDetail['is_expired'] = false;
                    //     $postsNormalDetail['poll_id'] = 0;
                    //     $postsNormalDetail['poll_question'] = "";
                    //     $postsNormalDetail['poll_option'] = [];
                    //     if ($value->post_type == '2') { // Poll

                    //         $polls = EventPostPoll::with('event_poll_option')->withCount('user_poll_data')->where(['event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                    //         $postsNormalDetail['total_poll_vote'] = $polls->user_poll_data_count;

                    //         $postsNormalDetail['poll_duration'] =  empty($polls->poll_duration) ? "" :  $polls->poll_duration;
                    //         $leftDay = (int) preg_replace('/[^0-9]/', '', $polls->poll_duration);
                    //         $postsNormalDetail['is_expired'] =  (dateDiffer($polls->created_at) > $leftDay) ? true : false;

                    //         $postsNormalDetail['poll_id'] = $polls->id;

                    //         $postsNormalDetail['poll_question'] = $polls->poll_question;


                    //         foreach ($polls->event_poll_option as $optionValue) {

                    //             $optionData['id'] = $optionValue->id;

                    //             $optionData['option'] = $optionValue->option;
                    //             $optionData['total_vote'] = "0%";
                    //             if (getOptionAllTotalVote($polls->id) != 0) {
                    //                 $optionData['total_vote'] =   round(getOptionTotalVote($optionValue->id) / getOptionAllTotalVote($polls->id) * 100) . "%";
                    //             }
                    //             $optionData['is_poll_selected'] = checkUserGivePoll($user->id, $polls->id, $optionValue->id);


                    //             $postsNormalDetail['poll_option'][] = $optionData;
                    //         }
                    //     }

                    //     $postsNormalDetail['post_recording'] = empty($value->post_recording) ? "" : asset('public/storage/event_post_recording/' . $value->post_recording);

                    //     $reactionList = getOnlyReaction($value->id);

                    //     $postsNormalDetail['reactionList'] = $reactionList;

                    //     $postsNormalDetail['total_comment'] = $value->event_post_comment_count;

                    //     $postsNormalDetail['total_likes'] = $value->event_post_reaction_count;

                    //     $postsNormalDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                    //     $postsNormalDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";

                    //     $postsNormalDetail['is_owner_post'] = ($value->user->id == $user->id) ? 1 : 0;
                    //     $postsNormalDetail['is_mute'] =  0;
                    //     if ($postControl != null) {

                    //         if ($postControl->post_control == 'mute') {
                    //             $postsNormalDetail['is_mute'] =  1;
                    //         }
                    //     }
                    //     $postList[] = $postsNormalDetail;
                    // }





                    // if ($checkUserTypeForPost->rsvp_d == '0' && $value->post_privacy == '4') {

                    //     $postsNormalDetail['id'] =  $value->id;

                    //     $postsNormalDetail['user_id'] =  $value->user->id;

                    //     $postsNormalDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;
                    //     $postsNormalDetail['is_host'] =  ($ischeckEventOwner != null) ? 1 : 0;
                    //     $postsNormalDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                    //     $postsNormalDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;

                    //     $postsNormalDetail['rsvp_status'] = $checkUserRsvp;
                    //     $postsNormalDetail['location'] = ($value->user->city != NULL) ? $value->user->city : "";



                    //     $postsNormalDetail['post_type'] = $value->post_type;

                    //     $postsNormalDetail['created_at'] = $value->created_at;
                    //     $postsNormalDetail['posttime'] = setpostTime($value->created_at);



                    //     $postsNormalDetail['post_image'] = [];
                    //     if ($value->post_type == '1' && !empty($value->post_image)) {
                    //         foreach ($value->post_image as $imgVal) {
                    //             $postMedia = [
                    //                 'media_url' => asset('public/storage/post_image/' . $imgVal->post_image),
                    //                 'type' => $imgVal->type,
                    //             ];

                    //             if ($imgVal->type == 'video' && isset($imgVal->duration) && $imgVal->duration !== "") {
                    //                 $postMedia['video_duration'] = $imgVal->duration;
                    //             } else {
                    //                 unset($postMedia['video_duration']);
                    //             }

                    //             $postsNormalDetail['post_image'][] = $postMedia;
                    //         }
                    //     }

                    //     $postsNormalDetail['total_poll_vote'] = 0;
                    //     $postsNormalDetail['poll_duration'] = "";
                    //     $postsNormalDetail['is_expired'] = false;
                    //     $postsNormalDetail['poll_id'] = 0;
                    //     $postsNormalDetail['poll_question'] = "";
                    //     $postsNormalDetail['poll_option'] = [];
                    //     if ($value->post_type == '2') { // Poll

                    //         $polls = EventPostPoll::with('event_poll_option')->withCount('user_poll_data')->where(['event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                    //         $postsNormalDetail['total_poll_vote'] = $polls->user_poll_data_count;

                    //         $postsNormalDetail['poll_duration'] =  empty($polls->poll_duration) ? "" :  $polls->poll_duration;
                    //         $leftDay = (int) preg_replace('/[^0-9]/', '', $polls->poll_duration);
                    //         $postsNormalDetail['is_expired'] =  (dateDiffer($polls->created_at) > $leftDay) ? true : false;

                    //         $postsNormalDetail['poll_id'] = $polls->id;

                    //         $postsNormalDetail['poll_question'] = $polls->poll_question;


                    //         foreach ($polls->event_poll_option as $optionValue) {

                    //             $optionData['id'] = $optionValue->id;

                    //             $optionData['option'] = $optionValue->option;
                    //             $optionData['total_vote'] = "0%";
                    //             if (getOptionAllTotalVote($polls->id) != 0) {

                    //                 $optionData['total_vote'] =   round(getOptionTotalVote($optionValue->id) / getOptionAllTotalVote($polls->id) * 100) . "%";
                    //             }
                    //             $optionData['is_poll_selected'] = checkUserGivePoll($user->id, $polls->id, $optionValue->id);


                    //             $postsNormalDetail['poll_option'][] = $optionData;
                    //         }
                    //     }

                    //     $postsNormalDetail['post_recording'] = empty($value->post_recording) ? "" : asset('public/storage/event_post_recording/' . $value->post_recording);

                    //     $reactionList = getOnlyReaction($value->id);

                    //     $postsNormalDetail['reactionList'] = $reactionList;

                    //     $postsNormalDetail['total_comment'] = $value->event_post_comment_count;

                    //     $postsNormalDetail['total_likes'] = $value->event_post_reaction_count;

                    //     $postsNormalDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                    //     $postsNormalDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";

                    //     $postsNormalDetail['is_owner_post'] = ($value->user->id == $user->id) ? 1 : 0;
                    //     $postsNormalDetail['is_mute'] =  0;
                    //     if ($postControl != null) {

                    //         if ($postControl->post_control == 'mute') {
                    //             $postsNormalDetail['is_mute'] =  1;
                    //         }
                    //     }
                    //     $postList[] = $postsNormalDetail;
                    // }
                }
            }
        }




        $wallData['stories'] = $storiesList;

        $wallData['posts'] = $postList;

        return response()->json(['status' => 1, 'total_page_of_stories' => $total_page_of_stories, 'total_page_of_eventPosts' => $total_page_of_eventPosts, 'data' => $wallData, 'message' => "Event wall data"]);
        // } catch (QueryException $e) {
        //     DB::rollBack();
        //     return response()->json(['status' => 0, 'message' => "db error"]);
        // } catch (\Exception $e) {
        //     return response()->json(['status' => 0, 'message' => "something went wrong"]);
        // }
    }

    public function eventPostDetail(Request $request)
    {
        $user  = Auth::guard('api')->user();
        $rawData = $request->getContent();
        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [
            'event_post_id' => ['required', 'exists:event_posts,id']
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }
        // try {

        $eventDetails = EventPost::with('user', 'post_control')->withCount(['event_post_comment' => function ($query) {
            $query->where('parent_comment_id', NULL);
        }, 'event_post_reaction'])->where(['id' => $input['event_post_id']])->first();
        dd($eventDetails);
        if ($eventDetails != null) {
            $checkUserIsReaction = EventPostReaction::where(['event_id' => $eventDetails->event_id, 'event_post_id' => $input['event_post_id'], 'user_id' => $user->id])->first();
            $ischeckEventOwner = Event::where(['id' => $eventDetails->event_id, 'user_id' => $eventDetails->user->id])->first();

            $postsDetail['id'] =  $eventDetails->id;

            $postsDetail['user_id'] =  $eventDetails->user->id;
            $postsDetail['is_host'] =  ($ischeckEventOwner != null) ? 1 : 0;

            $postsDetail['username'] =  $eventDetails->user->firstname . ' ' . $eventDetails->user->lastname;

            $postsDetail['profile'] =  empty($eventDetails->user->profile) ? "" : asset('public/storage/profile/' . $eventDetails->user->profile);

            $postsDetail['post_message'] =  empty($eventDetails->post_message) ? "" :  $eventDetails->post_message;

            $postsDetail['location'] = ($eventDetails->user->city != NULL) ? $eventDetails->user->city : "";
            $postsDetail['posttime'] = setpostTime($eventDetails->created_at);
            if ($eventDetails->post_type == '1') { // Image
                $postsDetail['post_image'] = [];
                $postImages = getPostImages($eventDetails->id);
                foreach ($postImages as $imgVal) {

                    $postMedia['id'] =  $imgVal->id;
                    $postMedia['media_url'] = asset('public/storage/post_image/' . $imgVal->post_image);

                    $postMedia['type'] = $imgVal->type;


                    if (isset($imgVal->type) && $imgVal->type == 'video') {
                        if (isset($imgVal->duration) && $imgVal->duration !== "") {
                            $postMedia['video_duration'] = $imgVal->duration;
                        } else {
                            unset($postMedia['video_duration']);
                        }
                    } else {
                        unset($postMedia['video_duration']);
                    }

                    $postsDetail['post_image'][] = $postMedia;
                }
            }

            if ($eventDetails->post_type == '2') { // Poll



                $polls = EventPostPoll::with('event_poll_option')->withCount('user_poll_data')->where(['event_id' => $eventDetails->event_id, 'event_post_id' => $input['event_post_id']])->first();

                $postsDetail['total_poll_vote'] = $polls->user_poll_data_count;

                $postsDetail['poll_id'] = $polls->id;

                $pollDura = getLeftPollTime($polls->updated_at, $polls->poll_duration);
                $postsDetail['poll_duration'] = $pollDura;
                // $postsDetail['poll_duration'] = $polls->poll_duration;

                $postsDetail['is_expired'] =  ($pollDura == "") ? true : false;
                $postsDetail['poll_question'] = $polls->poll_question;

                $postsDetail['poll_option'] = [];


                foreach ($polls->event_poll_option as $optionValue) {

                    $optionData['id'] = $optionValue->id;

                    $optionData['option'] = $optionValue->option;
                    $optionData['total_vote'] =  "0%";
                    if (getOptionAllTotalVote($polls->id) != 0) {

                        $optionData['total_vote'] =  round(getOptionTotalVote($optionValue->id) / getOptionAllTotalVote($polls->id) * 100) . "%";
                    }
                    $optionData['is_poll_selected'] = checkUserGivePoll($user->id, $polls->id, $optionValue->id);


                    $postsDetail['poll_option'][] = $optionData;
                }
            }

            if ($eventDetails->post_type == '3') { // record
                $postsDetail['post_recording'] = empty($eventDetails->post_recording) ? "" : asset('public/storage/event_post_recording/' . $eventDetails->post_recording);
            }


            $postsDetail['post_type'] = $eventDetails->post_type;

            $postsDetail['created_at'] = $eventDetails->created_at;

            $reactionList = getOnlyReaction($eventDetails->id);


            $postReaction = [];

            $postReactions = getReaction($eventDetails->id);

            foreach ($postReactions as $reactionVal) {

                $reactionInfo['id'] = $reactionVal->id;

                $reactionInfo['event_post_id'] = $reactionVal->event_post_id;

                $reactionInfo['reaction'] = $reactionVal->reaction;

                $reactionInfo['user_id'] = $reactionVal->user_id;
                $reactionInfo['username'] = $reactionVal->user->firstname . ' ' . $reactionVal->user->lastname;
                $reactionInfo['location'] = ($reactionVal->user->city != NULL) ? $reactionVal->user->city : "";

                $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                $postReaction[] = $reactionInfo;
            }

            $postsDetail['post_reaction'] = $postReaction;
            $postsDetail['reactionList'] = $reactionList;

            $postsDetail['total_comment'] = $eventDetails->event_post_comment_count;

            $postsDetail['total_likes'] = $eventDetails->event_post_reaction_count;

            $postsDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

            $postsDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";




            $postCommentList = [];

            $postComment = getComments($eventDetails->id);


            foreach ($postComment as $commentVal) {




                $commentInfo['id'] = $commentVal->id;

                $commentInfo['event_post_id'] = $commentVal->event_post_id;

                $commentInfo['comment'] = $commentVal->comment_text;

                $commentInfo['user_id'] = $commentVal->user_id;

                $commentInfo['username'] = $commentVal->user->firstname . ' ' . $commentVal->user->lastname;

                $commentInfo['profile'] = (!empty($commentVal->user->profile)) ? asset('public/storage/profile/' . $commentVal->user->profile) : "";
                $commentInfo['location'] = ($commentVal->user->city != NULL) ? $commentVal->user->city : "";
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

                    $replyCommentInfo['profile'] = (!empty($reply->user->profile)) ? asset('public/storage/profile/' . $reply->user->profile) : "";

                    $replyCommentInfo['location'] = ($reply->user->city != NULL) ? $reply->user->city : "";
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

                            $commentChildReply['profile'] = (!empty($childReplyVal->user->profile)) ? asset('public/storage/profile/' . $childReplyVal->user->profile) : "";
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

                                    $commentChildInReply['profile'] = (!empty($childInReplyVal->user->profile)) ? asset('public/storage/profile/' . $childInReplyVal->user->profile) : "";
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
            $postsDetail['is_mute'] = 0;
            if (isset($eventDetails->post_control) && !$eventDetails->isEmpty()) {
                foreach ($eventDetails->post_control as $postcontrol) {
                    if ($postcontrol->post_control == 'mute') {
                        $postsDetail['is_mute'] = 1;
                        break;
                    }
                }
            }
            $postsDetail['post_comment'] = $postCommentList;


            return response()->json(['status' => 1, 'message' => "Post Details", 'data' => $postsDetail]);
        } else {
            return response()->json(['status' => 0, 'message' => "No data found"]);
        }
        // } catch (QueryException $e) {
        //     DB::rollBack();
        //     return response()->json(['status' => 0, 'message' => "db error"]);
        // } catch (\Exception $e) {
        //     return response()->json(['status' => 0, 'message' => "something went wrong"]);
        // }
    }



    public function createStory(Request $request)
    {
        $user  = Auth::guard('api')->user();
        $input = $request->all();

        $validator = Validator::make($input, [

            'event_id' => ['required', 'exists:events,id'],
            'story' => ['required', 'array'],

        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first()

            ]);
        }

        try {

            DB::beginTransaction();
            $checkAlreadyStories = EventUserStory::where(['event_id' => $input['event_id'], 'user_id' => $user->id])->first();
            $createStory = $checkAlreadyStories;

            if ($checkAlreadyStories == null) {

                $createStory =  EventUserStory::create([
                    'event_id' => $request->event_id,
                    'user_id' => $user->id,
                ]);
            }
            if ($createStory) {
                if (!empty($request->story)) {

                    $createStory->created_at = Carbon::now();
                    $createStory->save();

                    $storyData = $request->file('story');

                    foreach ($storyData as $postStoryValue) {
                        $postStory = $postStoryValue;
                        $imageName = time() . '_' . $postStory->getClientOriginalName();
                        $checkIsimageOrVideo = checkIsimageOrVideo($postStory);
                        $duration = '0';
                        if ($checkIsimageOrVideo == 'video') {
                            $duration = getVideoDuration($postStory);

                            // if (file_exists(public_path('storage/event_user_stories/') . $imageName)) {

                            //     $imagePath = public_path('storage/event_user_stories/') . $imageName;
                            //     unlink($imagePath);
                            // }


                            $postStory->move(public_path('storage/event_user_stories'), $imageName);
                        } else {
                            $postStory->move(public_path('storage/event_user_stories'), $imageName);
                        }

                        $storyId = $createStory->id;
                        $storylestest =   UserEventStory::create([
                            'event_story_id' => $storyId,
                            'story' => $imageName,
                            'duration' => $duration,
                            'type' => $checkIsimageOrVideo
                        ]);
                    }
                    DB::commit();
                    $currentDateTime = Carbon::now();

                    $getStoryData =   EventUserStory::with(['user', 'user_event_story' => function ($query) use ($currentDateTime) {
                        $query->where('created_at', '>', $currentDateTime->subHours(24));
                    }])->where(['event_id' => $input['event_id'], 'user_id' => $user->id])->where('created_at', '>', $currentDateTime->subHours(24))->first();

                    $storiesDeta['owner_stories'] = [];
                    if ($getStoryData != null) {

                        $storiesDetail['id'] =  $getStoryData->id;
                        $storiesDetail['user_id'] =  $getStoryData->user->id;

                        $storiesDetail['username'] =  $getStoryData->user->firstname . ' ' . $getStoryData->user->lastname;

                        $storiesDetail['profile'] =  empty($getStoryData->user->profile) ? "" : asset('public/storage/profile/' . $getStoryData->user->profile);

                        $storiesDetail['story'] = [];
                        foreach ($getStoryData->user_event_story as $storyVal) {
                            $storiesData['id'] = $storyVal->id;
                            $storiesData['storyurl'] = empty($storyVal->story) ? "" : asset('public/storage/event_user_stories/' . $storyVal->story);
                            $storiesData['type'] = $storyVal->type;

                            if ($storyVal->type == 'video') {
                                $storiesData['video_duration'] = (!empty($storyVal->duration)) ? $storyVal->duration : "";
                            }
                            $storiesData['post_time'] =  $this->setpostTime($storyVal->created_at);
                            $storiesData['created_at'] =  $storyVal->created_at;
                            $storiesDetail['story'][] = $storiesData;
                        }
                        $storiesDeta['owner_stories'][] = $storiesDetail;
                    }

                    return response()->json(['status' => 1, 'message' => "Event story uploaded successfully", 'data' => $storiesDeta]);
                }
            } else {
                return response()->json(['status' => 0, 'message' => "Event story not uploaded"]);
            }
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {

            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }


    public function userSeenStory(Request $request)
    {
        $user  = Auth::guard('api')->user();
        $input = $request->all();

        $validator = Validator::make($input, [

            'user_event_story_id' => ['required'],


        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first()

            ]);
        }

        try {

            DB::beginTransaction();
            $checkAlreadyStories = UserSeenStory::where(['user_id' => $user->id, 'user_event_story_id' => $input['user_event_story_id']])->first();

            if ($checkAlreadyStories == null) {

                $seenUser =  new UserSeenStory();
                $seenUser->user_event_story_id = $request->user_event_story_id;
                $seenUser->user_id = $user->id;
                $seenUser->save();
            }
            DB::commit();
            return response()->json(['status' => 1, 'message' => "Event story seen successfully"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {

            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }

    public function deleteStory(Request $request)
    {
        $user  = Auth::guard('api')->user();


        $rawData = $request->getContent();

        $input = json_decode($rawData, true);

        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [

            'story_id' => ['required', 'exists:event_user_stories,id'],
        ]);



        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }

        try {


            $record = EventUserStory::where(['id' => $input['story_id']])->first();

            if ($record) {

                $record->delete();
                return response()->json(['status' => 1, 'message' => "Story deleted"]);
            } else {
                return response()->json(['status' => 0, 'message' => "Post is not deleted"]);
            }
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        }
    }




    public function postCommentReplyList(Request $request)

    {



        $user  = Auth::guard('api')->user();



        $rawData = $request->getContent();



        $input = json_decode($rawData, true);

        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [

            'event_post_comment_id' => ['required'],

        ]);



        if ($validator->fails()) {

            return response()->json(

                [
                    'status' => 0,
                    'message' => $validator->errors()->first()

                ],



            );
        }



        try {

            DB::beginTransaction();



            $replyList = EventPostComment::with('user')->withcount('post_comment_reaction')->where("parent_comment_id", $input['event_post_comment_id'])->get();

            $commentInfo = [];

            if (!empty($replyList)) {

                foreach ($replyList as $replyVal) {

                    $commentReply['id'] = $replyVal->id;

                    $commentReply['event_post_id'] = $replyVal->event_post_id;

                    $commentReply['comment'] = $replyVal->comment_text;

                    $commentReply['parent_comment_id'] = $replyVal->parent_comment_id;

                    $commentReply['user_id'] = $replyVal->user_id;

                    $commentReply['username'] = $replyVal->user->firstname . ' ' . $replyVal->user->lastname;

                    $commentReply['profile'] = (!empty($replyVal->user->profile)) ? asset('public/storage/profile/' . $replyVal->user->profile) : "";
                    $commentReply['posttime'] = setpostTime($replyVal->created_at);
                    $commentReply['reply_comment_total_likes'] = $replyVal->post_comment_reaction_count;

                    $commentReply['is_like'] = checkUserIsLike($replyVal->id, $user->id);

                    $commentReply['created_at'] = $replyVal->created_at;

                    $commentInfo[] = $commentReply;
                }
            }

            return response()->json(['status' => 1, 'total_comments_replies' => count($replyList), 'data' => $commentInfo, 'message' => "Comment Reply List"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {

            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }


    // Event Post Module //

    public function createPost(Request $request)
    {
        $user  = Auth::guard('api')->user();
        $input = $request->all();
        $validator = Validator::make($input, [
            'event_id' => ['required', 'exists:events,id'],
            'post_privacy' => ['required', 'in:1,2,3,4'],
            'post_type' => ['required', 'in:0,1,2,3'],
            'commenting_on_off' => ['required', 'in:0,1'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }
        // try {
        DB::beginTransaction();
        $creatEventPost = new EventPost;
        $creatEventPost->event_id = $request->event_id;
        $creatEventPost->user_id = $user->id;
        $creatEventPost->post_message = $request->post_message;

        if ($request->hasFile('post_recording')) {
            $record = $request->post_recording;
            $recordingName = time() . '_' . $record->getClientOriginalName();
            $record->move(public_path('storage/event_post_recording'), $recordingName);
            $creatEventPost->post_recording = $recordingName;
        }

        // if ($request->hasFile('post_recording')) {
        //     try {
        //         $record = $request->file('post_recording');

        //         // Generate a unique file name
        //         $recordingName = time() . '_' . $record->getClientOriginalName();

        //         // Move the uploaded file to the desired location
        //         $record->move(public_path('storage/event_post_recording'), $recordingName);


        //         $inputPath = public_path('storage/event_post_recording') . '/' . $recordingName;
        //         $outputPath = public_path('storage/event_post_recording/new/') . '/' . pathinfo($recordingName . 'new_', PATHINFO_FILENAME) . '.mp3';


        //         // Convert the audio to MP3 using FFmpeg
        //         $ffmpeg = FFMpeg::create();
        //         $audio = $ffmpeg->open($inputPath);

        //         $format = new Mp3();
        //         $audio->save($format, $outputPath);

        //         // Save the recording name to the database
        //         $creatEventPost->post_recording = pathinfo($outputPath, PATHINFO_BASENAME);
        //     } catch (RuntimeException $e) {
        //         // Log the error message

        //         Log::error('FFmpeg error: ' . $e->getMessage());
        //         echo 'Error: ' . $e->getMessage();
        //     }
        // }

        $creatEventPost->post_privacy = $request->post_privacy;
        $creatEventPost->post_type = $request->post_type;
        $creatEventPost->commenting_on_off = $request->commenting_on_off;
        $creatEventPost->is_in_photo_moudle = $request->is_in_photo_moudle;
        $creatEventPost->save();
        if ($creatEventPost->id) {
            if ($request->post_type == '1') {
                if (!empty($request->post_image)) {
                    $postimages = $request->post_image;
                    foreach ($postimages as $postImgValue) {
                        $postImage = $postImgValue;
                        $imageName = time() . '_' . $postImage->getClientOriginalName();
                        $checkIsimageOrVideo = checkIsimageOrVideo($postImage);
                        $duration = "";
                        if ($checkIsimageOrVideo == 'video') {
                            $duration = getVideoDuration($postImage);
                        }
                        if (file_exists(public_path('storage/post_image/') . $imageName)) {
                            $imagePath = public_path('storage/post_image/') . $imageName;
                            unlink($imagePath);
                        }
                        $postImage->move(public_path('storage/post_image'), $imageName);
                        EventPostImage::create([
                            'event_id' => $request->event_id,
                            'event_post_id' => $creatEventPost->id,
                            'post_image' => $imageName,
                            'duration' => $duration,
                            'type' => $checkIsimageOrVideo
                        ]);
                    }
                }
            }

            if ($request->post_type == '2') {
                $eventPostPoll = new EventPostPoll;
                $eventPostPoll->event_id = $request->event_id;
                $eventPostPoll->event_post_id = $creatEventPost->id;
                $eventPostPoll->poll_question = $request->poll_question;
                $eventPostPoll->poll_duration = $request->poll_duration;
                if ($eventPostPoll->save()) {
                    $option = json_decode($request->option);
                    foreach ($option as $value) {
                        $pollOption = new EventPostPollOption;
                        $pollOption->event_post_poll_id = $eventPostPoll->id;
                        $pollOption->option = $value;
                        $pollOption->save();
                    }
                }
            }
            $notificationParam = [
                'sender_id' => $user->id,
                'event_id' => $request->event_id,
                'post_id' => $creatEventPost->id,
                'is_in_photo_moudle' => $request->is_in_photo_moudle,
                'post_type' => $request->post_type,
                'post_privacy' => $request->post_privacy
            ];
        }

        DB::commit();

        if ($request->is_in_photo_moudle == '1') {

            sendNotification('photos', $notificationParam);
        } else {
            sendNotification('upload_post', $notificationParam);
        }

        return response()->json(['status' => 1, 'message' => "Post is created sucessfully"]);
        // } catch (QueryException $e) {

        //     DB::rollBack();

        //     return response()->json(['status' => 0, 'message' => "db error"]);
        // } catch (\Exception $e) {

        //     return response()->json(['status' => 0, 'message' => "something went wrong"]);
        // }
    }


    public function postControl(Request $request)
    {

        $user  = Auth::guard('api')->user();
        $rawData = $request->getContent();
        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [
            'event_id' => ['required', 'exists:events,id'],
            'event_post_id' => ['required'],
            'post_control' => ['required', 'in:hide_post,unhide_post,mute,unmute,report'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
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
                $reportCreate->event_post_id = $input['event_post_id'];
                $reportCreate->save();
                $message = "Reported to admin for this post";
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

    public function postMediaReport(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);

        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }


        $validator = Validator::make($input, [
            'event_id' => ['required', 'exists:events,id'],
            'event_post_id' => ['required'],
            'post_media_id' => ['required', 'exists:event_post_images,id'],

        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),

            ]);
        }

        try {

            DB::beginTransaction();
            $reportCreate = new UserReportToPost;
            $reportCreate->event_id = $input['event_id'];
            $reportCreate->user_id =  $user->id;
            $reportCreate->event_post_id = $input['event_post_id'];
            $reportCreate->post_media_id = $input['post_media_id'];
            $reportCreate->specific_report = '1';
            $reportCreate->save();
            DB::commit();
            $message = "Reported to admin for this media";

            return response()->json(['status' => 1, 'message' => $message]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {

            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }
    public function deletePost(Request $request)
    {
        $user  = Auth::guard('api')->user();


        $rawData = $request->getContent();

        $input = json_decode($rawData, true);

        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [

            'event_id' => ['required', 'exists:events,id'],

            'event_post_id' => ['required', 'exists:event_posts,id', new checkUserEventPost],

        ]);



        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }

        try {

            $id = $input['event_post_id'];
            $record = EventPost::find($id);
            $record->delete();
            if ($record) {

                $record->delete();
                return response()->json(['status' => 1, 'message' => "Post deleted"]);
            } else {
                return response()->json(['status' => 0, 'message' => "Post is not deleted"]);
            }
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        }
    }

    public function userPostLikeDislike(Request $request)

    {

        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);

        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [

            'event_id' => ['required', 'exists:events,id'],

            'event_post_id' => ['required'],

            'reaction' => ['required'],

        ]);



        if ($validator->fails()) {

            return response()->json(

                [
                    'status' => 0,
                    'message' => $validator->errors()->first()

                ],



            );
        }



        // try {

        DB::beginTransaction();



        $checkReaction = EventPostReaction::where(['event_id' => $input['event_id'], 'event_post_id' => $input['event_post_id'], 'user_id' => $user->id])->count();



        if ($checkReaction == 0) {

            $event_post_reaction = new EventPostReaction;



            $event_post_reaction->event_id = $input['event_id'];

            $event_post_reaction->event_post_id = $input['event_post_id'];

            $event_post_reaction->user_id = $user->id;


            $event_post_reaction->reaction = $input['reaction'];

            $unicode = mb_convert_encoding($input['reaction'], 'UTF-32', 'UTF-8');
            $unicode = strtoupper(bin2hex($unicode));

            $event_post_reaction->unicode = $unicode;
            $event_post_reaction->save();


            $eventModule = EventPost::where('id', $input['event_post_id'])->first();
            $notificationParam = [

                'sender_id' => $user->id,

                'event_id' => $input['event_id'],

                'post_id' => $input['event_post_id'],
                'is_in_photo_moudle' => $eventModule->is_in_photo_moudle
            ];
            sendNotification('like_post', $notificationParam);
            DB::commit();
            $total_counts = EventPostReaction::where([
                'event_id' => $input['event_id'],
                'event_post_id' => $input['event_post_id']
            ])
                ->select('reaction', 'unicode', DB::raw('COUNT(*) as count'))
                ->groupBy('reaction', 'unicode') // Add 'reaction' to the GROUP BY clause
                ->orderByDesc('count')
                ->take(3)
                ->pluck('reaction')
                ->toArray();


            $postReaction = [];

            $postReactions = getReaction($input['event_post_id']);

            foreach ($postReactions as $reactionVal) {

                $reactionInfo['id'] = $reactionVal->id;

                $reactionInfo['event_post_id'] = $reactionVal->event_post_id;

                $reactionInfo['reaction'] = $reactionVal->reaction;

                $reactionInfo['user_id'] = $reactionVal->user_id;
                $reactionInfo['username'] = $reactionVal->user->firstname . ' ' . $reactionVal->user->lastname;
                $reactionInfo['location'] = ($reactionVal->user->city != NULL) ? $reactionVal->user->city : "";

                $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                $postReaction[] = $reactionInfo;
            }


            $counts = EventPostReaction::where([
                'event_id' => $input['event_id'],
                'event_post_id' => $input['event_post_id']
            ])->count();
            return response()->json(['status' => 1, 'is_reaction' => 1, 'message' => "Post liked by you", "count" => $counts, "post_reaction" =>  $postReaction, "reactionList" => $total_counts]);
        } else {

            $message = "";
            $isReaction  = 0;
            $checkReaction = EventPostReaction::where(['event_id' => $input['event_id'], 'event_post_id' => $input['event_post_id'], 'user_id' => $user->id])->first();
            if ($checkReaction != null) {
                $unicode = mb_convert_encoding($input['reaction'], 'UTF-32', 'UTF-8');
                $unicode = strtoupper(bin2hex($unicode));
                if ($checkReaction->unicode != $unicode) {

                    $checkReaction->reaction = $input['reaction'];
                    $checkReaction->unicode = $unicode;
                    $checkReaction->save();
                    $message = "Post liked by you";
                    $isReaction = 1;
                } else if ($checkReaction->unicode == $unicode) {
                    $checkReaction->delete();
                    $removeNotification = Notification::where(['event_id' => $input['event_id'], 'sender_id' => $user->id, 'post_id' => $input['event_post_id'], 'notification_type' => 'like_post'])->first();

                    if (!empty($removeNotification)) {

                        $removeNotification->delete();
                    }
                    $message = "Post Disliked by you";
                }
            }

            DB::commit();
            $counts = EventPostReaction::where([
                'event_id' => $input['event_id'],
                'event_post_id' => $input['event_post_id']
            ])->count();
            $total_counts = EventPostReaction::where([
                'event_id' => $input['event_id'],
                'event_post_id' => $input['event_post_id']
            ])
                ->select('reaction', 'unicode', DB::raw('COUNT(*) as count'))
                ->groupBy('reaction', 'unicode') // Add 'reaction' to the GROUP BY clause
                ->orderByDesc('count')
                ->take(3)
                ->pluck('reaction')
                ->toArray();

            $postReaction = [];

            $postReactions = getReaction($input['event_post_id']);

            foreach ($postReactions as $reactionVal) {

                $reactionInfo['id'] = $reactionVal->id;

                $reactionInfo['event_post_id'] = $reactionVal->event_post_id;

                $reactionInfo['reaction'] = $reactionVal->reaction;

                $reactionInfo['user_id'] = $reactionVal->user_id;
                $reactionInfo['username'] = $reactionVal->user->firstname . ' ' . $reactionVal->user->lastname;
                $reactionInfo['location'] = ($reactionVal->user->city != NULL) ? $reactionVal->user->city : "";

                $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                $postReaction[] = $reactionInfo;
            }
            return response()->json(['status' => 1, 'is_reaction' => $isReaction, 'message' => $message,  "count" => $counts, "post_reaction" =>  $postReaction, "reactionList" => $total_counts]);
        }
        // } 

        // catch (QueryException $e) {

        //     DB::rollBack();

        //     return response()->json(['status' => 0, 'message' => "db error"]);
        // }
    }



    public function userPostComment(Request $request)

    {

        $user  = Auth::guard('api')->user();



        $rawData = $request->getContent();





        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }


        $validator = Validator::make($input, [

            'event_id' => ['required', 'exists:events,id'],

            'event_post_id' => ['required'],

            'comment_text' => ['required'],

        ]);



        if ($validator->fails()) {

            return response()->json(

                [
                    'status' => 0,
                    'message' => $validator->errors()->first()

                ],


            );
        }




        try {

            DB::beginTransaction();



            EventPostComment::where(['event_id' => $input['event_id'], 'event_post_id' => $input['event_post_id'], 'user_id' => $user->id])->count();





            $event_post_comment = new EventPostComment;



            $event_post_comment->event_id = $input['event_id'];

            $event_post_comment->event_post_id = $input['event_post_id'];

            $event_post_comment->user_id = $user->id;

            $event_post_comment->comment_text = $input['comment_text'];

            if (isset($input['media']) && !empty($input['media'])) {
                $image = $input['media'];

                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('storage/comment_media'), $imageName);
                $event_post_comment->media = $imageName;
                $event_post_comment->type = $input['type'];
            }

            $event_post_comment->save();

            $notificationParam = [

                'sender_id' => $user->id,

                'event_id' => $input['event_id'],

                'post_id' => $input['event_post_id'],
                'comment_id' => $event_post_comment->id

            ];

            sendNotification('comment_post', $notificationParam);

            DB::commit();

            $postComment = getComments($input['event_post_id']);


            $letestComment =  EventPostComment::with('user')->withcount('post_comment_reaction', 'replies')->where(['event_post_id' => $input['event_post_id'], 'parent_comment_id' => NULL])->orderBy('id', 'DESC')->limit(1)->first();


            $postCommentList = [
                'id' => $letestComment->id,

                'event_post_id' => $letestComment->event_post_id,

                'comment' => $letestComment->comment_text,
                'media' => (!empty($letestComment->media) && $letestComment->media != NULL) ? asset('public/storage/comment_media/' . $letestComment->media) : "",

                'user_id' => $letestComment->user_id,

                'username' => $letestComment->user->firstname . ' ' . $letestComment->user->lastname,

                'profile' => (!empty($letestComment->user->profile)) ? asset('public/storage/profile/' . $letestComment->user->profile) : "",

                'comment_total_likes' => $letestComment->post_comment_reaction_count,

                'is_like' => checkUserPhotoIsLike($letestComment->id, $user->id),

                'created_at' => $letestComment->created_at,

                'total_replies' => $letestComment->replies_count,

                'posttime' => setpostTime($letestComment->created_at),
                'comment_replies' => []
            ];

            // foreach ($postComment as $commentVal) {

            //     $commentInfo['id'] = $commentVal->id;

            //     $commentInfo['event_post_id'] = $commentVal->event_post_id;

            //     $commentInfo['comment'] = $commentVal->comment_text;

            //     $commentInfo['user_id'] = $commentVal->user_id;

            //     $commentInfo['username'] = $commentVal->user->firstname . ' ' . $commentVal->user->lastname;

            //     $commentInfo['profile'] = (!empty($commentVal->user->profile)) ? asset('public/storage/profile/' . $commentVal->user->profile) : "";

            //     $commentInfo['comment_total_likes'] = $commentVal->post_comment_reaction_count;

            //     $commentInfo['is_like'] = checkUserPhotoIsLike($commentVal->id, $user->id);

            //     $commentInfo['created_at'] = $commentVal->created_at;

            //     $commentInfo['total_replies'] = $commentVal->replies_count;

            //     $commentInfo['posttime'] = setpostTime($commentVal->created_at);

            //     $commentInfo['comment_replies'] = [];

            //     foreach ($commentVal->replies as $reply) {
            //         $replyCommentInfo['id'] = $reply->id;

            //         $replyCommentInfo['event_post_id'] = $reply->event_post_id;

            //         $replyCommentInfo['comment'] = $reply->comment_text;

            //         $replyCommentInfo['user_id'] = $reply->user_id;

            //         $replyCommentInfo['username'] = $reply->user->firstname . ' ' . $reply->user->lastname;

            //         $replyCommentInfo['profile'] = (!empty($reply->user->profile)) ? asset('public/storage/profile/' . $reply->user->profile) : "";

            //         $replyCommentInfo['location'] = ($reply->user->city != NULL) ? $reply->user->city : "";
            //         $replyCommentInfo['comment_total_likes'] = $reply->post_comment_reaction_count;

            //         $replyCommentInfo['is_like'] = checkUserIsLike($reply->id, $user->id);

            //         $replyCommentInfo['total_replies'] = $reply->replies_count;

            //         $replyCommentInfo['created_at'] = $reply->created_at;
            //         $replyCommentInfo['posttime'] = setpostTime($reply->created_at);
            //         $commentInfo['comment_replies'][] = $replyCommentInfo;
            //     }


            //     $postCommentList[] = $commentInfo;
            // }


            return response()->json(['status' => 1, 'total_comments' => count($postComment), 'data' => $postCommentList, 'message' => "Post commented by you"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {

            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }


    public function userPostCommentReply(Request $request)

    {

        $user  = Auth::guard('api')->user();
        $rawData = $request->getContent();
        $input = json_decode($rawData, true);

        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [

            'event_id' => ['required', 'exists:events,id'],

            'event_post_id' => ['required'],

            'parent_comment_id' => ['required'],

            'comment_text' => ['required'],

        ]);



        if ($validator->fails()) {

            return response()->json(

                [
                    'status' => 0,
                    'message' => $validator->errors()->first()

                ],



            );
        }



        try {

            $parentCommentId =  $input['parent_comment_id'];
            $mainParentId = (new EventPostComment())->getMainParentId($parentCommentId);

            DB::beginTransaction();



            $event_post_comment = new EventPostComment;

            $event_post_comment->event_id = $input['event_id'];

            $event_post_comment->event_post_id = $input['event_post_id'];

            $event_post_comment->user_id = $user->id;

            $event_post_comment->parent_comment_id = $input['parent_comment_id'];
            $event_post_comment->main_parent_comment_id = $mainParentId;

            $event_post_comment->comment_text = $input['comment_text'];
            if (isset($input['media']) && !empty($input['media'])) {
                $image = $input['media'];

                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('storage/comment_media'), $imageName);
                $event_post_comment->media = $imageName;
                $event_post_comment->type = $input['type'];
            }
            $event_post_comment->save();
            DB::commit();

            $notificationParam = [

                'sender_id' => $user->id,

                'event_id' => $input['event_id'],

                'post_id' => $input['event_post_id'],
                'comment_id' => $event_post_comment->id
            ];
            sendNotification('reply_on_comment_post', $notificationParam);




            $replyList =   EventPostComment::with(['user', 'replies' => function ($query) {
                $query->withcount('post_comment_reaction', 'replies')->orderBy('id', 'DESC');
            }])->withcount('post_comment_reaction', 'replies')->where(['id' => $mainParentId, 'event_post_id' => $input['event_post_id']])->orderBy('id', 'DESC')->first();



            $commentInfo['id'] = $replyList->id;

            $commentInfo['event_post_id'] = $replyList->event_post_id;

            $commentInfo['comment'] = $replyList->comment_text;

            $commentInfo['user_id'] = $replyList->user_id;

            $commentInfo['username'] = $replyList->user->firstname . ' ' . $replyList->user->lastname;

            $commentInfo['profile'] = (!empty($replyList->user->profile)) ? asset('public/storage/profile/' . $replyList->user->profile) : "";

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

                    $commentReply['profile'] = (!empty($replyVal->user->profile)) ? asset('public/storage/profile/' . $replyVal->user->profile) : "";
                    $commentReply['location'] = (!empty($replyVal->user->city)) ? $replyVal->user->city : "";

                    $commentReply['comment_total_likes'] = $replyVal->post_comment_reaction_count;

                    $commentReply['main_comment_id'] = $replyVal->main_parent_comment_id;
                    $commentReply['is_like'] = checkUserIsLike($replyVal->id, $user->id);

                    $commentReply['total_replies'] = $totalReply;
                    $commentReply['posttime'] = setpostTime($replyVal->created_at);
                    $commentReply['created_at'] = $replyVal->created_at;

                    $commentInfo['comment_replies'][] = $commentReply;
                    $replyComment =  EventPostComment::with(['user'])->withcount('post_comment_reaction', 'replies')->where(['main_parent_comment_id' => $mainParentId, 'event_post_id' => $input['event_post_id'], 'parent_comment_id' => $replyVal->id])->orderBy('id', 'DESC')->get();

                    foreach ($replyComment as $childReplyVal) {

                        if ($childReplyVal->parent_comment_id != $childReplyVal->main_parent_comment_id) {

                            $totalReply = EventPostComment::withcount('post_comment_reaction')->where("parent_comment_id", $childReplyVal->id)->count();


                            $commentChildReply['id'] = $childReplyVal->id;

                            $commentChildReply['event_post_id'] = $childReplyVal->event_post_id;

                            $commentChildReply['comment'] = $childReplyVal->comment_text;
                            $commentChildReply['user_id'] = $childReplyVal->user_id;

                            $commentChildReply['username'] = $childReplyVal->user->firstname . ' ' . $childReplyVal->user->lastname;

                            $commentChildReply['profile'] = (!empty($childReplyVal->user->profile)) ? asset('public/storage/profile/' . $childReplyVal->user->profile) : "";
                            $commentChildReply['location'] = (!empty($childReplyVal->user->city)) ? $childReplyVal->user->city : "";

                            $commentChildReply['comment_total_likes'] = $childReplyVal->post_comment_reaction_count;
                            $commentReply['main_comment_id'] = $childReplyVal->main_parent_comment_id;
                            $commentChildReply['is_like'] = checkUserIsLike($childReplyVal->id, $user->id);

                            $commentChildReply['total_replies'] = $totalReply;
                            $commentChildReply['posttime'] = setpostTime($childReplyVal->created_at);
                            $commentChildReply['created_at'] = $childReplyVal->created_at;

                            $commentInfo['comment_replies'][] = $commentChildReply;

                            $replyChildComment =  EventPostComment::with(['user'])->withcount('post_comment_reaction', 'replies')->where(['main_parent_comment_id' => $mainParentId, 'event_post_id' => $input['event_post_id'], 'parent_comment_id' => $childReplyVal->id])->orderBy('id', 'DESC')->get();

                            foreach ($replyChildComment as $childInReplyVal) {

                                if ($childInReplyVal->parent_comment_id != $childInReplyVal->main_parent_comment_id) {

                                    $totalReply = EventPostComment::withcount('post_comment_reaction')->where("parent_comment_id", $childInReplyVal->id)->count();


                                    $commentChildInReply['id'] = $childInReplyVal->id;

                                    $commentChildInReply['event_post_id'] = $childInReplyVal->event_post_id;

                                    $commentChildInReply['comment'] = $childInReplyVal->comment_text;
                                    $commentChildInReply['user_id'] = $childInReplyVal->user_id;

                                    $commentChildInReply['username'] = $childInReplyVal->user->firstname . ' ' . $childInReplyVal->user->lastname;

                                    $commentChildInReply['profile'] = (!empty($childInReplyVal->user->profile)) ? asset('public/storage/profile/' . $childInReplyVal->user->profile) : "";
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



            return response()->json(['status' => 1, 'total_comments' => 0, 'data' => $commentInfo, 'message' => "Post comment replied by you"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "error"]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }

    public function userPostCommentReplyReaction(Request $request)

    {

        $user  = Auth::guard('api')->user();



        $rawData = $request->getContent();



        $input = json_decode($rawData, true);

        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [

            'event_post_comment_id' => ['required', 'exists:event_post_comments,id'],

            'reaction' => ['required'],

        ]);



        if ($validator->fails()) {

            return response()->json(

                [
                    'status' => 0,
                    'message' => $validator->errors()->first()

                ],


            );
        }



        try {



            DB::beginTransaction();

            $checkcommentReaction = EventPostCommentReaction::with(['event_post_comment'])->where(['event_post_comment_id' => $input['event_post_comment_id'], 'user_id' => $user->id])->count();

            if ($checkcommentReaction == 0) {
                $post_comment_reaction = new EventPostCommentReaction;

                $post_comment_reaction->event_post_comment_id = $input['event_post_comment_id'];

                $post_comment_reaction->user_id = $user->id;

                $post_comment_reaction->reaction = $input['reaction'];

                $post_comment_reaction->save();

                DB::commit();

                $checkcommentReactionData = EventPostCommentReaction::with('event_post_comment')->where(['event_post_comment_id' => $input['event_post_comment_id'], 'user_id' => $user->id])->first();

                $notificationParam = [

                    'sender_id' => $user->id,

                    'event_id' => $checkcommentReactionData->event_post_comment->event_id,

                    'post_id' => $checkcommentReactionData->event_post_comment->event_post_id,
                    'comment_id' => $input['event_post_comment_id']

                ];

                sendNotification('reply_comment_reaction', $notificationParam);
                $totalCount = EventPostCommentReaction::where(['event_post_comment_id' => $input['event_post_comment_id']])->count();

                return response()->json(['status' => 1, 'message' => "Post comment like by you", "self_reaction" => $input['reaction'], "count" => $totalCount]);
            } else {

                $checkcommentReaction = EventPostCommentReaction::where(['event_post_comment_id' => $input['event_post_comment_id'], 'user_id' => $user->id]);

                $checkcommentReaction->delete();

                DB::commit();
                $totalCount = EventPostCommentReaction::where(['event_post_comment_id' => $input['event_post_comment_id']])->count();
                return response()->json(['status' => 1, 'message' => "Post comment disliked by you", "self_reaction" => $input['reaction'], "count" => $totalCount]);
            }
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        }
        // catch (\Exception $e) {

        //     DB::rollBack();

        //     return response()->json(['status' => 0, 'message' => "something went wrong"]);
        // }
    }



    // Event Post Module //

    public function userVoteOfPoll(Request $request)

    {



        $user  = Auth::guard('api')->user();



        $rawData = $request->getContent();


        $input = json_decode($rawData, true);

        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [

            'event_post_poll_id' => ['required', 'exists:event_post_polls,id'],

            'event_poll_option_id' => ['required', 'exists:event_post_poll_options,id'],

        ]);



        if ($validator->fails()) {

            return response()->json(

                [
                    'status' => 0,
                    'message' => $validator->errors()->first()

                ],



            );
        }

        try {

            DB::beginTransaction();

            $checkPollExist = UserEventPollData::where([

                'user_id' => $user->id, 'event_post_poll_id' => $input['event_post_poll_id']

            ])->count();

            if ($checkPollExist != 0) {

                UserEventPollData::where([

                    'user_id' => $user->id, 'event_post_poll_id' => $input['event_post_poll_id']

                ])->delete();
            }

            $event_post_comment = new UserEventPollData;

            $event_post_comment->event_post_poll_id = $input['event_post_poll_id'];

            $event_post_comment->event_poll_option_id = $input['event_poll_option_id'];

            $event_post_comment->user_id = $user->id;

            $event_post_comment->save();



            DB::commit();

            $polls = EventPostPoll::with(['event_poll_option', 'event_post.user' => function ($query) {
                $query->withCount(['event_post_comment' => function ($query) {
                    $query->where('parent_comment_id', NULL);
                }, 'event_post_reaction']);
            }])->withCount('user_poll_data')->where(['id' => $input['event_post_poll_id']])->first();
            $checkUserIsReaction = EventPostReaction::where(['event_id' => $polls->event_post->event_id, 'event_post_id' => $polls->event_post->id, 'user_id' => $user->id])->first();
            $checkUserRsvp = checkUserAttendOrNot($polls->event_post->event_id, $user->id);


            $postsPollDetail['id'] =  $polls->event_post->id;

            $postsPollDetail['user_id'] =  $polls->event_post->user->id;

            $postsPollDetail['username'] =  $polls->event_post->user->firstname . ' ' . $polls->event_post->user->lastname;

            $postsPollDetail['profile'] =  empty($polls->event_post->user->profile) ? "" : asset('public/storage/profile/' . $polls->event_post->user->profile);

            $postsPollDetail['post_message'] =  empty($polls->event_post->post_message) ? "" :  $polls->event_post->post_message;
            $postsPollDetail['location'] =  empty($polls->event_post->user->city) ? "" :  $polls->event_post->user->city;


            $postsPollDetail['total_poll_vote'] = $polls->user_poll_data_count;



            $postsPollDetail['poll_id'] = $polls->id;

            $postsPollDetail['poll_question'] = $polls->poll_question;

            $postsPollDetail['poll_option'] = [];

            foreach ($polls->event_poll_option as $optionValue) {

                $optionData['id'] = $optionValue->id;

                $optionData['option'] = $optionValue->option;
                $optionData['total_vote'] =  "0%";
                if (getOptionAllTotalVote($polls->id) != 0) {

                    $optionData['total_vote'] =  round(getOptionTotalVote($optionValue->id) / getOptionAllTotalVote($polls->id) * 100) . "%";
                }

                $optionData['is_poll_selected'] = checkUserGivePoll($user->id, $polls->id, $optionValue->id);

                $postsPollDetail['poll_option'][] = $optionData;
            }

            $postsPollDetail['post_type'] = $polls->event_post->post_type;

            $postsPollDetail['rsvp_status'] = $checkUserRsvp;

            $postsPollDetail['created_at'] = $polls->event_post->created_at;

            $reactionList = getReaction($polls->event_post->id);

            $postReactionList = [];

            foreach ($reactionList as $values) {

                $postReactionList[] = $values->reaction;
            }

            $postsPollDetail['reactionList'] = $postReactionList;

            $postsPollDetail['total_comment'] = $polls->event_post->user->event_post_comment_count;

            $postsPollDetail['total_likes'] = $polls->event_post->user->event_post_reaction_count;

            $postsPollDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

            $postsPollDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";



            // postDetail // 



            $postDetails = [];

            $postReaction = [];

            $postReactions = getReaction($polls->event_post->id);

            foreach ($postReactions as $reactionVal) {

                $reactionInfo['id'] = $reactionVal->id;

                $reactionInfo['event_post_id'] = $reactionVal->event_post_id;

                $reactionInfo['reaction'] = $reactionVal->reaction;

                $reactionInfo['user_id'] = $reactionVal->user_id;

                $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                $postReaction[] = $reactionInfo;
            }

            $postPollDetailList['post_reaction'] = $postReaction;


            $postPollDetailList['reactionList'] = $postReactionList;

            $postPollDetailList['total_comment'] = $polls->event_post->user->event_post_comment_count;

            $postPollDetailList['total_likes'] = $polls->event_post->user->event_post_reaction_count;

            $postPollDetailList['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

            $postPollDetailList['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";


            $postCommentList = [];

            $postComment = getComments($polls->id);



            foreach ($postComment as $commentVal) {

                $commentInfo['id'] = $commentVal->id;

                $commentInfo['event_post_id'] = $commentVal->event_post_id;

                $commentInfo['comment'] = $commentVal->comment_text;

                $commentInfo['user_id'] = $commentVal->user_id;

                $commentInfo['username'] = $commentVal->user->firstname . ' ' . $commentVal->user->lastname;

                $commentInfo['profile'] = (!empty($commentVal->user->profile)) ? asset('public/storage/profile/' . $commentVal->user->profile) : "";

                $commentInfo['comment_total_likes'] = $commentVal->post_comment_reaction_count;

                $commentInfo['is_like'] = checkUserIsLike($commentVal->id, $user->id);

                $commentInfo['total_replies'] = $commentVal->replies_count;

                $commentInfo['created_at'] = $commentVal->created_at;



                $postCommentList[] = $commentInfo;
            }

            $postPollDetailList['post_comment'] = $postCommentList;
            $postDetails[] = $postPollDetailList;



            $postsPollDetail['post_detail'] = $postDetails;


            return response()->json(['status' => 1, 'message' => "Voted sucessfully", "data" => $postsPollDetail]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        }

        // catch (\Exception $e) {

        //     DB::rollBack();

        //     return response()->json(['status' => 0, 'message' => "something went wrong"]);
        // }
    }



    public function eventGuest(Request $request)

    {

        $user  = Auth::guard('api')->user();
        $rawData = $request->getContent();
        $input = json_decode($rawData, true);

        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [
            'event_id' => ['required', 'exists:events,id']
        ]);

        if ($validator->fails()) {
            return response()->json([

                'status' => 0,
                'message' => $validator->errors()->first(),


            ]);
        }

        try {
            $eventDetail = Event::with(['user', 'event_settings', 'event_image', 'event_schedule' => function ($query) {
            }])->where('id', $input['event_id'])->first();

            $eventattending = EventInvitedUser::whereHas('user', function ($query) {

                $query->where('app_user', '1');
            })->where(['rsvp_status' => '1', 'event_id' => $eventDetail->id])->count();

            $eventNotComing = EventInvitedUser::whereHas('user', function ($query) {

                $query->where('app_user', '1');
            })->where(['rsvp_d' => '1', 'rsvp_status' => '0', 'event_id' => $eventDetail->id])->count();


            $pendingUser = EventInvitedUser::whereHas('user', function ($query) {

                $query->where('app_user', '1');
            })->where(['event_id' => $eventDetail->id, 'rsvp_d' => '0'])->count();



            $adults = EventInvitedUser::whereHas('user', function ($query) {
                $query->where('app_user', '1');
            })->where(['event_id' => $eventDetail->id, 'rsvp_status' => '1', 'rsvp_d' => '1'])->sum('adults');

            $kids = EventInvitedUser::whereHas('user', function ($query) {
                $query->where('app_user', '1');
            })->where(['event_id' => $eventDetail->id, 'rsvp_status' => '1', 'rsvp_d' => '1'])->sum('kids');

            $eventAboutHost['is_event_owner'] = ($eventDetail->user_id == $user->id) ? 1 : 0;
            $eventAboutHost['event_wall'] = $eventDetail->event_settings->event_wall;
            $eventAboutHost['guest_list_visible_to_guests'] = $eventDetail->event_settings->guest_list_visible_to_guests;
            $eventAboutHost['attending'] = $adults + $kids;
            $eventAboutHost['total_invitation'] =  count(getEventInvitedUser($input['event_id']));
            $eventAboutHost['adults'] = (int)$adults;
            $eventAboutHost['kids'] =  (int)$kids;
            $eventAboutHost['not_attending'] = $eventNotComing;
            $eventAboutHost['pending'] = $pendingUser;
            $eventAboutHost['allow_limit'] = $eventDetail->event_settings->allow_limit;
            $eventAboutHost['adult_only_party'] = $eventDetail->event_settings->adult_only_party;

            $userRsvpStatusList = EventInvitedUser::query();
            $userRsvpStatusList->whereHas('user', function ($query) {
                $query->where('app_user', '1');
            })->where(['event_id' => $eventDetail->id, 'invitation_sent' => '1'])->get();

            $selectedFilters = $request->input('filters');
            if (!empty($selectedFilters) && !in_array('all', $selectedFilters)) {

                $userRsvpStatusList->where(function ($query) use ($selectedFilters) {
                    foreach ($selectedFilters as $filter) {

                        switch ($filter) {
                            case 'attending':
                                $query->orWhere('rsvp_status', '1');
                                break;
                            case 'not_attending':
                                $query->orWhere(function ($qury) {
                                    $qury->where(['rsvp_status' => '0']);
                                });
                                break;
                            case 'no_reply':
                                $query->orWhere(function ($qury) {
                                    $qury->where(['rsvp_status' => NULL]);
                                });
                                break;
                        }
                    }
                });
            }

            $result = $userRsvpStatusList->get();

            $eventAboutHost['rsvp_status_list'] = [];

            if (count($result) != 0) {

                foreach ($result as $value) {
                    $rsvpUserStatus = [];
                    $rsvpUserStatus['id'] = $value->id;

                    $rsvpUserStatus['user_id'] = $value->user->id;

                    $rsvpUserStatus['first_name'] = $value->user->firstname;
                    $rsvpUserStatus['last_name'] = $value->user->lastname;
                    $rsvpUserStatus['username'] = $value->user->firstname . ' ' . $value->user->lastname;


                    $rsvpUserStatus['profile'] = (!empty($value->user->profile) || $value->user->profile != NULL) ? asset('public/storage/profile/' . $value->user->profile) : "";


                    $rsvpUserStatus['email'] = ($value->user->email != '') ? $value->user->email : "";

                    $rsvpUserStatus['phone_number'] = ($value->user->phone_number != '') ? $value->user->phone_number : "";
                    $rsvpUserStatus['prefer_by'] =  $value->prefer_by;
                    $rsvpUserStatus['kids'] = $value->kids;

                    $rsvpUserStatus['adults'] = $value->adults;

                    $rsvpUserStatus['rsvp_status'] =  ($value->rsvp_status != null) ? (int)$value->rsvp_status : NULL;

                    if ($value->rsvp_d == '0' && ($value->read == '1' || $value->read == '0') || $value->rsvp_status == null) {

                        $rsvpUserStatus['rsvp_status'] = 2; // no reply 
                    }

                    $rsvpUserStatus['read'] = $value->read;

                    $rsvpUserStatus['rsvp_d'] = $value->rsvp_d;

                    $rsvpUserStatus['invitation_sent'] = $value->invitation_sent;
                    $totalEvent =  Event::where('user_id', $value->user->id)->count();
                    $totalEventPhotos =  EventPost::where(['user_id' => $value->user->id, 'post_type' => '1'])->count();
                    $comments =  EventPostComment::where('user_id', $value->user->id)->count();
                    $rsvpUserStatus['user_profile'] = [
                        'id' => $value->user->id,
                        'profile' => empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile),
                        'bg_profile' => empty($value->user->bg_profile) ? "" : asset('public/storage/bg_profile/' . $value->user->bg_profile),
                        'app_user' =>  $value->user->app_user,
                        'gender' => ($value->user->gender != NULL) ? $value->user->gender : "",
                        'first_name' => $value->user->firstname,
                        'last_name' => $value->user->lastname,
                        'username' => $value->user->firstname . ' ' . $value->user->lastname,
                        'location' => ($value->user->city != NULL) ? $value->user->city : "",
                        'about_me' => ($value->user->about_me != NULL) ? $value->user->about_me : "",
                        'created_at' => empty($value->user->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($value->user->created_at))),
                        'total_events' => $totalEvent,
                        'visible' => $value->user->visible,
                        'total_photos' => $totalEventPhotos,
                        'comments' => $comments,
                        'message_privacy' =>  $value->user->message_privacy
                    ];



                    $eventAboutHost['rsvp_status_list'][] = $rsvpUserStatus;
                }
            }

            $getInvitedusers = getInvitedUsers($input['event_id']);


            $eventAboutHost['invited_user_id'] = $getInvitedusers['invited_user_id'];
            $eventAboutHost['invited_guests'] = $getInvitedusers['invited_guests'];
            //  event about view //

            $totalEnvitedUser = EventInvitedUser::whereHas('user', function ($query) {

                $query->where('app_user', '1');
            })->where(['event_id' => $eventDetail->id])->count();
            $todayrsvprate = EventInvitedUser::whereHas('user', function ($query) {

                $query->where('app_user', '1');
            })->where(['rsvp_status' => '1', 'event_id' => $eventDetail->id])

                ->whereDate('created_at', '=', date('Y-m-d'))

                ->count();


            $eventAboutHost['total_invite'] =  count(getEventInvitedUser($input['event_id']));


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
            return response()->json(['status' => 1, 'data' => $eventAboutHost, 'message' => "Guest event"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "error"]);
        }
        //  catch (\Exception $e) {

        //     DB::rollBack();

        //     return response()->json(['status' => 0, 'message' => "something went wrong"]);
        // }
    }



    public function  faildInvites(Request $request)

    {
        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();



        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }


        $validator = Validator::make($input, [

            'event_id' => ['required', 'exists:events,id']

        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),



            ]);
        }

        try {
            $sendFaildInvites = EventInvitedUser::where(['event_id' => $input['event_id'], 'invitation_sent' => '0'])->get();

            $faildInviteList = [];

            foreach ($sendFaildInvites as $value) {

                $userDetail['id'] = $value->user->id;

                $userDetail['first_name'] = (!empty($value->user->firstname) || $value->user->firstname != NULL) ? $value->user->firstname : "";
                $userDetail['last_name'] = (!empty($value->user->lastname) || $value->user->lastname != NULL) ? $value->user->lastname : "";

                $userDetail['profile'] = (!empty($value->user->profile) || $value->user->profile != NULL) ? asset('public/storage/profile/' . $value->user->profile) : "";

                $userDetail['email'] = (!empty($value->user->email)) ? $value->user->email : "";

                $userDetail['country_code'] = (string)$value->user->country_code;
                $userDetail['phone_number'] = (!empty($value->user->phone_number)) ? $value->user->phone_number : "";
                $userDetail['app_user'] = $value->user->app_user;

                $userDetail['prefer_by'] = $value->prefer_by;

                $faildInviteList[] = $userDetail;
            }

            return response()->json(['status' => 1, 'data' => $faildInviteList, 'message' => "Faild invites"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "error"]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }



    public function sendInvitation(Request $request)
    {
        $user  = Auth::guard('api')->user();
        $rawData = $request->getContent();
        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [

            'event_id' => ['required', 'exists:events,id'],
            'guest_list' => ['required', 'array'],

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }

        // try {
        if (!empty($input['guest_list'])) {
            DB::beginTransaction();
            $id = 0;
            foreach ($input['guest_list'] as $value) {

                if ($value['id'] == "0") {
                    $addNewUser = new User;
                    $addNewUser->firstname = $value['first_name'];
                    $addNewUser->email = $value['email'];
                    $addNewUser->country_code = '1';
                    $addNewUser->app_user = '0';
                    $addNewUser->is_user_phone_contact = '1';
                    $addNewUser->parent_user_phone_contact = $user->id;

                    $addNewUser->phone_number = $value['phone_number'];
                    $addNewUser->prefer_by = $value['prefer_by'];

                    if ($addNewUser->save()) {

                        EventInvitedUser::create([
                            'event_id' => $input['event_id'],
                            'prefer_by' => $value['prefer_by'],
                            'user_id' => $addNewUser->id
                        ]);
                    }
                    $id = $addNewUser->id;
                } else {

                    $checkUserInvitation = EventInvitedUser::with(['user'])->where(['event_id' => $input['event_id']])->get()->pluck('user_id')->toArray();

                    $id = $value['id'];
                    if (!in_array($value['id'], $checkUserInvitation)) {

                        EventInvitedUser::create([

                            'event_id' => $input['event_id'],

                            'prefer_by' => $value['prefer_by'],

                            'user_id' => $value['id']

                        ]);
                    } else {
                        $updateUser =  EventInvitedUser::with('user')->where(['event_id' => $input['event_id'], 'user_id' => $id])->first();
                        $updateUser->prefer_by = $value['prefer_by'];
                        $updateUser->save();
                    }
                }
                if ($value['prefer_by'] == 'email') {

                    $email = $value['email'];

                    $eventInfo = Event::with(['user', 'event_image'])->where('id', $input['event_id'])->first();
                    $eventData = [
                        'event_name' => $eventInfo->event_name,
                        'hosted_by' => $eventInfo->hosted_by,
                        'profileUser' => ($eventInfo->user->profile != NULL || $eventInfo->user->profile != "") ? $eventInfo->user->profile : "no_profile.png",
                        'event_image' => ($eventInfo->event_image->isNotEmpty()) ? $eventInfo->event_image[0]->image : "no_image.png",
                        'date' =>  date('l, M. jS', strtotime($eventInfo->start_date)),
                        'time' => '1PM',
                        'address' => $eventInfo->event_location_name . ' ' . $eventInfo->address_1 . ' ' . $eventInfo->address_2 . ' ' . $eventInfo->state . ' ' . $eventInfo->city . ' - ' . $eventInfo->zip_code,
                    ];



                    // $checkEmail =  emailChecker($email);

                    // if ($checkEmail == 'ok') {

                    $invitation_sent_status =  EventInvitedUser::where(['event_id' => $input['event_id'], 'user_id' => $id])->first();
                    $invitation_sent_status->invitation_sent = '1';
                    $invitation_sent_status->save();
                    if ($invitation_sent_status->user->app_user == '1') {
                        Notification::where(['user_id' => $id, 'sender_id' => $user->id, 'event_id' => $input['event_id']])->delete();
                        $notification_message = "You have invited in " . $eventInfo->event_name;
                        $notification = new Notification;
                        $notification->event_id = $input['event_id'];
                        $notification->user_id = $id;
                        $notification->notification_type = 'invite';
                        $notification->sender_id = $user->id;
                        $notification->notification_message = $notification_message;
                        $notification->save();
                    }
                    // } else {
                    //     $faildinvitation_sent_status =  EventInvitedUser::where(['event_id' => $input['event_id'], 'user_id' => $id])->first();
                    //     $faildinvitation_sent_status->invitation_sent = '0';
                    //     $faildinvitation_sent_status->save();
                    // }
                    dispatch(new sendInvitation(array($email, $eventData)));
                }

                if ($value['prefer_by'] == 'phone') {
                    $eventInfo = Event::with(['user', 'event_image'])->where('id', $input['event_id'])->first();
                    $notification_message = " have invited you to: " . $eventInfo->event_name;


                    $sent = sendSMSForApplication($value['phone_number'], $notification_message);

                    if ($sent == true) {
                        $invitation_sent_status =  EventInvitedUser::where(['event_id' => $input['event_id'], 'user_id' => $id])->first();
                        $invitation_sent_status->invitation_sent = '1';
                        $invitation_sent_status->save();
                    }
                }
            }
        }


        DB::commit();


        return response()->json(['status' => 1, 'message' => "invites sent sucessfully"]);
        // } 
        // catch (QueryException $e) {

        //     DB::rollBack();

        //     return response()->json(['status' => 0, 'message' => "db error"]);
        // }
        //  catch (\Exception $e) {

        //     DB::rollBack();

        //     return response()->json(['status' => 0, 'message' => "something went wrong"]);
        // }
    }

    public function eventPostPhotoList(Request $request)
    {


        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();



        $input = json_decode($rawData, true);

        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [

            'event_id' => ['required', 'exists:events,id']

        ]);

        if ($validator->fails()) {

            return response()->json([

                'status' => 0,
                'message' => $validator->errors()->first(),


            ]);
        }

        try {

            $selectedFilters = $request->input('filters');

            $getPhotoList = EventPostPhoto::query();

            $getPhotoList->with(['user', 'event_post_photo_reaction', 'event_post_photo_data'])->withCount(['event_post_photo_reaction', 'event_post_Photo_comment' => function ($query) {
                $query->where('parent_comment_id', NULL);
            }])->where('event_id', $input['event_id']);
            $eventCreator = Event::where('id', $input['event_id'])->first();
            if (!empty($selectedFilters) && !in_array('all', $selectedFilters)) {
                $getPhotoList->where(function ($query) use ($selectedFilters, $eventCreator) {
                    foreach ($selectedFilters as $filter) {
                        switch ($filter) {
                            case 'time_posted':
                                $query->orderBy('id', 'desc');
                                break;
                            case 'guest':
                                $query->orWhere('user_id', '!=', $eventCreator->user_id);

                                break;
                            case 'photos':
                                $query->orWhereHas('event_post_photo_data', function ($subQuery) {
                                    $subQuery->where('type', 'image');
                                });
                                break;
                            case 'videos':
                                $query->orWhereHas('event_post_photo_data', function ($subQuery) {
                                    $subQuery->where('type', 'video');
                                });
                                break;
                                // Add more cases for other filters if needed
                        }
                    }
                });
            }

            $getPhotoList->orderBy('id', 'desc');

            $results = $getPhotoList->get();

            $postPhotoList = [];



            foreach ($results as $value) {


                $postControl = PostControl::where(['user_id' => $user->id, 'event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                if ($postControl != null) {

                    if ($postControl->post_control == 'hide_post') {
                        continue;
                    }
                }

                $postPhotoDetail['user_id'] = $value->user->id;

                $postPhotoDetail['firstname'] = $value->user->firstname;

                $postPhotoDetail['lastname'] = $value->user->lastname;



                $postPhotoDetail['profile'] = (!empty($value->user->profile) || $value->user->profile != NULL) ? asset('public/storage/profile/' . $value->user->profile) : "";

                $selfReaction = EventPostPhotoReaction::where(['user_id' => $user->id, 'event_post_photo_id' => $value->id])->first();

                $postPhotoDetail['is_reaction'] = ($selfReaction != NULL) ? '1' : '0';

                $postPhotoDetail['self_reaction'] = ($selfReaction != NULL) ? $selfReaction->reaction : "";

                $postPhotoDetail['event_id'] = $value->event_id;
                $postPhotoDetail['id'] = $value->id;

                $postPhotoDetail['post_message'] = (!empty($value->post_message) || $value->post_message != NULL) ? $value->post_message : "";

                $postPhotoDetail['post_time'] = $this->setpostTime($value->updated_at);

                $photoVideoData = "";



                if (!empty($value->event_post_photo_data)) {



                    $photData = $value->event_post_photo_data;

                    foreach ($photData as $val) {

                        $photoVideoDetail['id'] = $val->id;

                        $photoVideoDetail['event_post_photo_id'] = $val->event_post_photo_id;

                        $photoVideoDetail['post_media'] = (!empty($val->post_media) || $val->post_media != NULL) ? asset('public/storage/post_photo/' . $val->post_media) : "";

                        $photoVideoDetail['type'] = $val->type;

                        $photoVideoData = $photoVideoDetail;
                    }
                }

                $postPhotoDetail['mediaData'] = $photoVideoData;

                $postPhotoDetail['total_media'] = ($value->event_post_photo_data_count - 1 != 0 && $value->event_post_photo_data_count - 1 != -1)  ? "+" . $value->event_post_photo_data_count - 1 : "";

                $getPhotoReaction = getPhotoReaction($value->id);

                $reactionList = [];

                foreach ($getPhotoReaction as $values) {

                    $reactionList[] = $values->reaction;
                }

                $postPhotoDetail['reactionList'] = $reactionList;



                $postPhotoDetail['total_likes'] = $value->event_post_photo_reaction_count;



                $postPhotoDetail['total_comments'] = $value->event_post__photo_comment_count;


                $postPhotoList[] = $postPhotoDetail;
            }
            if (!empty($postPhotoList)) {

                return response()->json(['status' => 1, 'data' => $postPhotoList, 'message' => "Photo List"]);
            } else {
                return response()->json(['status' => 0, 'data' => $postPhotoList, 'message' => "Photo not found"]);
            }
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => 'db error']);
        } catch (Exception $e) {


            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }


    public function eventPostPhotoList1(Request $request)
    {


        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();



        $input = json_decode($rawData, true);

        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [

            'event_id' => ['required', 'exists:events,id']

        ]);

        if ($validator->fails()) {

            return response()->json([

                'status' => 0,
                'message' => $validator->errors()->first(),


            ]);
        }

        try {

            $selectedFilters = $request->input('filters');
            $getPhotoList = EventPost::query();
            $getPhotoList->with(['user', 'event_post_reaction', 'post_image'])->withCount(['event_post_reaction', 'post_image', 'event_post_comment' => function ($query) {
                $query->where('parent_comment_id', NULL);
            }])->where(['event_id' => $input['event_id'], 'post_type' => '1']);
            $eventCreator = Event::where('id', $input['event_id'])->first();
            if (!empty($selectedFilters) && !in_array('all', $selectedFilters)) {
                $getPhotoList->where(function ($query) use ($selectedFilters, $eventCreator) {
                    foreach ($selectedFilters as $filter) {
                        switch ($filter) {
                            case 'time_posted':
                                $query->orderBy('id', 'desc');
                                break;
                            case 'guest':
                                $query->orWhere('user_id', '!=', $eventCreator->user_id);

                                break;
                            case 'photos':
                                $query->orWhereHas('post_image', function ($subQuery) {
                                    $subQuery->where('type', 'image');
                                });
                                break;
                            case 'videos':
                                $query->orWhereHas('post_image', function ($subQuery) {
                                    $subQuery->where('type', 'video');
                                });
                                break;
                                // Add more cases for other filters if needed
                        }
                    }
                });
            }

            $getPhotoList->orderBy('id', 'desc');

            $results = $getPhotoList->get();

            $postPhotoList = [];



            foreach ($results as $value) {

                $postControl = PostControl::where(['user_id' => $user->id, 'event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                if ($postControl != null) {

                    if ($postControl->post_control == 'hide_post') {
                        continue;
                    }
                }


                $postPhotoDetail['user_id'] = $value->user->id;
                $postPhotoDetail['is_own_post'] = ($value->user->id == $user->id) ? "1" : "0";

                $postPhotoDetail['firstname'] = $value->user->firstname;

                $postPhotoDetail['lastname'] = $value->user->lastname;



                $postPhotoDetail['profile'] = (!empty($value->user->profile) || $value->user->profile != NULL) ? asset('public/storage/profile/' . $value->user->profile) : "";

                $selfReaction = EventPostReaction::where(['user_id' => $user->id, 'event_post_id' => $value->id])->first();

                $postPhotoDetail['is_reaction'] = ($selfReaction != NULL) ? '1' : '0';

                $postPhotoDetail['self_reaction'] = ($selfReaction != NULL) ? $selfReaction->reaction : "";

                $postPhotoDetail['event_id'] = $value->event_id;
                $postPhotoDetail['id'] = $value->id;

                $postPhotoDetail['post_message'] = (!empty($value->post_message) || $value->post_message != NULL) ? $value->post_message : "";

                $postPhotoDetail['post_time'] = $this->setpostTime($value->updated_at);
                $postPhotoDetail['is_in_photo_moudle'] = $value->is_in_photo_moudle;

                $photoVideoData = "";



                if (!empty($value->post_image)) {



                    $photData = $value->post_image;

                    foreach ($photData as $val) {

                        $photoVideoDetail['id'] = $val->id;

                        $photoVideoDetail['event_post_id'] = $val->event_post_id;

                        $photoVideoDetail['post_media'] = (!empty($val->post_image) || $val->post_media != NULL) ? asset('public/storage/post_image/' . $val->post_image) : "";

                        $photoVideoDetail['type'] = $val->type;

                        $photoVideoData = $photoVideoDetail;
                    }
                }

                $postPhotoDetail['mediaData'] = $photoVideoData;

                $postPhotoDetail['total_media'] = ($value->post_image_count - 1 != 0 && $value->post_image_count - 1 != -1)  ? "+" . $value->post_image_count - 1 : "";

                $getPhotoReaction = getReaction($value->id);

                $reactionList = [];

                foreach ($getPhotoReaction as $values) {

                    $reactionList[] = $values->reaction;
                }

                $postPhotoDetail['reactionList'] = $reactionList;



                $postPhotoDetail['total_likes'] = $value->event_post_reaction_count;



                $postPhotoDetail['total_comments'] = $value->event_post_comment_count;


                $postPhotoList[] = $postPhotoDetail;
            }
            if (!empty($postPhotoList)) {

                return response()->json(['status' => 1, 'data' => $postPhotoList, 'message' => "Photo List"]);
            } else {
                return response()->json(['status' => 0, 'data' => $postPhotoList, 'message' => "Photo not found"]);
            }
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => 'db error']);
        } catch (Exception $e) {


            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }

    public function removeEventPostPhoto(Request $request)
    {
        $rawData = $request->getContent();



        $input = json_decode($rawData, true);

        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [

            'event_post_id' => ['required', 'exists:event_posts,id']

        ]);

        if ($validator->fails()) {

            return response()->json([

                'status' => 0,
                'message' => $validator->errors()->first(),


            ]);
        }


        try {
            EventPost::whereIn('id', $input['event_post_id'])->delete();
            return response()->json(['status' => 1, 'message' => "Post removed successfully"]);
        } catch (QueryException $e) {

            return response()->json(['status' => 0, 'message' => 'db error']);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }


    public function eventPostPhotoDetail(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();



        $input = json_decode($rawData, true);

        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [

            'event_post_photo_id' => ['required', 'exists:event_post_photos,id']

        ]);

        if ($validator->fails()) {

            return response()->json([

                'status' => 0,
                'message' => $validator->errors()->first(),


            ]);
        }

        try {
            $getPhotoList = EventPost::with(['user', 'event_post_reaction', 'event_post_comment' => function ($query) {
                $query->with('replies');
            }, 'post_image'])->withCount(['event_post_reaction', 'event_post_comment' => function ($query) {
                $query->where('parent_comment_id', NULL);
            }, 'post_image'])->where('id', $input['event_post_photo_id'])->first();





            $postPhotoDetail['id'] = $getPhotoList->id;

            $postPhotoDetail['user_id'] = $getPhotoList->user->id;

            $postPhotoDetail['username'] = $getPhotoList->user->firstname . ' ' . $getPhotoList->user->lastname;



            $postPhotoDetail['profile'] = (!empty($getPhotoList->user->profile) || $getPhotoList->user->profile != NULL) ? asset('public/storage/profile/' . $getPhotoList->user->profile) : "";


            $postPhotoDetail['post_message'] = (!empty($getPhotoList->post_message) || $getPhotoList->post_message != NULL) ? $getPhotoList->post_message : "";
            $postPhotoDetail['location'] = (!empty($getPhotoList->user->city)) ? $getPhotoList->user->city : "";
            $postPhotoDetail['posttime'] = $this->setpostTime($getPhotoList->updated_at);

            $selfReaction = EventPostReaction::where(['user_id' => $user->id, 'event_post_id' => $getPhotoList->id])->first();

            $postPhotoDetail['is_reaction'] = ($selfReaction != NULL) ? '1' : '0';

            $postPhotoDetail['self_reaction'] = ($selfReaction != NULL) ? $selfReaction->reaction : "";

            $postPhotoDetail['event_id'] = $getPhotoList->event_id;

            $postPhotoDetail['post_type'] = "1";


            $postPhotoDetail['post_image'] = [];


            if (!empty($getPhotoList->post_image)) {



                $photData = $getPhotoList->post_image;

                foreach ($photData as $val) {

                    $photoVideoDetail['media_url'] = (!empty($val->post_media) || $val->post_media != NULL) ? asset('public/storage/post_photo/' . $val->post_media) : "";

                    $photoVideoDetail['type'] = $val->type;

                    $postPhotoDetail['post_image'][] = $photoVideoDetail;
                }
            }


            $postPhotoDetail['total_media'] = ($getPhotoList->post_image - 1 != 0) ? "+" . $getPhotoList->post_image - 1 : "";

            $getPhotoReaction = getReaction($getPhotoList->id);

            $postPhotoDetail['reactionList'] = [];

            foreach ($getPhotoReaction as $values) {

                $postPhotoDetail['reactionList'][] = $values->reaction;
            }

            $postPhotoDetail['post_reaction'] = [];
            foreach ($getPhotoReaction as $val) {

                $reactionInfo['id'] = $val->id;

                $reactionInfo['event_post_id'] = $val->event_post_photo_id;

                $reactionInfo['reaction'] = $val->reaction;

                $reactionInfo['user_id'] = $val->user_id;
                $reactionInfo['username'] = $val->user->firstname . ' ' . $val->user->lastname;
                $reactionInfo['location'] = ($val->user->city != NULL) ? $val->user->city : "";

                $reactionInfo['profile'] = (!empty($val->user->profile)) ? asset('public/storage/profile/' . $val->user->profile) : "";

                $postPhotoDetail['post_reaction'][] = $reactionInfo;
            }




            $postPhotoDetail['total_likes'] = $getPhotoList->event_post_reaction_count;



            $postPhotoDetail['total_comment'] = $getPhotoList->event_post_comment_count;

            $getPostPhotoComments = getPostComments($val->event_post_photo_id);
            $postPhotoDetail['post_comment'] = [];


            foreach ($getPostPhotoComments as $commentVal) {


                $commentInfo['id'] = $commentVal->id;

                $commentInfo['event_post_id'] = $commentVal->event_post_photo_id;

                $commentInfo['comment'] = $commentVal->comment_text;

                $commentInfo['user_id'] = $commentVal->user_id;

                $commentInfo['username'] = $commentVal->user->firstname . ' ' . $commentVal->user->lastname;

                $commentInfo['profile'] = (!empty($commentVal->user->profile)) ? asset('public/storage/profile/' . $commentVal->user->profile) : "";
                $commentInfo['location'] = ($commentVal->user->city != NULL) ? $commentVal->user->city : "";
                $commentInfo['comment_total_likes'] = $commentVal->post_comment_reaction_count;

                $commentInfo['is_like'] = checkUserIsLike($commentVal->id, $user->id);

                $commentInfo['total_replies'] = $commentVal->replies_count;

                $commentInfo['created_at'] = $commentVal->created_at;
                $commentInfo['posttime'] = setpostTime($commentVal->created_at);

                $commentInfo['comment_replies'] = [];

                foreach ($commentVal->replies as $reply) {
                    $mainParentId = (new EventPostPhotoComment())->getMainParentId($reply->parent_comment_id);
                    $replyCommentInfo['id'] = $reply->id;

                    $replyCommentInfo['event_post_id'] = $reply->event_post_photo_id;
                    $replyCommentInfo['main_comment_id'] = $reply->main_parent_comment_id;
                    $replyCommentInfo['comment'] = $reply->comment_text;

                    $replyCommentInfo['user_id'] = $reply->user_id;

                    $replyCommentInfo['username'] = $reply->user->firstname . ' ' . $reply->user->lastname;

                    $replyCommentInfo['profile'] = (!empty($reply->user->profile)) ? asset('public/storage/profile/' . $reply->user->profile) : "";

                    $replyCommentInfo['location'] = ($reply->user->city != NULL) ? $reply->user->city : "";
                    $replyCommentInfo['comment_total_likes'] = $reply->post_photo_comment_reaction_count;

                    $replyCommentInfo['is_like'] = checkUserPhotoIsLike($reply->id, $user->id);

                    $replyCommentInfo['total_replies'] = $reply->replies_count;

                    $replyCommentInfo['created_at'] = $reply->created_at;
                    $replyCommentInfo['posttime'] = setpostTime($reply->created_at);
                    $commentInfo['comment_replies'][] = $replyCommentInfo;

                    $replyComment =  EventPostPhotoComment::with(['user'])->withcount('post_photo_comment_reaction', 'replies')->where(['main_parent_comment_id' => $mainParentId, 'event_post_photo_id' => $reply->event_post_photo_id, 'parent_comment_id' => $reply->id])->orderBy('id', 'DESC')->get();

                    foreach ($replyComment as $childReplyVal) {

                        if ($childReplyVal->parent_comment_id != $childReplyVal->main_parent_comment_id) {

                            $totalReply = EventPostPhotoComment::withcount('post_photo_comment_reaction')->where("parent_comment_id", $childReplyVal->id)->count();


                            $commentChildReply['id'] = $childReplyVal->id;

                            $commentChildReply['event_post_id'] = $childReplyVal->event_post_photo_id;
                            $commentChildReply['main_comment_id'] = $childReplyVal->main_parent_comment_id;
                            $commentChildReply['comment'] = $childReplyVal->comment_text;
                            $commentChildReply['user_id'] = $childReplyVal->user_id;

                            $commentChildReply['username'] = $childReplyVal->user->firstname . ' ' . $childReplyVal->user->lastname;

                            $commentChildReply['profile'] = (!empty($childReplyVal->user->profile)) ? asset('public/storage/profile/' . $childReplyVal->user->profile) : "";
                            $commentChildReply['location'] = (!empty($childReplyVal->user->city)) ? $childReplyVal->user->city : "";

                            $commentChildReply['comment_total_likes'] = $childReplyVal->post_photo_comment_reaction_count;

                            $commentChildReply['is_like'] = checkUserPhotoIsLike($childReplyVal->id, $user->id);

                            $commentChildReply['total_replies'] = $totalReply;
                            $commentChildReply['posttime'] = setpostTime($childReplyVal->created_at);
                            $commentChildReply['created_at'] = $childReplyVal->created_at;

                            $commentInfo['comment_replies'][] = $commentChildReply;

                            $replyChildComment =  EventPostPhotoComment::with(['user'])->withcount('post_photo_comment_reaction', 'replies')->where(['main_parent_comment_id' => $mainParentId, 'event_post_photo_id' => $childReplyVal->event_post_photo_id, 'parent_comment_id' => $childReplyVal->id])->orderBy('id', 'DESC')->get();

                            foreach ($replyChildComment as $childInReplyVal) {

                                if ($childInReplyVal->parent_comment_id != $childInReplyVal->main_parent_comment_id) {

                                    $totalReply = EventPostPhotoComment::withcount('post_photo_comment_reaction')->where("parent_comment_id", $childInReplyVal->id)->count();


                                    $commentChildInReply['id'] = $childInReplyVal->id;

                                    $commentChildInReply['event_post_id'] = $childInReplyVal->event_post_photo_id;
                                    $commentChildInReply['main_comment_id'] = $childInReplyVal->main_parent_comment_id;
                                    $commentChildInReply['comment'] = $childInReplyVal->comment_text;
                                    $commentChildInReply['user_id'] = $childInReplyVal->user_id;

                                    $commentChildInReply['username'] = $childInReplyVal->user->firstname . ' ' . $childInReplyVal->user->lastname;

                                    $commentChildInReply['profile'] = (!empty($childInReplyVal->user->profile)) ? asset('public/storage/profile/' . $childInReplyVal->user->profile) : "";
                                    $commentChildInReply['location'] = (!empty($childInReplyVal->user->city)) ? $childInReplyVal->user->city : "";

                                    $commentChildInReply['comment_total_likes'] = $childInReplyVal->post_photo_comment_reaction_count;

                                    $commentChildInReply['is_like'] = checkUserPhotoIsLike($childInReplyVal->id, $user->id);

                                    $commentChildInReply['total_replies'] = $totalReply;
                                    $commentChildInReply['posttime'] = setpostTime($childInReplyVal->created_at);
                                    $commentChildInReply['created_at'] = $childInReplyVal->created_at;

                                    $commentInfo['comment_replies'][] = $commentChildInReply;
                                }
                            }
                        }
                    }
                }


                $postPhotoDetail['post_comment'][] = $commentInfo;
            }



            return response()->json(['status' => 1, 'data' => $postPhotoDetail, 'message' => "Photo Details"]);
        }
        // catch (QueryException $e) {

        //     DB::rollBack();

        //     return response()->json(['status' => 0, 'message' => 'db error']);
        // } 
        catch (Exception $e) {


            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }



    public function postPhotoCommentReplyList(Request $request)

    {



        $user  = Auth::guard('api')->user();



        $rawData = $request->getContent();



        $input = json_decode($rawData, true);

        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [

            'event_photo_comment_id' => ['required'],

        ]);



        if ($validator->fails()) {

            return response()->json(

                [
                    'status' => 0,
                    'message' => $validator->errors()->first()

                ],



            );
        }



        try {

            DB::beginTransaction();



            $replyList = EventPostPhotoComment::with(['user'])->withcount('post_photo_comment_reaction')->where("parent_comment_id", $input['event_photo_comment_id'])->get();



            $commentInfo = [];

            if (!empty($replyList)) {

                foreach ($replyList as $replyVal) {



                    $totalReply = EventPostPhotoComment::withcount('post_photo_comment_reaction')->where("parent_comment_id", $replyVal->id)->count();



                    $commentReply['id'] = $replyVal->id;

                    $commentReply['event_post_photo_id'] = $replyVal->event_post_photo_id;

                    $commentReply['comment'] = $replyVal->comment_text;

                    $commentReply['parent_comment_id'] = $replyVal->parent_comment_id;

                    $parentUser = getPhotoParentCommentUserData($replyVal->parent_comment_id);



                    $commentReply['parent_username'] = $parentUser->user->firstname . ' ' . $parentUser->user->lastname;

                    $commentReply['user_id'] = $replyVal->user_id;

                    $commentReply['username'] = $replyVal->user->firstname . ' ' . $replyVal->user->lastname;

                    $commentReply['profile'] = (!empty($replyVal->user->profile)) ? asset('public/storage/profile/' . $replyVal->user->profile) : "";

                    $commentReply['reply_comment_total_likes'] = $replyVal->post_photo_comment_reaction_count;

                    $commentReply['is_like'] = checkUserPhotoIsLike($replyVal->id, $user->id);

                    $commentReply['total_replies'] = $totalReply;

                    $commentReply['created_at'] = $replyVal->created_at;

                    $commentInfo[] = $commentReply;
                }
            }

            return response()->json(['status' => 1, 'total_comments_replies' => count($replyList), 'data' => $commentInfo, 'message' => "Comment Reply List"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception $e) {

            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }

    public function getEventsList()
    {


        $user  = Auth::guard('api')->user();

        $eventData = EventInvitedUser::where(['user_id' => $user->id])->get();

        $eventList = [];

        foreach ($eventData as $val) {

            $eventDatas =   Event::select('id', 'event_name')->where('id', $val->event_id)->get();
            foreach ($eventDatas as $vals) {
                $eventDetail['id'] = $vals->id;
                $eventDetail['event_name'] = $vals->event_name;
                $eventList[] = $eventDetail;
            }
        }
        $ownerEvent =    Event::select('id', 'event_name')->where(['user_id' => $user->id, 'is_draft_save' => '0'])->get();

        foreach ($ownerEvent as $ownerEvent) {
            $eventOwnDetail['id'] = $ownerEvent->id;
            $eventOwnDetail['event_name'] = $ownerEvent->event_name;
            $eventList[] = $eventOwnDetail;
        }

        return response()->json(['status' => 1, 'message' => "Event List", 'data' => $eventList]);
    }
    public function notificationList(Request $request)

    {

        $user = Auth::guard('api')->user();


        $rawData = $request->getContent();



        $input = json_decode($rawData, true);

        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }


        $page = $input['page'];

        $pages = ($page != "") ? $page : 1;

        $notificationData = Notification::query();

        $notificationData->with(['user', 'event', 'event.event_settings', 'sender_user', 'post' => function ($query) {
            $query->with(['post_image', 'event_post_poll'])->withcount(['event_post_reaction', 'event_post_comment' => function ($query) {
                $query->where('parent_comment_id', NULL);
            }]);
        }])->orderBy('id', 'DESC')->where(['user_id' => $user->id]);
        // ->where('notification_type', '!=', 'upload_post')->where('notification_type', '!=', 'photos')->where('notification_type', '!=', 'invite')
        if (isset($input['filters']) && !empty($input['filters']) && !in_array('all', $input['filters'])) {

            $selectedEvents = $input['filters']['events'];
            $notificationTypes = $input['filters']['notificationTypes'];
            $activityTypes = $input['filters']['activityTypes'];

            $notificationData->where(function ($query) use ($selectedEvents, $notificationTypes, $activityTypes) {
                // Add conditions based on selected events
                if (!empty($selectedEvents)) {
                    $query->whereIn('event_id', $selectedEvents);
                }

                // Add conditions based on notification types (read, unread)
                if (!empty($notificationTypes) && in_array('read', $notificationTypes)) {
                    $query->orWhere('read', "1");
                }
                if (!empty($notificationTypes) && in_array('unread', $notificationTypes)) {
                    $query->orWhere('read', "0");
                }

                // Add conditions based on activity types
                if (!empty($activityTypes)) {

                    $query->whereIn('notification_type', $activityTypes);
                }
            });
        }
        $notificationDatacount = $notificationData->count();

        $total_page = ceil($notificationDatacount / 10);
        $result = $notificationData->paginate(10, ['*'], 'page', $page);


        $notificationInfo = [];

        foreach ($result as $values) {

            if ($values->user_id == $user->id) {
                $notificationDetail['event_name'] = $values->event->event_name;
                $notificationDetail['notification_id'] = $values->id;
                $notificationDetail['notification_type'] = $values->notification_type;
                $notificationDetail['user_id'] = $values->sender_id;
                $notificationDetail['profile'] = (!empty($values->sender_user->profile) || $values->sender_user->profile != null) ? asset('public/storage/profile/' . $values->sender_user->profile) : "";
                $notificationDetail['email'] = $values->sender_user->email;
                $notificationDetail['first_name'] = $values->sender_user->firstname;
                $notificationDetail['last_name'] = ($values->sender_user->lastname != null) ? $values->sender_user->lastname : "";
                $notificationDetail['event_id'] = ($values->event_id != null) ? $values->event_id : 0;
                $notificationDetail['post_id'] = ($values->post_id != null) ? $values->post_id : 0;
                $notificationDetail['comment_id'] = ($values->comment_id != null) ? $values->comment_id : 0;
                $postCommentDetail =  EventPostComment::where(['id' => $values->comment_id])->first();
                $notificationDetail['comment'] = ($postCommentDetail != null) ? $postCommentDetail->comment_text : "";
                $notificationDetail['video'] = ($postCommentDetail != null && $postCommentDetail->video != null) ? asset('public/storage/comment_video' . $postCommentDetail->video) : "";
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
                    $notificationDetail['video'] = ($values->rsvp_video != null && $values->rsvp_video != null) ? asset('public/storage/rsvp_video/' . $values->rsvp_video) : "";
                }
                if (isset($values->post->post_type) && $values->post->post_type == '1') {
                    if ($values->post->post_image[0]->type == 'video') {
                        $notificationDetail['video'] = asset('public/storage/post_image/' . $values->post->post_image[0]->post_image);
                        $notificationDetail['media_type'] = $values->post->post_image[0]->type;
                    }
                }


                $notificationDetail['total_likes'] = (!empty($values->post->event_post_reaction_count)) ? $values->post->event_post_reaction_count : 0;
                $notificationDetail['total_comments'] = (!empty($values->post->event_post_comment_count)) ? $values->post->event_post_comment_count : 0;
                $postreplyCommentDetail =  EventPostComment::where(['user_id' => $values->sender_id, 'parent_comment_id' => $values->comment_id])->first();
                $notificationDetail['comment_reply'] = ($values->notification_type == 'reply_on_comment_post' && $postreplyCommentDetail != null) ? $postreplyCommentDetail->comment_text : "";
                $notificationDetail['post_image'] = "";
                $notificationDetail['media_type'] = "";

                if (isset($values->post->post_type) && $values->post->post_type == '1') {
                    $notificationDetail['post_image'] = asset('public/storage/post_image/' . $values->post->post_image[0]->post_image);
                    if ($values->post->post_image[0]->type == 'image') {

                        $notificationDetail['media_type'] = 'photo';
                    } elseif ($values->post->post_image[0]->type == 'video') {
                        $notificationDetail['media_type'] = $values->post->post_image[0]->type;
                    }
                }
                $notificationDetail['post_type'] = "";

                if (isset($values->post->post_type)) {

                    $notificationDetail['post_type'] = $values->post->post_type;
                }
                $notificationDetail['post_message'] = (!empty($values->post->post_message)) ? $values->post->post_message : "";
                $notificationDetail['notification_message'] = $values->notification_message;
                $notificationDetail['read'] = $values->read;
                $notificationDetail['post_time'] = $this->setpostTime($values->created_at);
                $notificationDetail['created_at'] = $values->created_at;
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
                    'profile' => empty($values->sender_user->profile) ? "" : asset('public/storage/profile/' . $values->sender_user->profile),
                    'bg_profile' => empty($values->sender_user->bg_profile) ? "" : asset('public/storage/bg_profile/' . $values->sender_user->bg_profile),
                    'gender' => ($values->sender_user->gender != NULL) ? $values->sender_user->gender : "",
                    'username' => $values->sender_user->firstname . ' ' . $values->sender_user->lastname,
                    'location' => ($values->sender_user->city != NULL) ? $values->sender_user->city : "",
                    'about_me' => ($values->sender_user->about_me != NULL) ? $values->sender_user->about_me : "",
                    'created_at' => empty($values->sender_user->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($values->sender_user->created_at))),
                    'total_events' => $totalEvent,

                    'visible' => $values->sender_user->visible,
                    'comments' => $comments,
                ];
                $notificationInfo[] = $notificationDetail;
            }
        }

        $unreadCount = Notification::where(['user_id' => $user->id, 'read' => '0'])->count();
        return response()->json(['status' => 1, 'unread_count' => $unreadCount, 'count' => $notificationDatacount, 'total_page' => $total_page, 'data' => $notificationInfo, 'message' => "Notification list"]);
    }

    public function deleteNotification(Request $request)

    {

        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();



        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [

            'notification_id' => ['required', 'exists:notifications,id'],

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }

        try {
            DB::beginTransaction();
            $deleteNotification = Notification::where(['id' => $input['notification_id']])->first();
            if (!empty($deleteNotification)) {
                $deleteNotification->delete();
                DB::commit();
                $unreadCount = Notification::where(['user_id' => $user->id, 'read' => '0'])->count();
                return response()->json(['status' => 1, 'message' => "Notification deleted successfully", 'unread_count' => $unreadCount]);
            } else {

                return response()->json(['status' => 0, 'message' => "data is incorrect"]);
            }
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {


            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }

    public function notificationReadUnread(Request $request)

    {

        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();



        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        // $validator = Validator::make($input, [

        //     'notification_id' => ['required', 'exists:notifications,id'],

        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => 0,
        //         'message' => $validator->errors()->first(),
        //     ]);
        // }

        try {
            DB::beginTransaction();
            if (isset($input['type']) && $input['type'] == 'all_read') {
                $user  = Auth::guard('api')->user();
                $updateNotification = Notification::where('user_id', $user->id)->update(['read' => '1']);
                DB::commit();
            } else {
                $updateNotification = Notification::where(['id' => $input['notification_id']])->first();
                if (!empty($updateNotification)) {
                    $updateNotification->read = '1';
                    $updateNotification->save();
                    DB::commit();
                } else {

                    return response()->json(['status' => 0, 'message' => "data is incorrect"]);
                }
            }
            $unreadCount = Notification::where(['user_id' => $user->id, 'read' => '0'])->count();
            return response()->json(['status' => 1, 'unread_count' => $unreadCount, 'message' => "Notification read successfully"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {


            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }

    public function notificationAllRead()

    {




        try {


            return response()->json(['status' => 1, 'unread_count' => $unreadCount, 'message' => "Notification read successfully"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {


            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }

    public function logout()

    {

        if (Auth::guard('api')->check()) {

            $patient = Auth::guard('api')->user();

            $check = Device::where('user_id', $patient->id)->first();


            if ($check != null) {
                $check->delete();
                Token::where('user_id', $patient->id)->delete();
            }


            return response()->json(['status' => 1, 'message' => "logout succesfully"]);
        }
    }

    public function getEvent(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();
        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [

            'event_id' => ['required', 'exists:events,id'],

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }
        $getEventData = Event::with(['event_image', 'event_settings', 'user', 'event_schedule'])
            ->where('id', $input['event_id'])
            ->where('is_draft_save', '0')->first();



        $eventDetail['id'] = $getEventData->id;
        $eventDetail['event_name'] = $getEventData->event_name;
        $eventDetail['is_event_owner'] = ($getEventData->user->id == $user->id) ? 1 : 0;
        $eventDetail['user_id'] = $getEventData->user->id;
        $eventDetail['host_profile'] = empty($getEventData->user->profile) ? "" : asset('public/storage/profile/' . $getEventData->user->profile);
        $eventDetail['message_to_guests'] = $getEventData->message_to_guests;
        $eventDetail['event_wall'] = $getEventData->event_settings->event_wall;
        $eventDetail["guest_list_visible_to_guests"] = $getEventData->event_settings->guest_list_visible_to_guests;
        $eventDetail['host_name'] = $getEventData->hosted_by;

        $eventDetail['kids'] = 0;
        $eventDetail['adults'] = 0;

        $checkRsvpDone = EventInvitedUser::where(['event_id' => $getEventData->id, 'user_id' => $user->id])->first();
        if ($checkRsvpDone != null) {
            $eventDetail['kids'] = $checkRsvpDone->kids;
            $eventDetail['adults'] = $checkRsvpDone->adults;
        }


        $images = EventImage::where('event_id', $getEventData->id)->first();

        $eventDetail['event_images'] = ($images != null) ? asset('public/storage/event_images/' . $images->image) : "";

        $eventDetail['event_date'] = $getEventData->start_date;


        $event_time = "-";
        if ($getEventData->event_schedule->isNotEmpty()) {

            $event_time =  $getEventData->event_schedule->first()->start_time;
        }

        $eventDetail['start_time'] =  $getEventData->rsvp_start_time;



        $eventDetail['rsvp_start_timezone'] = $getEventData->rsvp_start_timezone;


        $rsvp_status = "";



        if ($getEventData->rsvp_end_time != "" || $getEventData->rsvp_end_time != NULL) {

            $checkUserrsvp = EventInvitedUser::whereHas('user', function ($query) {

                $query->where('app_user', '1');
            })->where(['user_id' => $user->id, 'event_id' => $getEventData->id])->first();
            if ($checkUserrsvp != null) {
                if ($checkUserrsvp->rsvp_status == '1') {

                    $rsvp_status = '1'; // rsvp you'r going

                } else if ($checkUserrsvp->rsvp_status == '0') {
                    $rsvp_status = '2'; // rsvp you'r not going
                }
                if ($checkUserrsvp->rsvp_status == '0') {

                    if ($getEventData->rsvp_start_time <= strtotime(env('DATE')) && strtotime(env('DATE')) <= $getEventData->rsvp_end_time) {
                        $rsvp_status = '0'; // rsvp button//
                    }
                }
            }
        } else {

            $startEventTime = $getEventData->start_date;

            $oneDayBefore = date('Y-m-d', strtotime('-1 day', strtotime($startEventTime)));

            $svrp_end_time = strtotime($oneDayBefore . ' 12:00:00');

            $checkUserrsvp = EventInvitedUser::whereHas('user', function ($query) {

                $query->where('app_user', '1');
            })->where(['user_id' => $user->id, 'event_id' => $getEventData->id])->first();
            if ($checkUserrsvp != null) {
                if ($checkUserrsvp->rsvp_status == '1') {

                    $rsvp_status = '1'; // rsvp you'r going

                } else if ($checkUserrsvp->rsvp_status == '0' && $checkUserrsvp->rsvp_d == '1') {

                    $rsvp_status = '2'; // rsvp you'r not going

                }

                if ($checkUserrsvp->rsvp_status == '0') {

                    if ($getEventData->rsvp_start_time <= strtotime(env('DATE')) && strtotime(env('DATE')) <= $svrp_end_time) {

                        $rsvp_status = '0'; // rsvp button//

                    }
                }
            }
        }

        $eventDetail['rsvp_status'] = $rsvp_status;

        $total_accept_event_user = EventInvitedUser::whereHas('user', function ($query) {

            $query->where('app_user', '1');
        })->where(['event_id' => $getEventData->id, 'rsvp_status' => '1', 'rsvp_d' => '1'])->count();

        $eventDetail['total_accept_event_user'] = $total_accept_event_user;





        $total_invited_user = EventInvitedUser::whereHas('user', function ($query) {

            $query->where('app_user', '1');
        })->where(['event_id' => $getEventData->id])->count();



        $eventDetail['total_invited_user'] = $total_invited_user;



        $total_refuse_event_user = EventInvitedUser::whereHas('user', function ($query) {

            $query->where('app_user', '1');
        })->where(['event_id' => $getEventData->id, 'rsvp_status' => '0', 'rsvp_d' => '1'])->count();

        $eventDetail['total_refuse_event_user'] = $total_refuse_event_user;

        $total_notification = Notification::where(['event_id' => $getEventData->id, 'user_id' => $user->id, 'read' => '0'])->count();

        $eventDetail['total_notification'] = $total_notification;
        $eventDetail['event_detail'] = [];
        if ($getEventData->event_settings) {
            $eventData = [];

            if ($getEventData->event_settings->allow_for_1_more == '1') {
                $eventData[] = "Can Bring Guests ( limit " . $getEventData->event_settings->allow_limit . ")";
            }
            if ($getEventData->event_settings->adult_only_party == '1') {
                $eventData[] = "Adults Only";
            }
            if ($getEventData->rsvp_by_date_set == '1') {
                $eventData[] = date('F d, Y', strtotime($getEventData->rsvp_by_date));
            }
            if ($getEventData->event_settings->podluck == '1') {
                $eventData[] = "Event Potluck";
            }
            $eventDetail['event_detail'] = $eventData;
        }
        $totalEvent =  Event::where('user_id', $getEventData->user->id)->count();
        $totalEventPhotos =  EventPost::where(['user_id' => $getEventData->user->id, 'post_type' => '1'])->count();
        $comments =  EventPostComment::where('user_id', $getEventData->user->id)->count();

        $eventDetail['user_profile'] = [
            'id' => $getEventData->user->id,
            'profile' => empty($getEventData->user->profile) ? "" : asset('public/storage/profile/' . $getEventData->user->profile),
            'bg_profile' => empty($getEventData->user->bg_profile) ? "" : asset('public/storage/bg_profile/' . $getEventData->user->bg_profile),
            'gender' => ($getEventData->user->gender != NULL) ? $getEventData->user->gender : "",
            'username' => $getEventData->user->firstname . ' ' . $getEventData->user->lastname,
            'location' => ($getEventData->user->city != NULL) ? $getEventData->user->city : "",
            'about_me' => ($getEventData->user->about_me != NULL) ? $getEventData->user->about_me : "",
            'created_at' => empty($getEventData->user->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($getEventData->user->created_at))),
            'total_events' => $totalEvent,
            'total_photos' => $totalEventPhotos,
            'comments' => $comments,
        ];

        return response()->json(['status' => 1, 'message' => "Event Data", 'data' => $eventDetail]);
    }

    public function setpostTime($dateTime)
    {

        $commentDateTime = $dateTime; // Replace this with your actual timestamp

        // Convert the timestamp to a Carbon instance
        $commentTime = Carbon::parse($commentDateTime);

        // Calculate the time difference
        $timeAgo = $commentTime->diffForHumans(); // This will give the time ago format


        // Display the time ago
        return $timeAgo;
    }

    public function myAccount(Request $request)
    {

        $user  = Auth::guard('api')->user();

        $userAccount = [];
        $ownAccount = [
            'id' => $user->id,
            'profile' => (isset($user->profile) && $user->profile != null) ? asset('public/storage/profile/' . $user->profile) : "",
            'first_name' => $user->firstname,
            'last_name' => $user->lastname,
            'email' => $user->email
        ];

        $userAccount[] = $ownAccount;

        return response()->json(['status' => 1, 'message' => "Accounts", 'data' => $userAccount]);
    }

    public function regenarateToken(Request $request)
    {

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);

        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [
            "user_id" => ['required']
        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 0,
                    'message' => $validator->errors()->first()
                ],
            );
        }

        $user = User::where('id', $input['user_id'])->first();

        if ($user != null) {


            if ($user->email_verified_at != NULL) {

                $loggedUser  = Auth::guard('api')->user();
                $alreadyLog = User::select('id', 'firstname as first_name', 'lastname as last_name', 'email', 'profile')->where('id', $loggedUser->id)->first();

                $alreadyLog['profile'] = ($alreadyLog->profile != null) ? asset('public/storage/profile/' . $alreadyLog->profile) : "";

                // device  add//
                if ($user->status == '9') {
                    return response()->json(['status' => 0, 'message' => 'Account deleted']);
                }



                $updateUserId = Device::where('user_id', $loggedUser->user_id)->first();
                if ($updateUserId != null) {
                    $updateUserId->user_id = $user->id;
                    $updateUserId->save();
                }

                // device  add//
                $token = Token::where('user_id', $user->id)->first();

                if ($token) {
                    $token->delete();
                }

                $token = $user->createToken('API Token')->accessToken;
                $detail = [
                    'firstname' => $user->firstname,
                    'lastname' => $user->lastname,
                    'email' => $user->email,
                    'account_type' => $user->account_type,
                    'is_first_login' => $user->is_first_login,
                    'is_already_log' => $alreadyLog
                ];


                logoutFromWeb($user->id);
                return response()->json(['status' => 1, 'data' => $detail, 'token' => $token]);
            } else {

                $users = User::where('id', $user->id)->first();
                $randomString = Str::random(30);
                $users->remember_token = $randomString;
                $users->save();

                $userDetails = User::where('id', $user->id)->first();

                $userData = [
                    'username' => $userDetails->firstname . ' ' . $userDetails->lastname,
                    'email' => $userDetails->email,
                    'token' => $randomString,
                    'is_first_login' => $userDetails->is_first_login
                ];
                Mail::send('emails.emailVerificationEmail', ['userData' => $userData], function ($message) use ($input) {
                    $message->to($input['email']);
                    $message->subject('Email Verification Mail');
                });

                return response()->json(['status' => 0, 'message' => 'Please check and verify your email address.']);
            }
        } else {
            return response()->json(['status' => 0, 'message' => 'Email or password invalid']);
        }
    }

    public function installAndroidApp()
    {
        $versionSetting =  VersionSetting::first();

        return response()->json(["status" => true, 'message' => 'Application', 'url' => asset('public/appversion/yesvite_android.apk'), 'version' => $versionSetting->android_version]);
    }
    public function installIosApp()
    {
        $versionSetting =  VersionSetting::first();

        return response()->json(["status" => true, 'message' => 'Application', 'url' => asset('public/appversion/yesvite_ios.apk'), 'version' => $versionSetting->ios_version]);
    }

    public function uploadApplication(Request $request)
    {
        try {



            if (!empty($request->application)) {



                if (file_exists(public_path('appversion/yesvite_android.apk'))) {
                    $imagePath = public_path('appversion/yesvite_android.apk');
                    unlink($imagePath);
                }



                $image = $request->application;

                $imageName = 'yesvite_android.apk';


                $uploaded = $image->move(public_path('appversion'), $imageName);
            }

            $versionSetting = VersionSetting::first();
            if ($versionSetting != NULL) {
                $versionSetting->android_version = $request->android_version;
                $versionSetting->android_in_force = $request->android_in_force;
                $versionSetting->ios_version = $request->ios_version;
                $versionSetting->ios_in_force = $request->ios_in_force;
                $versionSetting->save();
            } else {
                $newVersionSetting = new VersionSetting();
                $newVersionSetting->android_version = $request->android_version;
                $newVersionSetting->android_in_force = $request->android_in_force;
                $newVersionSetting->ios_version = $request->ios_version;
                $newVersionSetting->ios_in_force = $request->ios_in_force;
                $newVersionSetting->save();
            }

            return response()->json(['status' => 1, 'message' => "version changed succesfully"]);
        } catch (QueryException $e) {
            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }

    public function setUserEventCreateStep(Request $request)
    {

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);

        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [
            "step" => ['required', 'in:1,2,3,4'],
            "event_id" => ['required', 'exists:events,id']
        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 0,
                    'message' => $validator->errors()->first()
                ],
            );
        }

        try {
            $updateStepOfEvent = Event::where('id', $input['event_id'])->first();
            $updateStepOfEvent->step = $input['step'];
            $updateStepOfEvent->save();
            return response()->json(['status' => 1, 'message' => "step updated"]);
        } catch (QueryException $e) {
            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }



    public function addSubscription(Request $request)
    {

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);

        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }



        $validator = Validator::make($input, [

            'orderId' => 'required',
            'packageName' => 'required',
            'productId' => 'required',
            'purchaseTime' => 'required',
            'purchaseToken' => 'required|string',
            'autoRenewing' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 0,
                    'message' => $validator->errors()->first()
                ],
            );
        }


        try {
            $app_id = $input['packageName'];
            $product_id = $input['productId'];
            $user_id = $this->user->id;
            $purchaseToken = $input['purchaseToken'];

            $responce =  $this->set_android_iap($app_id, $product_id, $purchaseToken, 'subscribe');

            if (isset($responce['autoRenewing']) && ($responce['autoRenewing'] == false || $responce['autoRenewing'] == "")) {

                $exp_date =  date('Y-m-d H:i:s', ($responce['expiryTimeMillis'] /  1000));

                $current_date = date('Y-m-d H:i:s');
                if (strtotime($current_date) > strtotime($exp_date)) {


                    return response()->json(['status' => 0, 'message' => "subscription package expired"]);
                }
            }

            $enddate = date('Y-m-d H:i:s', ($responce['expiryTimeMillis'] / 1000));



            $new_subscription = new UserSubscription();
            $new_subscription->user_id = $user_id;
            $new_subscription->orderId = $input['orderId'];
            $new_subscription->packageName = $input['packageName'];
            $new_subscription->priceCurrencyCode = $responce['priceCurrencyCode'];
            $new_subscription->price = $responce['priceAmountMicros'];
            $new_subscription->countryCode = $responce['countryCode'];
            $new_subscription->startDate = now();
            $new_subscription->endDate = $enddate;
            $new_subscription->productId = $input['productId'];
            $new_subscription->type = 'subscribe';
            $new_subscription->purchaseToken = $input['purchaseToken'];
            $new_subscription->save();


            return response()->json(['status' => 1, 'message' => "subscription sucessfully"]);
        } catch (QueryException $e) {
            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }



    public function addProductSubscription(Request $request)
    {

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);

        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }



        $validator = Validator::make($input, [

            'orderId' => 'required',
            'packageName' => 'required',
            'productId' => 'required',
            'purchaseTime' => 'required',
            'purchaseToken' => 'required|string',
        ]);


        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 0,
                    'message' => $validator->errors()->first()
                ],
            );
        }


        try {
            $app_id = $input['packageName'];
            $product_id = $input['productId'];
            $user_id = $this->user->id;
            $purchaseToken = $input['purchaseToken'];

            $responce =  $this->set_android_iap($app_id, $product_id, $purchaseToken, 'product');



            $startDate = date('Y-m-d H:i:s', ($responce['purchaseTimeMillis'] / 1000));


            $new_subscription = new UserSubscription();
            $new_subscription->user_id = $user_id;
            $new_subscription->orderId = $input['orderId'];
            $new_subscription->packageName = $input['packageName'];
            $new_subscription->countryCode = $responce['regionCode'];
            $new_subscription->startDate = $startDate;

            $new_subscription->productId = $input['productId'];
            $new_subscription->type = 'product';
            $new_subscription->purchaseToken = $input['purchaseToken'];
            $new_subscription->save();

            return response()->json(['status' => 1, 'message' => "purchase sucessfully"]);
        } catch (QueryException $e) {
            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }
    public function checkSubscription()
    {

        $userSubscription = UserSubscription::where('user_id', $this->user->id)->orderBy('id', 'DESC')->limit(1)->first();
        if ($userSubscription != null) {
            $app_id = $userSubscription->packageName;
            $product_id = $userSubscription->productId;
            $purchaseToken = $userSubscription->purchaseToken;

            $responce =  $this->set_android_iap($app_id, $product_id, $purchaseToken, 'subscribe');


            $exp_date =  date('Y-m-d H:i:s', ($responce['expiryTimeMillis'] /  1000));


            $current_date = date('Y-m-d H:i:s');

            if (strtotime($current_date) > strtotime($exp_date)) {

                $userSubscription->endDate = $exp_date;
                $userSubscription->save();
                return response()->json(['status' => 0, 'message' => "subscription is not active", 'type' => 'Free']);
            }
            if (isset($responce['userCancellationTimeMillis'])) {

                $cancellationdate =  date('Y-m-d H:i:s', ($responce['userCancellationTimeMillis'] /  1000));
                $userSubscription->cancellationdate = $cancellationdate;
                $userSubscription->save();
                return response()->json(['status' => 0, 'message' => "subscription is not active", 'type' => 'Free']);
            }
            return response()->json(['status' => 1, 'message' => "subscription is active", 'type' => 'Pro-Year']);
        }
        return response()->json(['status' => 0, 'message' => "No subscribe", 'type' => 'Free']);
    }
    public function set_android_iap($appid, $productID, $purchaseToken, $type)
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

    public function getSingleEvent(Request $request)
    {
        $user  = Auth::guard('api')->user();
        $event_id = $request->input('event_id');

        $usercreatedList = Event::with(['user', 'event_settings', 'event_schedule'])->where('start_date', '>', date('Y-m-d'))

            ->where('user_id', $user->id)
            ->where('is_draft_save', '0')
            ->orderBy('start_date', 'ASC')
            ->where('id', $event_id)
            ->get();


        $eventList = [];

        if ($usercreatedList) {
            foreach ($usercreatedList as $value) {
                $eventDetail['id'] = $value->id;

                $eventDetail['event_name'] = $value->event_name;
                $eventDetail['is_event_owner'] = ($value->user->id == $user->id) ? 1 : 0;
                $isCoHost =     EventInvitedUser::where(['event_id' => $value->id, 'user_id' => $user->id])->first();
                $eventDetail['is_notification_on_off']  = "";
                if ($value->user->id == $user->id) {

                    $eventDetail['is_notification_on_off'] =  $value->notification_on_off;
                } else {


                    $eventDetail['is_notification_on_off'] =  $isCoHost->notification_on_off;
                }
                $eventDetail['is_co_host'] = "0";
                if ($isCoHost != null) {
                    $eventDetail['is_co_host'] = $isCoHost->is_co_host;
                }
                $eventDetail['message_to_guests'] = $value->message_to_guests;
                $eventDetail['event_wall'] = $value->event_settings->event_wall;
                $eventDetail['guest_list_visible_to_guests'] = $value->event_settings->guest_list_visible_to_guests;
                $eventDetail['event_potluck'] = $value->event_settings->podluck;


                $eventDetail['guest_pending_count'] = getGuestRsvpPendingCount($value->id);
                $eventDetail['adult_only_party'] = $value->event_settings->adult_only_party;
                $eventDetail['post_time'] =  $this->setpostTime($value->updated_at);


                $rsvp_status = "";
                $checkUserrsvp = EventInvitedUser::whereHas('user', function ($query) {

                    $query->where('app_user', '1');
                })->where(['user_id' => $user->id, 'event_id' => $value->id])->first();

                // if ($value->rsvp_by_date >= date('Y-m-d')) {

                $rsvp_status = "";

                if ($checkUserrsvp != null) {
                    if ($checkUserrsvp->rsvp_status == '1') {

                        $rsvp_status = '1'; // rsvp you'r going

                    } else if ($checkUserrsvp->rsvp_status == '0') {
                        $rsvp_status = '2'; // rsvp you'r not going
                    }
                    if ($checkUserrsvp->rsvp_status == NULL) {

                        $rsvp_status = '0'; // rsvp button//

                    }
                }
                // }


                $eventDetail['rsvp_status'] = $rsvp_status;

                $eventDetail['user_id'] = $value->user->id;

                $eventDetail['host_profile'] = empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                $eventDetail['host_name'] = $value->hosted_by;

                $eventDetail['kids'] = 0;
                $eventDetail['adults'] = 0;

                $checkRsvpDone = EventInvitedUser::where(['event_id' => $value->id, 'user_id' => $user->id])->first();
                if ($checkRsvpDone != null) {
                    $eventDetail['kids'] = $checkRsvpDone->kids;
                    $eventDetail['adults'] = $checkRsvpDone->adults;
                }

                $images = EventImage::where('event_id', $value->id)->first();



                $eventDetail['event_images'] = ($images != null) ? asset('public/storage/event_images/' . $images->image) : "";



                $eventDetail['event_date'] = $value->start_date;


                $event_time = "-";
                if ($value->event_schedule->isNotEmpty()) {

                    $event_time =  $value->event_schedule->first()->start_time;
                }

                $eventDetail['start_time'] =  $value->rsvp_start_time;

                $eventDetail['rsvp_start_timezone'] = $value->rsvp_start_timezone;


                $total_accept_event_user = EventInvitedUser::where(['event_id' => $value->id, 'rsvp_status' => '1'])->count();

                $eventDetail['total_accept_event_user'] = $total_accept_event_user;



                $total_invited_user = EventInvitedUser::whereHas('user', function ($query) {

                    $query->where('app_user', '1');
                })->where(['event_id' => $value->id])->count();

                $eventDetail['total_invited_user'] = $total_invited_user;


                $total_refuse_event_user = EventInvitedUser::where(['event_id' => $value->id, 'rsvp_status' => '0'])->count();

                $eventDetail['total_refuse_event_user'] = $total_refuse_event_user;



                $total_notification = Notification::where(['event_id' => $value->id, 'user_id' => $user->id, 'read' => '0'])->count();

                $eventDetail['total_notification'] = $total_notification;
                $eventDetail['event_detail'] = [];
                if ($value->event_settings) {
                    $eventData = [];

                    if ($value->event_settings->allow_for_1_more == '1') {
                        $eventData[] = "Can Bring Guests ( limit " . $value->event_settings->allow_limit . ")";
                    }
                    if ($value->event_settings->adult_only_party == '1') {
                        $eventData[] = "Adults Only";
                    }
                    if ($value->rsvp_by_date_set == '1') {
                        $eventData[] = date('F d, Y', strtotime($value->rsvp_by_date));
                    }
                    if ($value->event_settings->podluck == '1') {
                        $eventData[] = "Event Potluck";
                    }
                    if ($value->event_settings->gift_registry == '1') {
                        $eventData[] = "Gift Registry";
                    }
                    if (empty($eventData)) {
                        $eventData[] = date('F d, Y', strtotime($value->start_date));
                        $numberOfGuest = EventInvitedUser::where('event_id', $value->id)->count();
                        $eventData[] = "Number of guests : " . $numberOfGuest;
                    }
                    $eventDetail['event_detail'] = $eventData;
                }
                $eventDetail['allow_limit'] = $value->event_settings->allow_limit;
                $totalEvent =  Event::where('user_id', $value->user->id)->count();
                $totalEventPhotos =  EventPost::where(['user_id' => $value->user->id, 'post_type' => '1'])->count();
                $comments =  EventPostComment::where('user_id', $value->user->id)->count();

                $eventDetail['user_profile'] = [
                    'id' => $value->user->id,
                    'profile' => empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile),
                    'bg_profile' => empty($value->user->bg_profile) ? "" : asset('public/storage/bg_profile/' . $value->user->bg_profile),
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

                $eventList[] = $eventDetail;
            }

            return response()->json(['status' => 1, 'data' => $eventList, 'message' => "Event Data"]);
        } else {

            return response()->json(['status' => 0, 'data' => $eventList, 'message' => "No upcoming event found"]);
        }
    }

    public function getYesviteSelectedUserListPage(Request $request)
    {

        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        try {
            $page = (isset($input['page']) || $input['page'] != "") ? $input['page'] : "1";
            // $search_name = (isset($input['search_name']) || $input['search_name'] != "") ? $input['search_name'] : "";
            $search_name = '';
            $user  = Auth::guard('api')->user();
            $groupList = getGroupList($user->id);
            $event_id = (int)$input['event_id'];
            $invitedUser = EventInvitedUser::with(['user' => function ($query) {
                $query->where('is_user_phone_contact', '0');
            }])
                ->where(['event_id' => $event_id])
                ->when($input['type'] == 'guest', function ($qu) {
                    return  $qu->where('is_co_host', '0');
                })
                ->when($input['type'] == 'co-host', function ($qu) {
                    return  $qu->where('is_co_host', '1');
                })
                ->paginate('10', ['*'], 'page', $page);
            $yesviteUser = [];
            foreach ($invitedUser as $guestVal) {
                // dd($guestVal->user);
                $yesviteUserDetail['id'] = $guestVal->user->id;
                $yesviteUserDetail['profile'] = empty($guestVal->user->profile) ? "" : asset('storage/profile/' . $guestVal->user->profile);
                $yesviteUserDetail['first_name'] = (!empty($guestVal->user->firstname) || $guestVal->user->firstname != Null) ? $guestVal->user->firstname : "";;
                $yesviteUserDetail['last_name'] = (!empty($guestVal->user->lastname) || $guestVal->user->lastname != Null) ? $guestVal->user->lastname : "";
                $yesviteUserDetail['email'] = (!empty($guestVal->user->email) || $guestVal->user->email != Null) ? $guestVal->user->email : "";
                $yesviteUserDetail['country_code'] = (!empty($guestVal->user->country_code) || $guestVal->user->country_code != Null) ? strval($guestVal->user->country_code) : "";
                $yesviteUserDetail['phone_number'] = (!empty($guestVal->user->phone_number) || $guestVal->user->phone_number != Null) ? $guestVal->user->phone_number : "";
                $yesviteUserDetail['app_user']  = $guestVal->user->app_user;
                $yesviteUserDetail['visible'] =  $guestVal->user->visible;
                $yesviteUserDetail['message_privacy'] =  $guestVal->user->message_privacy;
                $yesviteUserDetail['prefer_by']  = $guestVal->user->prefer_by;
                $yesviteUser[] = $yesviteUserDetail;
            }
            // dd($yesviteUser);
            // $yesvitecontactList = getYesviteSelectedUserPage($user->id, "10", $page, $event_id);
            $yesviteRegisteredUser =  EventInvitedUser::with(['user' => function ($query) {
                $query->where('is_user_phone_contact', '0');
            }])
                ->where(['event_id' => $event_id])
                ->when($input['type'] == 'guest', function ($qu) {
                    return  $qu->where('is_co_host', '0');
                })
                ->when($input['type'] == 'co-host', function ($qu) {
                    return  $qu->where('is_co_host', '1');
                })
                ->count();
            $total_page = ceil($yesviteRegisteredUser / 10);
            return response()->json(['status' => 1, 'message' => "Yesvite contact list", 'total_page' => $total_page, "total_count" => $yesviteRegisteredUser, "data" => $yesviteUser]);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }
    public function appInviteLink(Request $request)
    {
        $rawData = $request->getContent();
        $input = json_decode($rawData, true);

        $userdata = ['send_by' => $input['send_by']];

        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [
            'email' => ['required', 'email'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        Mail::send('emails.app_inivite_link', ['userdata' => $userdata], function ($message) use ($input) {
            $message->to($input['email']);
            $message->subject('Yesvite Invite');
        });

        return response()->json(['status' => 1, 'message' => 'Mail sent successfully']);
    }
}
