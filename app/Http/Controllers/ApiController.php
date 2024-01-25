<?php



namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;



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
    UserEventStory
};

// Rules //

use App\Rules\CheckUserEvent;

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

class ApiController extends Controller

{

    public function notificationtest()

    {
        $deviceToken = "fO7cPyV-SBG7kvNXb_omEC:APA91bEtFLpUvMaNhQXZUJzqgLMJvUXNGaJgsC0ICp-mcUU5em_hzpTa5UAfGR5ZikAwT0I3zIlJ1IxTl7UvjRoL1aGuTIeL6-kbbDloBOv25UcKk-s1tx58x_8-Fa6rl_MeXpE9fYVC";

        send_notification_FCM($deviceToken, "Hi");
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



            // Pagination parameters
            // $perPage = 10; // Number of items per page
            // $pages = ($page != "") ? $page : 1; // Get current page number from request, default to 1

            // // Calculate offset based on current page and perPage
            // $offset = ($pages - 1) * $perPage;

            // // Get paginated data using offset and take
            // $paginatedEvents = $allEvents->slice($offset)->take($perPage);
            $eventList = [];

            if (count($allEvents) != 0) {

                foreach ($allEvents as $value) {


                    $eventDetail['id'] = $value->id;

                    $eventDetail['event_name'] = $value->event_name;



                    $eventDetail['user_id'] = $value->user->id;

                    $eventDetail['host_profile'] = empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                    $eventDetail['host_name'] = $value->hosted_by;



                    $images = EventImage::where('event_id', $value->id)->first();



                    $eventDetail['event_images'] = ($images != null) ? asset('public/storage/event_images/' . $images->image) : "";



                    $eventDetail['event_date'] = $value->start_date;



                    $eventDetail['start_time'] =  date('h:i A', $value->rsvp_start_time);

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



                    $eventList[] = $eventDetail;
                }

                return response()->json(['status' => 1, 'count' => count($allEvents), 'data' => $eventList, 'message' => "Events Data"]);
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

            'email' => [

                'required',

                'email',

                Rule::unique('users')->ignore($user->id),

            ],
            'country_code' => ['required', 'numeric'],
            'phone_number' => ['required', 'numeric'],
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

            $userUpdate->email = ($input['email'] != "") ? $input['email'] : $userUpdate->email;

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


            $details = User::where('id', $user->id)->get();



            $profileData = [];

            if (!empty($details)) {

                $userDetail['id'] = empty($details[0]->id) ? "" : $details[0]->id;

                $userDetail['profile'] =  empty($details[0]->profile) ?  asset('storage/profile/default.jpg') : asset('public/storage/profile/' . $details[0]->profile);

                $userDetail['bg_profile'] =  empty($details[0]->bg_profile) ? "" : asset('public/storage/bg_profile/' . $details[0]->bg_profile);

                $userDetail['firstname'] = empty($details[0]->firstname) ? "" : $details[0]->firstname;

                $userDetail['lastname'] = empty($details[0]->lastname) ? "" : $details[0]->lastname;

                $userDetail['gender'] = empty($details[0]->gender) ? "" : $details[0]->gender;

                $userDetail['birth_date'] = empty($details[0]->birth_date) ? "" : $details[0]->birth_date;

                $userDetail['email'] = empty($details[0]->email) ? "" : $details[0]->email;

                $userDetail['country_code'] = empty($details[0]->country_code) ? "" : strval($details[0]->country_code);

                $userDetail['phone_number'] = empty($details[0]->phone_number) ? "" : $details[0]->phone_number;

                $userDetail['about_me'] = empty($details[0]->about_me) ? "" : $details[0]->about_me;

                $userDetail['visible'] =  $details[0]->visible;

                $userDetail['account_type'] =  $details[0]->account_type;

                if ($details[0]->account_type == '1') {

                    $userDetail['company_name'] = empty($details[0]->company_name) ? "" : $details[0]->company_name;
                }

                $userDetail['address'] = empty($details[0]->address) ? "" : $details[0]->address;

                $userDetail['city'] = empty($details[0]->city) ? "" : $details[0]->city;

                $userDetail['state'] = empty($details[0]->state) ? "" : $details[0]->state;

                $userDetail['zip_code'] = empty($details[0]->zip_code) ? "" : $details[0]->zip_code;

                $profileData[] = $userDetail;



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



                if (file_exists(public_path('storage/profile/') . $user->profile)) {
                    $imagePath = public_path('storage/profile/') . $user->profile;
                    unlink($imagePath);
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

            $details = User::where('id', $user->id)->get();



            $profileData = [];

            if (!empty($details)) {

                $userDetail['id'] = empty($details[0]->id) ? "" : $details[0]->id;

                $userDetail['profile'] =  empty($details[0]->profile) ?  "" : asset('public/storage/profile/' . $details[0]->profile);

                $userDetail['bg_profile'] =  empty($details[0]->bg_profile) ? "" : asset('public/storage/bg_profile/' . $details[0]->bg_profile);

                $userDetail['firstname'] = empty($details[0]->firstname) ? "" : $details[0]->firstname;

                $userDetail['lastname'] = empty($details[0]->lastname) ? "" : $details[0]->lastname;

                $userDetail['gender'] = empty($details[0]->gender) ? "" : $details[0]->gender;

                $userDetail['birth_date'] = empty($details[0]->birth_date) ? "" : $details[0]->birth_date;

                $userDetail['email'] = empty($details[0]->email) ? "" : $details[0]->email;

                $userDetail['country_code'] = empty($details[0]->country_code) ? "" : strval($details[0]->country_code);

                $userDetail['phone_number'] = empty($details[0]->phone_number) ? "" : $details[0]->phone_number;

                $userDetail['about_me'] = empty($details[0]->about_me) ? "" : $details[0]->about_me;

                $userDetail['visible'] =  $details[0]->visible;

                $userDetail['account_type'] =  $details[0]->account_type;

                if ($details[0]->account_type == '1') {

                    $userDetail['company_name'] = empty($details[0]->company_name) ? "" : $details[0]->company_name;
                }
                $userDetail['address'] = empty($details[0]->address) ? "" : $details[0]->address;

                $userDetail['city'] = empty($details[0]->city) ? "" : $details[0]->city;

                $userDetail['state'] = empty($details[0]->state) ? "" : $details[0]->state;

                $userDetail['zip_code'] = empty($details[0]->zip_code) ? "" : $details[0]->zip_code;

                $profileData[] = $userDetail;



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



            $totalEvent =  Event::where('user_id', $user->id)->count();



            $totalEventPhotos = 0;

            $comments = 0;





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
                    'created_at' => empty($user->created_at) ? "" :   date('l Y', strtotime($user->created_at)),
                    'total_events' => $totalEvent,
                    'total_photos' => $totalEventPhotos,
                    'comments' => $comments,
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
        $validator = Validator::make($input, [

            'firstname' => ['required'],

            'lastname' => ['required'],

            'country_code' => ['required'],

            'phone_number' => ['required', 'unique:users,phone_number'],

            'email' => ['required'],

            'prefer_by' => ['required', 'in:email,phone']

        ]);

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

            User::create([

                'user_id' => $user->id,

                'firstname' => $input['firstname'],

                'lastname' => $input['lastname'],

                'country_code' => $input['country_code'],

                'phone_number' => ['required', 'unique:users,phone_number'],

                'email' => $input['email'],

                'app_user' => '0'

            ]);

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
        $validator = Validator::make($input, [

            'id' => ['required'],

            'firstname' => ['required'],

            'lastname' => ['required'],

            'country_code' => ['required'],

            'phone_number' => ['required'],

            'email' => ['required'],

        ]);

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

            $user->country_code = $input['country_code'];

            $user->phone_number = $input['phone_number'];

            $user->email = $input['email'];

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
        } catch (QueryException $e) {

            return response()->json(['status' => 0, 'message' => 'db error']);
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
            return response()->json(['message' => "Category list", "data" => $categoryList], 200);
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
            'invited_user_id' => ['present', 'array'],
            'invited_guests' => ['present', 'array'],
            'event_setting' => ['present'],
            'greeting_card_list' => ['array'],
            'co_host_list' => ['array'],
            'guest_co_host_list' => ['array'],
            'gift_registry_list' => ['array'],
            'podluck_category_list' => ['array'],
            'events_schedule_list' => ['array'],
            'is_draft_save' => ['required', 'in:0,1']
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
            ]);
        }

        try {
            DB::beginTransaction();


            if ($eventData['rsvp_end_time_set'] == '0') {


                $startEventTime = $eventData['start_date'];

                $oneDayBefore = date('Y-m-d', strtotime('-1 day', strtotime($startEventTime)));

                $rsvp_end_time = strtotime($oneDayBefore . ' 12:00:00');
            }

            $eventCreation =  Event::create([

                'event_type_id' => $eventData['event_type_id'],

                'event_name' => $eventData['event_name'],

                'user_id' => $user->id,

                'hosted_by' => $eventData['hosted_by'],

                'start_date' => $eventData['start_date'],

                'end_date' => $eventData['end_date'],
                'rsvp_by_date_set' => $eventData['rsvp_by_date_set'],

                'rsvp_by_date' => (!empty($eventData['rsvp_by_date'])) ? $eventData['rsvp_by_date'] : NULL,

                'rsvp_start_time' => (!empty($eventData['rsvp_by_date'])) ? strtotime($eventData['rsvp_by_date'] . '' . $eventData['rsvp_start_time']) : strtotime(date('Y-m-d') . '' . $eventData['rsvp_start_time']),

                'rsvp_start_timezone' => $eventData['rsvp_start_timezone'],

                'rsvp_end_time_set' => $eventData['rsvp_end_time_set'],
                'rsvp_end_time' => ($eventData['rsvp_end_time_set'] == '1') ? strtotime($eventData['rsvp_by_date'] . '' . $eventData['rsvp_end_time']) : $rsvp_end_time,
                'rsvp_end_timezone' => ($eventData['rsvp_end_time_set'] == '1') ? $eventData['rsvp_end_timezone'] : "",
                'event_location_name' => (!empty($eventData['event_location_name'])) ? $eventData['event_location_name'] : "",
                'address_1' => $eventData['address_1'],
                'address_2' => $eventData['address_2'],
                'state' => $eventData['state'],
                'zip_code' => $eventData['zip_code'],
                'city' => $eventData['city'],
                'message_to_guests' => $eventData['message_to_guests'],
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


                                    'country_code' => $value['country_code'],

                                    'phone_number' => $value['phone_number'],

                                    'app_user' => '0'

                                ]);

                                EventInvitedUser::create([

                                    'event_id' => $eventId,

                                    'prefer_by' => $value['prefer_by'],

                                    'user_id' => $guestUser->id

                                ]);
                            } else {



                                EventInvitedUser::create([

                                    'event_id' => $eventId,

                                    'prefer_by' => (isset($value['prefer_by'])) ? $value['prefer_by'] : "email",

                                    'user_id' => $checkUserExist->id

                                ]);
                            }
                        } else if ($value['prefer_by'] == 'email') {

                            $checkUserExist = User::where('email', $value['email'])->first();

                            if (empty($checkUserExist)) {

                                $guestUser = User::create([



                                    'firstname' => $value['first_name'],

                                    'lastname' => $value['last_name'],

                                    'email' => $value['email'],

                                    'app_user' => '0'

                                ]);

                                EventInvitedUser::create([

                                    'event_id' => $eventId,

                                    'prefer_by' => $value['prefer_by'],

                                    'user_id' => $guestUser->id

                                ]);
                            } else {

                                EventInvitedUser::create([

                                    'event_id' => $eventId,

                                    'prefer_by' => (isset($value['prefer_by'])) ? $value['prefer_by'] : "email",

                                    'user_id' => $checkUserExist->id
                                ]);
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



                if ($eventData['event_setting']['thank_you_cards'] == '1') {



                    $greetingCardList = $eventData['greeting_card_list'];

                    if (!empty($greetingCardList)) {



                        foreach ($greetingCardList as $value) {



                            EventGreeting::create([

                                "event_id" => $eventId,

                                "template_name" => $value['template_name'],

                                "message" => $value['message'],

                                "message_sent_time" => (isset($value['message_sent_time'])) ? $value['message_sent_time'] : "0",

                                "custom_hours_after_event" => (isset($value['custom_hours_after_event'])) ? $value['custom_hours_after_event'] : "0",

                            ]);
                        }
                    }
                }

                if ($eventData['event_setting']['add_co_host'] == '1') {



                    $coHostList = $eventData['co_host_list'];

                    if (!empty($coHostList)) {



                        foreach ($coHostList as $value) {



                            EventCoHost::create([

                                "event_id" => $eventId,

                                "user_id" => $value,

                            ]);
                        }
                    }



                    $guestcoHostList = $eventData['guest_co_host_list'];

                    if (!empty($guestcoHostList)) {



                        foreach ($guestcoHostList as $value) {



                            EventGuestCoHost::create([

                                'event_id' => $eventId,

                                'first_name' => $value['first_name'],

                                'last_name' => $value['last_name'],

                                'email' => $value['email'],

                                'country_code' => $value['country_code'],

                                'phone_number' => $value['phone_number']

                            ]);
                        }
                    }
                }



                if ($eventData['event_setting']['gift_registry'] == '1') {

                    $giftRegistryList = $eventData['gift_registry_list'];

                    if (!empty($giftRegistryList)) {



                        foreach ($giftRegistryList as $value) {



                            EventGiftRegistry::create([

                                'event_id' => $eventId,

                                'registry_recipient_name' => $value['registry_recipient_name'],

                                'registry_link' => $value['registry_link'],

                            ]);
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





                if ($eventData['event_setting']['podluck'] == '1') {

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



                if ($eventData['event_setting']['rsvp_by_date_status'] == '1') {

                    $updateRsvpDate = Event::where('id', $eventId)->first();

                    $updateRsvpDate->rsvp_by_date = $eventData['event_setting']['rsvp_by_date'];
                    $updateRsvpDate->rsvp_by_date_set = '1';
                    $updateRsvpDate->save();
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

    // public function editEvent(Request $request)
    // {


    //     $user  = Auth::guard('api')->user();


    //     $rawData = $request->getContent();


    //     $eventData = json_decode($rawData, true);


    //     if ($eventData == null) {
    //         return response()->json(['status' => 0, 'message' => "Json invalid"]);
    //     }
    //     $validator = Validator::make($eventData, [

    //         'event_id' => ['required'],
    //         'event_type_id' => ['required'],
    //         'event_name' => ['required'],
    //         'hosted_by' => ['required'],
    //         'start_date' => ['required'],
    //         'end_date' => ['required'],
    //         'rsvp_by_date_set' => ['required', 'in:0,1'],
    //         'rsvp_by_date' => ['present'],
    //         'rsvp_start_time' => ['required'],
    //         'rsvp_start_timezone' => ['required'],
    //         'rsvp_end_time_set' => ['required', 'in:0,1'],
    //         'rsvp_end_time' => ['present'],
    //         'rsvp_end_timezone' => ['present'],
    //         'event_location_name' => ['required'],
    //         'address_1' => ['required'],
    //         'address_2' => ['required'],
    //         'state' => ['required'],
    //         'zip_code' => ['required'],
    //         'city' => ['required'],
    //         'latitude' => ['required'],
    //         'longitude' => ['required'],
    //         'message_to_guests' => ['present'],
    //         'invited_user_id' => ['present', 'array'],
    //         'invited_guests' => ['present', 'array'],
    //         'event_setting' => ['present'],
    //         'greeting_card_list' => ['array'],
    //         'co_host_list' => ['array'],
    //         'guest_co_host_list' => ['array'],
    //         'gift_registry_list' => ['array'],
    //         'podluck_category_list' => ['array'],
    //         'events_schedule_list' => ['array'],
    //         'is_draft_save' => ['required', 'in:0,1']
    //     ]);

    //     if ($validator->fails()) {

    //         return response()->json([
    //             'status' => 0,
    //             'message' => $validator->errors()->first(),
    //         ]);
    //     }

    //     try {
    //         DB::beginTransaction();


    //         if ($eventData['rsvp_end_time_set'] == '0') {


    //             $startEventTime = $eventData['start_date'];

    //             $oneDayBefore = date('Y-m-d', strtotime('-1 day', strtotime($startEventTime)));

    //             $rsvp_end_time = strtotime($oneDayBefore . ' 12:00:00');
    //         }

    //         $getEventData = Event::where(['id' => $eventData['event_id']])->first();


    //         $getEventData->event_type_id = $eventData['event_type_id'];

    //         $getEventData->event_name = $eventData['event_name'];

    //         $getEventData->user_id = $user->id;

    //         $getEventData->hosted_by = $eventData['hosted_by'];

    //         $getEventData->start_date = $eventData['start_date'];

    //         $getEventData->end_date = $eventData['end_date'];
    //         $getEventData->rsvp_by_date_set = $eventData['rsvp_by_date_set'];

    //         $getEventData->rsvp_by_date = (!empty($eventData['rsvp_by_date'])) ? $eventData['rsvp_by_date'] : NULL;

    //         $getEventData->rsvp_start_time = (!empty($eventData['rsvp_by_date'])) ? strtotime($eventData['rsvp_by_date'] . '' . $eventData['rsvp_start_time']) : strtotime(date('Y-m-d') . '' . $eventData['rsvp_start_time']);

    //         $getEventData->rsvp_start_timezone = $eventData['rsvp_start_timezone'];

    //         $getEventData->rsvp_end_time_set = $eventData['rsvp_end_time_set'];
    //         $getEventData->rsvp_end_time = ($eventData['rsvp_end_time_set'] == '1') ? strtotime($eventData['rsvp_by_date'] . '' . $eventData['rsvp_end_time']) : $rsvp_end_time;
    //         $getEventData->rsvp_end_timezone = ($eventData['rsvp_end_time_set'] == '1') ? $eventData['rsvp_end_timezone'] : "";
    //         $getEventData->event_location_name = $eventData['event_location_name'];
    //         $getEventData->address_1 = $eventData['address_1'];
    //         $getEventData->address_2 = $eventData['address_2'];
    //         $getEventData->state = $eventData['state'];
    //         $getEventData->zip_code = $eventData['zip_code'];
    //         $getEventData->city = $eventData['city'];
    //         $getEventData->message_to_guests = $eventData['message_to_guests'];
    //         $getEventData->is_draft_save = $eventData['is_draft_save'];
    //         $getEventData->save();

    //         if ($getEventData->save()) {

    //             $eventId = $eventData['event_id'];



    //             if (!empty($eventData['invited_user_id'])) {

    //                 $invitedUsers = $eventData['invited_user_id'];



    //                 foreach ($invitedUsers as $value) {



    //                     EventInvitedUser::create([

    //                         'event_id' => $eventId,

    //                         'prefer_by' => $value['prefer_by'],

    //                         'user_id' => $value['user_id']

    //                     ]);
    //                 }
    //             }



    //             if (!empty($eventData['invited_guests'])) {

    //                 $invitedGuestUsers = $eventData['invited_guests'];



    //                 foreach ($invitedGuestUsers as $value) {

    //                     $checkUserExist = User::where('phone_number', $value['phone_number'])->first();



    //                     if (empty($checkUserExist)) {

    //                         $guestUser = User::create([



    //                             'firstname' => $value['first_name'],

    //                             'lastname' => $value['last_name'],

    //                             'email' => $value['email'],

    //                             'country_code' => $value['country_code'],

    //                             'phone_number' => $value['phone_number'],

    //                             'app_user' => '0'

    //                         ]);

    //                         EventInvitedUser::create([

    //                             'event_id' => $eventId,

    //                             'prefer_by' => $value['prefer_by'],

    //                             'user_id' => $guestUser->id

    //                         ]);
    //                     } else {



    //                         EventInvitedUser::create([

    //                             'event_id' => $eventId,

    //                             'prefer_by' => (isset($value['prefer_by'])) ? $value['prefer_by'] : "email",

    //                             'user_id' => $checkUserExist->id

    //                         ]);
    //                     }
    //                 }
    //             }



    //             if ($eventData['event_setting']) {

    //                 EventSetting::create([

    //                     'event_id' => $eventId,

    //                     'allow_for_1_more' => $eventData['event_setting']['allow_for_1_more'],

    //                     'allow_limit' => $eventData['event_setting']['allow_limit'],

    //                     'adult_only_party' => $eventData['event_setting']['adult_only_party'],

    //                     'rsvp_by_date_status' => $eventData['event_setting']['rsvp_by_date_status'],

    //                     'thank_you_cards' => $eventData['event_setting']['thank_you_cards'],

    //                     'add_co_host' => $eventData['event_setting']['add_co_host'],

    //                     'gift_registry' => $eventData['event_setting']['gift_registry'],

    //                     'events_schedule' => $eventData['event_setting']['events_schedule'],

    //                     'event_wall' => $eventData['event_setting']['event_wall'],

    //                     'guest_list_visible_to_guests' => $eventData['event_setting']['guest_list_visible_to_guests'],

    //                     'podluck' => $eventData['event_setting']['podluck'],

    //                     'rsvp_updates' => $eventData['event_setting']['rsvp_updates'],

    //                     'event_updates' => $eventData['event_setting']['event_updates'],

    //                     'send_event_dater_reminders' => $eventData['event_setting']['send_event_dater_reminders'],

    //                     'request_event_photos_from_guests' => $eventData['event_setting']['request_event_photos_from_guests'],

    //                 ]);
    //             }



    //             if ($eventData['event_setting']['thank_you_cards'] == '1') {



    //                 $greetingCardList = $eventData['greeting_card_list'];

    //                 if (!empty($greetingCardList)) {



    //                     foreach ($greetingCardList as $value) {



    //                         EventGreeting::create([

    //                             "event_id" => $eventId,

    //                             "template_name" => $value['template_name'],

    //                             "message" => $value['message'],

    //                             "message_sent_time" => (isset($value['message_sent_time'])) ? $value['message_sent_time'] : "0",

    //                             "custom_hours_after_event" => (isset($value['custom_hours_after_event'])) ? $value['custom_hours_after_event'] : "0",

    //                         ]);
    //                     }
    //                 }
    //             }

    //             if ($eventData['event_setting']['add_co_host'] == '1') {



    //                 $coHostList = $eventData['co_host_list'];

    //                 if (!empty($coHostList)) {



    //                     foreach ($coHostList as $value) {



    //                         EventCoHost::create([

    //                             "event_id" => $eventId,

    //                             "user_id" => $value,

    //                         ]);
    //                     }
    //                 }



    //                 $guestcoHostList = $eventData['guest_co_host_list'];

    //                 if (!empty($guestcoHostList)) {



    //                     foreach ($guestcoHostList as $value) {



    //                         EventGuestCoHost::create([

    //                             'event_id' => $eventId,

    //                             'first_name' => $value['first_name'],

    //                             'last_name' => $value['last_name'],

    //                             'email' => $value['email'],

    //                             'country_code' => $value['country_code'],

    //                             'phone_number' => $value['phone_number']

    //                         ]);
    //                     }
    //                 }
    //             }



    //             if ($eventData['event_setting']['gift_registry'] == '1') {

    //                 $giftRegistryList = $eventData['gift_registry_list'];

    //                 if (!empty($giftRegistryList)) {



    //                     foreach ($giftRegistryList as $value) {



    //                         EventGiftRegistry::create([

    //                             'event_id' => $eventId,

    //                             'registry_recipient_name' => $value['registry_recipient_name'],

    //                             'registry_link' => $value['registry_link'],

    //                         ]);
    //                     }
    //                 }
    //             }



    //             if ($eventData['event_setting']['events_schedule'] == '1') {

    //                 $eventsScheduleList = $eventData['events_schedule_list'];

    //                 if (!empty($eventsScheduleList)) {



    //                     foreach ($eventsScheduleList as $value) {



    //                         EventSchedule::create([

    //                             'event_id' => $eventId,

    //                             'event_name' => $value['event_name'],

    //                             'event_schedule' => $value['event_schedule'],

    //                         ]);
    //                     }
    //                 }
    //             }





    //             if ($eventData['event_setting']['podluck'] == '1') {

    //                 $podluckCategoryList = $eventData['podluck_category_list'];

    //                 if (!empty($podluckCategoryList)) {



    //                     foreach ($podluckCategoryList as $value) {



    //                         $eventPodluck = EventPotluckCategory::create([

    //                             'event_id' => $eventId,

    //                             'category' => $value['category'],

    //                             'quantity' => $value['quantity'],

    //                         ]);



    //                         if (!empty($value['items'])) {

    //                             $items = $value['items'];



    //                             foreach ($items as $value) {



    //                                 EventPotluckCategoryItem::create([

    //                                     'event_id' => $eventId,

    //                                     'event_potluck_category_id' => $eventPodluck->id,

    //                                     'description' => $value['description'],

    //                                     'quantity' => $value['quantity'],

    //                                 ]);
    //                             }
    //                         }
    //                     }
    //                 }
    //             }



    //             if ($eventData['event_setting']['rsvp_by_date_status'] == '1') {

    //                 $updateRsvpDate = Event::where('id', $eventId)->first();

    //                 $updateRsvpDate->rsvp_by_date = $eventData['event_setting']['rsvp_by_date'];
    //                 $updateRsvpDate->rsvp_by_date_set = '1';
    //                 $updateRsvpDate->save();
    //             }
    //         }


    //         if (!empty($eventData['invited_user_id']) && $eventData['is_draft_save'] == '0') {

    //             $notificationParam = [

    //                 'sender_id' => $user->id,

    //                 'event_id' => $eventId,

    //                 'post_id' => ""

    //             ];

    //             sendNotification('invite', $notificationParam);
    //         }


    //         DB::commit();


    //         return response()->json(['status' => 1, 'message' => "Event Created Successfully"]);
    //     } catch (QueryException $e) {
    //         DB::rollBack();

    //         return response()->json(['status' => 0, 'message' => 'db error:-' . $e->getMessage()]);
    //     } catch (Exception $e) {
    //         Log::info('API request event create something error successfully');;
    //         return response()->json(['status' => 0, 'message' => 'something went wrong']);
    //     }
    // }


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

                foreach ($eventOldImages as $oldImages) {



                    if (Storage::disk('public')->exists('event_images/' . $oldImages->image)) {

                        Storage::disk('public')->delete('event_images/' . $oldImages->image);

                        EventImage::where('event_id', $request->event_id)->delete();
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

            'event_id' => ['required', 'exists:events,id', new CheckUserEvent],

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
            'category' => 'required|unique:event_potluck_categories',
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
                'category' => $input['category']
            ]);

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
            'description' => 'required'

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
                'description' => $input['description']
            ]);

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

            $allEvents = collect($allEvent)->sortByDesc('id')->values()->all();



            $createdEventList = [];

            if (count($allEvents) != 0) {


                foreach ($allEvents as $value) {


                    $eventDetail['id'] = $value->id;
                    $eventDetail['event_name'] = $value->event_name;
                    $eventDetail['user_id'] = $value->user->id;
                    $eventDetail['host_profile'] = empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                    $eventDetail['host_name'] = $value->hosted_by;



                    $images = EventImage::where('event_id', $value->id)->first();

                    $eventDetail['event_images'] = ($images != null) ? asset('public/storage/event_images/' . $images->image) : "";

                    $eventDetail['event_date'] = $value->start_date;

                    $eventDetail['start_time'] = date('h:i A', $value->rsvp_start_time);

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



                    $createdEventList[] = $eventDetail;
                }
            }


            // All  //
            // Invited To //

            $userInvitedEventList = EventInvitedUser::whereHas('user', function ($query) {
                $query->where('app_user', '1');
            })->with(['event' => function ($query) use ($event_date) {

                $query->when($event_date, function ($query, $event_date) {

                    return $query->where('start_date', $event_date);
                })->with('event_image')->where('is_draft_save', '0')->orderBy('id', 'DESC');
            }])->where('user_id', $user->id)->get();

            $invitedeventList = [];

            if (count($userInvitedEventList) != 0) {



                foreach ($userInvitedEventList as $value) {



                    $eventDetail['id'] = $value->event->id;

                    $eventDetail['event_name'] = $value->event->event_name;

                    $eventDetail['host_profile'] = empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                    $eventDetail['host_name'] = $value->hosted_by;

                    $images = EventImage::where('event_id', $value->event->id)->first();

                    $eventDetail['event_images'] = "";

                    if (!empty($images)) {

                        $eventDetail['event_images'] = asset('public/storage/event_images/' . $images->image);
                    }



                    $eventDetail['event_date'] = $value->event->start_date;

                    $eventDetail['start_time'] =  date('h:i A', $value->event->rsvp_start_time);

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



                    $invitedeventList[] = $eventDetail;
                }
            }
            // Invited To //


            // Past Event // 
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

            $allPastEvents = collect($allPastEvent)->sortByDesc('id')->values()->all();

            $PastEventList = [];

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

                    $eventDetail['start_time'] =  date('h:i A', $value->rsvp_start_time);

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



                    $PastEventList[] = $eventDetail;
                }
            }

            // Past Event // 
            $eventList['all'] =  $createdEventList;

            $eventList['invited_to'] =  $invitedeventList;
            $eventList['past_event'] =  $PastEventList;

            return response()->json(['status' => 1, 'data' => $eventList, 'message' => "All events"]);
        } catch (QueryException $e) {
            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception $e) {
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

            'message_to_host' => "required",

            'read' => ["required", "in:0,1"],

            'rsvp_d' => ["required", "in:0,1"],

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

                $rsvpSent->read = $request->read;

                $rsvpSent->rsvp_d = $request->rsvp_d;

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
            $eventDetail = Event::with(['user', 'event_image', 'event_schedule', 'event_co_host' => function ($query) {

                $query->with('user');
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

            $eventDetails['event_time'] = $eventDetail->event_schedule->first()->event_schedule . ' to ' . $eventDetail->event_schedule->last()->event_schedule;;

            $eventDetails['rsvp_by'] = (!empty($eventDetail->rsvp_by_date) || $eventDetail->rsvp_by_date != NULL) ? $eventDetail->rsvp_by_date : date('Y-m-d', strtotime($eventDetail->created_at));



            $current_date = Carbon::now();

            $eventDate = $eventDetail->start_date;





            $datetime1 = Carbon::parse($eventDate);

            $datetime2 =  Carbon::parse($current_date);



            $till_days = $datetime1->diff($datetime2)->days;

            $eventDetails['days_till_event'] = $till_days;

            $eventDetails['message_to_guests'] = $eventDetail->message_to_guests;



            $coHosts = [];

            foreach ($eventDetail->event_co_host as $hostValues) {

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

            $eventDetails['latitude'] = $eventDetail->latitude;

            $eventDetails['logitude'] = $eventDetail->logitude;



            $eventsScheduleList = [];

            foreach ($eventDetail->event_schedule as $value) {

                $scheduleDetail['id'] = $value->id;

                $scheduleDetail['event_name'] = $value->event_name;

                $scheduleDetail['event_schedule'] = $value->event_schedule;

                $eventsScheduleList[] = $scheduleDetail;
            }

            $eventDetails['event_schedule'] = $eventsScheduleList;





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

            // $eventHostDetail['attending'] = count($eventattending);

            // $eventHostDetail['adults'] = $eventattending;

            // $eventHostDetail['kids'] = $eventattending;



            $eventAboutHost['attending'] = $adults + $kids;



            $eventAboutHost['adults'] = $adults;

            $eventAboutHost['kids'] = $kids;



            $eventAboutHost['not_attending'] = $eventNotComing;

            $eventAboutHost['pending'] = $pendingUser;

            $eventAboutHost['comment'] = EventPostComment::where('event_id', $eventDetail->id)->count();

            $eventAboutHost['total_invite'] = $totalEnvitedUser;

            $eventAboutHost['invite_view_rate'] = EventInvitedUser::whereHas('user', function ($query) {

                $query->where('app_user', '1');
            })->where(['event_id' => $eventDetail->id, 'read' => '1'])->count();

            $eventAboutHost['invite_view_percent'] = EventInvitedUser::whereHas('user', function ($query) {

                $query->where('app_user', '1');
            })->where(['event_id' => $eventDetail->id, 'read' => '1'])->count() / $totalEnvitedUser * 100 . "%";

            $eventAboutHost['today_invite_view_percent'] = EventInvitedUser::whereHas('user', function ($query) {

                $query->where('app_user', '1');
            })->where(['event_id' => $eventDetail->id, 'read' => '1', 'event_view_date' => date('Y-m-d')])->count() / $totalEnvitedUser * 100 . "%";

            $eventAboutHost['rsvp_rate'] = $eventattending;

            $eventAboutHost['rsvp_rate_percent'] = $eventattending / $totalEnvitedUser * 100 . "%";

            $eventAboutHost['today_upstick'] = $todayrsvprate / $totalEnvitedUser * 100 . "%";



            $eventInfo['host_view'] = $eventAboutHost;

            return response()->json(['status' => 1, 'data' => $eventInfo, 'message' => "About event"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {

            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }


    public function eventViewByUser(Request $request)

    {

        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();



        $input = json_decode($rawData, true);

        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [
            'event_id' => 'required|exists:events,id'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),

            ]);
        }

        try {
            DB::beginTransaction();
            $checkViewbyuser = EventInvitedUser::whereHas('user', function ($query) {

                $query->where('app_user', '1');
            })->where(['user_id' => $user->id, 'event_id' => $input['event_id']])->first();
            if ($checkViewbyuser != null) {
                if ($checkViewbyuser->read == '0') {


                    $checkViewbyuser->read = '1';

                    $checkViewbyuser->event_view_date = date('Y-m-d');

                    $checkViewbyuser->save();

                    DB::commit();
                    return response()->json(['status' => 1, 'message' => "viewed invite"]);
                }
            }
            return response()->json(['status' => 0, 'message' => "user is not viewed"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'message' => "something went wrong" . $e->getMessage()]);
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

            'event_id' => ['required', 'exists:events,id']

        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),


            ]);
        }

        try {
            //  Stories List //
            $currentDateTime = Carbon::now();

            $wallData = [];

            $wallData['owner_stories'] = [];

            $eventLoginUserStoriesList = EventUserStory::with(['user', 'user_event_story' => function ($query) use ($currentDateTime) {
                $query->where('created_at', '>', $currentDateTime->subHours(24));
            }])->where(['event_id' => $input['event_id'], 'user_id' => $user->id])->first();


            if ($eventLoginUserStoriesList != null) {


                $storiesDetaill['id'] =  $eventLoginUserStoriesList->id;
                $storiesDetaill['user_id'] =  $eventLoginUserStoriesList->user->id;

                $storiesDetaill['username'] =  $eventLoginUserStoriesList->user->firstname . ' ' . $eventLoginUserStoriesList->user->lastname;

                $storiesDetaill['profile'] =  empty($eventLoginUserStoriesList->user->profile) ? "" : asset('public/storage/profile/' . $eventLoginUserStoriesList->user->profile);

                $storiesDetaill['story'] = [];
                foreach ($eventLoginUserStoriesList->user_event_story as $storyVal) {
                    $storiesData['id'] = $storyVal->id;
                    $storiesData['storyurl'] = empty($storyVal->story) ? "" : asset('storage/event_user_stories/' . $storyVal->story);
                    $storiesData['type'] = $storyVal->type;
                    $storiesData['post_time'] =  setpostTime($storyVal->created_at);
                    if ($storyVal->type == 'video') {

                        $storiesData['video_duration'] = (!empty($storyVal->duration)) ? $storyVal->duration : "";
                    }
                    $storiesData['post_time'] =  setpostTime($storyVal->created_at);
                    $storiesDetaill['story'][] = $storiesData;
                }
                $wallData['owner_stories'][] = $storiesDetaill;
            }


            $eventStoriesList = EventUserStory::with(['user', 'user_event_story' => function ($query) use ($currentDateTime) {
                $query->where('created_at', '>', $currentDateTime->subHours(24));
            }])->where('event_id', $input['event_id'])->where('user_id', '!=', $user->id)->get();


            $storiesList = [];

            if (count($eventStoriesList) != 0) {



                foreach ($eventStoriesList as $value) {



                    $storiesDetaill['id'] =  $value->id;
                    $storiesDetaill['user_id'] =  $value->user->id;

                    $storiesDetaill['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                    $storiesDetaill['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                    $storiesDetaill['story'] = [];
                    foreach ($value->user_event_story as $storyVal) {
                        $storiesData['id'] = $storyVal->id;
                        $storiesData['storyurl'] = empty($storyVal->story) ? "" : asset('storage/event_user_stories/' . $storyVal->story);
                        $storiesData['type'] = $storyVal->type;
                        $storiesData['post_time'] =  setpostTime($storyVal->created_at);
                        if ($storyVal->type == 'video') {

                            $storiesData['video_duration'] = (!empty($storyVal->duration)) ? $storyVal->duration : "";
                        }
                        $storiesData['post_time'] =  setpostTime($storyVal->created_at);
                        $storiesDetaill['story'][] = $storiesData;
                    }

                    $storiesList[] = $storiesDetaill;
                }
            }


            //  Posts List //

            $eventPostList = EventPost::with(['user'])->withCount(['event_post_comment' => function ($query) {
                $query->where('parent_comment_id', NULL);
            }, 'event_post_reaction'])->where('event_id', $input['event_id'])->orderBy('id', 'desc')->get();



            $postList = [];

            // $this->checkUserTypeForPost($input['event_id'], $user->id);

            $checkEventOwner = Event::where(['id' => $input['event_id'], 'user_id' => $user->id])->first();

            if (!empty($checkEventOwner)) {

                if (count($eventPostList) != 0) {



                    foreach ($eventPostList as $value) {

                        $checkUserRsvp = checkUserAttendOrNot($value->event_id, $value->user->id);



                        $checkUserIsReaction = EventPostReaction::where(['event_id' => $input['event_id'], 'event_post_id' => $value->id, 'user_id' => $user->id])->first();
                        if ($value->post_type == '0') { // Normal

                            $postsNormalDetail['id'] =  $value->id;

                            $postsNormalDetail['user_id'] =  $value->user->id;

                            $postsNormalDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                            $postsNormalDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                            $postsNormalDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;

                            $postsNormalDetail['rsvp_status'] = $checkUserRsvp;


                            $postsNormalDetail['post_type'] = $value->post_type;

                            $postsNormalDetail['created_at'] = $value->created_at;



                            $reactionList = getReaction($value->id);

                            $postReactionList = [];

                            foreach ($reactionList as $values) {

                                $postReactionList[] = $values->reaction;
                            }

                            $postsNormalDetail['reactionList'] = $postReactionList;

                            $postsNormalDetail['total_comment'] = $value->event_post_comment_count;

                            $postsNormalDetail['total_likes'] = $value->event_post_reaction_count;

                            $postsNormalDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                            $postsNormalDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";





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
                            $postNormalDetailList['reactionList'] = $postReactionList;

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

                            $postsImageDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;

                            $postsImageDetail['rsvp_status'] = $checkUserRsvp;



                            $eventPostImage = EventPostImage::where(['event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                            $postsImageDetail['post_image'] = empty($eventPostImage->post_image) ? "" : asset('storage/post_image/' . $eventPostImage->post_image);



                            $postsImageDetail['post_type'] = $value->post_type;

                            $postsImageDetail['created_at'] = $value->created_at;



                            $reactionList = getReaction($value->id);

                            $postReactionList = [];

                            foreach ($reactionList as $values) {

                                $postReactionList[] = $values->reaction;
                            }

                            $postsImageDetail['reactionList'] = $postReactionList;

                            $postsImageDetail['total_comment'] = $value->event_post_comment_count;

                            $postsImageDetail['total_likes'] = $value->event_post_reaction_count;

                            $postsImageDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                            $postsImageDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";





                            // postDetail // 



                            $postImages = getPostImages($value->id);

                            $postDetails = [];

                            $postImg = [];


                            foreach ($postImages as $imgVal) {





                                $postMedia['media_url'] = asset('storage/post_image/' . $imgVal->post_image);

                                $postMedia['type'] = $imgVal->type;


                                if ($imgVal->type == 'video') {

                                    $postMedia['video_duration'] = ($imgVal->duration != NULL) ? $imgVal->duration : "";
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
                            $postImgDetailList['reactionList'] = $postReactionList;

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

                            $polls = EventPostPoll::with('event_poll_option')->withCount('user_poll_data')->where(['event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                            $postsPollDetail['total_poll_vote'] = $polls->user_poll_data_count;



                            $postsPollDetail['poll_id'] = $polls->id;

                            $postsPollDetail['poll_question'] = $polls->poll_question;

                            $postsPollDetail['poll_option'] = [];

                            foreach ($polls->event_poll_option as $optionValue) {

                                $optionData['id'] = $optionValue->id;

                                $optionData['option'] = $optionValue->option;

                                $optionData['total_vote'] =  round(getOptionTotalVote($optionValue->id) * 100 / count(getEventInvitedUser($input['event_id']))) . "%";



                                $postsPollDetail['poll_option'][] = $optionData;
                            }

                            $postsPollDetail['post_type'] = $value->post_type;

                            $postsPollDetail['rsvp_status'] = $checkUserRsvp;

                            $postsPollDetail['created_at'] = $value->created_at;

                            $reactionList = getReaction($value->id);

                            $postReactionList = [];

                            foreach ($reactionList as $values) {

                                $postReactionList[] = $values->reaction;
                            }

                            $postsPollDetail['reactionList'] = $postReactionList;

                            $postsPollDetail['total_comment'] = $value->event_post_comment_count;

                            $postsPollDetail['total_likes'] = $value->event_post_reaction_count;

                            $postsPollDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                            $postsPollDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";



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


                            $postPollDetailList['reactionList'] = $postReactionList;

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

                            $postsRecordDetail['post_recording'] = empty($value->post_recording) ? "" : asset('storage/event_post_recording/' . $value->post_recording);

                            $postsRecordDetail['post_type'] = $value->post_type;

                            $postsRecordDetail['created_at'] = $value->created_at;

                            $reactionList = getReaction($value->id);

                            $postReactionList = [];

                            foreach ($reactionList as $values) {

                                $postReactionList[] = $values->reaction;
                            }

                            $postsRecordDetail['rsvp_status'] = $checkUserRsvp;

                            $postsRecordDetail['reactionList'] = $postReactionList;

                            $postsRecordDetail['total_comment'] = $value->event_post_comment_count;

                            $postsRecordDetail['total_likes'] = $value->event_post_reaction_count;

                            $postsRecordDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                            $postsRecordDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";

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

                            $postRecordDetailList['reactionList'] = $postReactionList;

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

                if (count($eventPostList) != 0) {



                    foreach ($eventPostList as $value) {

                        $checkUserRsvp = checkUserAttendOrNot($value->event_id, $value->user->id);



                        $checkUserIsReaction = EventPostReaction::where(['event_id' => $input['event_id'], 'event_post_id' => $value->id, 'user_id' => $user->id])->first();

                        if ($value->post_privacy == '1') {
                            if ($value->post_type == '0') { // Normal

                                $postsNormalDetail['id'] =  $value->id;

                                $postsNormalDetail['user_id'] =  $value->user->id;

                                $postsNormalDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;

                                $postsNormalDetail['profile'] =  empty($value->user->profile) ? "" : asset('public/storage/profile/' . $value->user->profile);

                                $postsNormalDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;

                                $postsNormalDetail['rsvp_status'] = $checkUserRsvp;


                                $postsNormalDetail['post_type'] = $value->post_type;

                                $postsNormalDetail['created_at'] = $value->created_at;



                                $reactionList = getReaction($value->id);

                                $postReactionList = [];

                                foreach ($reactionList as $values) {

                                    $postReactionList[] = $values->reaction;
                                }

                                $postsNormalDetail['reactionList'] = $postReactionList;

                                $postsNormalDetail['total_comment'] = $value->event_post_comment_count;

                                $postsNormalDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsNormalDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsNormalDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";





                                // postDetail // 



                                $postImages = getPostImages($value->id);

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
                                $postNormalDetailList['reactionList'] = $postReactionList;

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

                                $postsImageDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;

                                $postsImageDetail['rsvp_status'] = $checkUserRsvp;



                                $eventPostImage = EventPostImage::where(['event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                                $postsImageDetail['post_image'] = empty($eventPostImage->post_image) ? "" : asset('storage/post_image/' . $eventPostImage->post_image);



                                $postsImageDetail['post_type'] = $value->post_type;

                                $postsImageDetail['created_at'] = $value->created_at;



                                $reactionList = getReaction($value->id);

                                $postReactionList = [];

                                foreach ($reactionList as $values) {

                                    $postReactionList[] = $values->reaction;
                                }

                                $postsImageDetail['reactionList'] = $postReactionList;

                                $postsImageDetail['total_comment'] = $value->event_post_comment_count;

                                $postsImageDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsImageDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsImageDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";





                                // postDetail // 



                                $postImages = getPostImages($value->id);

                                $postDetails = [];

                                $postImg = [];


                                foreach ($postImages as $imgVal) {





                                    $postMedia['media_url'] = asset('storage/post_image/' . $imgVal->post_image);

                                    $postMedia['type'] = $imgVal->type;


                                    if ($imgVal->type == 'video') {

                                        $postMedia['video_duration'] = ($imgVal->duration != NULL) ? $imgVal->duration : "";
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
                                $postImgDetailList['reactionList'] = $postReactionList;

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

                                $polls = EventPostPoll::with('event_poll_option')->withCount('user_poll_data')->where(['event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                                $postsPollDetail['total_poll_vote'] = $polls->user_poll_data_count;



                                $postsPollDetail['poll_id'] = $polls->id;

                                $postsPollDetail['poll_question'] = $polls->poll_question;

                                $postsPollDetail['poll_option'] = [];

                                foreach ($polls->event_poll_option as $optionValue) {

                                    $optionData['id'] = $optionValue->id;

                                    $optionData['option'] = $optionValue->option;

                                    $optionData['total_vote'] =  round(getOptionTotalVote($optionValue->id) * 100 / count(getEventInvitedUser($input['event_id']))) . "%";



                                    $postsPollDetail['poll_option'][] = $optionData;
                                }

                                $postsPollDetail['post_type'] = $value->post_type;

                                $postsPollDetail['rsvp_status'] = $checkUserRsvp;

                                $postsPollDetail['created_at'] = $value->created_at;

                                $reactionList = getReaction($value->id);

                                $postReactionList = [];

                                foreach ($reactionList as $values) {

                                    $postReactionList[] = $values->reaction;
                                }

                                $postsPollDetail['reactionList'] = $postReactionList;

                                $postsPollDetail['total_comment'] = $value->event_post_comment_count;

                                $postsPollDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsPollDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsPollDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";



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


                                $postPollDetailList['reactionList'] = $postReactionList;

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

                                $postsRecordDetail['post_recording'] = empty($value->post_recording) ? "" : asset('storage/event_post_recording/' . $value->post_recording);

                                $postsRecordDetail['post_type'] = $value->post_type;

                                $postsRecordDetail['created_at'] = $value->created_at;

                                $reactionList = getReaction($value->id);

                                $postReactionList = [];

                                foreach ($reactionList as $values) {

                                    $postReactionList[] = $values->reaction;
                                }

                                $postsRecordDetail['rsvp_status'] = $checkUserRsvp;

                                $postsRecordDetail['reactionList'] = $postReactionList;

                                $postsRecordDetail['total_comment'] = $value->event_post_comment_count;

                                $postsRecordDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsRecordDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsRecordDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";

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

                                $postRecordDetailList['reactionList'] = $postReactionList;

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


                                $postsNormalDetail['post_type'] = $value->post_type;

                                $postsNormalDetail['created_at'] = $value->created_at;



                                $reactionList = getReaction($value->id);

                                $postReactionList = [];

                                foreach ($reactionList as $values) {

                                    $postReactionList[] = $values->reaction;
                                }

                                $postsNormalDetail['reactionList'] = $postReactionList;

                                $postsNormalDetail['total_comment'] = $value->event_post_comment_count;

                                $postsNormalDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsNormalDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsNormalDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";





                                // postDetail // 



                                $postImages = getPostImages($value->id);

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
                                $postNormalDetailList['reactionList'] = $postReactionList;

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

                                $postsImageDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;

                                $postsImageDetail['rsvp_status'] = $checkUserRsvp;



                                $eventPostImage = EventPostImage::where(['event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                                $postsImageDetail['post_image'] = empty($eventPostImage->post_image) ? "" : asset('storage/post_image/' . $eventPostImage->post_image);



                                $postsImageDetail['post_type'] = $value->post_type;

                                $postsImageDetail['created_at'] = $value->created_at;



                                $reactionList = getReaction($value->id);

                                $postReactionList = [];

                                foreach ($reactionList as $values) {

                                    $postReactionList[] = $values->reaction;
                                }

                                $postsImageDetail['reactionList'] = $postReactionList;

                                $postsImageDetail['total_comment'] = $value->event_post_comment_count;

                                $postsImageDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsImageDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsImageDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";





                                // postDetail // 



                                $postImages = getPostImages($value->id);

                                $postDetails = [];

                                $postImg = [];


                                foreach ($postImages as $imgVal) {





                                    $postMedia['media_url'] = asset('storage/post_image/' . $imgVal->post_image);

                                    $postMedia['type'] = $imgVal->type;


                                    if ($imgVal->type == 'video') {

                                        $postMedia['video_duration'] = ($imgVal->duration != NULL) ? imgVal->duration : "";
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
                                $postImgDetailList['reactionList'] = $postReactionList;

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

                                $polls = EventPostPoll::with('event_poll_option')->withCount('user_poll_data')->where(['event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                                $postsPollDetail['total_poll_vote'] = $polls->user_poll_data_count;



                                $postsPollDetail['poll_id'] = $polls->id;

                                $postsPollDetail['poll_question'] = $polls->poll_question;

                                $postsPollDetail['poll_option'] = [];

                                foreach ($polls->event_poll_option as $optionValue) {

                                    $optionData['id'] = $optionValue->id;

                                    $optionData['option'] = $optionValue->option;

                                    $optionData['total_vote'] =  round(getOptionTotalVote($optionValue->id) * 100 / count(getEventInvitedUser($input['event_id']))) . "%";



                                    $postsPollDetail['poll_option'][] = $optionData;
                                }

                                $postsPollDetail['post_type'] = $value->post_type;

                                $postsPollDetail['rsvp_status'] = $checkUserRsvp;

                                $postsPollDetail['created_at'] = $value->created_at;

                                $reactionList = getReaction($value->id);

                                $postReactionList = [];

                                foreach ($reactionList as $values) {

                                    $postReactionList[] = $values->reaction;
                                }

                                $postsPollDetail['reactionList'] = $postReactionList;

                                $postsPollDetail['total_comment'] = $value->event_post_comment_count;

                                $postsPollDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsPollDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsPollDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";



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


                                $postPollDetailList['reactionList'] = $postReactionList;

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

                                $postsRecordDetail['post_recording'] = empty($value->post_recording) ? "" : asset('storage/event_post_recording/' . $value->post_recording);

                                $postsRecordDetail['post_type'] = $value->post_type;

                                $postsRecordDetail['created_at'] = $value->created_at;

                                $reactionList = getReaction($value->id);

                                $postReactionList = [];

                                foreach ($reactionList as $values) {

                                    $postReactionList[] = $values->reaction;
                                }

                                $postsRecordDetail['rsvp_status'] = $checkUserRsvp;

                                $postsRecordDetail['reactionList'] = $postReactionList;

                                $postsRecordDetail['total_comment'] = $value->event_post_comment_count;

                                $postsRecordDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsRecordDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsRecordDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";

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

                                $postRecordDetailList['reactionList'] = $postReactionList;

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


                                $postsNormalDetail['post_type'] = $value->post_type;

                                $postsNormalDetail['created_at'] = $value->created_at;



                                $reactionList = getReaction($value->id);

                                $postReactionList = [];

                                foreach ($reactionList as $values) {

                                    $postReactionList[] = $values->reaction;
                                }

                                $postsNormalDetail['reactionList'] = $postReactionList;

                                $postsNormalDetail['total_comment'] = $value->event_post_comment_count;

                                $postsNormalDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsNormalDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsNormalDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";





                                // postDetail // 



                                $postImages = getPostImages($value->id);

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
                                $postNormalDetailList['reactionList'] = $postReactionList;

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

                                $postsImageDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;

                                $postsImageDetail['rsvp_status'] = $checkUserRsvp;



                                $eventPostImage = EventPostImage::where(['event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                                $postsImageDetail['post_image'] = empty($eventPostImage->post_image) ? "" : asset('storage/post_image/' . $eventPostImage->post_image);



                                $postsImageDetail['post_type'] = $value->post_type;

                                $postsImageDetail['created_at'] = $value->created_at;



                                $reactionList = getReaction($value->id);

                                $postReactionList = [];

                                foreach ($reactionList as $values) {

                                    $postReactionList[] = $values->reaction;
                                }

                                $postsImageDetail['reactionList'] = $postReactionList;

                                $postsImageDetail['total_comment'] = $value->event_post_comment_count;

                                $postsImageDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsImageDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsImageDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";





                                // postDetail // 



                                $postImages = getPostImages($value->id);

                                $postDetails = [];

                                $postImg = [];


                                foreach ($postImages as $imgVal) {





                                    $postMedia['media_url'] = asset('storage/post_image/' . $imgVal->post_image);

                                    $postMedia['type'] = $imgVal->type;


                                    if ($imgVal->type == 'video') {

                                        $postMedia['video_duration'] = ($imgVal->duration != NULL) ? imgVal->duration : "";
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
                                $postImgDetailList['reactionList'] = $postReactionList;

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

                                $polls = EventPostPoll::with('event_poll_option')->withCount('user_poll_data')->where(['event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                                $postsPollDetail['total_poll_vote'] = $polls->user_poll_data_count;



                                $postsPollDetail['poll_id'] = $polls->id;

                                $postsPollDetail['poll_question'] = $polls->poll_question;

                                $postsPollDetail['poll_option'] = [];

                                foreach ($polls->event_poll_option as $optionValue) {

                                    $optionData['id'] = $optionValue->id;

                                    $optionData['option'] = $optionValue->option;

                                    $optionData['total_vote'] =  round(getOptionTotalVote($optionValue->id) * 100 / count(getEventInvitedUser($input['event_id']))) . "%";



                                    $postsPollDetail['poll_option'][] = $optionData;
                                }

                                $postsPollDetail['post_type'] = $value->post_type;

                                $postsPollDetail['rsvp_status'] = $checkUserRsvp;

                                $postsPollDetail['created_at'] = $value->created_at;

                                $reactionList = getReaction($value->id);

                                $postReactionList = [];

                                foreach ($reactionList as $values) {

                                    $postReactionList[] = $values->reaction;
                                }

                                $postsPollDetail['reactionList'] = $postReactionList;

                                $postsPollDetail['total_comment'] = $value->event_post_comment_count;

                                $postsPollDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsPollDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsPollDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";



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


                                $postPollDetailList['reactionList'] = $postReactionList;

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

                                $postsRecordDetail['post_recording'] = empty($value->post_recording) ? "" : asset('storage/event_post_recording/' . $value->post_recording);

                                $postsRecordDetail['post_type'] = $value->post_type;

                                $postsRecordDetail['created_at'] = $value->created_at;

                                $reactionList = getReaction($value->id);

                                $postReactionList = [];

                                foreach ($reactionList as $values) {

                                    $postReactionList[] = $values->reaction;
                                }

                                $postsRecordDetail['rsvp_status'] = $checkUserRsvp;

                                $postsRecordDetail['reactionList'] = $postReactionList;

                                $postsRecordDetail['total_comment'] = $value->event_post_comment_count;

                                $postsRecordDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsRecordDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsRecordDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";

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

                                $postRecordDetailList['reactionList'] = $postReactionList;

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


                                $postsNormalDetail['post_type'] = $value->post_type;

                                $postsNormalDetail['created_at'] = $value->created_at;



                                $reactionList = getReaction($value->id);

                                $postReactionList = [];

                                foreach ($reactionList as $values) {

                                    $postReactionList[] = $values->reaction;
                                }

                                $postsNormalDetail['reactionList'] = $postReactionList;

                                $postsNormalDetail['total_comment'] = $value->event_post_comment_count;

                                $postsNormalDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsNormalDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsNormalDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";





                                // postDetail // 



                                $postImages = getPostImages($value->id);

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
                                $postNormalDetailList['reactionList'] = $postReactionList;

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

                                $postsImageDetail['post_message'] = empty($value->post_message) ? "" :  $value->post_message;

                                $postsImageDetail['rsvp_status'] = $checkUserRsvp;



                                $eventPostImage = EventPostImage::where(['event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                                $postsImageDetail['post_image'] = empty($eventPostImage->post_image) ? "" : asset('storage/post_image/' . $eventPostImage->post_image);



                                $postsImageDetail['post_type'] = $value->post_type;

                                $postsImageDetail['created_at'] = $value->created_at;



                                $reactionList = getReaction($value->id);

                                $postReactionList = [];

                                foreach ($reactionList as $values) {

                                    $postReactionList[] = $values->reaction;
                                }

                                $postsImageDetail['reactionList'] = $postReactionList;

                                $postsImageDetail['total_comment'] = $value->event_post_comment_count;

                                $postsImageDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsImageDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsImageDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";





                                // postDetail // 



                                $postImages = getPostImages($value->id);

                                $postDetails = [];

                                $postImg = [];


                                foreach ($postImages as $imgVal) {





                                    $postMedia['media_url'] = asset('storage/post_image/' . $imgVal->post_image);

                                    $postMedia['type'] = $imgVal->type;


                                    if ($imgVal->type == 'video') {

                                        $postMedia['video_duration'] = ($imgVal->duration != NULL) ? imgVal->duration : "";
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
                                $postImgDetailList['reactionList'] = $postReactionList;

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

                                $polls = EventPostPoll::with('event_poll_option')->withCount('user_poll_data')->where(['event_id' => $input['event_id'], 'event_post_id' => $value->id])->first();

                                $postsPollDetail['total_poll_vote'] = $polls->user_poll_data_count;



                                $postsPollDetail['poll_id'] = $polls->id;

                                $postsPollDetail['poll_question'] = $polls->poll_question;

                                $postsPollDetail['poll_option'] = [];

                                foreach ($polls->event_poll_option as $optionValue) {

                                    $optionData['id'] = $optionValue->id;

                                    $optionData['option'] = $optionValue->option;

                                    $optionData['total_vote'] =  round(getOptionTotalVote($optionValue->id) * 100 / count(getEventInvitedUser($input['event_id']))) . "%";



                                    $postsPollDetail['poll_option'][] = $optionData;
                                }

                                $postsPollDetail['post_type'] = $value->post_type;

                                $postsPollDetail['rsvp_status'] = $checkUserRsvp;

                                $postsPollDetail['created_at'] = $value->created_at;

                                $reactionList = getReaction($value->id);

                                $postReactionList = [];

                                foreach ($reactionList as $values) {

                                    $postReactionList[] = $values->reaction;
                                }

                                $postsPollDetail['reactionList'] = $postReactionList;

                                $postsPollDetail['total_comment'] = $value->event_post_comment_count;

                                $postsPollDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsPollDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsPollDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";



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


                                $postPollDetailList['reactionList'] = $postReactionList;

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

                                $postsRecordDetail['post_recording'] = empty($value->post_recording) ? "" : asset('storage/event_post_recording/' . $value->post_recording);

                                $postsRecordDetail['post_type'] = $value->post_type;

                                $postsRecordDetail['created_at'] = $value->created_at;

                                $reactionList = getReaction($value->id);

                                $postReactionList = [];

                                foreach ($reactionList as $values) {

                                    $postReactionList[] = $values->reaction;
                                }

                                $postsRecordDetail['rsvp_status'] = $checkUserRsvp;

                                $postsRecordDetail['reactionList'] = $postReactionList;

                                $postsRecordDetail['total_comment'] = $value->event_post_comment_count;

                                $postsRecordDetail['total_likes'] = $value->event_post_reaction_count;

                                $postsRecordDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                                $postsRecordDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";

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

                                $postRecordDetailList['reactionList'] = $postReactionList;

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

                    $storyData = $request->story;

                    foreach ($storyData as $postStoryValue) {



                        $postStory = $postStoryValue;

                        $imageName = time() . '_' . $postStory->getClientOriginalName();

                        Storage::disk('public')->putFileAs('event_user_stories', $postStory, $imageName);

                        $checkIsimageOrVideo = checkIsimageOrVideo($postStory);
                        $duration = '0';
                        if ($checkIsimageOrVideo == 'video') {
                            $duration = getVideoDuration($postStory);
                        }

                        $storyId = $createStory->id;
                        UserEventStory::create([
                            'event_story_id' => $storyId,
                            'story' => $imageName,
                            'duration' => $duration,
                            'type' => $checkIsimageOrVideo
                        ]);
                    }
                    DB::commit();
                    return response()->json(['status' => 1, 'message' => "Event story uploaded successfully"]);
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

            DB::beginTransaction();

            $event_post_comment = new EventPostComment;

            $event_post_comment->event_id = $input['event_id'];

            $event_post_comment->event_post_id = $input['event_post_id'];

            $event_post_comment->user_id = $user->id;

            $event_post_comment->parent_comment_id = $input['parent_comment_id'];

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


            $replyList = EventPostComment::with('user')->withcount('post_comment_reaction')->where("parent_comment_id", $input['parent_comment_id'])->orderBy('id', 'DESC')->get();



            $commentInfo = [];

            if (!empty($replyList)) {

                foreach ($replyList as $replyVal) {



                    $totalReply = EventPostComment::withcount('post_comment_reaction')->where("parent_comment_id", $replyVal->id)->count();



                    $commentReply['id'] = $replyVal->id;

                    $commentReply['event_post_id'] = $replyVal->event_post_id;

                    $commentReply['comment'] = $replyVal->comment_text;

                    $commentReply['parent_comment_id'] = $replyVal->parent_comment_id;

                    $parentUser = getParentCommentUserData($replyVal->parent_comment_id);



                    $commentReply['parent_username'] = $parentUser->user->firstname . ' ' . $parentUser->user->lastname;

                    $commentReply['user_id'] = $replyVal->user_id;

                    $commentReply['username'] = $replyVal->user->firstname . ' ' . $replyVal->user->lastname;

                    $commentReply['profile'] = (!empty($replyVal->user->profile)) ? asset('public/storage/profile/' . $replyVal->user->profile) : "";

                    $commentReply['reply_comment_total_likes'] = $replyVal->post_comment_reaction_count;

                    $commentReply['is_like'] = checkUserPhotoIsLike($replyVal->id, $user->id);

                    $commentReply['total_replies'] = $totalReply;

                    $commentReply['created_at'] = $replyVal->created_at;

                    $commentInfo[] = $commentReply;
                }
            }



            return response()->json(['status' => 1, 'total_comments_replies' => count($replyList), 'data' => $commentInfo, 'message' => "Post commented by you"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "error"]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "something went wrong"]);
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

                Storage::disk('public')->putFileAs('event_post_recording', $record, $recordingName);

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

                            $checkImage =   Storage::disk('public')->putFileAs('post_image', $postImage, $imageName);

                            $checkIsimageOrVideo = checkIsimageOrVideo($postImage);

                            $duration = "";
                            if ($checkIsimageOrVideo == 'video') {
                                $duration = getVideoDuration($postImage);
                            }

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

                $event_post_reaction->save();



                $notificationParam = [

                    'sender_id' => $user->id,

                    'event_id' => $input['event_id'],

                    'post_id' => $input['event_post_id']

                ];
                sendNotification('like_post', $notificationParam);
                DB::commit();

                $reactionList = getReaction($input['event_post_id']);



                $totalComment = EventPostComment::where(['event_post_id' => $input['event_post_id'], 'parent_comment_id' => NULl])->count();

                $postReactionList = [];

                foreach ($reactionList as $values) {

                    $postReactionList[] = $values->reaction;
                }



                $reactionData['reactionList'] = $postReactionList;

                $reactionData['total_likes'] = count($reactionList);

                $reactionData['total_comments'] = $totalComment;

                $reactionData['is_reaction'] = '1';

                $reactionData['self_reaction'] = $input['reaction'];


                // Post Detail

                $postDetails = [];
                $postReaction = [];

                $postReactions = getReaction($input['event_post_id']);
                $checkUserIsReaction = EventPostReaction::where(['event_id' => $input['event_id'], 'event_post_id' => $input['event_post_id'], 'user_id' => $user->id])->first();
                foreach ($postReactions as $reactionVal) {

                    $reactionInfo['id'] = $reactionVal->id;

                    $reactionInfo['event_post_id'] = $reactionVal->event_post_id;

                    $reactionInfo['reaction'] = $reactionVal->reaction;

                    $reactionInfo['user_id'] = $reactionVal->user_id;

                    $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                    $postReaction[] = $reactionInfo;
                }

                $postDetailList['post_reaction'] = $postReaction;
                $postDetailList['reactionList'] = $postReactionList;

                $postDetailList['total_comment'] = $totalComment;

                $postDetailList['total_likes'] = count($reactionList);

                $postDetailList['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                $postDetailList['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";


                $postCommentList = [];

                $postComment = getComments($input['event_post_id']);



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

                $postDetailList['post_comment'] = $postCommentList;





                $postDetails[] = $postDetailList;



                $reactionData['post_detail'] = $postDetails;




                return response()->json(['status' => 1, 'data' => $reactionData, 'message' => "Post liked by you"]);
            } else {

                $checkReaction = EventPostReaction::where(['event_id' => $input['event_id'], 'event_post_id' => $input['event_post_id'], 'user_id' => $user->id]);

                $checkReaction->delete();


                $removeNotification = Notification::where(['event_id' => $input['event_id'], 'sender_id' => $user->id, 'post_id' => $input['event_post_id'], 'notification_type' => 'like_post'])->first();

                if (!empty($removeNotification)) {

                    $removeNotification->delete();
                }

                DB::commit();


                $reactionList = getReaction($input['event_post_id']);

                $totalComment = EventPostComment::where(['event_post_id' => $input['event_post_id'], 'parent_comment_id' => NULl])->count();

                $postReactionList = [];

                foreach ($reactionList as $values) {

                    $postReactionList[] = $values->reaction;
                }



                $reactionData['reactionList'] = $postReactionList;

                $reactionData['total_likes'] = count($reactionList);

                $reactionData['total_comments'] = $totalComment;

                $reactionData['is_reaction'] = '0';

                $reactionData['self_reaction'] = "";



                // Post Detail

                $postDetails = [];
                $postReaction = [];

                $postReactions = getReaction($input['event_post_id']);
                $checkUserIsReaction = EventPostReaction::where(['event_id' => $input['event_id'], 'event_post_id' => $input['event_post_id'], 'user_id' => $user->id])->first();
                foreach ($postReactions as $reactionVal) {

                    $reactionInfo['id'] = $reactionVal->id;

                    $reactionInfo['event_post_id'] = $reactionVal->event_post_id;

                    $reactionInfo['reaction'] = $reactionVal->reaction;

                    $reactionInfo['user_id'] = $reactionVal->user_id;

                    $reactionInfo['profile'] = (!empty($reactionVal->user->profile)) ? asset('public/storage/profile/' . $reactionVal->user->profile) : "";

                    $postReaction[] = $reactionInfo;
                }

                $postDetailList['post_reaction'] = $postReaction;
                $postDetailList['reactionList'] = $postReactionList;

                $postDetailList['total_comment'] = $totalComment;

                $postDetailList['total_likes'] = count($reactionList);

                $postDetailList['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';

                $postDetailList['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";


                $postCommentList = [];

                $postComment = getComments($input['event_post_id']);



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

                $postDetailList['post_comment'] = $postCommentList;





                $postDetails[] = $postDetailList;



                $reactionData['post_detail'] = $postDetails;
                return response()->json(['status' => 1, 'data' => $reactionData, 'message' => "Post disliked by you"]);
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

            $postCommentList = [];

            foreach ($postComment as $commentVal) {

                $commentInfo['id'] = $commentVal->id;

                $commentInfo['event_post_id'] = $commentVal->event_post_id;

                $commentInfo['comment'] = $commentVal->comment_text;

                $commentInfo['user_id'] = $commentVal->user_id;

                $commentInfo['username'] = $commentVal->user->firstname . ' ' . $commentVal->user->lastname;

                $commentInfo['profile'] = (!empty($commentVal->user->profile)) ? asset('public/storage/profile/' . $commentVal->user->profile) : "";

                $commentInfo['comment_total_likes'] = $commentVal->post_comment_reaction_count;

                $commentInfo['is_like'] = checkUserPhotoIsLike($commentVal->id, $user->id);

                $commentInfo['created_at'] = $commentVal->created_at;

                $commentInfo['total_replies'] = $commentVal->replies_count;



                $postCommentList[] = $commentInfo;
            }


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

            return response()->json(['status' => 1, 'message' => "voted sucessfully"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "error"]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
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
            $eventDetail = Event::with(['user', 'event_image', 'event_schedule', 'event_co_host' => function ($query) {

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



            $eventAboutHost['adults'] = $adults;

            $eventAboutHost['kids'] = $kids;



            $eventAboutHost['not_attending'] = $eventNotComing;

            $eventAboutHost['pending'] = $pendingUser;





            $userRsvpStatusList = EventInvitedUser::whereHas('user', function ($query) {

                $query->where('app_user', '1');
            })->where(['event_id' => $eventDetail->id])->get();

            $rsvpUserStatusList = [];

            if (count($userRsvpStatusList) != 0) {



                foreach ($userRsvpStatusList as $value) {

                    $rsvpUserStatus['id'] = $value->id;

                    $rsvpUserStatus['user_id'] = $value->user->id;

                    $rsvpUserStatus['username'] = $value->user->firstname . ' ' . $value->user->lastname;

                    $rsvpUserStatus['profile'] = (!empty($value->user->profile) || $value->user->profile != NULL) ? asset('public/storage/profile/' . $value->user->profile) : "";

                    $rsvpUserStatus['email'] = ($value->prefer == 'email') ? $value->user->email : "";

                    $rsvpUserStatus['phone_number'] = ($value->prefer == 'phone') ? $value->user->phone_number : "";

                    $rsvpUserStatus['kids'] = $value->kids;

                    $rsvpUserStatus['adults'] = $value->adults;

                    $rsvpUserStatus['rsvp_status'] = $value->rsvp_status;

                    if ($value->rsvp_d == '0' && $value->read == '1') {

                        $rsvpUserStatus['rsvp_status'] = 2;
                    }

                    $rsvpUserStatus['read'] = $value->read;

                    $rsvpUserStatus['rsvp_d'] = $value->rsvp_d;

                    $rsvpUserStatus['invitation_sent'] = $value->invitation_sent;



                    $rsvpUserStatusList[] = $rsvpUserStatus;
                }

                $eventAboutHost['rsvp_status_list'] = $rsvpUserStatusList;
            }

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

                        $invitation = new InvitationEmail();

                        $result =  Mail::to($email)->send($invitation);



                        $invitation_sent_status =  EventInvitedUser::where(['event_id' => $input['event_id'], 'user_id' => $value['id']])->first();

                        if ($result != null) {

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

                    $postmedia = $request->post_media;



                    $event_post_data = EventPostPhoto::create([

                        'event_id' => $request->event_id,

                        'user_id' => $user->id,

                        'post_message' => $request->post_message,

                    ]);



                    if (!empty($event_post_data->id)) {



                        foreach ($postmedia as $postMediaValue) {



                            $postMedia = $postMediaValue;

                            $mediaName = time() . '_' . $postMedia->getClientOriginalName();

                            Storage::disk('public')->putFileAs('post_photo', $postMedia, $mediaName);

                            $checkIsimageOrVideo = checkIsimageOrVideo($postMedia);



                            EventPostPhotoData::create([

                                'event_post_photo_id' => $event_post_data->id,

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

            return response()->json(['status' => 0, 'message' => "error"]);
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

                    $commentInfo['post_time'] =  setpostTime($commentVal->created_at);

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

                    $commentInfo['post_time'] =  setpostTime($commentVal->created_at);

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

                $postPhotoDetail['id'] = $value->id;

                $postPhotoDetail['post_message'] = (!empty($value->post_message) || $value->post_message != NULL) ? $value->post_message : "";

                $postPhotoDetail['post_time'] = setpostTime($value->created_at);

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

                    $commentInfo['post_time'] =  setpostTime($commentVal->created_at);

                    $commentInfo['total_replies'] = $commentVal->replies_count;



                    $postCommentList[] = $commentInfo;
                }

                $postPhotoDetail['post_comment'] = $postCommentList;





                $postDetails[] = $postPhotoDetail;



                $postPhotoDetail['post_detail'] = $postDetails;





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

            $notificationData = Notification::with(['user', 'post' => function ($query) {
                $query->with(['post_image', 'event_post_poll'])->withcount(['event_post_reaction', 'event_post_comment' => function ($query) {
                    $query->where('parent_comment_id', NULL);
                }]);
            }])->orderBy('id', 'DESC')->where('event_id', $value->id)->get();

            $notificationInfo = [];
            foreach ($notificationData as $values) {

                if ($values->user_id == $user->id) {

                    if ($values->notification_type == 'invite') {



                        $notificationDetail['notification_id'] = $values->id;

                        $notificationDetail['notification_type'] = $values->notification_type;

                        $notificationDetail['user_id'] = $values->user_id;

                        $notificationDetail['sender_id'] = $values->sender_id;

                        $notificationDetail['notification_message'] = $values->notification_message;

                        $notificationDetail['read'] = $values->read;

                        $notificationInfo[] = $notificationDetail;
                    }

                    if ($values->notification_type == 'upload_post') {



                        $notificationDetail['notification_id'] = $values->id;

                        $notificationDetail['notification_type'] = $values->notification_type;

                        $notificationDetail['user_id'] = $values->user_id;

                        $notificationDetail['sender_id'] = $values->sender_id;

                        $notificationDetail['post_id'] = $values->post_id;

                        $notificationDetail['notification_message'] = $values->notification_message;

                        $notificationDetail['read'] = $values->read;
                        $notificationDetail['post_time'] = setpostTime($values->created_at);
                        $notificationInfo[] = $notificationDetail;
                    }

                    if ($values->notification_type == 'like_post') {



                        $notificationDetail['notification_id'] = $values->id;

                        $notificationDetail['notification_type'] = $values->notification_type;

                        $notificationDetail['user_id'] = $values->user_id;

                        $notificationDetail['sender_id'] = $values->sender_id;

                        $notificationDetail['post_id'] = $values->post_id;


                        $notificationDetail['post_detail'] = [];
                        if ($values->post->post_type == '1') {
                            $postDetail['post_type'] = !empty($values->post->post_type) ? $values->post->post_type : "";
                            $postDetail['post_message'] = !empty($values->post->post_message) ? $values->post->post_message : "";
                            $postDetail['post_media'] = !empty($values->post->post_image) ? asset('storage/post_image/' . $values->post->post_image[0]['post_image']) : "";
                            $postDetail['total_likes'] = !empty($values->post->event_post_reaction_count) ? $values->post->event_post_reaction_count : "";
                            $postDetail['total_comments'] = !empty($values->post->event_post_comment_count) ? $values->post->event_post_comment_count : "";
                            $notificationDetail['post_detail'][] = $postDetail;
                        }


                        $notificationDetail['notification_message'] = $values->notification_message;

                        $notificationDetail['read'] = $values->read;
                        $notificationDetail['post_time'] = setpostTime($values->created_at);
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
                        $notificationDetail['comment_detail'] = [];

                        $commentDetail['id'] = $postCommentDetail->id;
                        $commentDetail['comment_text'] = $postCommentDetail->comment_text;
                        $notificationDetail['comment_detail'][] = $commentDetail;

                        $notificationDetail['post_detail'] = [];
                        if ($values->post->post_type == '1') {
                            $postDetail['post_type'] = !empty($values->post->post_type) ? $values->post->post_type : "";
                            $postDetail['post_message'] = !empty($values->post->post_message) ? $values->post->post_message : "";
                            $postDetail['post_media'] = !empty($values->post->post_image) ? asset('storage/post_image/' . $values->post->post_image[0]['post_image']) : "";
                            $postDetail['total_likes'] = !empty($values->post->event_post_reaction_count) ? $values->post->event_post_reaction_count : "";
                            $postDetail['total_comments'] = !empty($values->post->event_post_comment_count) ? $values->post->event_post_comment_count : "";
                            $notificationDetail['post_detail'][] = $postDetail;
                        }


                        $notificationDetail['notification_message'] = $values->notification_message;

                        $notificationDetail['read'] = $values->read;
                        $notificationDetail['post_time'] = setpostTime($values->created_at);

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
                        $notificationDetail['post_time'] = setpostTime($values->created_at);

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
                        $notificationDetail['post_time'] = setpostTime($values->created_at);
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
}
