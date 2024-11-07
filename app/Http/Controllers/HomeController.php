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
    EventImage
};
use Illuminate\Support\Facades\Session;
use App\Services\CSVImportService;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as Exception;
class HomeController extends Controller
{

    protected $perPage;
    protected $user;

    public function __construct()
    {
        $this->user = Auth::guard('web')->user();

        // $this->user = Auth::guard('web')->user();

        $this->perPage = 5;
        // if ($this->user != null) {

        //     $this->upcomingEventCount = upcomingEventsCount($this->user->id);
        //     $this->pendingRsvpCount = pendingRsvpCount($this->user->id);

        //     $this->hostingCount = hostingCount($this->user->id);
        //     $this->invitedToCount = invitedToCount($this->user->id);
        // }
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

    public function home()
    {
        $page='1';
        try {
            $user  = Auth::guard('web')->user();

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

                dd($eventList);
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
}
