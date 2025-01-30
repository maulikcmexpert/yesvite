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
        $js = ['event_guest','post_like_comment'];
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
                    $guestData = EventInvitedUser::with('user') // Eager load the related 'user' model
                        ->where('event_id', $eventDetail->id)
                        ->get();



                    $eventData[] = "Number of guests : " . $numberOfGuest;
                    $eventData['guests'] = $guestData;
                }
                $eventDetails['event_detail'] = $eventData;

            }
            //  dd($eventDetails['event_detail']);

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

            $eventAboutHost['rsvp_rate_percent'] = ($totalEnvitedUser != 0)
            ? number_format($eventattending / $totalEnvitedUser * 100, 2) . "%"
            : "0.00%";

            $eventAboutHost['today_upstick'] = ($totalEnvitedUser != 0) ? $todayrsvprate / $totalEnvitedUser * 100 . "%" : 0 . "%";

            $eventInfo['host_view'] = $eventAboutHost;
            $login_user_id  = $user->id;
            $current_page = "guest";

            return view('layout', compact('page', 'title', 'event', 'js', 'eventDetails', 'eventInfo', 'current_page', 'login_user_id')); // return compact('eventInfo');

        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['status' => 0, 'message' => "error"]);
        }
    }

    function fetch_guest($id)
    {

        $guest = EventInvitedUser::with('user')->findOrFail($id); // Eager load the related 'user' model

        return response()->json([
            'id' => $guest->id,
            'user_id' => $guest->user_id,
            'event_id'=> $guest->event_id,
            'firstname' => $guest->user->firstname,
            'lastname' => $guest->user->lastname,
            'email' => $guest->user->email,
            'profile' => $guest->user->profile ? asset('storage/profile/' . $guest->user->profile) : asset('images/default-profile.png'),
            'adults' => $guest->adults,
            'kids' => $guest->kids,
            'rsvp_status' => $guest->rsvp_status,
        ]);
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
}
