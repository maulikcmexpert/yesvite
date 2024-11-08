<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\User;

use App\Models\{
    User,
    EventPost,
    EventPostComment,
    Notification,
    EventInvitedUser,
    Event,
    EventImage,
    UserNotificationType,
    UserProfilePrivacy
};
use Illuminate\Support\Facades\Session;
use App\Services\CSVImportService;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as Exception;
use Carbon\Carbon;

class HomeController extends Controller
{

    protected $perPage;
    // protected  $upcomingEventCount;
    // protected $user;
    // protected $pendingRsvpCount;
    // protected $hostingCount;
    // protected $invitedToCount;

    public function __construct()
    {
     
        $this->perPage = 5;
        
    }
    public function index()
    {

        $title = 'Home';
        $page = 'front.home';
        return view('layout', compact(
            'title',
            'page',
        ));
    }


    public function importCSV(Request $request, CSVImportService $importService)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|mimes:csv,txt|max:2048', // Validate file type and size
        ]);

        if ($validator->fails()) {
            // Validation failed
            $errors = $validator->errors()->first();
            // Handle the validation errors, log them, or return a response
            return  redirect()->route('profile')->with('error', $errors);
        }

        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');
            $filePath =  $file->move(public_path('temp'),  $file->getClientOriginalName());
        }
        $filePath = public_path('temp/' . $file->getClientOriginalName()); // Adjust path to your CSV file
        $importService->import($filePath);

        return  redirect()->route('profile')->with('success', 'Contact imported successfully.');
    }


    public function setpostTime($dateTime)
    {
        $commentDateTime = $dateTime; 
        $commentTime = Carbon::parse($commentDateTime);
        $timeAgo = $commentTime->diffForHumans(); 
        return $timeAgo;
    }

    function upcomingEventsCount($userId)
    {
        $usercreatedList = Event::with(['user', 'event_settings', 'event_schedule'])->where('start_date', '>', date('Y-m-d'))
    
            ->where('user_id', $userId)
            ->where('is_draft_save', '0')
            ->orderBy('start_date', 'ASC')
    
            ->get();
    
        $invitedEvents = EventInvitedUser::whereHas('user', function ($query) {
    
            $query->where('app_user', '1');
        })->where('user_id', $userId)->get()->pluck('event_id');
    
    
    
        $invitedEventsList = Event::with(['event_image', 'user', 'event_settings', 'event_schedule'])
    
            ->whereIn('id', $invitedEvents)->where('start_date', '>', date('Y-m-d'))
            ->where('is_draft_save', '0')
            ->orderBy('start_date', 'ASC')
            ->get();
    
        $allEvents = $usercreatedList->merge($invitedEventsList)->sortBy('start_date');
    
        return count($allEvents);
    }
    
    function pendingRsvpCount($userId)
    {
        dd(1);
        $total_need_rsvp_event_count = EventInvitedUser::whereHas('event', function ($query) {
            $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'));
        })->where(['user_id' => $userId, 'rsvp_status' => NULL])->count();
    
        $PendingRsvpEventId = "";
        if ($total_need_rsvp_event_count == 1) {
            $res = EventInvitedUser::select('event_id')->whereHas('event', function ($query) {
                $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'));
            })->where(['user_id' => $userId, 'rsvp_status' => NULL])->first();
            $PendingRsvpEventId = $res->event_id;
        }
        return compact('total_need_rsvp_event_count', 'PendingRsvpEventId');
    }
    
    function hostingCount($userId)
    {
    
        $totalHosting = Event::where(['is_draft_save' => '0', 'user_id' => $userId])->where('start_date', '>=', date('Y-m-d'))->count();
        return $totalHosting;
    }
    
    function invitedToCount($userId)
    {
    
        $totalInvited = EventInvitedUser::whereHas('event', function ($query) {
            $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'));
        })->where('user_id', $userId)->count();
        return $totalInvited;
    }
    public function home()
    {
        // dd(1);
        $page='1';
        try {
            $user  = Auth::guard('web')->user();
            if ($user->is_first_login == '1') {
                $userIsLogin = User::where('id', $user->id)->first();
                $userIsLogin->is_first_login = '0';
                $userIsLogin->save();
            }
            $totalEvent =  Event::where(['user_id' => $user->id, 'is_draft_save' => '0'])->count();
            $totalEventOfYear = Event::where([
                'user_id' => $user->id, 
                'is_draft_save' => '0'
            ])
            ->whereYear('start_date', date('Y')) 
            ->count();

            $totalEventOfCurrentMonth = Event::where([
                'user_id' => $user->id, 
                'is_draft_save' => '0'
            ])
            ->whereYear('start_date', date('Y')) // Filter by current year
            ->whereMonth('start_date', date('m')) // Filter by current month
            ->count();

        // dd($totalEventOfYear);
            $totalDraftEvent =  Event::where(['user_id' => $user->id, 'is_draft_save' => '1'])->count();
            $totalEventPhotos = EventPost::where(['user_id' => $user->id, 'post_type' => '1'])->count();
            $postComments =  EventPostComment::where('user_id', $user->id)->count();
            $getUserPrivacyPolicy = UserProfilePrivacy::select('profile_privacy', 'status')->where('user_id', $user->id)->get();
            $checkNotificationSetting =  UserNotificationType::where(['user_id' => $user->id, 'type' => 'private_message'])->first();
            // dd($checkNotificationSetting);
            $upcomingEventCount = upcomingEventsCount($user->id);
            $pendingRsvpCount = pendingRsvpCount($user->id);
            $hostingCount = hostingCount($user->id);
            $hostingCountCurrentMonth = hostingCountCurrentMonth($user->id);
            $invitedToCount = invitedToCount($user->id);
            $invitedToCountCurrentMonth= invitedToCountCurrentMonth($user->id);
            if (!empty($user)) {
                $profileData = [
                    'id' =>  empty($user->id) ? "" : $user->id,
                    'profile' =>  empty($user->profile) ?  "" : asset('storage/profile/' . $user->profile),
                    'bg_profile' =>  empty($user->bg_profile) ? "" : asset('storage/bg_profile/' . $user->bg_profile),
                    'firstname' => empty($user->firstname) ? "" : $user->firstname,
                    'lastname' => empty($user->lastname) ? "" : $user->lastname,
                    'created_at' => empty($user->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($user->created_at))),
                    'total_events' => $totalEvent,
                    'total_events_of_year' => $totalEventOfYear,
                    'total_events_of_current_month' => $totalEventOfCurrentMonth,
                    'total_photos' => $totalEventPhotos,
                    'comments' => $postComments,
                    'pending_rsvp_count' =>  $pendingRsvpCount['total_need_rsvp_event_count'],
                    'Pending_rsvp_event_id' => $pendingRsvpCount['PendingRsvpEventId'],
                    'total_upcoming_events' => $upcomingEventCount,
                    'invitedTo_count' => $invitedToCount,
                    'invitedTo_count_current_month' => $invitedToCountCurrentMonth,
                    'hosting_count' => $hostingCount,
                    'hosting_count_current_month'=>$hostingCountCurrentMonth,
                    'total_draft_events' => $totalDraftEvent,
                    'total_notification' => Notification::where(['user_id' => $user->id, 'read' => '0'])->count(),

                    // 'is_message_notification' => ($checkNotificationSetting->push != "" && isset($checkNotificationSetting->push)) ? $checkNotificationSetting->push : ""
                    // 'birth_date' => empty($user->birth_date) ? "" : $user->birth_date,
                    // 'email' => empty($user->email) ? "" : $user->email,
                    // 'about_me' => empty($user->about_me) ? "" : $user->about_me,
                    // // 'created_at' => empty($user->created_at) ? "" :   date('F Y', strtotime($user->created_at)),
                    // 'gender' => empty($user->gender) ? "" : $user->gender,
                    // 'country_code' => empty($user->country_code) ? "" : strval($user->country_code),
                    // 'phone_number' => empty($user->phone_number) ? "" : $user->phone_number,
                    // 'visible' =>  $user->visible,
                    // 'message_privacy' =>  $user->message_privacy,
                    // 'photo_via_wifi' =>  $user->photo_via_wifi,
                    // 'enable_face_id_login' =>  $user->enable_face_id_login,
                    // 'profile_privacy' =>  $getUserPrivacyPolicy,
                    // 'account_type' =>  $user->account_type,
                    // 'company_name' => empty($user->company_name) ? "" : $user->company_name,
                    // 'address' => empty($user->address) ? "" : $user->address,
                    // 'address_2' => empty($user->address_2) ? "" : $user->address_2,
                    // 'city' => empty($user->city) ? "" : $user->city,
                    // 'state' => empty($user->state) ? "" : $user->state,
                    // 'zip_code' => empty($user->zip_code) ? "" : $user->zip_code,
                    // 'password_updated_date' => empty($user->password_updated_date) ? "" : $user->password_updated_date,
                ];
            }
            dd($profileData);

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
            // $page = $request->input('page');
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
                    $eventDetail['host_profile'] = empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile);
                    $eventDetail['host_name'] = $value->hosted_by;
                    $eventDetail['kids'] = 0;
                    $eventDetail['adults'] = 0;
                    $checkRsvpDone = EventInvitedUser::where(['event_id' => $value->id, 'user_id' => $user->id, 'rsvp_status' => '1'])->first();
                    if ($checkRsvpDone != null) {
                        $eventDetail['kids'] = $checkRsvpDone->kids;
                        $eventDetail['adults'] = $checkRsvpDone->adults;
                    }
                    $images = EventImage::where('event_id', $value->id)->first();
                    $eventDetail['event_images'] = ($images != null) ? asset('storage/event_images/' . $images->image) : "";
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
                    $eventDetail['event_plan_name'] = $value->subscription_plan_name;
                    $eventList[] = $eventDetail;
                }


                $draftEvents = Event::where(['user_id' => $user->id, 'is_draft_save' => '1'])->orderBy('id', 'DESC')->get();
                $draftEventArray = [];
                if (!empty($draftEvents) && count($draftEvents) != 0) {

                    foreach ($draftEvents as $value) {
                        $eventdraft['id'] = $value->id;
                        $eventdraft['event_name'] = $value->event_name;
                        $formattedDate = Carbon::createFromFormat('Y-m-d H:i:s', $value->updated_at)->format('F j, Y');
                        $eventdraft['saved_date'] = $formattedDate;
                        $eventdraft['step'] = ($value->step != NULL) ? $value->step : 0;
                        $draftEventArray[] = $eventdraft;
                    }
                }

                // return response()->json(['status' => 1, 'count' => count($allEvents), 'total_page' => $total_page, 'data' => $eventList, 'message' => "Events Data"]);
            } else {
                // return response()->json(['status' => 0, 'data' => $eventList, 'message' => "No upcoming events found"]);
            }

        } catch (QueryException $e) {
            return response()->json(['status' => 0, 'message' => "Db error"]);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'Something went wrong']);
        }
    }
}
