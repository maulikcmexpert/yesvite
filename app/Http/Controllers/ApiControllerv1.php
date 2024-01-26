<?php



namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;

use Location;

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
    UserReportToPost
};
// Rules //

use App\Rules\CheckUserEvent;
use App\Rules\checkUserEventPost;
use App\Rules\checkUserGreetingId;
use App\Rules\checkIsUserEvent;
use App\Rules\checkUserGiftregistryId;

use App\Rules\checkInvitedUser;

// Rules //

use Illuminate\Support\Facades\Validator;

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

class ApiControllerv1 extends Controller

{
    protected $perPage;

    public function __construct()
    {

        $this->perPage = 5;
    }

    public function sendThanks()
    {
        $endedEvents =  Event::where('end_date', '<', now())->get();
        foreach ($endedEvents as $event) {
            if (!empty($event->greeting_card_id)) {

                $greetingsCard = explode(',', $event->greeting_card_id);
                $eventcards =   EventGreeting::whereIn('id', $greetingsCard)->first();

                if ($eventcards->message_sent_time == '0') {

                    $currentDate = Carbon::now();

                    $endDate = Carbon::parse($event->end_date);

                    $hoursDifference = $endDate->diffInHours($currentDate);

                    if ($hoursDifference == 0) {

                        $invitedUsers = EventInvitedUser::with('user')->where('event_id', $event->id)->get();
                        foreach ($invitedUsers as $invitedUserVal) {

                            // Mail::to($invitedUserVal->user->email)->send(new ThankYouEmail($event));
                        }
                    }
                } else if ($eventcards->message_sent_time == '1') {

                    $currentDate = Carbon::now();

                    $endDate = Carbon::parse($event->end_date);

                    $hoursDifference = $endDate->diffInHours($currentDate);

                    if ($hoursDifference == $eventcards->custom_hours_after_event) {

                        $invitedUsers = EventInvitedUser::with('user')->where('event_id', $event->id)->get();
                        foreach ($invitedUsers as $invitedUserVal) {

                            // Mail::to($invitedUserVal->user->email)->send(new ThankYouEmail($event));
                        }
                    }
                }
            }
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

            return response()->json(
                [
                    'status' => 0,
                    'message' => $validator->errors()->first()

                ],
            );
        }

        try {

            DB::beginTransaction();



            $randomString = Str::random(30);



            User::create([
                'firstname' => $input['firstname'],
                'lastname' => $input['lastname'],
                'email' => $input['email'],
                'account_type' => $input['account_type'],
                'company_name' => ($input['account_type'] == '1') ? $input['company_name'] : "",
                'password' => Hash::make($input['password']),
                'remember_token' =>  $randomString,
                'user_parent_id' => $user->id
            ]);



            DB::commit();

            Mail::send('emails.emailVerificationEmail', ['token' => $randomString], function ($message) use ($input) {

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



    public function home(Request $request)

    {

        try {



            $user  = Auth::guard('api')->user();
            $usercreatedList = Event::with(['user', 'event_schedule'])->where('start_date', '>', date('Y-m-d'))

                ->where('user_id', $user->id)
                ->where('is_draft_save', '0')
                ->orderBy('id', 'DESC')

                ->get();

            $invitedEvents = EventInvitedUser::whereHas('user', function ($query) {

                $query->where('app_user', '1');
            })->where('user_id', $user->id)->get()->pluck('event_id');



            $invitedEventsList = Event::with(['event_image', 'user', 'event_schedule'])

                ->whereIn('id', $invitedEvents)->where('start_date', '>', date('Y-m-d'))
                ->where('is_draft_save', '0')
                ->orderBy('id', 'DESC')
                ->get();



            $allEvents = $usercreatedList->merge($invitedEventsList)->sortByDesc('id');


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



                    $eventDetail['user_id'] = $value->user->id;

                    $eventDetail['host_profile'] = empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                    $eventDetail['host_name'] = $value->hosted_by;



                    $images = EventImage::where('event_id', $value->id)->first();



                    $eventDetail['event_images'] = ($images != null) ? asset('public/storage/event_images/' . $images->image) : "";



                    $eventDetail['event_date'] = $value->start_date;

                    // $userCurrentloc =  \Location::get($request->ip())->regionCode.' , '.\Location::get($request->ip())->countryName;
                    $carbonDate = Carbon::createFromTimestamp($value->rsvp_start_time);
                    // $carbonDate->setTimezone('America/Los_Angeles'); // Set to PST timezone
                    // $carbonDate->setTimezone($value->rsvp_start_timezone);
                    $timeInAMPM = $carbonDate->format('g:i A');
                    $eventDetail['start_time'] =  $timeInAMPM;

                    $eventDetail['rsvp_start_timezone'] = $value->rsvp_start_timezone;

                    $total_accept_event_user = EventUserRsvp::where(['event_id' => $value->id, 'rsvp_status' => '1'])->count();

                    $eventDetail['total_accept_event_user'] = $total_accept_event_user;



                    $total_invited_user = EventInvitedUser::whereHas('user', function ($query) {

                        $query->where('app_user', '1');
                    })->where(['event_id' => $value->id])->count();

                    $eventDetail['total_invited_user'] = $total_invited_user;



                    $total_refuse_event_user = EventUserRsvp::where(['event_id' => $value->id, 'rsvp_status' => '0'])->count();

                    $eventDetail['total_refuse_event_user'] = $total_refuse_event_user;



                    $total_notification = Notification::where(['event_id' => $value->id, 'user_id' => $user->id, 'notification_type' => '0'])->count();

                    $eventDetail['total_notification'] = $total_notification;

                    $totalEvent =  Event::where('user_id', $value->user->id)->count();
                    $totalEventPhotos =  EventPostPhoto::where('user_id', $value->user->id)->count();
                    $comments =  EventPostComment::where('user_id', $value->user->id)->count();
                    $photocomments =  EventPostPhotoComment::where('user_id', $value->user->id)->count();
                    $eventDetail['user_profile'] = [
                        'id' => $value->user->id,
                        'profile' => empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile),
                        'username' => $value->user->firstname . ' ' . $value->user->lastname,
                        'location' => ($value->user->city != NULL) ? $value->user->city : "",
                        'about_me' => ($value->user->about_me != NULL) ? $value->user->about_me : "",
                        'created_at' => empty($value->user->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($value->user->created_at))),
                        'total_events' => $totalEvent,
                        'total_photos' => $totalEventPhotos,
                        'comments' => $comments + $photocomments,
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



    public function updateProfile(Request $request)

    {


        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [
            'firstname' => 'required',

            'lastname' => 'required',

            'gender' => 'required',
            // 'country_code' => ['required', 'numeric'],
            // 'phone_number' => ['required', 'numeric'],
            "birth_date" => "present",
            "about_me" => "present",
            "address" => "present",
            "city" => "present",
            "state" => "present",
            "zip_code" => "present"
        ]);

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

            if ($userUpdate->visible == '1') {

                $validator = Validator::make($input, [

                    'company_name' => 'required',

                ]);

                $userUpdate->company_name = $input['company_name'];
            }
            $userUpdate->address = ($input['address'] != "") ? $input['address'] : $userUpdate->address;

            $userUpdate->city = ($input['city'] != "") ? $input['city'] : $userUpdate->city;

            $userUpdate->state = ($input['state'] != "") ? $input['state'] : $userUpdate->state;

            $userUpdate->save();
            DB::commit();


            $details = User::where('id', $user->id)->first();

            if (!empty($details)) {

                $totalEvent =  Event::where('user_id', $user->id)->count();


                $totalEventPhotos = 0;

                $comments = 0;

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
                    'total_events' => $totalEvent,
                    'total_photos' => $totalEventPhotos,
                    'comments' => $comments,
                    'gender' => empty($details->gender) ? "" : $details->gender,
                    'country_code' => empty($details->country_code) ? "" : strval($details->country_code),
                    'phone_number' => empty($details->phone_number) ? "" : $details->phone_number,
                    'visible' =>  $details->visible,
                    'account_type' =>  $details->account_type,
                    'company_name' => empty($details->company_name) ? "" : $details->company_name,
                    'address' => empty($details->address) ? "" : $details->address,
                    'city' => empty($details->city) ? "" : $details->city,
                    'state' => empty($details->state) ? "" : $details->state,
                    'zip_code' => empty($details->zip_code) ? "" : $details->zip_code

                ];


                return response()->json(['status' => 1, 'data' => $profileData, 'message' => "Profile updated successfully"]);
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
            $user  = Auth::guard('api')->user();
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


            if (!empty($request->profile)) {

                if ($user->profile != "" || $user->profile != NULL) {

                    if (file_exists(public_path('storage/profile/') . $user->profile)) {
                        $imagePath = public_path('storage/profile/') . $user->profile;
                        unlink($imagePath);
                    }
                }



                $image = $request->profile;

                $imageName = time() . '_' . $image->getClientOriginalName();


                $image->move(public_path('storage/profile'), $imageName);
                $user->profile = $imageName;
            }



            if (!empty($request->bg_profile)) {



                if (file_exists(public_path('storage/bg_profile/') . $user->profile)) {
                    $imagePath = public_path('storage/bg_profile/') . $user->profile;
                    unlink($imagePath);
                }




                $bgimage = $request->bg_profile;

                $bgimageName = time() . '_' . $bgimage->getClientOriginalName();

                $bgimage->move(public_path('storage/bg_profile'), $bgimageName);



                $user->bg_profile = $bgimageName;
            }

            $user->save();

            $details = User::where('id', $user->id)->first();




            if (!empty($details)) {
                $totalEvent =  Event::where('user_id', $user->id)->count();



                $totalEventPhotos = 0;

                $comments = 0;

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
                    'total_events' => $totalEvent,
                    'total_photos' => $totalEventPhotos,
                    'comments' => $comments,
                    'gender' => empty($details->gender) ? "" : $details->gender,
                    'country_code' => empty($details->country_code) ? "" : strval($details->country_code),
                    'phone_number' => empty($details->phone_number) ? "" : $details->phone_number,
                    'visible' =>  $details->visible,
                    'account_type' =>  $details->account_type,
                    'company_name' => empty($details->company_name) ? "" : $details->company_name,
                    'address' => empty($details->address) ? "" : $details->address,
                    'city' => empty($details->city) ? "" : $details->city,
                    'state' => empty($details->state) ? "" : $details->state,
                    'zip_code' => empty($details->zip_code) ? "" : $details->zip_code

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



    public function myProfile()

    {

        try {


            $user  = Auth::guard('api')->user();



            $totalEvent =  Event::where(['user_id' => $user->id, 'is_draft_save' => '0'])->count();
            $totalDraftEvent =  Event::where(['user_id' => $user->id, 'is_draft_save' => '1'])->count();




            $totalEventPhotos = EventPostPhoto::where('user_id', $user->id)->count();

            $postComments =  EventPostComment::where('user_id', $user->id)->count();
            $postPhotocomments =  EventPostPhotoComment::where('user_id', $user->id)->count();





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
                    'total_photos' => $totalEventPhotos,
                    'comments' => $postComments + $postPhotocomments,
                    'gender' => empty($user->gender) ? "" : $user->gender,
                    'country_code' => empty($user->country_code) ? "" : strval($user->country_code),
                    'phone_number' => empty($user->phone_number) ? "" : $user->phone_number,
                    'visible' =>  $user->visible,
                    'account_type' =>  $user->account_type,
                    'company_name' => empty($user->company_name) ? "" : $user->company_name,
                    'address' => empty($user->address) ? "" : $user->address,
                    'city' => empty($user->city) ? "" : $user->city,
                    'state' => empty($user->state) ? "" : $user->state,
                    'zip_code' => empty($user->zip_code) ? "" : $user->zip_code

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

                'in:0,1,2'

            ],



        ]);

        $customMessages = [
            'privacy_visible.in' => 'The privacy_visible field must be 0, 1, or 2.',
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

            $user->save();
            DB::commit();
            return response()->json(['status' => 1, 'message' => "visible changed successfully"]);
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

            'show_photo_friend' => [

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

            $user->show_photo_friend = $input["show_photo_friend"];

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
                'email' => ['required', 'unique:users,email'],

                'prefer_by' => ['required', 'in:email,phone']

            ]);
        } elseif ($input['prefer_by'] == 'phone') {
            $validator = Validator::make($input, [
                'firstname' => ['required'],
                'lastname' => ['required'],
                'country_code' => ['required'],
                'phone_number' => ['required', 'unique:users,phone_number'],
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
            $addContact->country_code = $input['country_code'];
            $addContact->phone_number = $input['phone_number'];
            $addContact->email = $input['email'];
            $addContact->app_user = '0';
            $addContact->prefer_by = $input['prefer_by'];
            $addContact->save();

            DB::commit();
            return response()->json(['status' => 1, 'message' => "Contact added sucessfully"]);
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

                'lastname' => ['required'],

                'email' => ['required', Rule::unique("users")->ignore($input["id"])],

            ]);
        } elseif ($input['prefer_by'] == 'phone') {
            $validator = Validator::make($input, [

                'id' => ['required'],

                'firstname' => ['required'],

                'lastname' => ['required'],

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



            $user->firstname = $input['firstname'];

            $user->lastname = $input['lastname'];

            if ($input['prefer_by'] == 'email') {

                $user->email = $input['email'];
            }

            if ($input['prefer_by'] == 'phone') {

                $user->country_code = $input['country_code'];

                $user->phone_number = $input['phone_number'];
            }

            $user->prefer_by = $input['prefer_by'];
            $user->save();

            DB::commit();

            return response()->json(['status' => 1, 'message' => "Contact updated sucessfully"]);
        } catch (QueryException $e) {

            DB::rollBack();

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

            $yesvitecontactList = getYesviteContactList($user->id);
            return response()->json(['status' => 1, 'message' => "Yesvite contact list", "data" => $yesvitecontactList]);
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

    public function getDesignOptionDataList()
    {

        try {
            $eventCategory = EventDesignCategory::with('subcategory')->withCount('subcategory')->get();

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
                    $formattedDate = Carbon::createFromFormat('Y-m-d H:i:s', $value->created_at)->format('F j, Y h:i A');
                    $eventDetail['saved_date'] = $formattedDate;
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
                'hosted_by' => ['required'],
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
                'is_draft_save' => ['required', 'in:0,1']
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



            $rsvp_by_date = date('Y-m-d');
            $rsvp_by_date_set = '0';

            $rsvpEndTime = "";

            if (!empty($eventData['rsvp_by_date'])) {

                $rsvp_by_date = $eventData['rsvp_by_date'];
                $rsvp_by_date_set = '1';
                if (!empty($eventData['event_setting']['rsvp_by_date'])) {
                    $rsvp_by_date = $eventData['event_setting']['rsvp_by_date'];
                    $rsvp_by_date_set = '1';
                }
            } else {
                if (!empty($eventData['event_setting']['rsvp_by_date'])) {

                    $rsvp_by_date = $eventData['event_setting']['rsvp_by_date'];
                    $rsvp_by_date_set = '1';
                }
            }

            $rsvpStartTime = "";
            $rsvpEndTime = "";
            if (!empty($eventData['rsvp_start_time'])) {
                $dateString = $rsvp_by_date . ' ' . $eventData['rsvp_start_time']; // Sample date and time
                $timezone = $eventData['rsvp_start_timezone']; // Sample timezone

                // Convert string to Carbon object with specified timezone
                // $dateTime = Carbon::createFromFormat('Y-m-d h:i A', $dateString, $timezone);
                $dateTime = Carbon::createFromFormat('Y-m-d h:i A', $dateString);

                // Get the Unix timestamp
                $rsvpStartTime = $dateTime->timestamp;

                // $rsvpStartTime = Carbon::parse($rsvp_by_date . ' ' . $eventData['rsvp_start_time'], $eventData['rsvp_start_timezone']);
                if ($eventData['rsvp_end_time_set'] == '1') {
                    $dateString1 = $rsvp_by_date . ' ' . $eventData['rsvp_end_time']; // Sample date and time
                    $timezone1 = $eventData['rsvp_end_timezone']; // Sample timezone

                    // Convert string to Carbon object with specified timezone
                    // $dateTime1 = Carbon::createFromFormat('Y-m-d h:i A', $dateString1, $timezone1);
                    $dateTime1 = Carbon::createFromFormat('Y-m-d h:i A', $dateString1);

                    // Get the Unix timestamp
                    $rsvpEndTime = $dateTime1->timestamp;
                    // $rsvpEndTime = Carbon::parse($rsvp_by_date . ' ' . $eventData['rsvp_end_time'], $eventData['rsvp_end_timezone']);
                }
                if ($eventData['rsvp_end_time_set'] == '0') {


                    $startEventTime = $eventData['start_date'];

                    $oneDayBefore = date('Y-m-d', strtotime('-1 day', strtotime($startEventTime))) . ' 12:00 PM';
                    // Convert string to Carbon object with specified timezone
                    // $dateTime2 = Carbon::createFromFormat('Y-m-d h:i A', $oneDayBefore, $eventData['rsvp_start_timezone']);
                    $dateTime2 = Carbon::createFromFormat('Y-m-d h:i A', $oneDayBefore);
                    $rsvpEndTime = $dateTime2->timestamp;
                    // $rsvpEndTime = Carbon::parse($oneDayBefore . ' ' . $eventData['rsvp_start_timezone'], $eventData['rsvp_start_timezone']);
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

                'hosted_by' => (!empty($eventData['hosted_by'])) ? $eventData['hosted_by'] : "",

                'start_date' => (!empty($eventData['start_date'])) ? $eventData['start_date'] : NULL,

                'end_date' => (!empty($eventData['end_date'])) ? $eventData['end_date'] : NULL,
                //'rsvp_by_date_set' => $eventData['rsvp_by_date_set'],
                'rsvp_by_date_set' => $rsvp_by_date_set,
                // 'rsvp_by_date' => (!empty($eventData['rsvp_by_date'])) ? $eventData['rsvp_by_date'] : NULL,
                'rsvp_by_date' => $rsvp_by_date,

                'rsvp_start_time' => $rsvpStartTime,

                'rsvp_start_timezone' => (!empty($eventData['rsvp_start_timezone'])) ? $eventData['rsvp_start_timezone'] : "",
                'greeting_card_id' => $greeting_card_id,
                'gift_registry_id' => $gift_registry_id,
                'rsvp_end_time_set' => (!empty($eventData['rsvp_end_time_set'])) ? $eventData['rsvp_end_time_set'] : "0",
                'rsvp_end_time' => $rsvpEndTime,
                'rsvp_end_timezone' => ($eventData['rsvp_end_time_set'] == '1') ? $eventData['rsvp_end_timezone'] : "",
                'event_location_name' => (!empty($eventData['event_location_name'])) ? $eventData['event_location_name'] : "",
                'address_1' => (!empty($eventData['address_1'])) ? $eventData['address_1'] : "",
                'address_2' => (!empty($eventData['address_2'])) ? $eventData['address_2'] : "",
                'state' => (!empty($eventData['state'])) ? $eventData['state'] : "",
                'zip_code' => (!empty($eventData['zip_code'])) ? $eventData['zip_code'] : "",
                'city' => (!empty($eventData['city'])) ? $eventData['city'] : "",
                'message_to_guests' => (!empty($eventData['message_to_guests'])) ? $eventData['message_to_guests'] : "",

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

                        'rsvp_by_date_status' => $eventData['event_setting']['rsvp_by_date_status'],

                        'thank_you_cards' => $eventData['event_setting']['thank_you_cards'],

                        'add_co_host' => $eventData['event_setting']['add_co_host'],

                        'gift_registry' => $eventData['event_setting']['gift_registry'],

                        'events_schedule' => $eventData['event_setting']['events_schedule'],

                        'event_wall' => $eventData['event_setting']['event_wall'],

                        'guest_list_visible_to_guests' => $eventData['event_setting']['guest_list_visible_to_guests'],

                        'podluck' => $eventData['event_setting']['podluck'],

                        'rsvp_updates' => $eventData['event_setting']['rsvp_updates'],

                        'event_updates' => $eventData['event_setting']['event_updates'],

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



                        foreach ($eventsScheduleList as $value) {



                            EventSchedule::create([

                                'event_id' => $eventId,

                                'event_name' => $value['event_name'],

                                'event_schedule' => $value['event_schedule'],

                            ]);
                        }
                    }
                }


                if (isset($eventData['podluck_category_list']) && is_array($eventData['podluck_category_list']) && $eventData['event_setting']['podluck'] == '1'  && $eventData['is_draft_save'] == '0') {


                    $podluckCategoryList = $eventData['podluck_category_list'];

                    if (!empty($podluckCategoryList)) {



                        foreach ($podluckCategoryList as $value) {



                            $eventPodluck = EventPotluckCategory::create([

                                'event_id' => $eventId,

                                'category' => $value['category'],

                                'quantity' => $value['quantity'],

                            ]);



                            if (!empty($value['items'])) {

                                $items = $value['items'];



                                foreach ($items as $value) {



                                    EventPotluckCategoryItem::create([

                                        'event_id' => $eventId,

                                        'event_potluck_category_id' => $eventPodluck->id,

                                        'description' => $value['description'],

                                        'quantity' => $value['quantity'],

                                    ]);
                                }
                            }
                        }
                    }
                }
            }


            if (!empty($eventData['invited_user_id']) && $eventData['is_draft_save'] == '0') {

                $notificationParam = [

                    'sender_id' => $user->id,

                    'event_id' => $eventId,

                    'post_id' => ""

                ];

                sendNotification('invite', $notificationParam);
            }

            DB::commit();


            return response()->json(['status' => 1, 'event_id' => $eventCreation->id, 'message' => "Event Created Successfully"]);
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json(['status' => 0, 'message' => 'Db error:-' . $e->getMessage()]);
        } catch (Exception $e) {
            Log::info('API request event create something error successfully');;
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
            $getEventData = Event::where('id', $eventData['event_id'])->first();
            if ($getEventData != null) {
                $eventDetail['id'] = (!empty($getEventData->id) && $getEventData->id != NULL) ? $getEventData->id : "";
                $eventDetail['event_type_id'] = (!empty($getEventData->event_type_id) && $getEventData->event_type_id != NULL) ? $getEventData->event_type_id : "";
                $eventDetail['event_name'] = (!empty($getEventData->event_name) && $getEventData->event_name != NULL) ? $getEventData->event_name : "";
                $eventDetail['hosted_by'] = (!empty($getEventData->hosted_by) && $getEventData->hosted_by != NULL) ? $getEventData->hosted_by : "";
                $eventDetail['start_date'] = (!empty($getEventData->start_date) && $getEventData->start_date != NULL) ? $getEventData->start_date : "";
                $eventDetail['end_date'] = (!empty($getEventData->end_date) && $getEventData->end_date != NULL) ? $getEventData->end_date : "";
                $eventDetail['rsvp_by_date_set'] =  $getEventData->rsvp_by_date_set;
                $eventDetail['rsvp_by_date'] = (!empty($getEventData->rsvp_by_date) && $getEventData->rsvp_by_date != NULL) ? $getEventData->rsvp_by_date : "";
                $eventDetail['rsvp_start_time'] = (!empty($getEventData->rsvp_start_time) && $getEventData->rsvp_start_time != NULL) ? Carbon::createFromTimestamp($getEventData->rsvp_start_time)->format('h:i A') : "";
                $eventDetail['rsvp_start_timezone'] = (!empty($getEventData->rsvp_start_timezone) && $getEventData->rsvp_start_timezone != NULL) ? $getEventData->rsvp_start_timezone : "";
                $eventDetail['rsvp_end_time_set'] = $getEventData->rsvp_end_time_set;
                $eventDetail['rsvp_end_time'] = (!empty($getEventData->rsvp_end_time) && $getEventData->rsvp_end_time != NULL) ? Carbon::createFromTimestamp($getEventData->rsvp_end_time)->format('h:i A') : "";
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
                            if ($guestVal->user->parent_user_phone_contact ==  $getEventData->user_id && $guestVal->user->is_user_phone_contact == '1') {
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
                            if ($guestVal->user->parent_user_phone_contact ==  $getEventData->user_id && $guestVal->user->is_user_phone_contact == '1') {
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

                $eventDetail['greeting_card_list'] = [];
                if (!empty($getEventData->greeting_card_id) && $getEventData->greeting_card_id != NULL) {


                    $greeting_card_ids = array_map('intval', explode(',', $getEventData->greeting_card_id));

                    $eventDetail['greeting_card_list'] = $greeting_card_ids;
                    // $getEventGreetingData =  EventGreeting::where('user_id', $user->id)->get();

                    // if (!empty($getEventGreetingData)) {
                    //     foreach ($getEventGreetingData as $values) {

                    //         $greetingCardDetail['id'] = $values->id;
                    //         $greetingCardDetail['user_id'] = $values->user_id;
                    //         $greetingCardDetail['template_name'] = $values->template_name;
                    //         $greetingCardDetail['message'] = $values->message;
                    //         $greetingCardDetail['message_sent_time'] = $values->id;
                    //         $greetingCardDetail['custom_hours_after_event'] = $values->message_sent_time;
                    //         $greetingCardDetail['is_selected'] = (in_array($values->id, $greeting_card_ids)) ? 1 : 0;
                    //         $eventDetail['greeting_card_id'][] = $greetingCardDetail;
                    //     }

                    // }
                }

                $eventDetail['gift_registry_list'] = [];
                if (!empty($getEventData->gift_registry_id) && $getEventData->gift_registry_id != NULL) {

                    $gift_registry_ids = array_map('intval', explode(',', $getEventData->gift_registry_id));

                    $eventDetail['gift_registry_list'] = $gift_registry_ids;
                    // $getEventGiftRegistryData =  EventGiftRegistry::where('user_id', $user->id)->get();
                    // if (!empty($getEventGiftRegistryData)) {

                    //     foreach ($getEventGiftRegistryData as $values) {

                    //         $giftRegistryDetail['id'] = $values->id;
                    //         $giftRegistryDetail['user_id'] = $values->user_id;
                    //         $giftRegistryDetail['registry_recipient_name'] = $values->registry_recipient_name;
                    //         $giftRegistryDetail['registry_link'] = $values->registry_link;

                    //         $giftRegistryDetail['is_selected'] = (in_array($values->id, $gift_registry_ids)) ? 1 : 0;
                    //         $eventDetail['gift_registry_id'][] = $giftRegistryDetail;
                    //     }
                    // }
                }

                $eventDetail['event_setting'] = "";

                $eventSettings = EventSetting::where('event_id', $getEventData->id)->first();

                if ($eventSettings != NULL) {
                    $eventDetail['event_setting'] = [

                        "allow_for_1_more" => $eventSettings->allow_for_1_more,
                        "allow_limit" => strval($eventSettings->allow_limit),
                        "adult_only_party" => $eventSettings->adult_only_party,
                        "rsvp_by_date_status" => $eventSettings->rsvp_by_date_status,
                        "rsvp_by_date" => $getEventData->rsvp_by_date,
                        "thank_you_cards" => $eventSettings->thank_you_cards,
                        "add_co_host" => $eventSettings->add_co_host,
                        "gift_registry" => $eventSettings->gift_registry,
                        "events_schedule" => $eventSettings->events_schedule,
                        "event_wall" => $eventSettings->event_wall,
                        "guest_list_visible_to_guests" => $eventSettings->guest_list_visible_to_guests,
                        "podluck" => $eventSettings->podluck,
                        "rsvp_updates" => $eventSettings->rsvp_updates,
                        "event_updates" => $eventSettings->event_updates,
                        "send_event_dater_reminders" => $eventSettings->send_event_dater_reminders,
                        "request_event_photos_from_guests" => $eventSettings->request_event_photos_from_guests
                    ];
                }


                $eventDetail['podluck_category_list'] = [];

                $eventpotluckData =  EventPotluckCategory::with('event_potluck_category_item')->withCount('event_potluck_category_item')->where('event_id', $getEventData->id)->get();

                if (!empty($eventpotluckData)) {
                    $potluckCategoryData = [];
                    $potluckDetail['total_potluck_item'] = EventPotluckCategoryItem::where('event_id', $getEventData->id)->count();

                    foreach ($eventpotluckData as $value) {
                        $potluckCategory['id'] = $value->id;
                        $potluckCategory['category'] = $value->category;
                        $potluckCategory['quantity'] = $value->quantity;
                        $potluckCategory['items'] = [];
                        if (!empty($value->event_potluck_category_item) || $value->event_potluck_category_item != null) {

                            foreach ($value->event_potluck_category_item as $itemValue) {
                                $potluckImtem['id'] =  $itemValue->id;
                                $potluckImtem['description'] =  $itemValue->description;
                                $potluckImtem['quantity'] =  $itemValue->quantity;
                                $potluckCategory['items'][] = $potluckImtem;
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
                'hosted_by' => ['required'],
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


                $rsvp_by_date = date('Y-m-d');
                $rsvp_by_date_set = '0';

                $rsvpEndTime = "";

                if (!empty($eventData['rsvp_by_date'])) {

                    $rsvp_by_date = $eventData['rsvp_by_date'];
                    $rsvp_by_date_set = '1';
                    if (!empty($eventData['event_setting']['rsvp_by_date'])) {
                        $rsvp_by_date = $eventData['event_setting']['rsvp_by_date'];
                        $rsvp_by_date_set = '1';
                    }
                } else {
                    if (!empty($eventData['event_setting']['rsvp_by_date'])) {

                        $rsvp_by_date = $eventData['event_setting']['rsvp_by_date'];
                        $rsvp_by_date_set = '1';
                    }
                }

                $rsvpStartTime = "";
                $rsvpEndTime = "";
                if (!empty($eventData['rsvp_start_time'])) {
                    $dateString = $rsvp_by_date . ' ' . $eventData['rsvp_start_time']; // Sample date and time
                    $timezone = $eventData['rsvp_start_timezone']; // Sample timezone

                    // Convert string to Carbon object with specified timezone
                    // $dateTime = Carbon::createFromFormat('Y-m-d h:i A', $dateString, $timezone);
                    $dateTime = Carbon::createFromFormat('Y-m-d h:i A', $dateString);

                    // Get the Unix timestamp
                    $rsvpStartTime = $dateTime->timestamp;

                    // $rsvpStartTime = Carbon::parse($rsvp_by_date . ' ' . $eventData['rsvp_start_time'], $eventData['rsvp_start_timezone']);
                    if ($eventData['rsvp_end_time_set'] == '1') {
                        $dateString1 = $rsvp_by_date . ' ' . $eventData['rsvp_end_time']; // Sample date and time
                        $timezone1 = $eventData['rsvp_end_timezone']; // Sample timezone

                        // Convert string to Carbon object with specified timezone
                        // $dateTime1 = Carbon::createFromFormat('Y-m-d h:i A', $dateString1, $timezone1);
                        $dateTime1 = Carbon::createFromFormat('Y-m-d h:i A', $dateString1);

                        // Get the Unix timestamp
                        $rsvpEndTime = $dateTime1->timestamp;
                        // $rsvpEndTime = Carbon::parse($rsvp_by_date . ' ' . $eventData['rsvp_end_time'], $eventData['rsvp_end_timezone']);
                    }
                    if ($eventData['rsvp_end_time_set'] == '0') {


                        $startEventTime = $eventData['start_date'];

                        $oneDayBefore = date('Y-m-d', strtotime('-1 day', strtotime($startEventTime))) . ' 12:00 PM';
                        // Convert string to Carbon object with specified timezone
                        // $dateTime2 = Carbon::createFromFormat('Y-m-d h:i A', $oneDayBefore, $eventData['rsvp_start_timezone']);
                        $dateTime2 = Carbon::createFromFormat('Y-m-d h:i A', $oneDayBefore);
                        $rsvpEndTime = $dateTime2->timestamp;
                        // $rsvpEndTime = Carbon::parse($oneDayBefore . ' ' . $eventData['rsvp_start_timezone'], $eventData['rsvp_start_timezone']);
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



                $updateEvent->event_type_id = (!empty($eventData['event_type_id'])) ? $eventData['event_type_id'] : "";
                $updateEvent->event_name = (!empty($eventData['event_name'])) ? $eventData['event_name'] : "";
                $updateEvent->hosted_by = (!empty($eventData['hosted_by'])) ? $eventData['hosted_by'] : "";
                $updateEvent->start_date = (!empty($eventData['start_date'])) ? $eventData['start_date'] : NULL;
                $updateEvent->end_date = (!empty($eventData['end_date'])) ? $eventData['end_date'] : NULL;
                $updateEvent->rsvp_by_date_set = $rsvp_by_date_set;
                $updateEvent->rsvp_by_date = $rsvp_by_date;
                $updateEvent->rsvp_start_time = $rsvpStartTime;
                $updateEvent->rsvp_start_timezone = (!empty($eventData['rsvp_start_timezone'])) ? $eventData['rsvp_start_timezone'] : "";
                $updateEvent->greeting_card_id = $greeting_card_id;
                $updateEvent->gift_registry_id = $gift_registry_id;
                $updateEvent->rsvp_end_time_set = (!empty($eventData['rsvp_end_time_set'])) ? $eventData['rsvp_end_time_set'] : "0";
                $updateEvent->rsvp_end_time = $rsvpEndTime;
                $updateEvent->rsvp_end_timezone = ($eventData['rsvp_end_time_set'] == '1') ? $eventData['rsvp_end_timezone'] : "";
                $updateEvent->event_location_name = (!empty($eventData['event_location_name'])) ? $eventData['event_location_name'] : "";
                $updateEvent->address_1 = (!empty($eventData['address_1'])) ? $eventData['address_1'] : "";
                $updateEvent->address_2 = (!empty($eventData['address_2'])) ? $eventData['address_2'] : "";
                $updateEvent->state = (!empty($eventData['state'])) ? $eventData['state'] : "";
                $updateEvent->zip_code = (!empty($eventData['zip_code'])) ? $eventData['zip_code'] : "";
                $updateEvent->city = (!empty($eventData['city'])) ? $eventData['city'] : "";
                $updateEvent->message_to_guests = (!empty($eventData['message_to_guests'])) ? $eventData['message_to_guests'] : "";
                $updateEvent->is_draft_save = $eventData['is_draft_save'];


                if ($updateEvent->save()) {

                    EventInvitedUser::where('event_id', $eventData['event_id'])->delete();

                    if (isset($eventData['invited_user_id']) && !empty($eventData['invited_user_id'])) {
                        $invitedUsers = $eventData['invited_user_id'];


                        foreach ($invitedUsers as $value) {



                            EventInvitedUser::create([

                                'event_id' => $eventData['event_id'],

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

                                        EventInvitedUser::create([

                                            'event_id' => $eventData['event_id'],

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

                                        'event_id' =>  $eventData['event_id'],

                                        'prefer_by' => $value['prefer_by'],

                                        'user_id' => $guestUser->id

                                    ]);
                                } else {
                                    $alreadyselectedUser =  collect($eventData['invited_user_id'])->pluck('user_id')->toArray();

                                    if (!in_array($checkUserExist->id, $alreadyselectedUser)) {
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



                    if ($eventData['event_setting']) {

                        $updateEventSetting = EventSetting::where('event_id', $eventData['event_id'])->first();

                        $updateEventSetting->allow_for_1_more = $eventData['event_setting']['allow_for_1_more'];
                        $updateEventSetting->allow_limit = $eventData['event_setting']['allow_limit'];
                        $updateEventSetting->adult_only_party = $eventData['event_setting']['adult_only_party'];
                        $updateEventSetting->rsvp_by_date_status = $eventData['event_setting']['rsvp_by_date_status'];
                        $updateEventSetting->thank_you_cards = $eventData['event_setting']['thank_you_cards'];
                        $updateEventSetting->add_co_host = $eventData['event_setting']['add_co_host'];
                        $updateEventSetting->gift_registry = $eventData['event_setting']['gift_registry'];
                        $updateEventSetting->events_schedule = $eventData['event_setting']['events_schedule'];
                        $updateEventSetting->event_wall = $eventData['event_setting']['event_wall'];
                        $updateEventSetting->guest_list_visible_to_guests = $eventData['event_setting']['guest_list_visible_to_guests'];
                        $updateEventSetting->podluck = $eventData['event_setting']['podluck'];
                        $updateEventSetting->rsvp_updates = $eventData['event_setting']['rsvp_updates'];
                        $updateEventSetting->event_updates = $eventData['event_setting']['event_updates'];
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
                                    if (!in_array($value['user_id'], $alreadyselectedUser)) {
                                        EventInvitedUser::create([

                                            'event_id' => $eventData['event_id'],

                                            'prefer_by' => $value['prefer_by'],

                                            'user_id' => $value['user_id'],
                                            'is_co_host' => '1'
                                        ]);
                                    } else {
                                        $updateRecord = EventInvitedUser::where(['user_id' => $value['user_id'], 'event_id' => $eventData['event_id']])->first();
                                        $updateRecord->is_co_host = '1';
                                        $updateRecord->save();
                                    }
                                }
                            }
                        }
                        if (isset($eventData['guest_co_host_list'])) {
                            $guestcoHostList = $eventData['guest_co_host_list'];

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
                                            } else {
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
                                            } else {
                                                $updateRecord = EventInvitedUser::where(['user_id' => $checkUserExist->id, 'event_id' => $eventData['event_id']])->first();
                                                $updateRecord->is_co_host = '1';
                                                $updateRecord->save();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }



                    if ($eventData['event_setting']['events_schedule'] == '1') {

                        $eventsScheduleList = $eventData['events_schedule_list'];

                        if (!empty($eventsScheduleList)) {

                            EventSchedule::where('event_id', $eventData['event_id'])->delete();

                            foreach ($eventsScheduleList as $value) {



                                EventSchedule::create([

                                    'event_id' => $eventData['event_id'],

                                    'event_name' => $value['event_name'],

                                    'event_schedule' => $value['event_schedule'],

                                ]);
                            }
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

                                    'category' => $value['category'],

                                    'quantity' => $value['quantity'],

                                ]);



                                if (!empty($value['items'])) {

                                    $items = $value['items'];



                                    foreach ($items as $value) {



                                        EventPotluckCategoryItem::create([

                                            'event_id' => $eventData['event_id'],

                                            'event_potluck_category_id' => $eventPodluck->id,

                                            'description' => $value['description'],

                                            'quantity' => $value['quantity'],

                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }


                if (!empty($eventData['invited_user_id']) && $eventData['is_draft_save'] == '0') {

                    Notification::where('event_id', $eventData['event_id'])->delete();
                    $notificationParam = [

                        'sender_id' => $user->id,

                        'event_id' => $eventData['event_id'],

                        'post_id' => ""

                    ];

                    sendNotification('invite', $notificationParam);
                }



                DB::commit();
                if (!empty($eventData['invited_user_id']) && $eventData['is_draft_save'] == '0') {

                    $notificationParam = [

                        'sender_id' => $user->id,

                        'event_id' => $eventData['event_id'],

                        'post_id' => ""

                    ];

                    sendNotification('invite', $notificationParam);
                }

                return response()->json(['status' => 1, 'event_id' => (int)$eventData['event_id'], 'message' => "Event updated Successfully"]);
            } else {

                return response()->json(['status' => 0, 'message' => 'Event is not found']);
            }
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json(['status' => 0, 'message' => 'Db error:-' . $e->getMessage()]);
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
            'message_sent_time' => ['required', 'in:0,1'],
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
                'message_sent_time' => $input['message_sent_time'],
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
            'message_sent_time' => ['required', 'in:0,1'],
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
            $updateGreetingCard->message_sent_time = $input['message_sent_time'];
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

            'event_id' => ['required', 'exists:events,id'],
            'image' => ['required', 'array']

        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }



        try {

            DB::beginTransaction();



            if (!empty($request->image)) {

                $images = $request->image;



                $eventOldImages = EventImage::where('event_id', $request->event_id)->get();
                if (!empty($eventOldImages)) {


                    foreach ($eventOldImages as $oldImages) {
                        if (file_exists(public_path('storage/event_images/') . $oldImages->image)) {

                            $imagePath = public_path('storage/event_images/') . $oldImages->image;
                            unlink($imagePath);
                        }
                        EventImage::where('id', $oldImages->id)->delete();
                    }
                }



                foreach ($images as $value) {
                    $image = $value;
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('storage/event_images'), $imageName);

                    EventImage::create([

                        'event_id' => $request->event_id,

                        'image' => $imageName

                    ]);
                }

                DB::commit();



                return response()->json(['status' => 1, 'message' => "Event images stored successfully"]);
            }
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {


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

                $deleteEvent->delete();

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
            'event_id' => ['required', 'exists:events,id', new CheckUserEvent],
            'category' => 'required|unique:event_potluck_categories,category,NULL,id,event_id,' . $input['event_id'],
            'quantity' => ['required', 'numeric']
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

    public function addPotluckCategoryItem(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();



        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [


            'event_id' => ['required', 'exists:events,id', new CheckUserEvent],
            'event_potluck_category_id' => 'required|exists:event_potluck_categories,id',
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

            EventPotluckCategoryItem::Create([
                'event_id' => $input['event_id'],
                'event_potluck_category_id' => $input['event_potluck_category_id'],
                'description' => $input['description'],
                'quantity' => $input['quantity']
            ]);
            DB::commit();
            return response()->json(['status' => 1, 'message' => "Potluck category item created"]);
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
            'event_id' => ['required', 'exists:events,id', new CheckUserEvent],
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

    public function eventPotluck(Request $request)
    {

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

            $eventpotluckData =  EventPotluckCategory::with('event_potluck_category_item')->withCount('event_potluck_category_item')->where('event_id', $input['event_id'])->get();

            if (!empty($eventpotluckData)) {
                $potluckCategoryData = [];
                $potluckDetail['total_potluck_item'] = EventPotluckCategoryItem::where('event_id', $input['event_id'])->count();
                //   dd($eventpotluckData);
                foreach ($eventpotluckData as $value) {
                    $potluckCategory['id'] = $value->id;
                    $potluckCategory['category'] = $value->category;
                    $potluckCategory['quantity'] = $value->quantity;
                    $potluckCategory['potluck_items'] = [];
                    if (!empty($value->event_potluck_category_item) || $value->event_potluck_category_item != null) {

                        foreach ($value->event_potluck_category_item as $itemValue) {
                            $potluckImtem['id'] =  $itemValue->id;
                            $potluckImtem['description'] =  $itemValue->description;
                            $potluckImtem['quantity'] =  $itemValue->quantity;
                            $potluckCategory['potluck_items'][] = $potluckImtem;
                        }
                    }
                    $potluckCategoryData[] = $potluckCategory;
                }
                $potluckDetail['potluck_list'] = $potluckCategoryData;
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

    public function EventList(Request $request)

    {

        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();
        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [
            'event_date' => ['present', 'date']

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

            if (isset($input['event_date']) && !empty($input['event_date'])) {

                $event_date = $input['event_date'];
            }
            if (!isset($input['type'])) {
                $input['type'] = null;
            }
            $createdEventList = [];
            $total_allEvent_page = 0;
            if ((isset($input['type']) && $input['type'] == '1') || $input['type'] == null) {
                $usercreatedAllEventList = Event::with(['event_image', 'user'])

                    ->where('user_id', $user->id)
                    ->where('is_draft_save', '0')
                    ->when($event_date, function ($query, $event_date) {

                        return $query->where('start_date', $event_date);
                    })->orderBy('id', 'DESC')->get();



                $invitedEvents = EventInvitedUser::whereHas('user', function ($query) {

                    $query->where('app_user', '1');
                })->where('user_id', $user->id)->get()->pluck('event_id');



                $invitedEventsList = Event::with(['event_image', 'user'])

                    ->whereIn('id', $invitedEvents)
                    ->where('is_draft_save', '0')
                    ->orderBy('id', 'DESC')
                    ->get();



                $allEvent = $usercreatedAllEventList->merge($invitedEventsList);


                $page = $request->input('page');


                $pages = ($page != "") ? $page : 1;

                // Calculate offset based on current page and perPage
                $offset = ($pages - 1) * $this->perPage;

                $total_allEvent_page = ceil(count($allEvent) / $this->perPage);
                $paginatedEvents =  collect($allEvent)->forPage($page, $this->perPage);
                $paginatedEvents->sortByDesc('id')->values()->all();

                if (count($paginatedEvents) != 0) {


                    foreach ($paginatedEvents as $value) {


                        $eventDetail['id'] = $value->id;
                        $eventDetail['event_name'] = $value->event_name;
                        $eventDetail['is_event_owner'] = ($value->user->id == $user->id) ? 1 : 0;
                        $eventDetail['user_id'] = $value->user->id;
                        $eventDetail['host_profile'] = empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                        $eventDetail['host_name'] = $value->hosted_by;



                        $images = EventImage::where('event_id', $value->id)->first();

                        $eventDetail['event_images'] = ($images != null) ? asset('public/storage/event_images/' . $images->image) : "";

                        $eventDetail['event_date'] = $value->start_date;

                        $carbonDate = Carbon::createFromTimestamp($value->rsvp_start_time);

                        // $carbonDate->setTimezone($value->rsvp_start_timezone);
                        $timeInAMPM = $carbonDate->format('g:i A');

                        $eventDetail['start_time'] = $timeInAMPM;

                        $eventDetail['rsvp_start_timezone'] = $value->rsvp_start_timezone;

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



                        $total_notification = Notification::where(['event_id' => $value->id, 'user_id' => $user->id, 'notification_type' => '0'])->count();

                        $eventDetail['total_notification'] = $total_notification;

                        $totalEvent =  Event::where('user_id', $value->user->id)->count();
                        $totalEventPhotos =  EventPostPhoto::where('user_id', $value->user->id)->count();
                        $comments =  EventPostComment::where('user_id', $value->user->id)->count();
                        $photocomments =  EventPostPhotoComment::where('user_id', $value->user->id)->count();
                        $eventDetail['user_profile'] = [
                            'id' => $value->user->id,
                            'profile' => empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile),
                            'username' => $value->user->firstname . ' ' . $value->user->lastname,
                            'location' => ($value->user->city != NULL) ? $value->user->city : "",
                            'about_me' => ($value->user->about_me != NULL) ? $value->user->about_me : "",
                            'created_at' => empty($value->user->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($value->user->created_at))),
                            'total_events' => $totalEvent,
                            'total_photos' => $totalEventPhotos,
                            'comments' => $comments + $photocomments,
                        ];

                        $createdEventList[] = $eventDetail;
                    }
                }
                $eventList['all'] =  $createdEventList;
            }



            // All  //
            // Invited To //

            $invitedeventList = [];
            $total_invitedTo_page = 0;
            if ((isset($input['type']) && $input['type'] == '2') || $input['type'] == null) {


                $countsOfInvitedEvent = EventInvitedUser::whereHas('event', function ($query) use ($event_date) {
                    $query->where('is_draft_save', '0'); // Apply condition on the parent table
                })->whereHas('user', function ($query) {
                    $query->where('app_user', '1');
                })->with(['event' => function ($query) use ($event_date) {
                    $query->when($event_date, function ($query, $event_date) {
                        return $query->where('start_date', $event_date);
                    })->with('event_image')->orderBy('id', 'DESC');
                }])->where('user_id', $user->id)->count();

                $total_invitedTo_page = ceil($countsOfInvitedEvent / $this->perPage);

                $userInvitedEventList = EventInvitedUser::whereHas('event', function ($query) use ($event_date) {
                    $query->where('is_draft_save', '0'); // Apply condition on the parent table
                })->whereHas('user', function ($query) {
                    $query->where('app_user', '1');
                })->with(['event' => function ($query) use ($event_date) {
                    $query->when($event_date, function ($query, $event_date) {
                        return $query->where('start_date', $event_date);
                    })->with('event_image')->orderBy('id', 'DESC');
                }])->where('user_id', $user->id)->paginate($this->perPage);




                if (count($userInvitedEventList) != 0) {



                    foreach ($userInvitedEventList as $value) {



                        $eventDetail['id'] = $value->event->id;

                        $eventDetail['event_name'] = $value->event->event_name;

                        $eventDetail['host_profile'] = empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                        $eventDetail['host_name'] = $value->event->hosted_by;

                        $images = EventImage::where('event_id', $value->event->id)->first();

                        $eventDetail['event_images'] = "";

                        if (!empty($images)) {

                            $eventDetail['event_images'] = asset('public/storage/event_images/' . $images->image);
                        }



                        $eventDetail['event_date'] = $value->event->start_date;

                        $carbonDate = Carbon::createFromTimestamp($value->event->rsvp_start_time);
                        // $carbonDate->setTimezone($value->event->rsvp_start_timezone);
                        $timeInAMPM = $carbonDate->format('g:i A');
                        $eventDetail['start_time'] =  $timeInAMPM;

                        $eventDetail['rsvp_start_timezone'] = $value->event->rsvp_start_timezone;



                        $rsvp_status = "";



                        if ($value->event->rsvp_end_time != "" || $value->event->rsvp_end_time != NULL) {







                            $checkUserrsvp = EventInvitedUser::whereHas('user', function ($query) {

                                $query->where('app_user', '1');
                            })->where(['user_id' => $user->id, 'event_id' => $value->event->id])->first();



                            if ($checkUserrsvp->rsvp_status == '1') {

                                $rsvp_status = '1'; // rsvp you'r going

                            } else if ($checkUserrsvp->rsvp_status == '0') {

                                $rsvp_status = '2'; // rsvp you'r not going

                            }



                            if ($checkUserrsvp->rsvp_status == '0') {

                                if ($value->event->rsvp_start_time <= strtotime(env('DATE')) && strtotime(env('DATE')) <= $value->event->rsvp_end_time) {

                                    $rsvp_status = '0'; // rsvp button//

                                }
                            }
                        } else {



                            $startEventTime = $value->event->start_date;

                            $oneDayBefore = date('Y-m-d', strtotime('-1 day', strtotime($startEventTime)));

                            $svrp_end_time = strtotime($oneDayBefore . ' 12:00:00');





                            $checkUserrsvp = EventInvitedUser::whereHas('user', function ($query) {

                                $query->where('app_user', '1');
                            })->where(['user_id' => $user->id, 'event_id' => $value->event->id])->first();

                            if ($checkUserrsvp->rsvp_status == '1') {

                                $rsvp_status = '1'; // rsvp you'r going

                            } else if ($checkUserrsvp->rsvp_status == '0' && $checkUserrsvp->rsvp_d == '1') {

                                $rsvp_status = '2'; // rsvp you'r not going

                            }



                            if ($checkUserrsvp->rsvp_status == '0') {

                                if ($value->event->rsvp_start_time <= strtotime(env('DATE')) && strtotime(env('DATE')) <= $svrp_end_time) {

                                    $rsvp_status = '0'; // rsvp button//

                                }
                            }
                        }



                        $eventDetail['rsvp_status'] = $rsvp_status;



                        $total_notification = Notification::where(['event_id' => $value->event->id, 'user_id' => $user->id, 'notification_type' => '0'])->count();

                        $eventDetail['total_notification'] = $total_notification;

                        $totalEvent =  Event::where('user_id', $value->user->id)->count();
                        $totalEventPhotos =  EventPostPhoto::where('user_id', $value->user->id)->count();
                        $comments =  EventPostComment::where('user_id', $value->user->id)->count();
                        $photocomments =  EventPostPhotoComment::where('user_id', $value->user->id)->count();
                        $eventDetail['user_profile'] = [
                            'id' => $value->user->id,
                            'profile' => empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile),
                            'username' => $value->user->firstname . ' ' . $value->user->lastname,
                            'location' => ($value->user->city != NULL) ? $value->user->city : "",
                            'about_me' => ($value->user->about_me != NULL) ? $value->user->about_me : "",
                            'created_at' => empty($value->user->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($value->user->created_at))),
                            'total_events' => $totalEvent,
                            'total_photos' => $totalEventPhotos,
                            'comments' => $comments + $photocomments,
                        ];

                        $invitedeventList[] = $eventDetail;
                    }
                }
                $eventList['invited_to'] =  $invitedeventList;
            }


            // Invited To //


            // Past Event // 
            $PastEventList = [];
            $total_pastEvent_page = 0;

            if ((isset($input['type']) && $input['type'] == '3') || $input['type'] == null) {

                $usercreatedAllPastEventList = Event::with(['event_image', 'user'])

                    ->where(['user_id' => $user->id])
                    ->where('start_date', '<', date('Y-m-d'))
                    ->where('is_draft_save', '0')
                    ->get();



                $invitedPastEvents = EventInvitedUser::whereHas('user', function ($query) {

                    $query->where('app_user', '1');
                })->where('user_id', $user->id)->get()->pluck('event_id');



                $invitedPastEventsList = Event::with(['event_image', 'user'])

                    ->whereIn('id', $invitedPastEvents)
                    ->where('is_draft_save', '0')
                    ->where('start_date', '<', date('Y-m-d'))
                    ->orderBy('id', 'DESC')
                    ->get();



                $allPastEvent = $usercreatedAllPastEventList->merge($invitedPastEventsList)->sortByDesc('id');

                $total_pastEvent_page = ceil(count($allPastEvent) / $this->perPage);
                $allPastEvents =  collect($allPastEvent)->forPage($page, $this->perPage);
                $allPastEvents->sortByDesc('id')->values()->all();


                if (count($allPastEvents) != 0) {


                    foreach ($allPastEvents as $value) {


                        $eventDetail['id'] = $value->id;
                        $eventDetail['event_name'] = $value->event_name;
                        $eventDetail['user_id'] = $value->user->id;
                        $eventDetail['host_profile'] = empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                        $eventDetail['host_name'] = $value->hosted_by;



                        $images = EventImage::where('event_id', $value->id)->first();

                        $eventDetail['event_images'] = ($images != null) ? asset('public/storage/event_images/' . $images->image) : "";

                        $eventDetail['event_date'] = $value->start_date;

                        $carbonDate = Carbon::createFromTimestamp($value->rsvp_start_time);
                        // $carbonDate->setTimezone($value->rsvp_start_timezone);
                        $timeInAMPM = $carbonDate->format('g:i A');
                        $eventDetail['start_time'] =  $timeInAMPM;

                        $eventDetail['rsvp_start_timezone'] = $value->rsvp_start_timezone;

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



                        $total_notification = Notification::where(['event_id' => $value->id, 'user_id' => $user->id, 'notification_type' => '0'])->count();

                        $eventDetail['total_notification'] = $total_notification;

                        $totalEvent =  Event::where('user_id', $value->user->id)->count();
                        $totalEventPhotos =  EventPostPhoto::where('user_id', $value->user->id)->count();
                        $comments =  EventPostComment::where('user_id', $value->user->id)->count();
                        $photocomments =  EventPostPhotoComment::where('user_id', $value->user->id)->count();
                        $eventDetail['user_profile'] = [
                            'id' => $value->user->id,
                            'profile' => empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile),
                            'username' => $value->user->firstname . ' ' . $value->user->lastname,
                            'location' => ($value->user->city != NULL) ? $value->user->city : "",
                            'about_me' => ($value->user->about_me != NULL) ? $value->user->about_me : "",
                            'created_at' => empty($value->user->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($value->user->created_at))),
                            'total_events' => $totalEvent,
                            'total_photos' => $totalEventPhotos,
                            'comments' => $comments + $photocomments,
                        ];

                        $PastEventList[] = $eventDetail;
                    }
                }
                $eventList['past_event'] =  $PastEventList;
            }


            // Past Event // 



            return response()->json(['status' => 1, 'total_allEvent_page' => $total_allEvent_page, 'total_invitedTo_page' => $total_invitedTo_page, "total_pastEvent_page" => $total_pastEvent_page, 'data' => $eventList, 'message' => "All events"]);
        } catch (QueryException $e) {
            return response()->json(['status' => 0, 'message' => "db error"]);
        }
        // catch (Exception $e) {
        //     return response()->json(['status' => 0, 'message' => "something went wrong"]);
        // }
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

            'message_to_host' => "required",
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),

            ]);
        }



        try {

            DB::beginTransaction();

            $video = "";


            if (!empty($request->message_by_video)) {



                $video = $request->message_by_video;

                $videoName = time() . '_' . $video->getClientOriginalName();

                Storage::disk('public')->putFileAs('rsvp_video', $video, $videoName);

                $video = $videoName;
            }



            $rsvpSent = EventInvitedUser::whereHas('user', function ($query) {

                $query->where('app_user', '1');
            })->where(['user_id' => $user->id, 'event_id' => $request->event_id])->first();

            if ($rsvpSent != null) {

                $rsvpSent->event_id = $request->event_id;

                $rsvpSent->user_id = $user->id;

                $rsvpSent->rsvp_status = $request->rsvp_status;

                $rsvpSent->adults = $request->adults;

                $rsvpSent->kids = $request->kids;

                $rsvpSent->message_to_host = $request->message_to_host;

                $rsvpSent->message_by_video = $video;

                $rsvpSent->read = '1';
                $rsvpSent->rsvp_d = '1';

                $rsvpSent->event_view_date = date('Y-m-d');

                $rsvpSent->save();

                $notificationParam = [

                    'sender_id' => $user->id,
                    'event_id' => $request->event_id,
                    'rsvp_status' => $request->rsvp_status
                ];



                sendNotification('sent_rsvp', $notificationParam);

                DB::commit();
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
            $eventDetail = Event::with(['user', 'event_image', 'event_schedule', 'event_invited_user' => function ($query) {

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

            $eventDetails['event_date'] = $eventDetail->start_date;
            $eventDetails['event_time'] = "";
            if ($eventDetail->event_schedule->isNotEmpty()) {

                $eventDetails['event_time'] = $eventDetail->event_schedule->first()->event_schedule . ' to ' . $eventDetail->event_schedule->last()->event_schedule;
            }


            $eventDetails['rsvp_by'] = (!empty($eventDetail->rsvp_by_date) || $eventDetail->rsvp_by_date != NULL) ? $eventDetail->rsvp_by_date : date('Y-m-d', strtotime($eventDetail->created_at));



            $current_date = Carbon::now();

            $eventDate = $eventDetail->start_date;





            $datetime1 = Carbon::parse($eventDate);

            $datetime2 =  Carbon::parse($current_date);



            $till_days = $datetime1->diff($datetime2)->days;

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

                $scheduleDetail['id'] = $value->id;

                $scheduleDetail['event_name'] = $value->event_name;

                $scheduleDetail['event_schedule'] = $value->event_schedule;

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
            $total_photos = EventPostPhoto::withCount(['event_post_photo_data'])->where(['event_id' => $eventDetail->id])->get();
            $totalPhotoCount = 0;
            if (!empty($total_photos)) {
                foreach ($total_photos as $countVal) {
                    $totalPhotoCount += $countVal->event_post_photo_data_count;
                }
            }
            $eventAboutHost['photo_uploaded'] = $totalPhotoCount;

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

        try {

            $this->eventViewUser($user->id, $input['event_id']);

            $currentDateTime = Carbon::now();

            $wallData = [];

            $wallData['owner_stories'] = [];

            $eventLoginUserStoriesList = EventUserStory::with(['user', 'user_event_story' => function ($query) use ($currentDateTime) {
                $query->where('created_at', '>', $currentDateTime->subHours(24));
            }])->where(['event_id' => $input['event_id'], 'user_id' => $user->id])->where('created_at', '>', $currentDateTime->subHours(24))->first();


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
                    $storiesData['post_time'] =  $this->setpostTime($storyVal->created_at);
                    if ($storyVal->type == 'video') {

                        $storiesData['video_duration'] = (!empty($storyVal->duration)) ? $storyVal->duration : "";
                    }
                    $storiesData['post_time'] =  $this->setpostTime($storyVal->created_at);
                    $storiesDetaill['story'][] = $storiesData;
                }
                $wallData['owner_stories'][] = $storiesDetaill;
            }


            $eventStoriesList = EventUserStory::with(['user', 'user_event_story' => function ($query) use ($currentDateTime) {
                $query->where('created_at', '>', $currentDateTime->subHours(24));
            }])->where('event_id', $input['event_id'])->where('user_id', '!=', $user->id)->where('created_at', '>', $currentDateTime->subHours(24))->get();


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
                        if ($storyVal->type == 'video') {

                            $storiesData['video_duration'] = (!empty($storyVal->duration)) ? $storyVal->duration : "";
                        }
                        $storiesData['post_time'] =  $this->setpostTime($storyVal->created_at);
                        $storyAlldata[] = $storiesData;
                    }
                    $storiesDetaill['story'] = $storyAlldata;
                    $storiesList[] = $storiesDetaill;
                }
            }


            //  Posts List //



            $selectedFilters = $request->input('filters', ['host_update']);
            $eventCreator = Event::where('id', $input['event_id'])->first();

            $eventPostList = EventPost::query();
            $eventPostList->with(['user'])->withCount(['event_post_comment' => function ($query) {
                $query->where('parent_comment_id', NULL);
            }, 'event_post_reaction'])->where('event_id', $input['event_id'])->orderBy('id', 'desc');



            if (!empty($selectedFilters) && !in_array('all', $selectedFilters)) {

                foreach ($selectedFilters as $filter) {

                    switch ($filter) {
                        case 'host_update':

                            $eventPostList->where('user_id', '=', $eventCreator->user_id);

                            break;
                        case 'video_uploads':
                            $eventPostList->where('post_type', '1')->with(['post_image' => function ($qury) {
                                $qury->where('type', 'video');
                            }]);
                            break;
                        case 'photo_uploads':
                            $eventPostList->where('post_type', '1')->with(['post_image' => function ($qury) {
                                $qury->where('type', 'image');
                            }]);
                            break;
                        case 'polls':
                            $eventPostList->where('post_type', '2');
                            break;
                        case 'comments':
                            $eventPostList->where('post_type', '0');
                            break;
                            // Add more cases for other filters if needed
                    }
                }
            }

            $results = $eventPostList->get();

            $postList = [];

            // $this->checkUserTypeForPost($input['event_id'], $user->id);

            $checkEventOwner = Event::where(['id' => $input['event_id'], 'user_id' => $user->id])->first();

            if (!empty($checkEventOwner)) {

                if (count($results) != 0) {



                    foreach ($results as $value) {

                        $checkUserRsvp = checkUserAttendOrNot($value->event_id, $value->user->id);

                        $postControl = PostControl::where(['user_id' => $user->id, 'event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                        if ($postControl != null) {

                            if ($postControl->post_control == 'hide_post') {
                                continue;
                            }
                        }
                        $checkUserIsReaction = EventPostReaction::where(['event_id' => $input['event_id'], 'event_post_id' => $value->id, 'user_id' => $user->id])->first();
                        if ($value->post_type == '0') { // Normal

                            $postsNormalDetail['id'] =  $value->id;

                            $postsNormalDetail['user_id'] =  $value->user->id;

                            $postsNormalDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                            $postsNormalDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                            $postsNormalDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;

                            $postsNormalDetail['rsvp_status'] = $checkUserRsvp;
                            $postsNormalDetail['location'] = ($value->user->city != NULL) ? $value->user->city : "";



                            $postsNormalDetail['post_type'] = $value->post_type;

                            $postsNormalDetail['created_at'] = $value->created_at;
                            $postsNormalDetail['posttime'] = setpostTime($value->created_at);



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

                            // postDetail // 





                            $postDetails = [];




                            $postReaction = [];

                            $postReactions = getReaction($value->id);

                            foreach ($postReactions as $reactionVal) {

                                $reactionInfo['id'] = $reactionVal->id;

                                $reactionInfo['event_post_id'] = $reactionVal->event_post_id;

                                $reactionInfo['reaction'] = $reactionVal->reaction;

                                $reactionInfo['user_id'] = $reactionVal->user_id;

                                $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                                $postReaction[] = $reactionInfo;
                            }

                            $postNormalDetailList['post_reaction'] = $postReaction;
                            $postNormalDetailList['reactionList'] = $reactionList;

                            $postNormalDetailList['total_comment'] = $value->event_post_comment_count;

                            $postNormalDetailList['total_likes'] = $value->event_post_reaction_count;

                            $postNormalDetailList['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                            $postNormalDetailList['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";


                            $postCommentList = [];

                            $postComment = getComments($value->id);



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

                            $postNormalDetailList['post_comment'] = $postCommentList;





                            $postDetails[] = $postNormalDetailList;



                            $postsNormalDetail['post_detail'] = $postDetails;





                            $postList[] = $postsNormalDetail;
                        }

                        if ($value->post_type == '1') { // Image

                            $postsImageDetail['id'] =  $value->id;

                            $postsImageDetail['user_id'] =  $value->user->id;

                            $postsImageDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                            $postsImageDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);
                            $postsImageDetail['location'] =  ($value->user->city != NULL) ? $value->user->city : "";
                            $postsImageDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;

                            $postsImageDetail['rsvp_status'] = $checkUserRsvp;



                            $eventPostImage = EventPostImage::where(['event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                            $postsImageDetail['post_image'] = empty($eventPostImage->post_image) ? "" : asset('public/storage/post_image/' . $eventPostImage->post_image);



                            $postsImageDetail['post_type'] = $value->post_type;

                            $postsImageDetail['created_at'] = $value->created_at;

                            $postsImageDetail['posttime'] = setpostTime($value->created_at);

                            $reactionList = getOnlyReaction($value->id);


                            $postsImageDetail['reactionList'] = $reactionList;

                            $postsImageDetail['total_comment'] = $value->event_post_comment_count;

                            $postsImageDetail['total_likes'] = $value->event_post_reaction_count;

                            $postsImageDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                            $postsImageDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";


                            $postsImageDetail['is_owner_post'] = ($value->user->id == $user->id) ? 1 : 0;
                            $postsImageDetail['is_mute'] =  0;
                            if ($postControl != null) {

                                if ($postControl->post_control == 'mute') {
                                    $postsImageDetail['is_mute'] =  1;
                                }
                            }


                            // postDetail // 



                            $postImages = getPostImages($value->id);

                            $postDetails = [];

                            $postImg = [];


                            foreach ($postImages as $imgVal) {





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

                                $postImg[] = $postMedia;
                            }

                            $postImgDetailList['post_image'] = $postImg;





                            $postReaction = [];

                            $postReactions = getReaction($value->id);

                            foreach ($postReactions as $reactionVal) {

                                $reactionInfo['id'] = $reactionVal->id;

                                $reactionInfo['event_post_id'] = $reactionVal->event_post_id;

                                $reactionInfo['reaction'] = $reactionVal->reaction;

                                $reactionInfo['user_id'] = $reactionVal->user_id;

                                $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                                $postReaction[] = $reactionInfo;
                            }

                            $postImgDetailList['post_reaction'] = $postReaction;
                            $postImgDetailList['reactionList'] = $reactionList;

                            $postImgDetailList['total_comment'] = $value->event_post_comment_count;

                            $postImgDetailList['total_likes'] = $value->event_post_reaction_count;

                            $postImgDetailList['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                            $postImgDetailList['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";




                            $postCommentList = [];

                            $postComment = getComments($value->id);



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

                            $postImgDetailList['post_comment'] = $postCommentList;





                            $postDetails[] = $postImgDetailList;



                            $postsImageDetail['post_detail'] = $postDetails;





                            $postList[] = $postsImageDetail;
                        }

                        if ($value->post_type == '2') { // Poll

                            $postsPollDetail['id'] =  $value->id;

                            $postsPollDetail['user_id'] =  $value->user->id;

                            $postsPollDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                            $postsPollDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);


                            $postsPollDetail['post_message'] =  empty($value->post_message) ? "" :  $value->post_message;
                            $postsPollDetail['location'] =  ($value->user->city != NULL) ? $value->user->city : "";
                            $polls = EventPostPoll::with('event_poll_option')->withCount('user_poll_data')->where(['event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                            $postsPollDetail['total_poll_vote'] = $polls->user_poll_data_count;

                            $postsPollDetail['poll_duration'] =  empty($polls->poll_duration) ? "" :  $polls->poll_duration;
                            $leftDay = (int) preg_replace('/[^0-9]/', '', $polls->poll_duration);
                            $postsPollDetail['is_expired'] =  (dateDiffer($polls->created_at) > $leftDay) ? true : false;

                            $postsPollDetail['poll_id'] = $polls->id;

                            $postsPollDetail['poll_question'] = $polls->poll_question;

                            $postsPollDetail['poll_option'] = [];

                            foreach ($polls->event_poll_option as $optionValue) {

                                $optionData['id'] = $optionValue->id;

                                $optionData['option'] = $optionValue->option;

                                $optionData['total_vote'] =  round(getOptionTotalVote($optionValue->id) * 100 / getTotalEventInvitedUser($input['event_id'])) . "%";
                                $optionData['is_poll_selected'] = checkUserGivePoll($user->id, $polls->id, $optionValue->id);


                                $postsPollDetail['poll_option'][] = $optionData;
                            }

                            $postsPollDetail['post_type'] = $value->post_type;

                            $postsPollDetail['rsvp_status'] = $checkUserRsvp;

                            $postsPollDetail['created_at'] = $value->created_at;
                            $postsPollDetail['posttime'] = setpostTime($value->created_at);
                            $reactionList = getOnlyReaction($value->id);


                            $postsPollDetail['reactionList'] = $reactionList;

                            $postsPollDetail['total_comment'] = $value->event_post_comment_count;

                            $postsPollDetail['total_likes'] = $value->event_post_reaction_count;

                            $postsPollDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                            $postsPollDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";

                            $postsPollDetail['is_owner_post'] = ($value->user->id == $user->id) ? 1 : 0;
                            $postsPollDetail['is_mute'] =  0;
                            if ($postControl != null) {

                                if ($postControl->post_control == 'mute') {
                                    $postsPollDetail['is_mute'] =  1;
                                }
                            }

                            // postDetail // 



                            $postDetails = [];

                            $postReaction = [];

                            $postReactions = getReaction($value->id);

                            foreach ($postReactions as $reactionVal) {

                                $reactionInfo['id'] = $reactionVal->id;

                                $reactionInfo['event_post_id'] = $reactionVal->event_post_id;

                                $reactionInfo['reaction'] = $reactionVal->reaction;

                                $reactionInfo['user_id'] = $reactionVal->user_id;

                                $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                                $postReaction[] = $reactionInfo;
                            }

                            $postPollDetailList['post_reaction'] = $postReaction;


                            $postPollDetailList['reactionList'] = $reactionList;

                            $postPollDetailList['total_comment'] = $value->event_post_comment_count;

                            $postPollDetailList['total_likes'] = $value->event_post_reaction_count;

                            $postPollDetailList['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                            $postPollDetailList['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";


                            $postCommentList = [];

                            $postComment = getComments($value->id);



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

                            $postList[] = $postsPollDetail;
                        }

                        if ($value->post_type == '3') { // record

                            $postsRecordDetail['id'] =  $value->id;

                            $postsRecordDetail['user_id'] =  $value->user->id;

                            $postsRecordDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                            $postsRecordDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                            $postsRecordDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;
                            $postsRecordDetail['location'] = ($value->user->city != NULL) ? $value->user->city : "";
                            $postsRecordDetail['post_recording'] = empty($value->post_recording) ? "" : asset('public/storage/event_post_recording/' . $value->post_recording);

                            $postsRecordDetail['post_type'] = $value->post_type;

                            $postsRecordDetail['created_at'] = $value->created_at;
                            $postsRecordDetail['posttime'] = setpostTime($value->created_at);
                            $reactionList = getOnlyReaction($value->id);


                            $postsRecordDetail['rsvp_status'] = $checkUserRsvp;

                            $postsRecordDetail['reactionList'] = $reactionList;

                            $postsRecordDetail['total_comment'] = $value->event_post_comment_count;

                            $postsRecordDetail['total_likes'] = $value->event_post_reaction_count;

                            $postsRecordDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                            $postsRecordDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";

                            $postsRecordDetail['is_owner_post'] = ($value->user->id == $user->id) ? 1 : 0;
                            $postsRecordDetail['is_mute'] =  0;
                            if ($postControl != null) {

                                if ($postControl->post_control == 'mute') {
                                    $postsRecordDetail['is_mute'] =  1;
                                }
                            }
                            // postDetail // 



                            $postDetails = [];

                            $postReaction = [];

                            $postReactions = getReaction($value->id);

                            foreach ($postReactions as $reactionVal) {

                                $reactionInfo['id'] = $reactionVal->id;

                                $reactionInfo['event_post_id'] = $reactionVal->event_post_id;

                                $reactionInfo['reaction'] = $reactionVal->reaction;

                                $reactionInfo['user_id'] = $reactionVal->user_id;

                                $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                                $postReaction[] = $reactionInfo;
                            }

                            $postRecordDetailList['post_reaction'] = $postReaction;

                            $postRecordDetailList['reactionList'] = $reactionList;

                            $postRecordDetailList['total_comment'] = $value->event_post_comment_count;

                            $postRecordDetailList['total_likes'] = $value->event_post_reaction_count;

                            $postRecordDetailList['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                            $postRecordDetailList['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";



                            $postCommentList = [];

                            $postComment = getComments($value->id);



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

                            $postRecordDetailList['post_comment'] = $postCommentList;





                            $postDetails[] = $postRecordDetailList;

                            $postsRecordDetail['post_detail'] = $postDetails;

                            $postList[] = $postsRecordDetail;
                        }
                    }
                }
            } else {

                if (count($results) != 0) {



                    foreach ($results as $value) {

                        $checkUserRsvp = checkUserAttendOrNot($value->event_id, $value->user->id);


                        $postControl = PostControl::where(['user_id' => $user->id, 'event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                        if ($postControl != null) {

                            if ($postControl->post_control == 'hide_post') {
                                continue;
                            }
                        }

                        $checkUserIsReaction = EventPostReaction::where(['event_id' => $input['event_id'], 'event_post_id' => $value->id, 'user_id' => $user->id])->first();

                        if ($value->post_privacy == '1') {
                            if ($value->post_type == '0') { // Normal

                                $postsNormalDetail['id'] =  $value->id;

                                $postsNormalDetail['user_id'] =  $value->user->id;

                                $postsNormalDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                                $postsNormalDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                                $postsNormalDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;

                                $postsNormalDetail['rsvp_status'] = $checkUserRsvp;
                                $postsNormalDetail['location'] = ($value->user->city != NULL) ? $value->user->city : "";



                                $postsNormalDetail['post_type'] = $value->post_type;

                                $postsNormalDetail['created_at'] = $value->created_at;
                                $postsNormalDetail['posttime'] = setpostTime($value->created_at);



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

                                // postDetail // 





                                $postDetails = [];




                                $postReaction = [];

                                $postReactions = getReaction($value->id);

                                foreach ($postReactions as $reactionVal) {

                                    $reactionInfo['id'] = $reactionVal->id;

                                    $reactionInfo['event_post_id'] = $reactionVal->event_post_id;

                                    $reactionInfo['reaction'] = $reactionVal->reaction;

                                    $reactionInfo['user_id'] = $reactionVal->user_id;

                                    $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                                    $postReaction[] = $reactionInfo;
                                }

                                $postNormalDetailList['post_reaction'] = $postReaction;
                                $postNormalDetailList['reactionList'] = $reactionList;

                                $postNormalDetailList['total_comment'] = $value->event_post_comment_count;

                                $postNormalDetailList['total_likes'] = $value->event_post_reaction_count;

                                $postNormalDetailList['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postNormalDetailList['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";


                                $postCommentList = [];

                                $postComment = getComments($value->id);



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

                                $postNormalDetailList['post_comment'] = $postCommentList;





                                $postDetails[] = $postNormalDetailList;



                                $postsNormalDetail['post_detail'] = $postDetails;





                                $postList[] = $postsNormalDetail;
                            }

                            if ($value->post_type == '1') { // Image

                                $postsImageDetail['id'] =  $value->id;

                                $postsImageDetail['user_id'] =  $value->user->id;

                                $postsImageDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                                $postsImageDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);
                                $postsImageDetail['location'] =  ($value->user->city != NULL) ? $value->user->city : "";
                                $postsImageDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;

                                $postsImageDetail['rsvp_status'] = $checkUserRsvp;



                                $eventPostImage = EventPostImage::where(['event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                                $postsImageDetail['post_image'] = empty($eventPostImage->post_image) ? "" : asset('public/storage/post_image/' . $eventPostImage->post_image);



                                $postsImageDetail['post_type'] = $value->post_type;

                                $postsImageDetail['created_at'] = $value->created_at;

                                $postsImageDetail['posttime'] = setpostTime($value->created_at);

                                $reactionList = getOnlyReaction($value->id);


                                $postsImageDetail['reactionList'] = $reactionList;

                                $postsImageDetail['total_comment'] = $value->event_post_comment_count;

                                $postsImageDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsImageDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsImageDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";


                                $postsImageDetail['is_owner_post'] = ($value->user->id == $user->id) ? 1 : 0;
                                $postsImageDetail['is_mute'] =  0;
                                if ($postControl != null) {

                                    if ($postControl->post_control == 'mute') {
                                        $postsImageDetail['is_mute'] =  1;
                                    }
                                }


                                // postDetail // 



                                $postImages = getPostImages($value->id);

                                $postDetails = [];

                                $postImg = [];


                                foreach ($postImages as $imgVal) {





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

                                    $postImg[] = $postMedia;
                                }

                                $postImgDetailList['post_image'] = $postImg;





                                $postReaction = [];

                                $postReactions = getReaction($value->id);

                                foreach ($postReactions as $reactionVal) {

                                    $reactionInfo['id'] = $reactionVal->id;

                                    $reactionInfo['event_post_id'] = $reactionVal->event_post_id;

                                    $reactionInfo['reaction'] = $reactionVal->reaction;

                                    $reactionInfo['user_id'] = $reactionVal->user_id;

                                    $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                                    $postReaction[] = $reactionInfo;
                                }

                                $postImgDetailList['post_reaction'] = $postReaction;
                                $postImgDetailList['reactionList'] = $reactionList;

                                $postImgDetailList['total_comment'] = $value->event_post_comment_count;

                                $postImgDetailList['total_likes'] = $value->event_post_reaction_count;

                                $postImgDetailList['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postImgDetailList['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";




                                $postCommentList = [];

                                $postComment = getComments($value->id);



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

                                $postImgDetailList['post_comment'] = $postCommentList;





                                $postDetails[] = $postImgDetailList;



                                $postsImageDetail['post_detail'] = $postDetails;





                                $postList[] = $postsImageDetail;
                            }

                            if ($value->post_type == '2') { // Poll

                                $postsPollDetail['id'] =  $value->id;

                                $postsPollDetail['user_id'] =  $value->user->id;

                                $postsPollDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                                $postsPollDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);


                                $postsPollDetail['post_message'] =  empty($value->post_message) ? "" :  $value->post_message;
                                $postsPollDetail['location'] =  ($value->user->city != NULL) ? $value->user->city : "";
                                $polls = EventPostPoll::with('event_poll_option')->withCount('user_poll_data')->where(['event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                                $postsPollDetail['total_poll_vote'] = $polls->user_poll_data_count;

                                $postsPollDetail['poll_duration'] =  empty($polls->poll_duration) ? "" :  $polls->poll_duration;
                                $leftDay = (int) preg_replace('/[^0-9]/', '', $polls->poll_duration);
                                $postsPollDetail['is_expired'] =  (dateDiffer($polls->created_at) > $leftDay) ? true : false;

                                $postsPollDetail['poll_id'] = $polls->id;

                                $postsPollDetail['poll_question'] = $polls->poll_question;

                                $postsPollDetail['poll_option'] = [];

                                foreach ($polls->event_poll_option as $optionValue) {

                                    $optionData['id'] = $optionValue->id;

                                    $optionData['option'] = $optionValue->option;

                                    $optionData['total_vote'] =  round(getOptionTotalVote($optionValue->id) * 100 / getTotalEventInvitedUser($input['event_id'])) . "%";
                                    $optionData['is_poll_selected'] = checkUserGivePoll($user->id, $polls->id, $optionValue->id);


                                    $postsPollDetail['poll_option'][] = $optionData;
                                }

                                $postsPollDetail['post_type'] = $value->post_type;

                                $postsPollDetail['rsvp_status'] = $checkUserRsvp;

                                $postsPollDetail['created_at'] = $value->created_at;
                                $postsPollDetail['posttime'] = setpostTime($value->created_at);
                                $reactionList = getOnlyReaction($value->id);


                                $postsPollDetail['reactionList'] = $reactionList;

                                $postsPollDetail['total_comment'] = $value->event_post_comment_count;

                                $postsPollDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsPollDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsPollDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";

                                $postsPollDetail['is_owner_post'] = ($value->user->id == $user->id) ? 1 : 0;
                                $postsPollDetail['is_mute'] =  0;
                                if ($postControl != null) {

                                    if ($postControl->post_control == 'mute') {
                                        $postsPollDetail['is_mute'] =  1;
                                    }
                                }

                                // postDetail // 



                                $postDetails = [];

                                $postReaction = [];

                                $postReactions = getReaction($value->id);

                                foreach ($postReactions as $reactionVal) {

                                    $reactionInfo['id'] = $reactionVal->id;

                                    $reactionInfo['event_post_id'] = $reactionVal->event_post_id;

                                    $reactionInfo['reaction'] = $reactionVal->reaction;

                                    $reactionInfo['user_id'] = $reactionVal->user_id;

                                    $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                                    $postReaction[] = $reactionInfo;
                                }

                                $postPollDetailList['post_reaction'] = $postReaction;


                                $postPollDetailList['reactionList'] = $reactionList;

                                $postPollDetailList['total_comment'] = $value->event_post_comment_count;

                                $postPollDetailList['total_likes'] = $value->event_post_reaction_count;

                                $postPollDetailList['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postPollDetailList['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";


                                $postCommentList = [];

                                $postComment = getComments($value->id);



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

                                $postList[] = $postsPollDetail;
                            }

                            if ($value->post_type == '3') { // record

                                $postsRecordDetail['id'] =  $value->id;

                                $postsRecordDetail['user_id'] =  $value->user->id;

                                $postsRecordDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                                $postsRecordDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                                $postsRecordDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;
                                $postsRecordDetail['location'] = ($value->user->city != NULL) ? $value->user->city : "";
                                $postsRecordDetail['post_recording'] = empty($value->post_recording) ? "" : asset('public/storage/event_post_recording/' . $value->post_recording);

                                $postsRecordDetail['post_type'] = $value->post_type;

                                $postsRecordDetail['created_at'] = $value->created_at;
                                $postsRecordDetail['posttime'] = setpostTime($value->created_at);
                                $reactionList = getOnlyReaction($value->id);


                                $postsRecordDetail['rsvp_status'] = $checkUserRsvp;

                                $postsRecordDetail['reactionList'] = $reactionList;

                                $postsRecordDetail['total_comment'] = $value->event_post_comment_count;

                                $postsRecordDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsRecordDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsRecordDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";

                                $postsRecordDetail['is_owner_post'] = ($value->user->id == $user->id) ? 1 : 0;
                                $postsRecordDetail['is_mute'] =  0;
                                if ($postControl != null) {

                                    if ($postControl->post_control == 'mute') {
                                        $postsRecordDetail['is_mute'] =  1;
                                    }
                                }
                                // postDetail // 



                                $postDetails = [];

                                $postReaction = [];

                                $postReactions = getReaction($value->id);

                                foreach ($postReactions as $reactionVal) {

                                    $reactionInfo['id'] = $reactionVal->id;

                                    $reactionInfo['event_post_id'] = $reactionVal->event_post_id;

                                    $reactionInfo['reaction'] = $reactionVal->reaction;

                                    $reactionInfo['user_id'] = $reactionVal->user_id;

                                    $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                                    $postReaction[] = $reactionInfo;
                                }

                                $postRecordDetailList['post_reaction'] = $postReaction;

                                $postRecordDetailList['reactionList'] = $reactionList;

                                $postRecordDetailList['total_comment'] = $value->event_post_comment_count;

                                $postRecordDetailList['total_likes'] = $value->event_post_reaction_count;

                                $postRecordDetailList['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postRecordDetailList['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";



                                $postCommentList = [];

                                $postComment = getComments($value->id);



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

                                $postRecordDetailList['post_comment'] = $postCommentList;





                                $postDetails[] = $postRecordDetailList;

                                $postsRecordDetail['post_detail'] = $postDetails;

                                $postList[] = $postsRecordDetail;
                            }
                        }

                        //  reply by user and  RSVP



                        $checkUserTypeForPost = EventInvitedUser::whereHas('user', function ($query) {

                            $query->where('app_user', '1');
                        })->where(['event_id' => $input['event_id'], 'user_id' => $user->id])->first();



                        if ($checkUserTypeForPost->rsvp_d == '1' && $checkUserTypeForPost->rsvp_status == '1'  && $value->post_privacy == '2') {
                            if ($value->post_type == '0') { // Normal

                                $postsNormalDetail['id'] =  $value->id;

                                $postsNormalDetail['user_id'] =  $value->user->id;

                                $postsNormalDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                                $postsNormalDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                                $postsNormalDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;

                                $postsNormalDetail['rsvp_status'] = $checkUserRsvp;
                                $postsNormalDetail['location'] = ($value->user->city != NULL) ? $value->user->city : "";



                                $postsNormalDetail['post_type'] = $value->post_type;

                                $postsNormalDetail['created_at'] = $value->created_at;
                                $postsNormalDetail['posttime'] = setpostTime($value->created_at);



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

                                // postDetail // 





                                $postDetails = [];




                                $postReaction = [];

                                $postReactions = getReaction($value->id);

                                foreach ($postReactions as $reactionVal) {

                                    $reactionInfo['id'] = $reactionVal->id;

                                    $reactionInfo['event_post_id'] = $reactionVal->event_post_id;

                                    $reactionInfo['reaction'] = $reactionVal->reaction;

                                    $reactionInfo['user_id'] = $reactionVal->user_id;

                                    $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                                    $postReaction[] = $reactionInfo;
                                }

                                $postNormalDetailList['post_reaction'] = $postReaction;
                                $postNormalDetailList['reactionList'] = $reactionList;

                                $postNormalDetailList['total_comment'] = $value->event_post_comment_count;

                                $postNormalDetailList['total_likes'] = $value->event_post_reaction_count;

                                $postNormalDetailList['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postNormalDetailList['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";


                                $postCommentList = [];

                                $postComment = getComments($value->id);



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

                                $postNormalDetailList['post_comment'] = $postCommentList;





                                $postDetails[] = $postNormalDetailList;



                                $postsNormalDetail['post_detail'] = $postDetails;





                                $postList[] = $postsNormalDetail;
                            }

                            if ($value->post_type == '1') { // Image

                                $postsImageDetail['id'] =  $value->id;

                                $postsImageDetail['user_id'] =  $value->user->id;

                                $postsImageDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                                $postsImageDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);
                                $postsImageDetail['location'] =  ($value->user->city != NULL) ? $value->user->city : "";
                                $postsImageDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;

                                $postsImageDetail['rsvp_status'] = $checkUserRsvp;



                                $eventPostImage = EventPostImage::where(['event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                                $postsImageDetail['post_image'] = empty($eventPostImage->post_image) ? "" : asset('public/storage/post_image/' . $eventPostImage->post_image);



                                $postsImageDetail['post_type'] = $value->post_type;

                                $postsImageDetail['created_at'] = $value->created_at;

                                $postsImageDetail['posttime'] = setpostTime($value->created_at);

                                $reactionList = getOnlyReaction($value->id);


                                $postsImageDetail['reactionList'] = $reactionList;

                                $postsImageDetail['total_comment'] = $value->event_post_comment_count;

                                $postsImageDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsImageDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsImageDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";


                                $postsImageDetail['is_owner_post'] = ($value->user->id == $user->id) ? 1 : 0;
                                $postsImageDetail['is_mute'] =  0;
                                if ($postControl != null) {

                                    if ($postControl->post_control == 'mute') {
                                        $postsImageDetail['is_mute'] =  1;
                                    }
                                }


                                // postDetail // 



                                $postImages = getPostImages($value->id);

                                $postDetails = [];

                                $postImg = [];


                                foreach ($postImages as $imgVal) {





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

                                    $postImg[] = $postMedia;
                                }

                                $postImgDetailList['post_image'] = $postImg;





                                $postReaction = [];

                                $postReactions = getReaction($value->id);

                                foreach ($postReactions as $reactionVal) {

                                    $reactionInfo['id'] = $reactionVal->id;

                                    $reactionInfo['event_post_id'] = $reactionVal->event_post_id;

                                    $reactionInfo['reaction'] = $reactionVal->reaction;

                                    $reactionInfo['user_id'] = $reactionVal->user_id;

                                    $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                                    $postReaction[] = $reactionInfo;
                                }

                                $postImgDetailList['post_reaction'] = $postReaction;
                                $postImgDetailList['reactionList'] = $reactionList;

                                $postImgDetailList['total_comment'] = $value->event_post_comment_count;

                                $postImgDetailList['total_likes'] = $value->event_post_reaction_count;

                                $postImgDetailList['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postImgDetailList['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";




                                $postCommentList = [];

                                $postComment = getComments($value->id);



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

                                $postImgDetailList['post_comment'] = $postCommentList;





                                $postDetails[] = $postImgDetailList;



                                $postsImageDetail['post_detail'] = $postDetails;





                                $postList[] = $postsImageDetail;
                            }

                            if ($value->post_type == '2') { // Poll

                                $postsPollDetail['id'] =  $value->id;

                                $postsPollDetail['user_id'] =  $value->user->id;

                                $postsPollDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                                $postsPollDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);


                                $postsPollDetail['post_message'] =  empty($value->post_message) ? "" :  $value->post_message;
                                $postsPollDetail['location'] =  ($value->user->city != NULL) ? $value->user->city : "";
                                $polls = EventPostPoll::with('event_poll_option')->withCount('user_poll_data')->where(['event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                                $postsPollDetail['total_poll_vote'] = $polls->user_poll_data_count;

                                $postsPollDetail['poll_duration'] =  empty($polls->poll_duration) ? "" :  $polls->poll_duration;
                                $leftDay = (int) preg_replace('/[^0-9]/', '', $polls->poll_duration);
                                $postsPollDetail['is_expired'] =  (dateDiffer($polls->created_at) > $leftDay) ? true : false;

                                $postsPollDetail['poll_id'] = $polls->id;

                                $postsPollDetail['poll_question'] = $polls->poll_question;

                                $postsPollDetail['poll_option'] = [];

                                foreach ($polls->event_poll_option as $optionValue) {

                                    $optionData['id'] = $optionValue->id;

                                    $optionData['option'] = $optionValue->option;

                                    $optionData['total_vote'] =  round(getOptionTotalVote($optionValue->id) * 100 / getTotalEventInvitedUser($input['event_id'])) . "%";
                                    $optionData['is_poll_selected'] = checkUserGivePoll($user->id, $polls->id, $optionValue->id);


                                    $postsPollDetail['poll_option'][] = $optionData;
                                }

                                $postsPollDetail['post_type'] = $value->post_type;

                                $postsPollDetail['rsvp_status'] = $checkUserRsvp;

                                $postsPollDetail['created_at'] = $value->created_at;
                                $postsPollDetail['posttime'] = setpostTime($value->created_at);
                                $reactionList = getOnlyReaction($value->id);


                                $postsPollDetail['reactionList'] = $reactionList;

                                $postsPollDetail['total_comment'] = $value->event_post_comment_count;

                                $postsPollDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsPollDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsPollDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";

                                $postsPollDetail['is_owner_post'] = ($value->user->id == $user->id) ? 1 : 0;
                                $postsPollDetail['is_mute'] =  0;
                                if ($postControl != null) {

                                    if ($postControl->post_control == 'mute') {
                                        $postsPollDetail['is_mute'] =  1;
                                    }
                                }

                                // postDetail // 



                                $postDetails = [];

                                $postReaction = [];

                                $postReactions = getReaction($value->id);

                                foreach ($postReactions as $reactionVal) {

                                    $reactionInfo['id'] = $reactionVal->id;

                                    $reactionInfo['event_post_id'] = $reactionVal->event_post_id;

                                    $reactionInfo['reaction'] = $reactionVal->reaction;

                                    $reactionInfo['user_id'] = $reactionVal->user_id;

                                    $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                                    $postReaction[] = $reactionInfo;
                                }

                                $postPollDetailList['post_reaction'] = $postReaction;


                                $postPollDetailList['reactionList'] = $reactionList;

                                $postPollDetailList['total_comment'] = $value->event_post_comment_count;

                                $postPollDetailList['total_likes'] = $value->event_post_reaction_count;

                                $postPollDetailList['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postPollDetailList['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";


                                $postCommentList = [];

                                $postComment = getComments($value->id);



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

                                $postList[] = $postsPollDetail;
                            }

                            if ($value->post_type == '3') { // record

                                $postsRecordDetail['id'] =  $value->id;

                                $postsRecordDetail['user_id'] =  $value->user->id;

                                $postsRecordDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                                $postsRecordDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                                $postsRecordDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;
                                $postsRecordDetail['location'] = ($value->user->city != NULL) ? $value->user->city : "";
                                $postsRecordDetail['post_recording'] = empty($value->post_recording) ? "" : asset('public/storage/event_post_recording/' . $value->post_recording);

                                $postsRecordDetail['post_type'] = $value->post_type;

                                $postsRecordDetail['created_at'] = $value->created_at;
                                $postsRecordDetail['posttime'] = setpostTime($value->created_at);
                                $reactionList = getOnlyReaction($value->id);


                                $postsRecordDetail['rsvp_status'] = $checkUserRsvp;

                                $postsRecordDetail['reactionList'] = $reactionList;

                                $postsRecordDetail['total_comment'] = $value->event_post_comment_count;

                                $postsRecordDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsRecordDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsRecordDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";

                                $postsRecordDetail['is_owner_post'] = ($value->user->id == $user->id) ? 1 : 0;
                                $postsRecordDetail['is_mute'] =  0;
                                if ($postControl != null) {

                                    if ($postControl->post_control == 'mute') {
                                        $postsRecordDetail['is_mute'] =  1;
                                    }
                                }
                                // postDetail // 



                                $postDetails = [];

                                $postReaction = [];

                                $postReactions = getReaction($value->id);

                                foreach ($postReactions as $reactionVal) {

                                    $reactionInfo['id'] = $reactionVal->id;

                                    $reactionInfo['event_post_id'] = $reactionVal->event_post_id;

                                    $reactionInfo['reaction'] = $reactionVal->reaction;

                                    $reactionInfo['user_id'] = $reactionVal->user_id;

                                    $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                                    $postReaction[] = $reactionInfo;
                                }

                                $postRecordDetailList['post_reaction'] = $postReaction;

                                $postRecordDetailList['reactionList'] = $reactionList;

                                $postRecordDetailList['total_comment'] = $value->event_post_comment_count;

                                $postRecordDetailList['total_likes'] = $value->event_post_reaction_count;

                                $postRecordDetailList['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postRecordDetailList['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";



                                $postCommentList = [];

                                $postComment = getComments($value->id);



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

                                $postRecordDetailList['post_comment'] = $postCommentList;





                                $postDetails[] = $postRecordDetailList;

                                $postsRecordDetail['post_detail'] = $postDetails;

                                $postList[] = $postsRecordDetail;
                            }
                        }



                        if ($checkUserTypeForPost->rsvp_d == '1' && $checkUserTypeForPost->rsvp_status == '0' && $value->post_privacy == '3') {

                            if ($value->post_type == '0') { // Normal

                                $postsNormalDetail['id'] =  $value->id;

                                $postsNormalDetail['user_id'] =  $value->user->id;

                                $postsNormalDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                                $postsNormalDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                                $postsNormalDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;

                                $postsNormalDetail['rsvp_status'] = $checkUserRsvp;
                                $postsNormalDetail['location'] = ($value->user->city != NULL) ? $value->user->city : "";



                                $postsNormalDetail['post_type'] = $value->post_type;

                                $postsNormalDetail['created_at'] = $value->created_at;
                                $postsNormalDetail['posttime'] = setpostTime($value->created_at);



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

                                // postDetail // 





                                $postDetails = [];




                                $postReaction = [];

                                $postReactions = getReaction($value->id);

                                foreach ($postReactions as $reactionVal) {

                                    $reactionInfo['id'] = $reactionVal->id;

                                    $reactionInfo['event_post_id'] = $reactionVal->event_post_id;

                                    $reactionInfo['reaction'] = $reactionVal->reaction;

                                    $reactionInfo['user_id'] = $reactionVal->user_id;

                                    $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                                    $postReaction[] = $reactionInfo;
                                }

                                $postNormalDetailList['post_reaction'] = $postReaction;
                                $postNormalDetailList['reactionList'] = $reactionList;

                                $postNormalDetailList['total_comment'] = $value->event_post_comment_count;

                                $postNormalDetailList['total_likes'] = $value->event_post_reaction_count;

                                $postNormalDetailList['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postNormalDetailList['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";


                                $postCommentList = [];

                                $postComment = getComments($value->id);



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

                                $postNormalDetailList['post_comment'] = $postCommentList;





                                $postDetails[] = $postNormalDetailList;



                                $postsNormalDetail['post_detail'] = $postDetails;





                                $postList[] = $postsNormalDetail;
                            }

                            if ($value->post_type == '1') { // Image

                                $postsImageDetail['id'] =  $value->id;

                                $postsImageDetail['user_id'] =  $value->user->id;

                                $postsImageDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                                $postsImageDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);
                                $postsImageDetail['location'] =  ($value->user->city != NULL) ? $value->user->city : "";
                                $postsImageDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;

                                $postsImageDetail['rsvp_status'] = $checkUserRsvp;



                                $eventPostImage = EventPostImage::where(['event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                                $postsImageDetail['post_image'] = empty($eventPostImage->post_image) ? "" : asset('public/storage/post_image/' . $eventPostImage->post_image);



                                $postsImageDetail['post_type'] = $value->post_type;

                                $postsImageDetail['created_at'] = $value->created_at;

                                $postsImageDetail['posttime'] = setpostTime($value->created_at);

                                $reactionList = getOnlyReaction($value->id);


                                $postsImageDetail['reactionList'] = $reactionList;

                                $postsImageDetail['total_comment'] = $value->event_post_comment_count;

                                $postsImageDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsImageDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsImageDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";


                                $postsImageDetail['is_owner_post'] = ($value->user->id == $user->id) ? 1 : 0;
                                $postsImageDetail['is_mute'] =  0;
                                if ($postControl != null) {

                                    if ($postControl->post_control == 'mute') {
                                        $postsImageDetail['is_mute'] =  1;
                                    }
                                }


                                // postDetail // 



                                $postImages = getPostImages($value->id);

                                $postDetails = [];

                                $postImg = [];


                                foreach ($postImages as $imgVal) {





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

                                    $postImg[] = $postMedia;
                                }

                                $postImgDetailList['post_image'] = $postImg;





                                $postReaction = [];

                                $postReactions = getReaction($value->id);

                                foreach ($postReactions as $reactionVal) {

                                    $reactionInfo['id'] = $reactionVal->id;

                                    $reactionInfo['event_post_id'] = $reactionVal->event_post_id;

                                    $reactionInfo['reaction'] = $reactionVal->reaction;

                                    $reactionInfo['user_id'] = $reactionVal->user_id;

                                    $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                                    $postReaction[] = $reactionInfo;
                                }

                                $postImgDetailList['post_reaction'] = $postReaction;
                                $postImgDetailList['reactionList'] = $reactionList;

                                $postImgDetailList['total_comment'] = $value->event_post_comment_count;

                                $postImgDetailList['total_likes'] = $value->event_post_reaction_count;

                                $postImgDetailList['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postImgDetailList['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";




                                $postCommentList = [];

                                $postComment = getComments($value->id);



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

                                $postImgDetailList['post_comment'] = $postCommentList;





                                $postDetails[] = $postImgDetailList;



                                $postsImageDetail['post_detail'] = $postDetails;





                                $postList[] = $postsImageDetail;
                            }

                            if ($value->post_type == '2') { // Poll

                                $postsPollDetail['id'] =  $value->id;

                                $postsPollDetail['user_id'] =  $value->user->id;

                                $postsPollDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                                $postsPollDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);


                                $postsPollDetail['post_message'] =  empty($value->post_message) ? "" :  $value->post_message;
                                $postsPollDetail['location'] =  ($value->user->city != NULL) ? $value->user->city : "";
                                $polls = EventPostPoll::with('event_poll_option')->withCount('user_poll_data')->where(['event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                                $postsPollDetail['total_poll_vote'] = $polls->user_poll_data_count;

                                $postsPollDetail['poll_duration'] =  empty($polls->poll_duration) ? "" :  $polls->poll_duration;
                                $leftDay = (int) preg_replace('/[^0-9]/', '', $polls->poll_duration);
                                $postsPollDetail['is_expired'] =  (dateDiffer($polls->created_at) > $leftDay) ? true : false;

                                $postsPollDetail['poll_id'] = $polls->id;

                                $postsPollDetail['poll_question'] = $polls->poll_question;

                                $postsPollDetail['poll_option'] = [];

                                foreach ($polls->event_poll_option as $optionValue) {

                                    $optionData['id'] = $optionValue->id;

                                    $optionData['option'] = $optionValue->option;

                                    $optionData['total_vote'] =  round(getOptionTotalVote($optionValue->id) * 100 / getTotalEventInvitedUser($input['event_id'])) . "%";
                                    $optionData['is_poll_selected'] = checkUserGivePoll($user->id, $polls->id, $optionValue->id);


                                    $postsPollDetail['poll_option'][] = $optionData;
                                }

                                $postsPollDetail['post_type'] = $value->post_type;

                                $postsPollDetail['rsvp_status'] = $checkUserRsvp;

                                $postsPollDetail['created_at'] = $value->created_at;
                                $postsPollDetail['posttime'] = setpostTime($value->created_at);
                                $reactionList = getOnlyReaction($value->id);


                                $postsPollDetail['reactionList'] = $reactionList;

                                $postsPollDetail['total_comment'] = $value->event_post_comment_count;

                                $postsPollDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsPollDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsPollDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";

                                $postsPollDetail['is_owner_post'] = ($value->user->id == $user->id) ? 1 : 0;
                                $postsPollDetail['is_mute'] =  0;
                                if ($postControl != null) {

                                    if ($postControl->post_control == 'mute') {
                                        $postsPollDetail['is_mute'] =  1;
                                    }
                                }

                                // postDetail // 



                                $postDetails = [];

                                $postReaction = [];

                                $postReactions = getReaction($value->id);

                                foreach ($postReactions as $reactionVal) {

                                    $reactionInfo['id'] = $reactionVal->id;

                                    $reactionInfo['event_post_id'] = $reactionVal->event_post_id;

                                    $reactionInfo['reaction'] = $reactionVal->reaction;

                                    $reactionInfo['user_id'] = $reactionVal->user_id;

                                    $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                                    $postReaction[] = $reactionInfo;
                                }

                                $postPollDetailList['post_reaction'] = $postReaction;


                                $postPollDetailList['reactionList'] = $reactionList;

                                $postPollDetailList['total_comment'] = $value->event_post_comment_count;

                                $postPollDetailList['total_likes'] = $value->event_post_reaction_count;

                                $postPollDetailList['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postPollDetailList['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";


                                $postCommentList = [];

                                $postComment = getComments($value->id);



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

                                $postList[] = $postsPollDetail;
                            }

                            if ($value->post_type == '3') { // record

                                $postsRecordDetail['id'] =  $value->id;

                                $postsRecordDetail['user_id'] =  $value->user->id;

                                $postsRecordDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                                $postsRecordDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                                $postsRecordDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;
                                $postsRecordDetail['location'] = ($value->user->city != NULL) ? $value->user->city : "";
                                $postsRecordDetail['post_recording'] = empty($value->post_recording) ? "" : asset('public/storage/event_post_recording/' . $value->post_recording);

                                $postsRecordDetail['post_type'] = $value->post_type;

                                $postsRecordDetail['created_at'] = $value->created_at;
                                $postsRecordDetail['posttime'] = setpostTime($value->created_at);
                                $reactionList = getOnlyReaction($value->id);


                                $postsRecordDetail['rsvp_status'] = $checkUserRsvp;

                                $postsRecordDetail['reactionList'] = $reactionList;

                                $postsRecordDetail['total_comment'] = $value->event_post_comment_count;

                                $postsRecordDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsRecordDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsRecordDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";

                                $postsRecordDetail['is_owner_post'] = ($value->user->id == $user->id) ? 1 : 0;
                                $postsRecordDetail['is_mute'] =  0;
                                if ($postControl != null) {

                                    if ($postControl->post_control == 'mute') {
                                        $postsRecordDetail['is_mute'] =  1;
                                    }
                                }
                                // postDetail // 



                                $postDetails = [];

                                $postReaction = [];

                                $postReactions = getReaction($value->id);

                                foreach ($postReactions as $reactionVal) {

                                    $reactionInfo['id'] = $reactionVal->id;

                                    $reactionInfo['event_post_id'] = $reactionVal->event_post_id;

                                    $reactionInfo['reaction'] = $reactionVal->reaction;

                                    $reactionInfo['user_id'] = $reactionVal->user_id;

                                    $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                                    $postReaction[] = $reactionInfo;
                                }

                                $postRecordDetailList['post_reaction'] = $postReaction;

                                $postRecordDetailList['reactionList'] = $reactionList;

                                $postRecordDetailList['total_comment'] = $value->event_post_comment_count;

                                $postRecordDetailList['total_likes'] = $value->event_post_reaction_count;

                                $postRecordDetailList['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postRecordDetailList['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";



                                $postCommentList = [];

                                $postComment = getComments($value->id);



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

                                $postRecordDetailList['post_comment'] = $postCommentList;





                                $postDetails[] = $postRecordDetailList;

                                $postsRecordDetail['post_detail'] = $postDetails;

                                $postList[] = $postsRecordDetail;
                            }
                        }





                        if ($checkUserTypeForPost->rsvp_d == '0' && $value->post_privacy == '4') {

                            if ($value->post_type == '0') { // Normal

                                $postsNormalDetail['id'] =  $value->id;

                                $postsNormalDetail['user_id'] =  $value->user->id;

                                $postsNormalDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                                $postsNormalDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                                $postsNormalDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;

                                $postsNormalDetail['rsvp_status'] = $checkUserRsvp;
                                $postsNormalDetail['location'] = ($value->user->city != NULL) ? $value->user->city : "";



                                $postsNormalDetail['post_type'] = $value->post_type;

                                $postsNormalDetail['created_at'] = $value->created_at;
                                $postsNormalDetail['posttime'] = setpostTime($value->created_at);



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

                                // postDetail // 





                                $postDetails = [];




                                $postReaction = [];

                                $postReactions = getReaction($value->id);

                                foreach ($postReactions as $reactionVal) {

                                    $reactionInfo['id'] = $reactionVal->id;

                                    $reactionInfo['event_post_id'] = $reactionVal->event_post_id;

                                    $reactionInfo['reaction'] = $reactionVal->reaction;

                                    $reactionInfo['user_id'] = $reactionVal->user_id;

                                    $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                                    $postReaction[] = $reactionInfo;
                                }

                                $postNormalDetailList['post_reaction'] = $postReaction;
                                $postNormalDetailList['reactionList'] = $reactionList;

                                $postNormalDetailList['total_comment'] = $value->event_post_comment_count;

                                $postNormalDetailList['total_likes'] = $value->event_post_reaction_count;

                                $postNormalDetailList['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postNormalDetailList['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";


                                $postCommentList = [];

                                $postComment = getComments($value->id);



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

                                $postNormalDetailList['post_comment'] = $postCommentList;





                                $postDetails[] = $postNormalDetailList;



                                $postsNormalDetail['post_detail'] = $postDetails;





                                $postList[] = $postsNormalDetail;
                            }

                            if ($value->post_type == '1') { // Image

                                $postsImageDetail['id'] =  $value->id;

                                $postsImageDetail['user_id'] =  $value->user->id;

                                $postsImageDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                                $postsImageDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);
                                $postsImageDetail['location'] =  ($value->user->city != NULL) ? $value->user->city : "";
                                $postsImageDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;

                                $postsImageDetail['rsvp_status'] = $checkUserRsvp;



                                $eventPostImage = EventPostImage::where(['event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                                $postsImageDetail['post_image'] = empty($eventPostImage->post_image) ? "" : asset('public/storage/post_image/' . $eventPostImage->post_image);



                                $postsImageDetail['post_type'] = $value->post_type;

                                $postsImageDetail['created_at'] = $value->created_at;

                                $postsImageDetail['posttime'] = setpostTime($value->created_at);

                                $reactionList = getOnlyReaction($value->id);


                                $postsImageDetail['reactionList'] = $reactionList;

                                $postsImageDetail['total_comment'] = $value->event_post_comment_count;

                                $postsImageDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsImageDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsImageDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";


                                $postsImageDetail['is_owner_post'] = ($value->user->id == $user->id) ? 1 : 0;
                                $postsImageDetail['is_mute'] =  0;
                                if ($postControl != null) {

                                    if ($postControl->post_control == 'mute') {
                                        $postsImageDetail['is_mute'] =  1;
                                    }
                                }


                                // postDetail // 



                                $postImages = getPostImages($value->id);

                                $postDetails = [];

                                $postImg = [];


                                foreach ($postImages as $imgVal) {





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

                                    $postImg[] = $postMedia;
                                }

                                $postImgDetailList['post_image'] = $postImg;





                                $postReaction = [];

                                $postReactions = getReaction($value->id);

                                foreach ($postReactions as $reactionVal) {

                                    $reactionInfo['id'] = $reactionVal->id;

                                    $reactionInfo['event_post_id'] = $reactionVal->event_post_id;

                                    $reactionInfo['reaction'] = $reactionVal->reaction;

                                    $reactionInfo['user_id'] = $reactionVal->user_id;

                                    $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                                    $postReaction[] = $reactionInfo;
                                }

                                $postImgDetailList['post_reaction'] = $postReaction;
                                $postImgDetailList['reactionList'] = $reactionList;

                                $postImgDetailList['total_comment'] = $value->event_post_comment_count;

                                $postImgDetailList['total_likes'] = $value->event_post_reaction_count;

                                $postImgDetailList['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postImgDetailList['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";




                                $postCommentList = [];

                                $postComment = getComments($value->id);



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

                                $postImgDetailList['post_comment'] = $postCommentList;





                                $postDetails[] = $postImgDetailList;



                                $postsImageDetail['post_detail'] = $postDetails;





                                $postList[] = $postsImageDetail;
                            }

                            if ($value->post_type == '2') { // Poll

                                $postsPollDetail['id'] =  $value->id;

                                $postsPollDetail['user_id'] =  $value->user->id;

                                $postsPollDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                                $postsPollDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);


                                $postsPollDetail['post_message'] =  empty($value->post_message) ? "" :  $value->post_message;
                                $postsPollDetail['location'] =  ($value->user->city != NULL) ? $value->user->city : "";
                                $polls = EventPostPoll::with('event_poll_option')->withCount('user_poll_data')->where(['event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                                $postsPollDetail['total_poll_vote'] = $polls->user_poll_data_count;

                                $postsPollDetail['poll_duration'] =  empty($polls->poll_duration) ? "" :  $polls->poll_duration;
                                $leftDay = (int) preg_replace('/[^0-9]/', '', $polls->poll_duration);
                                $postsPollDetail['is_expired'] =  (dateDiffer($polls->created_at) > $leftDay) ? true : false;

                                $postsPollDetail['poll_id'] = $polls->id;

                                $postsPollDetail['poll_question'] = $polls->poll_question;

                                $postsPollDetail['poll_option'] = [];

                                foreach ($polls->event_poll_option as $optionValue) {

                                    $optionData['id'] = $optionValue->id;

                                    $optionData['option'] = $optionValue->option;

                                    $optionData['total_vote'] =  round(getOptionTotalVote($optionValue->id) * 100 / getTotalEventInvitedUser($input['event_id'])) . "%";
                                    $optionData['is_poll_selected'] = checkUserGivePoll($user->id, $polls->id, $optionValue->id);


                                    $postsPollDetail['poll_option'][] = $optionData;
                                }

                                $postsPollDetail['post_type'] = $value->post_type;

                                $postsPollDetail['rsvp_status'] = $checkUserRsvp;

                                $postsPollDetail['created_at'] = $value->created_at;
                                $postsPollDetail['posttime'] = setpostTime($value->created_at);
                                $reactionList = getOnlyReaction($value->id);


                                $postsPollDetail['reactionList'] = $reactionList;

                                $postsPollDetail['total_comment'] = $value->event_post_comment_count;

                                $postsPollDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsPollDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsPollDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";

                                $postsPollDetail['is_owner_post'] = ($value->user->id == $user->id) ? 1 : 0;
                                $postsPollDetail['is_mute'] =  0;
                                if ($postControl != null) {

                                    if ($postControl->post_control == 'mute') {
                                        $postsPollDetail['is_mute'] =  1;
                                    }
                                }

                                // postDetail // 



                                $postDetails = [];

                                $postReaction = [];

                                $postReactions = getReaction($value->id);

                                foreach ($postReactions as $reactionVal) {

                                    $reactionInfo['id'] = $reactionVal->id;

                                    $reactionInfo['event_post_id'] = $reactionVal->event_post_id;

                                    $reactionInfo['reaction'] = $reactionVal->reaction;

                                    $reactionInfo['user_id'] = $reactionVal->user_id;

                                    $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                                    $postReaction[] = $reactionInfo;
                                }

                                $postPollDetailList['post_reaction'] = $postReaction;


                                $postPollDetailList['reactionList'] = $reactionList;

                                $postPollDetailList['total_comment'] = $value->event_post_comment_count;

                                $postPollDetailList['total_likes'] = $value->event_post_reaction_count;

                                $postPollDetailList['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postPollDetailList['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";


                                $postCommentList = [];

                                $postComment = getComments($value->id);



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

                                $postList[] = $postsPollDetail;
                            }

                            if ($value->post_type == '3') { // record

                                $postsRecordDetail['id'] =  $value->id;

                                $postsRecordDetail['user_id'] =  $value->user->id;

                                $postsRecordDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                                $postsRecordDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                                $postsRecordDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;
                                $postsRecordDetail['location'] = ($value->user->city != NULL) ? $value->user->city : "";
                                $postsRecordDetail['post_recording'] = empty($value->post_recording) ? "" : asset('public/storage/event_post_recording/' . $value->post_recording);

                                $postsRecordDetail['post_type'] = $value->post_type;

                                $postsRecordDetail['created_at'] = $value->created_at;
                                $postsRecordDetail['posttime'] = setpostTime($value->created_at);
                                $reactionList = getOnlyReaction($value->id);


                                $postsRecordDetail['rsvp_status'] = $checkUserRsvp;

                                $postsRecordDetail['reactionList'] = $reactionList;

                                $postsRecordDetail['total_comment'] = $value->event_post_comment_count;

                                $postsRecordDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsRecordDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsRecordDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";

                                $postsRecordDetail['is_owner_post'] = ($value->user->id == $user->id) ? 1 : 0;
                                $postsRecordDetail['is_mute'] =  0;
                                if ($postControl != null) {

                                    if ($postControl->post_control == 'mute') {
                                        $postsRecordDetail['is_mute'] =  1;
                                    }
                                }
                                // postDetail // 



                                $postDetails = [];

                                $postReaction = [];

                                $postReactions = getReaction($value->id);

                                foreach ($postReactions as $reactionVal) {

                                    $reactionInfo['id'] = $reactionVal->id;

                                    $reactionInfo['event_post_id'] = $reactionVal->event_post_id;

                                    $reactionInfo['reaction'] = $reactionVal->reaction;

                                    $reactionInfo['user_id'] = $reactionVal->user_id;

                                    $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                                    $postReaction[] = $reactionInfo;
                                }

                                $postRecordDetailList['post_reaction'] = $postReaction;

                                $postRecordDetailList['reactionList'] = $reactionList;

                                $postRecordDetailList['total_comment'] = $value->event_post_comment_count;

                                $postRecordDetailList['total_likes'] = $value->event_post_reaction_count;

                                $postRecordDetailList['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postRecordDetailList['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";



                                $postCommentList = [];

                                $postComment = getComments($value->id);



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

                                $postRecordDetailList['post_comment'] = $postCommentList;





                                $postDetails[] = $postRecordDetailList;

                                $postsRecordDetail['post_detail'] = $postDetails;

                                $postList[] = $postsRecordDetail;
                            }
                        }
                    }
                }
            }




            $wallData['stories'] = $storiesList;

            $wallData['posts'] = $postList;



            return response()->json(['status' => 1, 'data' => $wallData, 'message' => "Event wall data"]);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }



    public function eventWallManage(Request $request)

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

        try {

            $this->eventViewUser($user->id, $input['event_id']);

            $wallData = [];


            $storyInfo = $this->getStories($input['event_id'], $user->id);

            // dd($storyInfo);
            $wallData['owner_stories'] = [];

            if (count($storyInfo['ownStories'])  != 0) {
                $wallData['owner_stories'][] = $storyInfo['ownStories']; // $this->getOwnerStories($input['event_id'], $user->id);
            }

            $wallData['stories'] =  $storyInfo['stories']; //$this->getStories($input['event_id'], $user->id);

            //  Posts List //

            $selectedFilters = $input['filters'];

            $page = isset($input['page']) ? $input['page'] : 0;

            $perPage = 5;
            $pages = ($page != "") ? $page : 1;


            $eventPostList = EventPost::query();
            $eventPostList->with(['user', 'post_control' => function ($query) {
                $query->where('post_control', '!=', 'hide_post');
            }, 'post_image', 'event_post_reaction', 'event_post_poll' => function ($query) use ($input) {
                $query->where(['event_id' => $input['event_id']])->with(['event_poll_option'])->withCount('user_poll_data');
            }])->withCount(['event_post_comment' => function ($query) {
                $query->where('parent_comment_id', NULL);
            }, 'event_post_reaction'])->where('event_id', $input['event_id'])->orderBy('id', 'desc');




            if (!empty($selectedFilters) && !in_array('all', $selectedFilters)) {

                foreach ($selectedFilters as $filter) {

                    switch ($filter) {
                        case 'host_update':
                            $eventCreator = Event::where('id', $input['event_id'])->first();
                            $eventPostList->where('user_id', '=', $eventCreator->user_id);

                            break;
                        case 'video_uploads':
                            $eventPostList->where('post_type', '1')->with(['post_image' => function ($qury) {
                                $qury->where('type', 'video');
                            }]);
                            break;
                        case 'photo_uploads':
                            $eventPostList->where('post_type', '1')->with(['post_image' => function ($qury) {
                                $qury->where('type', 'image');
                            }]);
                            break;
                        case 'polls':
                            $eventPostList->where('post_type', '2');
                            break;
                        case 'comments':
                            $eventPostList->where('post_type', '0');
                            break;
                            // Add more cases for other filters if needed
                    }
                }
            }
            $results = $eventPostList->get();

            $postList = [];

            $checkEventOwner = Event::where(['id' => $input['event_id'], 'user_id' => $user->id])->exists();

            if ($checkEventOwner == true) {

                if (count($results) != 0) {

                    foreach ($results as $value) {

                        $eventpostsData = [];
                        $checkUserRsvp = checkUserAttendOrNot($value->event_id, $value->user->id);


                        $checkUserIsReaction = EventPostReaction::selectRaw('reaction')->where(['event_id' => $input['event_id'], 'event_post_id' => $value->id, 'user_id' => $user->id])->first();

                        $eventpostsData['id'] =  $value->id;

                        $eventpostsData['user_id'] =  $value->user->id;

                        $eventpostsData['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                        $eventpostsData['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                        $eventpostsData['post_message'] = empty($value->post_message) ? "" :  $value->post_message;
                        $eventpostsData['rsvp_status'] = $checkUserRsvp;
                        $eventpostsData['post_type'] = $value->post_type;
                        $eventpostsData['created_at'] = $value->created_at;
                        $eventpostsData['location'] =  ($value->user->city != null) ? $value->user->city : "";

                        $postReactionList = [];

                        foreach ($value->event_post_reaction as $values) {

                            $postReactionList[] = $values->reaction;
                        }

                        $eventpostsData['reactionList'] = $postReactionList;

                        $eventpostsData['total_comment'] = $value->event_post_comment_count;

                        $eventpostsData['total_likes'] = $value->event_post_reaction_count;

                        $eventpostsData['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                        $eventpostsData['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";

                        $eventpostsData['post_image'] = [];


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

                                $eventpostsData['post_image'][] = $postMedia;
                            }
                        }
                        $eventpostsData['poll_option'] = [];
                        if ($value->post_type == '2') {  // Poll
                            if (!empty($value->event_post_poll)) {
                                $eventpostsData['total_poll_vote'] = $value->event_post_poll->user_poll_data_count;
                                $eventpostsData['poll_id'] = $value->event_post_poll->id;
                                $eventpostsData['poll_question'] = $value->event_post_poll->poll_question;
                                $eventpostsData['poll_option'] = [];
                                foreach ($value->event_post_poll->event_poll_option as $optionValue) {
                                    $optionData = [];
                                    $optionData['id'] = $optionValue->id;
                                    $optionData['option'] = $optionValue->option;
                                    $optionData['total_vote'] =  round(getOptionTotalVote($optionValue->id) * 100 / getTotalEventInvitedUser($input['event_id'])) . "%";

                                    $eventpostsData['poll_option'][] = $optionData;
                                }
                            }
                        }

                        $eventpostsData['post_recording'] = empty($value->post_recording) ? "" : asset('public/storage/event_post_recording/' . $value->post_recording);

                        $postList[] = $eventpostsData;
                    }
                }
            } else {
                if (count($results) != 0) {

                    foreach ($results as $value) {

                        $eventpostsData = [];

                        if ($value->post_privacy == '1') {

                            $checkUserRsvp = checkUserAttendOrNot($value->event_id, $value->user->id);


                            $checkUserIsReaction = EventPostReaction::selectRaw('reaction')->where(['event_id' => $input['event_id'], 'event_post_id' => $value->id, 'user_id' => $user->id])->first();

                            $eventpostsData['id'] =  $value->id;

                            $eventpostsData['user_id'] =  $value->user->id;

                            $eventpostsData['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                            $eventpostsData['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                            $eventpostsData['post_message'] = empty($value->post_message) ? "" :  $value->post_message;
                            $eventpostsData['rsvp_status'] = $checkUserRsvp;
                            $eventpostsData['post_type'] = $value->post_type;
                            $eventpostsData['created_at'] = $value->created_at;
                            $eventpostsData['location'] =  ($value->user->city != null) ? $value->user->city : "";

                            $postReactionList = [];

                            foreach ($value->event_post_reaction as $values) {

                                $postReactionList[] = $values->reaction;
                            }

                            $eventpostsData['reactionList'] = $postReactionList;

                            $eventpostsData['total_comment'] = $value->event_post_comment_count;

                            $eventpostsData['total_likes'] = $value->event_post_reaction_count;

                            $eventpostsData['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                            $eventpostsData['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";

                            $eventpostsData['post_image'] = [];


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

                                    $eventpostsData['post_image'][] = $postMedia;
                                }
                            }
                            $eventpostsData['poll_option'] = [];
                            if ($value->post_type == '2') {  // Poll
                                if (!empty($value->event_post_poll)) {
                                    $eventpostsData['total_poll_vote'] = $value->event_post_poll->user_poll_data_count;
                                    $eventpostsData['poll_id'] = $value->event_post_poll->id;
                                    $eventpostsData['poll_question'] = $value->event_post_poll->poll_question;
                                    $eventpostsData['poll_option'] = [];
                                    foreach ($value->event_post_poll->event_poll_option as $optionValue) {
                                        $optionData = [];
                                        $optionData['id'] = $optionValue->id;
                                        $optionData['option'] = $optionValue->option;
                                        $optionData['total_vote'] =  round(getOptionTotalVote($optionValue->id) * 100 / getTotalEventInvitedUser($input['event_id'])) . "%";

                                        $eventpostsData['poll_option'][] = $optionData;
                                    }
                                }
                            }

                            $eventpostsData['post_recording'] = empty($value->post_recording) ? "" : asset('public/storage/event_post_recording/' . $value->post_recording);

                            $postList[] = $eventpostsData;
                        }

                        $checkUserTypeForPost = EventInvitedUser::whereHas('user', function ($query) {

                            $query->where('app_user', '1');
                        })->where(['event_id' => $input['event_id'], 'user_id' => $user->id])->first();


                        if ($checkUserTypeForPost->rsvp_d == '1' && $checkUserTypeForPost->rsvp_status == '1' && $value->post_privacy == '2') {

                            $checkUserRsvp = checkUserAttendOrNot($value->event_id, $value->user->id);


                            $checkUserIsReaction = EventPostReaction::selectRaw('reaction')->where(['event_id' => $input['event_id'], 'event_post_id' => $value->id, 'user_id' => $user->id])->first();

                            $eventpostsData['id'] =  $value->id;

                            $eventpostsData['user_id'] =  $value->user->id;

                            $eventpostsData['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                            $eventpostsData['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                            $eventpostsData['post_message'] = empty($value->post_message) ? "" :  $value->post_message;
                            $eventpostsData['rsvp_status'] = $checkUserRsvp;
                            $eventpostsData['post_type'] = $value->post_type;
                            $eventpostsData['created_at'] = $value->created_at;
                            $eventpostsData['location'] =  ($value->user->city != null) ? $value->user->city : "";

                            $postReactionList = [];

                            foreach ($value->event_post_reaction as $values) {

                                $postReactionList[] = $values->reaction;
                            }

                            $eventpostsData['reactionList'] = $postReactionList;

                            $eventpostsData['total_comment'] = $value->event_post_comment_count;

                            $eventpostsData['total_likes'] = $value->event_post_reaction_count;

                            $eventpostsData['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                            $eventpostsData['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";

                            $eventpostsData['post_image'] = [];


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

                                    $eventpostsData['post_image'][] = $postMedia;
                                }
                            }
                            $eventpostsData['poll_option'] = [];
                            if ($value->post_type == '2') {  // Poll
                                if (!empty($value->event_post_poll)) {
                                    $eventpostsData['total_poll_vote'] = $value->event_post_poll->user_poll_data_count;
                                    $eventpostsData['poll_id'] = $value->event_post_poll->id;
                                    $eventpostsData['poll_question'] = $value->event_post_poll->poll_question;
                                    $eventpostsData['poll_option'] = [];
                                    foreach ($value->event_post_poll->event_poll_option as $optionValue) {
                                        $optionData = [];
                                        $optionData['id'] = $optionValue->id;
                                        $optionData['option'] = $optionValue->option;
                                        $optionData['total_vote'] =  round(getOptionTotalVote($optionValue->id) * 100 / getTotalEventInvitedUser($input['event_id'])) . "%";

                                        $eventpostsData['poll_option'][] = $optionData;
                                    }
                                }
                            }

                            $eventpostsData['post_recording'] = empty($value->post_recording) ? "" : asset('public/storage/event_post_recording/' . $value->post_recording);

                            $postList[] = $eventpostsData;
                        }
                        if ($checkUserTypeForPost->rsvp_d == '1' && $checkUserTypeForPost->rsvp_status == '0' && $value->post_privacy == '3') {
                            $checkUserRsvp = checkUserAttendOrNot($value->event_id, $value->user->id);


                            $checkUserIsReaction = EventPostReaction::selectRaw('reaction')->where(['event_id' => $input['event_id'], 'event_post_id' => $value->id, 'user_id' => $user->id])->first();

                            $eventpostsData['id'] =  $value->id;

                            $eventpostsData['user_id'] =  $value->user->id;

                            $eventpostsData['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                            $eventpostsData['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                            $eventpostsData['post_message'] = empty($value->post_message) ? "" :  $value->post_message;
                            $eventpostsData['rsvp_status'] = $checkUserRsvp;
                            $eventpostsData['post_type'] = $value->post_type;
                            $eventpostsData['created_at'] = $value->created_at;
                            $eventpostsData['location'] =  ($value->user->city != null) ? $value->user->city : "";

                            $postReactionList = [];

                            foreach ($value->event_post_reaction as $values) {

                                $postReactionList[] = $values->reaction;
                            }

                            $eventpostsData['reactionList'] = $postReactionList;

                            $eventpostsData['total_comment'] = $value->event_post_comment_count;

                            $eventpostsData['total_likes'] = $value->event_post_reaction_count;

                            $eventpostsData['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                            $eventpostsData['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";

                            $eventpostsData['post_image'] = [];


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

                                    $eventpostsData['post_image'][] = $postMedia;
                                }
                            }
                            $eventpostsData['poll_option'] = [];
                            if ($value->post_type == '2') {  // Poll
                                if (!empty($value->event_post_poll)) {
                                    $eventpostsData['total_poll_vote'] = $value->event_post_poll->user_poll_data_count;
                                    $eventpostsData['poll_id'] = $value->event_post_poll->id;
                                    $eventpostsData['poll_question'] = $value->event_post_poll->poll_question;
                                    $eventpostsData['poll_option'] = [];
                                    foreach ($value->event_post_poll->event_poll_option as $optionValue) {
                                        $optionData = [];
                                        $optionData['id'] = $optionValue->id;
                                        $optionData['option'] = $optionValue->option;
                                        $optionData['total_vote'] =  round(getOptionTotalVote($optionValue->id) * 100 / getTotalEventInvitedUser($input['event_id'])) . "%";

                                        $eventpostsData['poll_option'][] = $optionData;
                                    }
                                }
                            }

                            $eventpostsData['post_recording'] = empty($value->post_recording) ? "" : asset('public/storage/event_post_recording/' . $value->post_recording);

                            $postList[] = $eventpostsData;
                        }
                        if ($checkUserTypeForPost->rsvp_d == '0' && $value->post_privacy == '4') {
                            $checkUserRsvp = checkUserAttendOrNot($value->event_id, $value->user->id);


                            $checkUserIsReaction = EventPostReaction::selectRaw('reaction')->where(['event_id' => $input['event_id'], 'event_post_id' => $value->id, 'user_id' => $user->id])->first();

                            $eventpostsData['id'] =  $value->id;

                            $eventpostsData['user_id'] =  $value->user->id;

                            $eventpostsData['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                            $eventpostsData['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                            $eventpostsData['post_message'] = empty($value->post_message) ? "" :  $value->post_message;
                            $eventpostsData['rsvp_status'] = $checkUserRsvp;
                            $eventpostsData['post_type'] = $value->post_type;
                            $eventpostsData['created_at'] = $value->created_at;
                            $eventpostsData['location'] =  ($value->user->city != null) ? $value->user->city : "";

                            $postReactionList = [];

                            foreach ($value->event_post_reaction as $values) {

                                $postReactionList[] = $values->reaction;
                            }

                            $eventpostsData['reactionList'] = $postReactionList;

                            $eventpostsData['total_comment'] = $value->event_post_comment_count;

                            $eventpostsData['total_likes'] = $value->event_post_reaction_count;

                            $eventpostsData['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                            $eventpostsData['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";

                            $eventpostsData['post_image'] = [];


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

                                    $eventpostsData['post_image'][] = $postMedia;
                                }
                            }
                            $eventpostsData['poll_option'] = [];
                            if ($value->post_type == '2') {  // Poll
                                if (!empty($value->event_post_poll)) {
                                    $eventpostsData['total_poll_vote'] = $value->event_post_poll->user_poll_data_count;
                                    $eventpostsData['poll_id'] = $value->event_post_poll->id;
                                    $eventpostsData['poll_question'] = $value->event_post_poll->poll_question;
                                    $eventpostsData['poll_option'] = [];
                                    foreach ($value->event_post_poll->event_poll_option as $optionValue) {
                                        $optionData = [];
                                        $optionData['id'] = $optionValue->id;
                                        $optionData['option'] = $optionValue->option;
                                        $optionData['total_vote'] =  round(getOptionTotalVote($optionValue->id) * 100 / getTotalEventInvitedUser($input['event_id'])) . "%";

                                        $eventpostsData['poll_option'][] = $optionData;
                                    }
                                }
                            }

                            $eventpostsData['post_recording'] = empty($value->post_recording) ? "" : asset('public/storage/event_post_recording/' . $value->post_recording);

                            $postList[] = $eventpostsData;
                        }
                    }
                }
            }

            $wallData['posts'] = $postList;



            return response()->json(['status' => 1, 'data' => $wallData, 'message' => "Event wall data"]);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }


    public function getOwnerStories($eventId, $userId)
    {

        $currentDateTime = Carbon::now();
        $eventLoginUserStoriesList =   EventUserStory::with(['user', 'user_event_story' => function ($query) use ($currentDateTime) {
            $query->where('created_at', '>', $currentDateTime->subHours(24));
        }])->where(['event_id' => $eventId, 'user_id' => $userId])->where('created_at', '>', $currentDateTime->subHours(24))->first();


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
                $storiesData['post_time'] =  $this->setpostTime($storyVal->created_at);
                if ($storyVal->type == 'video') {

                    $storiesData['video_duration'] = (!empty($storyVal->duration)) ? $storyVal->duration : "";
                }
                $storiesData['post_time'] =  $this->setpostTime($storyVal->created_at);
                $storiesDetaill['story'][] = $storiesData;
            }
        }
        return $storiesDetaill;
    }


    public function getStories($eventId, $userId)
    {

        $currentDateTime = Carbon::now();
        $eventStoriesList = EventUserStory::with(['user', 'user_event_story' => function ($query) use ($currentDateTime) {
            $query->where('created_at', '>', $currentDateTime->subHours(24));
        }])->where('event_id', $eventId)->where('created_at', '>', $currentDateTime->subHours(24))->get();

        $ownstoriesList = [];
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
                    if ($storyVal->type == 'video') {

                        $storiesData['video_duration'] = (!empty($storyVal->duration)) ? $storyVal->duration : "";
                    }
                    $storiesData['post_time'] =  $this->setpostTime($storyVal->created_at);
                    $storyAlldata[] = $storiesData;
                }
                $storiesDetaill['story'] = $storyAlldata;

                if ($value->user->id == $userId) {
                    $ownstoriesList = $storiesDetaill;
                } else {
                    $storiesList[] = $storiesDetaill;
                }
            }
        }

        return array('ownStories' => $ownstoriesList, 'stories' => $storiesList);
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
        try {

            $eventDetails = EventPost::with('user')->withCount(['event_post_comment' => function ($query) {
                $query->where('parent_comment_id', NULL);
            }, 'event_post_reaction'])->where(['id' => $input['event_post_id']])->first();
            if ($eventDetails != null) {


                $checkUserIsReaction = EventPostReaction::where(['event_id' => $eventDetails->event_id, 'event_post_id' => $input['event_post_id'], 'user_id' => $user->id])->first();


                $postsDetail['id'] =  $eventDetails->id;

                $postsDetail['user_id'] =  $eventDetails->user->id;

                $postsDetail['username'] =  $eventDetails->user->firstname . ' ' . $eventDetails->user->lastname;

                $postsDetail['profile'] =  empty($eventDetails->user->profile) ? "" : asset('public/storage/profile/' . $eventDetails->user->profile);

                $postsDetail['post_message'] =  empty($eventDetails->post_message) ? "" :  $eventDetails->post_message;

                $postsDetail['location'] = ($eventDetails->user->city != NULL) ? $eventDetails->user->city : "";
                $postsDetail['posttime'] = setpostTime($eventDetails->created_at);
                if ($eventDetails->post_type == '1') { // Image
                    $postsDetail['post_image'] = [];
                    $postImages = getPostImages($eventDetails->id);
                    foreach ($postImages as $imgVal) {

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
                    $postsDetail['poll_duration'] = $polls->poll_duration;
                    $leftDay = (int) preg_replace('/[^0-9]/', '', $polls->poll_duration);
                    $postsDetail['is_expired'] =  (dateDiffer($polls->created_at) > $leftDay) ? true : false;
                    $postsDetail['poll_question'] = $polls->poll_question;

                    $postsDetail['poll_option'] = [];

                    foreach ($polls->event_poll_option as $optionValue) {

                        $optionData['id'] = $optionValue->id;

                        $optionData['option'] = $optionValue->option;

                        $optionData['total_vote'] =  round(getOptionTotalVote($optionValue->id) * 100 / getTotalEventInvitedUser($eventDetails->event_id)) . "%";
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

                                $commentChildReply['comment'] = $childReplyVal->comment_text;
                                $commentChildReply['user_id'] = $childReplyVal->user_id;

                                $commentChildReply['username'] = $childReplyVal->user->firstname . ' ' . $childReplyVal->user->lastname;

                                $commentChildReply['profile'] = (!empty($childReplyVal->user->profile)) ? asset('public/storage/profile/' . $childReplyVal->user->profile) : "";
                                $commentChildReply['location'] = (!empty($childReplyVal->user->city)) ? $childReplyVal->user->city : "";

                                $commentChildReply['comment_total_likes'] = $childReplyVal->post_comment_reaction_count;

                                $commentChildReply['is_like'] = checkUserPhotoIsLike($childReplyVal->id, $user->id);

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

                                        $commentChildInReply['comment'] = $childInReplyVal->comment_text;
                                        $commentChildInReply['user_id'] = $childInReplyVal->user_id;

                                        $commentChildInReply['username'] = $childInReplyVal->user->firstname . ' ' . $childInReplyVal->user->lastname;

                                        $commentChildInReply['profile'] = (!empty($childInReplyVal->user->profile)) ? asset('public/storage/profile/' . $childInReplyVal->user->profile) : "";
                                        $commentChildInReply['location'] = (!empty($childInReplyVal->user->city)) ? $childInReplyVal->user->city : "";

                                        $commentChildInReply['comment_total_likes'] = $childInReplyVal->post_comment_reaction_count;

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


                    $postCommentList[] = $commentInfo;
                }

                $postsDetail['post_comment'] = $postCommentList;

                return response()->json(['status' => 1, 'message' => "Post Details", 'data' => $postsDetail]);
            } else {
                return response()->json(['status' => 0, 'message' => "No data found"]);
            }
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['status' => 0, 'message' => "db error"]);
        }
        // catch (\Exception $e) {
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
                            $storiesData['post_time'] =  $this->setpostTime($storyVal->created_at);
                            if ($storyVal->type == 'video') {
                                $storiesData['video_duration'] = (!empty($storyVal->duration)) ? $storyVal->duration : "";
                            }
                            $storiesData['post_time'] =  $this->setpostTime($storyVal->created_at);
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

            return response()->json(['status' => 0, 'message' => "db error" . $e->getMessage()]);
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

            $event_post_comment->save();

            $notificationParam = [

                'sender_id' => $user->id,

                'event_id' => $input['event_id'],

                'post_id' => $input['event_post_id'],
                'comment_id' => $event_post_comment->id
            ];
            sendNotification('reply_on_comment_post', $notificationParam);


            DB::commit();


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

            $commentInfo['is_like'] = checkUserPhotoIsLike($replyList->id, $user->id);

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

                    $commentReply['is_like'] = checkUserPhotoIsLike($replyVal->id, $user->id);

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

                            $commentChildReply['is_like'] = checkUserPhotoIsLike($childReplyVal->id, $user->id);

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
            }



            return response()->json(['status' => 1, 'total_comments' => 0, 'data' => $commentInfo, 'message' => "Post comment replied by you"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "error"]);
        }
        // catch (\Exception $e) {

        //     DB::rollBack();

        //     return response()->json(['status' => 0, 'message' => "something went wrong"]);
        // }
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

        try {
            DB::beginTransaction();



            $creatEventPost = new EventPost;



            $creatEventPost->event_id = $request->event_id;

            $creatEventPost->user_id = $user->id;

            $creatEventPost->post_message = $request->post_message;



            if (isset($request->post_recording)) {

                $record = $request->post_recording;

                $recordingName = time() . '_' . $record->getClientOriginalName();

                $record->move(public_path('storage/event_post_recording'), $recordingName);
                $creatEventPost->post_recording = $recordingName;
            }

            $creatEventPost->post_privacy = $request->post_privacy;

            $creatEventPost->post_type = $request->post_type;

            $creatEventPost->commenting_on_off = $request->commenting_on_off;

            $creatEventPost->save();



            if ($creatEventPost->id) {

                if ($request->post_type == '1') {



                    if (!empty($request->post_image)) {



                        $postimages = $request->post_image;



                        foreach ($postimages as $postImgValue) {



                            $postImage = $postImgValue;

                            $imageName = time() . '_' . $postImage->getClientOriginalName();

                            Storage::disk('public')->putFileAs('post_image', $postImage, $imageName);

                            $checkIsimageOrVideo = checkIsimageOrVideo($postImage);

                            $duration = "";
                            if ($checkIsimageOrVideo == 'video') {
                                $duration = getVideoDuration($postImage);

                                if (Storage::disk('public')->exists('post_image/' . $imageName)) {

                                    Storage::disk('public')->delete('post_image/' . $imageName);
                                }
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

                    'post_id' => $creatEventPost->id

                ];



                sendNotification('upload_post', $notificationParam);
            }

            DB::commit();



            return response()->json(['status' => 1, 'message' => "Post is created sucessfully"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error :-" . $e->getMessage()]);
        } catch (\Exception $e) {

            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
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
            return response()->json(['status' => 1, 'message' => $message]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error :-" . $e->getMessage()]);
        }
        // catch (\Exception $e) {

        //     return response()->json(['status' => 0, 'message' => "something went wrong"]);
        // }
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



        try {

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



                $notificationParam = [

                    'sender_id' => $user->id,

                    'event_id' => $input['event_id'],

                    'post_id' => $input['event_post_id']

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
                $counts = EventPostReaction::where([
                    'event_id' => $input['event_id'],
                    'event_post_id' => $input['event_post_id']
                ])->count();
                return response()->json(['status' => 1, 'message' => "Post liked by you", "counts" => $counts, "reactionList" => $total_counts]);
            } else {

                $message = "";
                $checkReaction = EventPostReaction::where(['event_id' => $input['event_id'], 'event_post_id' => $input['event_post_id'], 'user_id' => $user->id])->first();
                if ($checkReaction != null) {
                    $unicode = mb_convert_encoding($input['reaction'], 'UTF-32', 'UTF-8');
                    $unicode = strtoupper(bin2hex($unicode));
                    if ($checkReaction->unicode != $unicode) {

                        $checkReaction->reaction = $input['reaction'];
                        $checkReaction->unicode = $unicode;
                        $checkReaction->save();
                        $message = "Post liked by you";
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

                return response()->json(['status' => 1, 'message' => $message,  "counts" => $counts, "reactionList" => $total_counts]);
            }
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        }
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

            $event_post_comment->save();

            $notificationParam = [

                'sender_id' => $user->id,

                'event_id' => $input['event_id'],

                'post_id' => $input['event_post_id']

            ];

            sendNotification('comment_post', $notificationParam);

            DB::commit();

            $postComment = getComments($input['event_post_id']);


            $letestComment =  EventPostComment::with('user')->withcount('post_comment_reaction', 'replies')->where(['event_post_id' => $input['event_post_id'], 'parent_comment_id' => NULL])->orderBy('id', 'DESC')->limit(1)->first();


            $postCommentList = [
                'id' => $letestComment->id,

                'event_post_id' => $letestComment->event_post_id,

                'comment' => $letestComment->comment_text,

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

            $checkcommentReaction = EventPostCommentReaction::where(['event_post_comment_id' => $input['event_post_comment_id'], 'user_id' => $user->id])->count();

            if ($checkcommentReaction == 0) {



                $post_comment_reaction = new EventPostCommentReaction;

                $post_comment_reaction->event_post_comment_id = $input['event_post_comment_id'];

                $post_comment_reaction->user_id = $user->id;

                $post_comment_reaction->reaction = $input['reaction'];

                $post_comment_reaction->save();

                DB::commit();

                return response()->json(['status' => 1, 'message' => "Post comment like by you"]);
            } else {

                $checkcommentReaction = EventPostCommentReaction::where(['event_post_comment_id' => $input['event_post_comment_id'], 'user_id' => $user->id]);

                $checkcommentReaction->delete();

                DB::commit();

                return response()->json(['status' => 1, 'message' => "Post comment disliked by you"]);
            }
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
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
            $postsPollDetail['location'] =  \Location::get($request->ip())->regionCode . ' , ' . \Location::get($request->ip())->countryName;


            $postsPollDetail['total_poll_vote'] = $polls->user_poll_data_count;



            $postsPollDetail['poll_id'] = $polls->id;

            $postsPollDetail['poll_question'] = $polls->poll_question;

            $postsPollDetail['poll_option'] = [];

            foreach ($polls->event_poll_option as $optionValue) {

                $optionData['id'] = $optionValue->id;

                $optionData['option'] = $optionValue->option;

                $optionData['total_vote'] =  round(getOptionTotalVote($optionValue->id) * 100 / getTotalEventInvitedUser($polls->event_post->event_id)) . "%";

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


            return response()->json(['status' => 1, 'message' => "voted sucessfully", "data" => $postsPollDetail]);
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

                $query->with('user');
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
            })->where(['event_id' => $eventDetail->id, 'rsvp_status' => '1'])->sum('adults');

            $kids = EventInvitedUser::whereHas('user', function ($query) {

                $query->where('app_user', '1');
            })->where(['event_id' => $eventDetail->id, 'rsvp_status' => '1'])->sum('kids');


            $eventAboutHost['attending'] = $adults + $kids;
            $eventAboutHost['total_invitation'] =  count(getEventInvitedUser($input['event_id']));
            $eventAboutHost['adults'] = (int)$adults;
            $eventAboutHost['kids'] =  (int)$kids;
            $eventAboutHost['not_attending'] = $eventNotComing;
            $eventAboutHost['pending'] = $pendingUser;
            $userRsvpStatusList = EventInvitedUser::whereHas('user', function ($query) {
                $query->where('app_user', '1');
            })->where(['event_id' => $eventDetail->id, 'invitation_sent' => '1'])->get();


            $rsvpUserStatusList = [];

            if (count($userRsvpStatusList) != 0) {

                foreach ($userRsvpStatusList as $value) {
                    $rsvpUserStatus = [];
                    $rsvpUserStatus['id'] = $value->id;

                    $rsvpUserStatus['user_id'] = $value->user->id;

                    $rsvpUserStatus['username'] = $value->user->firstname . ' ' . $value->user->lastname;

                    $rsvpUserStatus['profile'] = (!empty($value->user->profile) || $value->user->profile != NULL) ? asset('public/storage/profile/' . $value->user->profile) : "";

                    $rsvpUserStatus['email'] = ($value->prefer == 'email') ? $value->user->email : "";

                    $rsvpUserStatus['phone_number'] = ($value->prefer == 'phone') ? $value->user->phone_number : "";
                    $rsvpUserStatus['prefer_by'] =  $value->prefer_by;
                    $rsvpUserStatus['kids'] = $value->kids;

                    $rsvpUserStatus['adults'] = $value->adults;

                    $rsvpUserStatus['rsvp_status'] =  (int)$value->rsvp_status;

                    if ($value->rsvp_d == '0' && $value->read == '1' || $value->rsvp_status == null) {

                        $rsvpUserStatus['rsvp_status'] = 2; // no reply 
                    }

                    $rsvpUserStatus['read'] = $value->read;

                    $rsvpUserStatus['rsvp_d'] = $value->rsvp_d;

                    $rsvpUserStatus['invitation_sent'] = $value->invitation_sent;



                    $rsvpUserStatusList[] = $rsvpUserStatus;
                }
            }
            $eventAboutHost['rsvp_status_list'] = $rsvpUserStatusList;

            return response()->json(['status' => 1, 'data' => $eventAboutHost, 'message' => "Guest event"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "error"]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
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

                $userDetail['username'] = $value->user->firstname . ' ' . $value->user->lastname;

                $userDetail['profile'] = (!empty($value->user->profile) || $value->user->profile != NULL) ? asset('public/storage/profile/' . $value->user->profile) : "";

                $userDetail['email'] = (!empty($value->user->email)) ? $value->user->email : "";

                $userDetail['phone_number'] = (!empty($value->user->phone_number)) ? $value->user->phone_number : "";

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

        try {
            if (!empty($input['guest_list'])) {


                $checkUserInvitation = EventInvitedUser::where(['event_id' => $input['event_id']])->get()->pluck('user_id')->toArray();

                foreach ($input['guest_list'] as $value) {
                    DB::beginTransaction();
                    if (!in_array($value['id'], $checkUserInvitation)) {

                        EventInvitedUser::create([

                            'event_id' => $input['event_id'],

                            'prefer_by' => $value['prefer_by'],

                            'user_id' => $value['id']

                        ]);
                    }
                    if ($value['prefer_by'] == 'email') {

                        $email = $value['email'];

                        $eventInfo = Event::where('id', $input['event_id'])->first();
                        $eventData = [
                            'event_name' => $eventInfo->event_name,
                            'hosted_by' => $eventInfo->hosted_by,
                            'date' =>  date('l, M. jS', strtotime($eventInfo->start_date)),
                            'time' => '1PM',
                            'address' => $eventInfo->event_location_name . ' ' . $eventInfo->address_1 . ' ' . $eventInfo->address_2 . ' ' . $eventInfo->state . ' ' . $eventInfo->city . ' - ' . $eventInfo->zip_code,
                        ];

                        $emailsent = Mail::to($email)->send(new InvitationEmail(array($eventData)));

                        $invitation_sent_status =  EventInvitedUser::where(['event_id' => $input['event_id'], 'user_id' => $value['id']])->first();

                        if ($emailsent != null) {

                            $invitation_sent_status->invitation_sent = '1';
                        } else {

                            $invitation_sent_status->invitation_sent = '0';
                        }

                        $invitation_sent_status->save();
                    }
                }
            }

            $notificationParam = [

                'sender_id' => $user->id,

                'event_id' => $input['event_id'],

                'post_id' => ""

            ];

            sendNotification('invite', $notificationParam);
            DB::commit();
            return response()->json(['status' => 1, 'message' => "invites sent sucessfully"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "something went wrong:" . $e->getMessage()]);
        }
    }



    // Event Photo Module // 



    public function createEventPostPhoto(Request $request)
    {

        $user  = Auth::guard('api')->user();
        $input = $request->all();

        $validator = Validator::make($input, [

            'event_id' => ['required', 'exists:events,id'],

            'post_media' => ['required'],

        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first()

            ]);
        }



        try {

            DB::beginTransaction();



            if (isset($request->post_media)) {



                if (!empty($request->post_media)) {





                    $event_photo_uploaded = EventPostPhoto::create([

                        'event_id' => $request->event_id,

                        'user_id' => $user->id,

                        'post_message' => $request->post_message,

                    ]);
                    $event_photo_upload_id = $event_photo_uploaded->id;





                    if (!empty($event_photo_upload_id)) {

                        $postmedia = $request->post_media;


                        foreach ($postmedia as $postMediaValue) {



                            $postMedia = $postMediaValue;

                            $mediaName = time() . '_' . $postMedia->getClientOriginalName();


                            $postMedia->move(public_path('storage/post_photo'), $mediaName);
                            $checkIsimageOrVideo = checkIsimageOrVideo($postMedia);



                            EventPostPhotoData::create([

                                'event_post_photo_id' => $event_photo_upload_id,

                                'post_media' => $mediaName,

                                'type' => $checkIsimageOrVideo

                            ]);
                        }
                    }
                }
            }

            DB::commit();

            return response()->json(['status' => 1, 'message' => "Posted sucessfully"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }



    public function userPostPhotoLikeDislike(Request $request)

    {

        $user  = Auth::guard('api')->user();



        $rawData = $request->getContent();





        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }


        $validator = Validator::make($input, [

            'event_post_photo_id' => ['required'],

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



            $checkReaction = EventPostPhotoReaction::where(['event_post_photo_id' => $input['event_post_photo_id'], 'user_id' => $user->id])->count();



            if ($checkReaction == 0) {

                $event_post_photo_reaction = new EventPostPhotoReaction;



                $event_post_photo_reaction->event_post_photo_id = $input['event_post_photo_id'];

                $event_post_photo_reaction->user_id = $user->id;

                $event_post_photo_reaction->reaction = $input['reaction'];

                $event_post_photo_reaction->save();



                DB::commit();

                $reactionList = getPhotoReaction($input['event_post_photo_id']);



                $totalComment = EventPostPhotoComment::where(['event_post_photo_id' => $input['event_post_photo_id'], 'parent_comment_id' => NULl])->count();

                $postReactionList = [];

                foreach ($reactionList as $values) {

                    $postReactionList[] = $values->reaction;
                }



                $reactionData['reactionList'] = $postReactionList;

                $reactionData['total_likes'] = count($reactionList);

                $reactionData['total_comments'] = $totalComment;

                $reactionData['is_reaction'] = '1';

                $reactionData['self_reaction'] = $input['reaction'];



                // postDetail

                $postDetails = [];

                $postReaction = [];

                $postReactions = getPhotoReaction($input['event_post_photo_id']);

                foreach ($postReactions as $reactionVal) {

                    $reactionInfo['id'] = $reactionVal->id;

                    $reactionInfo['event_post_photo_id'] = $reactionVal->event_post_photo_id;

                    $reactionInfo['reaction'] = $reactionVal->reaction;

                    $reactionInfo['user_id'] = $reactionVal->user_id;

                    $reactionInfo['username'] = $reactionVal->user->firstname . ' ' . $reactionVal->user->lastname;



                    if (!empty($reactionVal->user->address) || $reactionVal->user->address != NULL) {



                        $reactionInfo['address'] = $reactionVal->user->address . ',' . $reactionVal->user->city . ',' . $reactionVal->user->state;
                    } else {

                        $reactionInfo['address'] = "";
                    }



                    $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                    $postReaction[] = $reactionInfo;
                }

                $reactionData['post_reaction'] = $postReaction;

                $postCommentList = [];

                $postComment = getPostPhotoComments($input['event_post_photo_id']);



                foreach ($postComment as $commentVal) {

                    $commentInfo['id'] = $commentVal->id;

                    $commentInfo['event_post_photo_id'] = $commentVal->event_post_photo_id;

                    $commentInfo['comment'] = $commentVal->comment_text;

                    $commentInfo['user_id'] = $commentVal->user_id;

                    $commentInfo['username'] = $commentVal->user->firstname . ' ' . $commentVal->user->lastname;

                    $commentInfo['profile'] = (!empty($commentVal->user->profile)) ? asset('public/storage/profile/' . $commentVal->user->profile) : "";

                    $commentInfo['comment_total_likes'] = $commentVal->post_photo_comment_reaction_count;

                    $commentInfo['is_like'] = checkUserPhotoIsLike($commentVal->id, $user->id);

                    $commentInfo['post_time'] =  $this->setpostTime($commentVal->created_at);

                    $commentInfo['total_replies'] = $commentVal->replies_count;



                    $postCommentList[] = $commentInfo;
                }

                $reactionData['post_comment'] = $postCommentList;





                $postDetails[] = $reactionData;



                $reactionData['post_detail'] = $postDetails;



                return response()->json(['status' => 1, 'data' => $reactionData, 'message' => "Post liked by you"]);
            } else {

                $checkReaction = EventPostPhotoReaction::where(['event_post_photo_id' => $input['event_post_photo_id'], 'user_id' => $user->id]);

                $checkReaction->delete();

                DB::commit();

                $reactionList = getPhotoReaction($input['event_post_photo_id']);

                $totalComment = EventPostPhotoComment::where(['event_post_photo_id' => $input['event_post_photo_id'], 'parent_comment_id' => NULl])->count();

                $postReactionList = [];

                foreach ($reactionList as $values) {

                    $postReactionList[] = $values->reaction;
                }



                $reactionData['reactionList'] = $postReactionList;

                $reactionData['total_likes'] = count($reactionList);

                $reactionData['total_comments'] = $totalComment;

                $reactionData['is_reaction'] = '0';

                $reactionData['self_reaction'] = "";







                // postDetail

                $postDetails = [];

                $postReaction = [];

                $postReactions = getPhotoReaction($input['event_post_photo_id']);

                foreach ($postReactions as $reactionVal) {

                    $reactionInfo['id'] = $reactionVal->id;

                    $reactionInfo['event_post_photo_id'] = $reactionVal->event_post_photo_id;

                    $reactionInfo['reaction'] = $reactionVal->reaction;

                    $reactionInfo['user_id'] = $reactionVal->user_id;

                    $reactionInfo['username'] = $reactionVal->user->firstname . ' ' . $reactionVal->user->lastname;



                    if (!empty($reactionVal->user->address) || $reactionVal->user->address != NULL) {



                        $reactionInfo['address'] = $reactionVal->user->address . ',' . $reactionVal->user->city . ',' . $reactionVal->user->state;
                    } else {

                        $reactionInfo['address'] = "";
                    }



                    $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                    $postReaction[] = $reactionInfo;
                }

                $reactionData['post_reaction'] = $postReaction;

                $postCommentList = [];

                $postComment = getPostPhotoComments($input['event_post_photo_id']);



                foreach ($postComment as $commentVal) {

                    $commentInfo['id'] = $commentVal->id;

                    $commentInfo['event_post_photo_id'] = $commentVal->event_post_photo_id;

                    $commentInfo['comment'] = $commentVal->comment_text;

                    $commentInfo['user_id'] = $commentVal->user_id;

                    $commentInfo['username'] = $commentVal->user->firstname . ' ' . $commentVal->user->lastname;

                    $commentInfo['profile'] = (!empty($commentVal->user->profile)) ? asset('public/storage/profile/' . $commentVal->user->profile) : "";

                    $commentInfo['comment_total_likes'] = $commentVal->post_photo_comment_reaction_count;

                    $commentInfo['is_like'] = checkUserPhotoIsLike($commentVal->id, $user->id);

                    $commentInfo['post_time'] =  $this->setpostTime($commentVal->created_at);

                    $commentInfo['total_replies'] = $commentVal->replies_count;



                    $postCommentList[] = $commentInfo;
                }

                $reactionData['post_comment'] = $postCommentList;





                $postDetails[] = $reactionData;

                $reactionData['post_detail'] = $postDetails;

                return response()->json(['status' => 1, 'data' => $reactionData, 'message' => "Post disliked by you"]);
            }
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => 'db error']);
        } catch (Exception $e) {


            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }



    public function userPostPhotoComment(Request $request)

    {

        $user  = Auth::guard('api')->user();



        $rawData = $request->getContent();





        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }


        $validator = Validator::make($input, [

            'event_id' => ['required', 'exists:events,id'],

            'event_post_photo_id' => ['required', 'exists:event_post_photos,id'],

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





            $event_post_comment = new EventPostPhotoComment;



            $event_post_comment->event_id = $input['event_id'];

            $event_post_comment->event_post_photo_id = $input['event_post_photo_id'];

            $event_post_comment->user_id = $user->id;

            $event_post_comment->comment_text = $input['comment_text'];

            $event_post_comment->save();



            DB::commit();



            $postComment = getPostPhotoComments($input['event_post_photo_id']);



            $postCommentList = [];
            foreach ($postComment as $commentVal) {

                $commentInfo['id'] = $commentVal->id;

                $commentInfo['event_post_photo_id'] = $commentVal->event_post_photo_id;

                $commentInfo['comment'] = $commentVal->comment_text;

                $commentInfo['user_id'] = $commentVal->user_id;

                $commentInfo['username'] = $commentVal->user->firstname . ' ' . $commentVal->user->lastname;

                $commentInfo['profile'] = (!empty($commentVal->user->profile)) ? asset('public/storage/profile/' . $commentVal->user->profile) : "";

                $commentInfo['comment_total_likes'] = $commentVal->post_photo_comment_reaction_count;

                $commentInfo['is_like'] = checkUserPhotoIsLike($commentVal->id, $user->id);

                $commentInfo['created_at'] = $commentVal->created_at;

                $commentInfo['total_replies'] = $commentVal->replies_count;



                $postCommentList[] = $commentInfo;
            }

            return response()->json(['status' => 1, 'total_comments' => count($postComment), 'data' => $postCommentList, 'message' => "Post commented by you"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => 'db error']);
        } catch (Exception $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }

    public function userPostPhotoCommentReply(Request $request)
    {

        $user  = Auth::guard('api')->user();
        $rawData = $request->getContent();
        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [
            'event_id' => ['required', 'exists:events,id'],
            'event_post_photo_id' => ['required'],
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

            DB::beginTransaction();

            $event_post_comment = new EventPostPhotoComment;

            $event_post_comment->event_id = $input['event_id'];

            $event_post_comment->event_post_photo_id = $input['event_post_photo_id'];

            $event_post_comment->user_id = $user->id;

            $event_post_comment->parent_comment_id = $input['parent_comment_id'];

            $event_post_comment->comment_text = $input['comment_text'];

            $event_post_comment->save();



            DB::commit();

            $replyList = EventPostPhotoComment::with(['user'])->withcount('post_photo_comment_reaction')->orderBy('id', 'DESC')->where("parent_comment_id", $input['parent_comment_id'])->get();



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
            return response()->json(['status' => 1, 'total_comments_replies' => count($replyList), 'data' => $commentInfo, 'message' => "Post commented by you"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception $e) {



            return response()->json(['status' => 0, 'message' => "Something went wrong"]);
        }
    }

    public function userPostPhotoCommentReplyReaction(Request $request)
    {

        $user  = Auth::guard('api')->user();



        $rawData = $request->getContent();



        $input = json_decode($rawData, true);

        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [

            'event_photo_comment_id' => ['required', 'exists:event_post_photo_comments,id'],

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

            $checkcommentReaction = EventPhotoCommentReaction::where(['event_photo_comment_id' => $input['event_photo_comment_id'], 'user_id' => $user->id])->count();

            if ($checkcommentReaction == 0) {



                $post_comment_reaction = new EventPhotoCommentReaction;

                $post_comment_reaction->event_photo_comment_id = $input['event_photo_comment_id'];

                $post_comment_reaction->user_id = $user->id;

                $post_comment_reaction->reaction = $input['reaction'];

                $post_comment_reaction->save();

                DB::commit();



                $totalReaction = getPhotoCommentReaction($input['event_photo_comment_id']);

                $reactionData['total_likes'] = $totalReaction;

                $reactionData['is_reaction'] = '1';





                return response()->json(['status' => 1, 'data' => $reactionData, 'message' => "Post comment like by you"]);
            } else {

                $checkcommentReaction = EventPhotoCommentReaction::where(['event_photo_comment_id' => $input['event_photo_comment_id'], 'user_id' => $user->id]);

                $checkcommentReaction->delete();

                DB::commit();

                $totalReaction = getPhotoCommentReaction($input['event_photo_comment_id']);

                $reactionData['total_likes'] = $totalReaction;

                $reactionData['is_reaction'] = '0';

                return response()->json(['status' => 1, 'data' => $reactionData, 'message' => "Post comment disliked by you"]);
            }
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => 'db error']);
        } catch (Exception $e) {


            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
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
            $getPhotoList = EventPostPhoto::with(['user', 'event_post_photo_reaction', 'event_post_photo_data'])->withCount(['event_post_photo_reaction', 'event_post_Photo_comment', 'event_post_photo_data'])->where('event_id', $input['event_id'])->orderBy('id', 'desc')->get();



            $postPhotoList = [];



            foreach ($getPhotoList as $value) {



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

                $postPhotoDetail['total_media'] = ($value->event_post_photo_data_count - 1 != 0) ? "+" . $value->event_post_photo_data_count - 1 : "";

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

            return response()->json(['status' => 1, 'data' => $postPhotoList, 'message' => "Photo List"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => 'db error']);
        } catch (Exception $e) {


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
            $getPhotoList = EventPostPhoto::with(['user', 'event_post_photo_reaction', 'event_post_Photo_comment' => function ($query) {
                $query->with('replies');
            }, 'event_post_photo_data'])->withCount(['event_post_photo_reaction', 'event_post_Photo_comment', 'event_post_photo_data'])->where('id', $input['event_post_photo_id'])->first();







            $postPhotoDetail['user_id'] = $getPhotoList->user->id;

            $postPhotoDetail['firstname'] = $getPhotoList->user->firstname;

            $postPhotoDetail['lastname'] = $getPhotoList->user->lastname;



            $postPhotoDetail['profile'] = (!empty($getPhotoList->user->profile) || $getPhotoList->user->profile != NULL) ? asset('public/storage/profile/' . $getPhotoList->user->profile) : "";

            $selfReaction = EventPostPhotoReaction::where(['user_id' => $user->id, 'event_post_photo_id' => $getPhotoList->id])->first();

            $postPhotoDetail['is_reaction'] = ($selfReaction != NULL) ? '1' : '0';

            $postPhotoDetail['self_reaction'] = ($selfReaction != NULL) ? $selfReaction->reaction : "";

            $postPhotoDetail['event_id'] = $getPhotoList->event_id;
            $postPhotoDetail['id'] = $getPhotoList->id;

            $postPhotoDetail['post_message'] = (!empty($getPhotoList->post_message) || $getPhotoList->post_message != NULL) ? $getPhotoList->post_message : "";

            $postPhotoDetail['post_time'] = $this->setpostTime($getPhotoList->updated_at);

            $postPhotoDetail['mediaData'] = [];


            if (!empty($getPhotoList->event_post_photo_data)) {



                $photData = $getPhotoList->event_post_photo_data;

                foreach ($photData as $val) {

                    $photoVideoDetail['id'] = $val->id;

                    $photoVideoDetail['event_post_photo_id'] = $val->event_post_photo_id;

                    $photoVideoDetail['post_media'] = (!empty($val->post_media) || $val->post_media != NULL) ? asset('public/storage/post_photo/' . $val->post_media) : "";

                    $photoVideoDetail['type'] = $val->type;

                    $postPhotoDetail['mediaData'][] = $photoVideoDetail;
                }
            }


            $postPhotoDetail['total_media'] = ($getPhotoList->event_post_photo_data_count - 1 != 0) ? "+" . $getPhotoList->event_post_photo_data_count - 1 : "";

            $getPhotoReaction = getPhotoReaction($getPhotoList->id);

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




            $postPhotoDetail['total_likes'] = $getPhotoList->event_post_photo_reaction_count;



            $postPhotoDetail['total_comments'] = $getPhotoList->event_post__photo_comment_count;

            $getPostPhotoComments = getPostPhotoComments($val->event_post_photo_id);
            $postPhotoDetail['comment_list'] = [];


            foreach ($getPostPhotoComments as $commentVal) {


                $commentInfo['id'] = $commentVal->id;

                $commentInfo['event_post_id'] = $commentVal->event_post_photo_id;

                $commentInfo['comment'] = $commentVal->comment_text;

                $commentInfo['user_id'] = $commentVal->user_id;

                $commentInfo['username'] = $commentVal->user->firstname . ' ' . $commentVal->user->lastname;

                $commentInfo['profile'] = (!empty($commentVal->user->profile)) ? asset('public/storage/profile/' . $commentVal->user->profile) : "";
                $commentInfo['location'] = ($commentVal->user->city != NULL) ? $commentVal->user->city : "";
                $commentInfo['comment_total_likes'] = $commentVal->post_photo_comment_reaction_count;

                $commentInfo['is_like'] = checkUserIsLike($commentVal->id, $user->id);

                $commentInfo['total_replies'] = $commentVal->replies_count;

                $commentInfo['created_at'] = $commentVal->created_at;
                $commentInfo['posttime'] = setpostTime($commentVal->created_at);

                $commentInfo['comment_replies'] = [];

                foreach ($commentVal->replies as $reply) {
                    $replyCommentInfo['id'] = $reply->id;

                    $replyCommentInfo['event_post_photo_id'] = $reply->event_post_photo_id;

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
                }


                $postPhotoDetail['comment_list'][] = $commentInfo;
            }



            return response()->json(['status' => 1, 'data' => $postPhotoDetail, 'message' => "Photo Details"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => 'db error']);
        } catch (Exception $e) {


            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }


    public function eventPostPhotoListFilter(Request $request)
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

            $selectedFilters = $request->input('filters', ['guest']);
            // DB::connection()->enableQueryLog();
            $getPhotoList = EventPostPhoto::query();
            $getPhotoList->with(['user', 'event_post_photo_reaction', 'event', 'event.event_invited_user', 'event_post_photo_data'])
                ->withCount(['event_post_photo_reaction', 'event_post_Photo_comment', 'event_post_photo_data'])
                ->where('event_id', $input['event_id']);




            if (!empty($selectedFilters) && !in_array('all', $selectedFilters)) {


                foreach ($selectedFilters as $filter) {
                    switch ($filter) {
                        case 'time_posted':

                            break;
                        case 'guest':

                            $getPhotoList->whereHas('event.event_invited_user', function ($query) use ($input) {
                                $query->where('user_id', '=', DB::raw('event_post_photos.user_id'));
                            });
                            break;
                        case 'photos':
                            $getPhotoList->whereHas('event_post_photo_data', function ($query) {
                                $query->where('type', 'image');
                            });
                            break;
                        case 'videos':
                            $getPhotoList->whereHas('event_post_photo_data', function ($query) {
                                $query->where('type', 'video');
                            });
                            break;
                    }
                }
            }

            $getPhotoList->orderBy('id', 'desc');

            $results = $getPhotoList->get();

            $postPhotoList = [];
            foreach ($results as $value) {



                $postPhotoDetail['user_id'] = $value->user->id;

                $postPhotoDetail['firstname'] = $value->user->firstname;

                $postPhotoDetail['lastname'] = $value->user->lastname;

                $postPhotoDetail['profile'] = (!empty($value->user->profile) || $value->user->profile != NULL) ? asset('public/storage/profile/' . $value->user->profile) : "";

                $selfReaction = EventPostPhotoReaction::where(['user_id' => $user->id, 'event_post_photo_id' => $value->id])->first();

                $postPhotoDetail['is_reaction'] = ($selfReaction != NULL) ? '1' : '0';

                $postPhotoDetail['self_reaction'] = ($selfReaction != NULL) ? $selfReaction->reaction : "";

                $postPhotoDetail['id'] = $value->id;

                $postPhotoDetail['post_message'] = (!empty($value->post_message) || $value->post_message != NULL) ? $value->post_message : "";

                $postPhotoDetail['post_time'] = $this->setpostTime($value->created_at);

                $photoVideoData = [];



                if (!empty($value->event_post_photo_data)) {



                    $photData = $value->event_post_photo_data;

                    foreach ($photData as $val) {

                        $photoVideoDetail['id'] = $val->id;

                        $photoVideoDetail['event_post_photo_id'] = $val->event_post_photo_id;

                        $photoVideoDetail['post_media'] = (!empty($val->post_media) || $val->post_media != NULL) ? asset('storage/post_photo/' . $val->post_media) : "";

                        $photoVideoDetail['type'] = $val->type;

                        $photoVideoData[] = $photoVideoDetail;
                    }
                }

                $postPhotoDetail['mediaData'] = $photoVideoData;

                $postPhotoDetail['total_media'] = $value->event_post_photo_data_count;



                $getPhotoReaction = getPhotoReaction($value->id);

                $reactionList = [];

                foreach ($getPhotoReaction as $values) {

                    $reactionList[] = $values->reaction;
                }

                $postPhotoDetail['reactionList'] = $reactionList;



                $postPhotoDetail['total_likes'] = $value->event_post_photo_reaction_count;



                $postPhotoDetail['total_comments'] = $value->event_post__photo_comment_count;



                // post Detail



                $postDetails = [];

                $postReaction = [];

                $postReactions = getPhotoReaction($value->id);

                foreach ($postReactions as $reactionVal) {

                    $reactionInfo['id'] = $reactionVal->id;

                    $reactionInfo['event_post_photo_id'] = $reactionVal->event_post_photo_id;

                    $reactionInfo['reaction'] = $reactionVal->reaction;

                    $reactionInfo['user_id'] = $reactionVal->user_id;

                    $reactionInfo['username'] = $reactionVal->user->firstname . ' ' . $reactionVal->user->lastname;



                    if (!empty($reactionVal->user->address) || $reactionVal->user->address != NULL) {



                        $reactionInfo['address'] = $reactionVal->user->address . ',' . $reactionVal->user->city . ',' . $reactionVal->user->state;
                    } else {

                        $reactionInfo['address'] = "";
                    }



                    $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                    $postReaction[] = $reactionInfo;
                }

                $postPhotoDetail['post_reaction'] = $postReaction;

                $postCommentList = [];

                $postComment = getPostPhotoComments($value->id);



                foreach ($postComment as $commentVal) {

                    $commentInfo['id'] = $commentVal->id;

                    $commentInfo['event_post_photo_id'] = $commentVal->event_post_photo_id;

                    $commentInfo['comment'] = $commentVal->comment_text;

                    $commentInfo['user_id'] = $commentVal->user_id;

                    $commentInfo['username'] = $commentVal->user->firstname . ' ' . $commentVal->user->lastname;

                    $commentInfo['profile'] = (!empty($commentVal->user->profile)) ? asset('public/storage/profile/' . $commentVal->user->profile) : "";

                    $commentInfo['comment_total_likes'] = $commentVal->post_photo_comment_reaction_count;

                    $commentInfo['is_like'] = checkUserPhotoIsLike($commentVal->id, $user->id);

                    $commentInfo['post_time'] =  $this->setpostTime($commentVal->created_at);

                    $commentInfo['total_replies'] = $commentVal->replies_count;



                    $postCommentList[] = $commentInfo;
                }

                $postPhotoDetail['post_comment'] = $postCommentList;





                $postDetails[] = $postPhotoDetail;



                $postPhotoDetail['post_detail'] = $postDetails;





                $postPhotoList[] = $postPhotoDetail;
            }

            return response()->json(['status' => 1, 'data' => $postPhotoList, 'message' => "Photo List"]);
        }
        // } catch (QueryException $e) {

        //     DB::rollBack();

        //     return response()->json(['status' => 0, 'message' => 'db error']);
        // 
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

    public function notificationList()

    {

        $user = Auth::guard('api')->user();

        $usercreatedAllEventList = Event::with(['user', 'event_image'])

            ->where('user_id', $user->id)

            ->get();



        $invitedEvents = EventInvitedUser::whereHas('user', function ($query) {

            $query->where('app_user', '1');
        })->where('user_id', $user->id)->get()->pluck('event_id');



        $invitedEventsList = Event::with(['event_image', 'user'])

            ->whereIn('id', $invitedEvents)

            ->get();



        $allEvent = $usercreatedAllEventList->merge($invitedEventsList);

        $allEvents = collect($allEvent)->sortByDesc('id')->values()->all();




        $notificationList = [];

        foreach ($allEvents as $value) {

            $notification['event_id'] = $value->id;

            $notification['event_name'] = $value->event_name;

            $notification['event_date'] = $value->start_date;

            $notification['event_image'] = (!empty($value->event_image[0]->image)) ? asset('public/storage/event_images/' . $value->event_image[0]->image) : "";

            $notificationData = Notification::with(['user', 'sender_user', 'post' => function ($query) {
                $query->with(['post_image', 'event_post_poll'])->withcount(['event_post_reaction', 'event_post_comment' => function ($query) {
                    $query->where('parent_comment_id', NULL);
                }]);
            }])->orderBy('id', 'DESC')->where(['event_id' => $value->id, 'user_id' => $user->id])->get();

            $notificationInfo = [];
            if (count($notificationData) == 0) {
                continue;
            }
            foreach ($notificationData as $values) {

                if ($values->user_id == $user->id) {

                    if ($values->notification_type == 'invite') {



                        $notificationDetail['notification_id'] = $values->id;

                        $notificationDetail['notification_type'] = $values->notification_type;

                        $notificationDetail['user_id'] = $values->user_id;

                        $notificationDetail['sender_id'] = $values->sender_id;
                        $notificationDetail['sender_username'] = $values->sender_user->firstname . '' . $values->sender_user->lastname;

                        $notificationDetail['notification_message'] = $values->notification_message;
                        $notificationDetail['read'] = $values->read;
                        $notificationDetail['post_time'] = $this->setpostTime($values->created_at);
                        $notificationDetail['post_id'] = 0;
                        if ($values->notification_type == 'upload_post') {
                            $notificationDetail['post_id'] = $values->post_id;
                        }
                        $notificationDetail['post_detail'] = [];
                        if ($values->notification_type == 'like_post') {

                            if ($values->post->post_type == '1') {
                                $postDetail['post_type'] = !empty($values->post->post_type) ? $values->post->post_type : "";
                                $postDetail['post_message'] = !empty($values->post->post_message) ? $values->post->post_message : "";
                                $postDetail['post_media'] = !empty($values->post->post_image) ? asset('public/storage/post_image/' . $values->post->post_image[0]['post_image']) : "";
                                $postDetail['total_likes'] = !empty($values->post->event_post_reaction_count) ? $values->post->event_post_reaction_count : "";
                                $postDetail['total_comments'] = !empty($values->post->event_post_comment_count) ? $values->post->event_post_comment_count : "";
                                $notificationDetail['post_detail'][] = $postDetail;
                            }
                        }
                        $notificationInfo[] = $notificationDetail;
                    }

                    if ($values->notification_type == 'comment_post') {



                        $notificationDetail['notification_id'] = $values->id;

                        $notificationDetail['notification_type'] = $values->notification_type;

                        $notificationDetail['user_id'] = $values->user_id;

                        $notificationDetail['sender_id'] = $values->sender_id;

                        $notificationDetail['post_id'] = $values->post_id;
                        $notificationDetail['comment_id'] = $values->comment_id;

                        $postCommentDetail =  EventPostComment::where(['event_post_id' => $values->post_id, 'user_id' => $values->sender_id])->first();

                        $commentDetail['comment_text'] = ($postCommentDetail != null) ? $postCommentDetail->comment_text : "";

                        $notificationDetail['post_detail'] = [];
                        if ($values->post->post_type == '1') {
                            $postDetail['post_type'] = !empty($values->post->post_type) ? $values->post->post_type : "";
                            $postDetail['post_message'] = !empty($values->post->post_message) ? $values->post->post_message : "";
                            $postDetail['post_media'] = !empty($values->post->post_image) ? asset('public/storage/post_image/' . $values->post->post_image[0]['post_image']) : "";
                            $postDetail['total_likes'] = !empty($values->post->event_post_reaction_count) ? $values->post->event_post_reaction_count : "";
                            $postDetail['total_comments'] = !empty($values->post->event_post_comment_count) ? $values->post->event_post_comment_count : "";
                            $notificationDetail['post_detail'][] = $postDetail;
                        }


                        $notificationDetail['notification_message'] = $values->notification_message;

                        $notificationDetail['read'] = $values->read;
                        $notificationDetail['post_time'] = $this->setpostTime($values->created_at);

                        $notificationInfo[] = $notificationDetail;
                    }
                    if ($values->notification_type == 'reply_on_comment_post') {



                        $notificationDetail['notification_id'] = $values->id;

                        $notificationDetail['notification_type'] = $values->notification_type;

                        $notificationDetail['user_id'] = $values->user_id;

                        $notificationDetail['sender_id'] = $values->sender_id;

                        $notificationDetail['post_id'] = $values->post_id;

                        $postCommentDetail =  EventPostComment::with(['parentComment' => function ($query) {
                            $query->withCount(['replies', 'post_comment_reaction']);
                        }])->where(['event_post_id' => $values->post_id, 'id' => $values->comment_id, 'user_id' => $values->sender_id])->first();

                        $notificationDetail['comment_detail'] = [];

                        $commentDetail['reply_comment_id'] = $postCommentDetail->id;
                        $commentDetail['reply_comment_text'] = $postCommentDetail->comment_text;
                        $commentDetail['comment_id'] = $postCommentDetail->parentComment->id;
                        $commentDetail['comment_text'] = $postCommentDetail->parentComment->comment_text;
                        $commentDetail['total_comments'] = $postCommentDetail->parentComment->replies_count;
                        $commentDetail['total_likes'] = $postCommentDetail->parentComment->post_comment_reaction_count;


                        $notificationDetail['comment_detail'][] = $commentDetail;


                        $notificationDetail['notification_message'] = $values->notification_message;

                        $notificationDetail['read'] = $values->read;
                        $notificationDetail['post_time'] = $this->setpostTime($values->created_at);

                        $notificationInfo[] = $notificationDetail;
                    }
                    if ($values->notification_type == 'sent_rsvp') {

                        $userRsvpDetail =  EventInvitedUser::where(['user_id' => $values->sender_id, 'event_id' => $values->event_id])->first();
                        $rsvpDetails = [];
                        if ($userRsvpDetail->rsvp_d == '1') {

                            $rsvpData['rsvpd_status'] = "RSVP'd Yes";
                        } elseif ($userRsvpDetail->rsvp_d == '0') {
                            $rsvpData['rsvpd_status'] = "RSVP'd No";
                        }
                        $rsvpData['Adults'] = $userRsvpDetail->adults;
                        $rsvpData['Kids'] = $userRsvpDetail->kids;
                        $rsvpDetails[] = $rsvpData;

                        $notificationDetail['notification_id'] = $values->id;

                        $notificationDetail['notification_type'] = $values->notification_type;

                        $notificationDetail['user_id'] = $values->user_id;

                        $notificationDetail['sender_id'] = $values->sender_id;

                        $notificationDetail['notification_message'] = $values->notification_message;

                        $notificationDetail['read'] = $values->read;
                        $notificationDetail['post_time'] = $this->setpostTime($values->created_at);
                        $notificationDetail['rsvp_detail'] = $rsvpDetails;
                        $notificationInfo[] = $notificationDetail;
                    }
                }
            }

            $notification['notification'] = $notificationInfo;

            $notificationList[] = $notification;
        }

        return response()->json(['data' => $notificationList, 'message' => "Notification list"], 200);
    }

    public function logout()

    {

        if (Auth::guard('api')->check()) {

            $patient = Auth::guard('api')->user();

            Token::where('user_id', $patient->id)->delete();

            return response()->json(['message' => "logout succesfully"], 200);
        }
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
}
