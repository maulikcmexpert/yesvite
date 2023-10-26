<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Event;
use App\Models\EventInvitedUser;
use App\Models\EventInvitedGuestUser;
use App\Models\EventSetting;
use App\Models\EventGreeting;
use App\Models\EventCoHost;
use App\Models\EventGuestCoHost;
use App\Models\EventGiftRegistry;
use App\Models\EventImage;
use App\Models\EventUserRsvp;
use App\Models\EventSchedule;
use App\Models\EventPotluckCategory;
use App\Models\EventPotluckCategoryItem;
use App\Models\Notification;
use Validator;
use Laravel\Passport\Token;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApiController extends Controller
{

    public function home()
    {
        $user  = Auth::guard('api')->user();


        $userInvitedEventList = Event::with(['event_image', 'user'])->where('start_date', '>', date('Y-m-d'))
            ->where('user_id', $user->id)->get();

        if (count($userInvitedEventList) != 0) {

            $eventList = [];
            foreach ($userInvitedEventList as $value) {

                $eventDetail['id'] = $value->id;
                $eventDetail['event_name'] = $value->event_name;
                $eventDetail['host_profile'] = empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile);
                $eventDetail['host_name'] = $value->user->firstname . ' ' . $value->user->lastname;
                $eventDetail['event_images'] = [];

                foreach ($value->event_image as $values) {

                    $eventDetail['event_images'][] = asset('storage/event_images/' . $values->image);
                }

                $eventDetail['event_date'] = $value->start_date;
                $eventDetail['start_time'] = $value->rsvp_start_time;
                $eventDetail['rsvp_start_timezone'] = $value->rsvp_start_timezone;

                $total_accept_event_user = EventUserRsvp::where(['event_id' => $value->id, 'rsvp_status' => '1'])->count();
                $eventDetail['total_accept_event_user'] = $total_accept_event_user;

                $total_invited_user = EventInvitedUser::where(['event_id' => $value->id])->count();
                $eventDetail['total_invited_user'] = $total_invited_user;

                $total_refuse_event_user = EventUserRsvp::where(['event_id' => $value->id, 'rsvp_status' => '0'])->count();
                $eventDetail['total_refuse_event_user'] = $total_refuse_event_user;

                $total_notification = Notification::where(['event_id' => $value->id, 'user_id' => $user->id, 'notification_type' => '0'])->count();
                $eventDetail['total_notification'] = $total_notification;

                $eventList[] = $eventDetail;
            }
            return response()->json(['data' => $eventList, 'message' => "upcoming events"], 200);
        } else {
            return response()->json(['message' => "upcoming Event is not available"], 200);
        }
    }

    public function updateProfile(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $input = $request->all();
        $validator = Validator::make($input, [
            'firstname' => 'required',
            'lastname' => 'required',
            'gender' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone_number' => ['required', 'numeric'],

        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all(),
                'status' => 401,
            ]);
        }

        if (!empty($request->profile)) {

            if (Storage::disk('public')->exists('profile/' . $user->profile)) {
                Storage::disk('public')->delete('profile/' . $user->profile);
            }

            $image = $request->profile;
            $imageName = time() . '_' . $image->getClientOriginalName();
            Storage::disk('public')->putFileAs('profile', $image, $imageName);
            $user->profile = $imageName;
        }

        if (!empty($request->bg_profile)) {

            if (Storage::disk('public')->exists('bg_profile/' . $user->bg_profile)) {
                Storage::disk('public')->delete('bg_profile/' . $user->bg_profile);
            }
            $bgimage = $request->bg_profile;
            $bgimageName = time() . '_' . $bgimage->getClientOriginalName();
            Storage::disk('public')->putFileAs('bg_profile', $bgimage, $bgimageName);
            $user->bg_profile = $bgimageName;
        }

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;


        $user->gender = ($request->gender != "") ? $request->gender : $user->gender;
        $user->birth_date = ($request->birth_date != "") ? $request->birth_date : $user->birth_date;
        $user->email = ($request->email != "") ? $request->email : $user->email;
        $user->country_code = ($request->country_code != "") ? $request->country_code : $user->country_code;
        $user->phone_number = ($request->phone_number != "") ? $request->phone_number : $user->phone_number;
        $user->about_me = ($request->about_me != "") ? $request->about_me : $user->about_me;
        $user->zip_code = ($request->zip_code != "") ? $request->zip_code : $user->zip_code;
        if ($user->visible == '1') {
            $validator = Validator::make($input, [
                'company_name' => 'required',
            ]);
            $user->company_name = $request->company_name;
            $user->address = ($request->address != "") ? $request->address : $user->address;
            $user->city = ($request->city != "") ? $request->city : $user->city;
            $user->state = ($request->state != "") ? $request->state : $user->state;
        }
        $user->save();

        $details = User::where('id', $user->id)->get();

        $profileData = [];
        if (!empty($details)) {
            $userDetail['id'] = empty($details[0]->id) ? "" : $details[0]->id;
            $userDetail['profile'] =  empty($details[0]->profile) ? "" : asset('storage/profile/' . $details[0]->profile);
            $userDetail['bg_profile'] =  empty($details[0]->bg_profile) ? "" : asset('storage/bg_profile/' . $details[0]->bg_profile);
            $userDetail['firstname'] = empty($details[0]->firstname) ? "" : $details[0]->firstname;
            $userDetail['lastname'] = empty($details[0]->lastname) ? "" : $details[0]->lastname;
            $userDetail['gender'] = empty($details[0]->gender) ? "" : $details[0]->gender;
            $userDetail['birth_date'] = empty($details[0]->birth_date) ? "" : $details[0]->birth_date;
            $userDetail['email'] = empty($details[0]->email) ? "" : $details[0]->email;
            $userDetail['country_code'] = empty($details[0]->country_code) ? "" : $details[0]->country_code;
            $userDetail['phone_number'] = empty($details[0]->phone_number) ? "" : $details[0]->phone_number;
            $userDetail['about_me'] = empty($details[0]->about_me) ? "" : $details[0]->about_me;
            $userDetail['visible'] =  $details[0]->visible;
            $userDetail['account_type'] =  $details[0]->account_type;
            if ($details[0]->account_type == '1') {
                $userDetail['company_name'] = empty($details[0]->company_name) ? "" : $details[0]->company_name;
                $userDetail['address'] = empty($details[0]->address) ? "" : $details[0]->address;
                $userDetail['city'] = empty($details[0]->city) ? "" : $details[0]->city;
                $userDetail['state'] = empty($details[0]->state) ? "" : $details[0]->state;
            }
            $userDetail['zip_code'] = empty($details[0]->zip_code) ? "" : $details[0]->zip_code;
            $profileData[] = $userDetail;

            return response()->json(['data' => $profileData, 'message' => "Profile updated successfully"], 200);
        } else {
            return response()->json(['error' => "User profile not found", 'message' => "The requested user profile data does not exist."], 404);
        }
    }

    public function myProfile()
    {
        $user  = Auth::guard('api')->user();

        $totalEvent =  Event::where('user_id', $user->id)->count();

        $totalEventPhotos = 0;
        $comments = 0;
        $profileData = [];

        if (!empty($user)) {
            $userDetail['id'] =  empty($user->id) ? "" : $user->id;
            $userDetail['profile'] =  empty($user->profile) ? "" : asset('storage/profile/' . $user->profile);
            $userDetail['bg_profile'] =  empty($user->bg_profile) ? "" : asset('storage/bg_profile/' . $user->bg_profile);
            $userDetail['firstname'] = empty($user->firstname) ? "" : $user->firstname;
            $userDetail['firstname'] = empty($user->firstname) ? "" : $user->firstname;
            $userDetail['lastname'] = empty($user->lastname) ? "" : $user->lastname;
            $userDetail['email'] = empty($user->email) ? "" : $user->email;
            $userDetail['about_me'] = empty($user->about_me) ? "" : $user->about_me;
            $userDetail['created_at'] = empty($user->created_at) ? "" :   date('l Y', strtotime($user->created_at));
            $userDetail['total_events'] = $totalEvent;
            $userDetail['total_photos'] = $totalEventPhotos;
            $userDetail['comments'] = $comments;
            $userDetail['gender'] = empty($user->gender) ? "" : $user->gender;
            $userDetail['country_code'] = empty($user->country_code) ? "" : $user->country_code;
            $userDetail['phone_number'] = empty($user->phone_number) ? "" : $user->phone_number;
            $userDetail['visible'] =  $user->visible;
            $userDetail['account_type'] =  $user->account_type;

            if ($user->account_type == '1') {
                $userDetail['company_name'] = empty($user->company_name) ? "" : $user->company_name;
                $userDetail['address'] = empty($user->address) ? "" : $user->address;
                $userDetail['city'] = empty($user->city) ? "" : $user->city;
                $userDetail['state'] = empty($user->state) ? "" : $user->state;
            }
            $userDetail['zip_code'] = empty($user->zip_code) ? "" : $user->zip_code;
            $profileData[] = $userDetail;

            return response()->json(['data' => $profileData, 'message' => "My Profile"], 200);
        } else {
            return response()->json(['error' => "User profile not found", 'message' => "The requested user profile data does not exist."], 404);
        }
    }

    public function privacySetting(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();


        $input = json_decode($rawData, true);

        $validator = Validator::make($input, [
            'privacy_visible' => [
                'required',
                'in:0,1,2'
            ],

        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all(),
                'status' => 401,
            ]);
        }

        $user->visible = $input["privacy_visible"];
        $user->save();
        return response()->json(['message' => "visible changed successfully"], 200);
    }


    public function generalSetting(Request $request)
    {
        $user  = Auth::guard('api')->user();
        $rawData = $request->getContent();
        $input = json_decode($rawData, true);
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
                'errors' => $validator->errors()->all(),
                'status' => 401,
            ]);
        }


        try {
            DB::beginTransaction();

            $user->photo_via_wifi = $input["photo_via_wifi"];
            $user->show_photo_friend = $input["show_photo_friend"];
            $user->save();
            DB::commit();
            return response()->json(['message' => "general changed successfully"], 200);
        } catch (QueryException $e) {

            DB::rollBack();
            return response()->json(['error' => "error"], 500);
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

            return response()->json(['message' => "Account deleted sucessfully"], 200);
        } catch (QueryException $e) {

            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    //  event create // 

    public function createEvent(Request $request)
    {
        $rawData = $request->getContent();

        $user  = Auth::guard('api')->user();

        $eventData = json_decode($rawData);
        // dd($eventData);

        try {
            DB::beginTransaction();

            if (!empty($eventData->rsvp_by_date)) {
                $rsvp_end_time = strtotime($eventData->rsvp_by_date . '' . $eventData->rsvp_start_time);
            } else {
                $rsvp_end_time = strtotime(date('Y-m-d') . '' . $eventData->rsvp_start_time);
            }


            $eventCreation =  Event::create([
                'event_type_id' => $eventData->event_type_id,
                'event_name' => $eventData->event_name,
                'user_id' => $user->id,
                'hosted_by' => $eventData->hosted_by,
                'start_date' => $eventData->start_date,
                'end_date' => $eventData->end_date,
                'rsvp_by_date' => (!empty($eventData->rsvp_by_date)) ? $eventData->rsvp_by_date : NULL,
                'rsvp_start_time' => (!empty($eventData->rsvp_by_date)) ? strtotime($eventData->rsvp_by_date . '' . $eventData->rsvp_start_time) : strtotime(date('Y-m-d') . '' . $eventData->rsvp_start_time),
                'rsvp_start_timezone' => $eventData->rsvp_start_timezone,
                'rsvp_end_time_set' => $eventData->rsvp_end_time_set,
                'rsvp_end_time' => ($eventData->rsvp_end_time_set == '1') ? $rsvp_end_time : "",
                'rsvp_end_timezone' => ($eventData->rsvp_end_time_set == '1') ? $eventData->rsvp_end_timezone : "",
                'event_location_name' => $eventData->event_location_name,
                'address_1' => $eventData->address_1,
                'address_2' => $eventData->address_2,
                'state' => $eventData->state,
                'zip_code' => $eventData->zip_code,
                'city' => $eventData->city,
                'message_to_guests' => $eventData->message_to_guests
            ]);

            if ($eventCreation) {
                $eventId = $eventCreation->id;

                if (!empty($eventData->invited_user_id)) {
                    $invitedUsers = $eventData->invited_user_id;

                    foreach ($invitedUsers as $value) {

                        EventInvitedUser::create([

                            'event_id' => $eventId,
                            'user_id' => $value
                        ]);
                    }
                }

                if (!empty($eventData->invited_guests)) {
                    $invitedGuestUsers = $eventData->invited_guests;

                    foreach ($invitedGuestUsers as $value) {

                        EventInvitedGuestUser::create([
                            'event_id' => $eventId,
                            'first_name' => $value->first_name,
                            'last_name' => $value->last_name,
                            'email' => $value->email,
                            'country_code' => $value->country_code,
                            'phone_number' => $value->phone_number
                        ]);
                    }
                }

                if ($eventData->event_setting) {
                    EventSetting::create([
                        'event_id' => $eventId,
                        'allow_for_1_more' => $eventData->event_setting->allow_for_1_more,
                        'allow_limit' => $eventData->event_setting->allow_limit,
                        'adult_only_party' => $eventData->event_setting->adult_only_party,
                        'rsvp_by_date_status' => $eventData->event_setting->rsvp_by_date_status,
                        'thank_you_cards' => $eventData->event_setting->thank_you_cards,
                        'add_co_host' => $eventData->event_setting->add_co_host,
                        'gift_registry' => $eventData->event_setting->gift_registry,
                        'events_schedule' => $eventData->event_setting->events_schedule,
                        'event_wall' => $eventData->event_setting->event_wall,
                        'guest_list_visible_to_guests' => $eventData->event_setting->guest_list_visible_to_guests,
                        'podluck' => $eventData->event_setting->podluck,
                        'rsvp_updates' => $eventData->event_setting->rsvp_updates,
                        'event_updates' => $eventData->event_setting->event_updates,
                        'send_event_dater_reminders' => $eventData->event_setting->send_event_dater_reminders,
                        'rsvp_reminders_once_a_week' => $eventData->event_setting->rsvp_reminders_once_a_week,
                    ]);
                }

                if ($eventData->event_setting->thank_you_cards == '1') {

                    $greetingCardList = $eventData->greeting_card_list;
                    if (!empty($greetingCardList)) {

                        foreach ($greetingCardList as $value) {

                            EventGreeting::create([
                                "event_id" => $eventId,
                                "template_name" => $value->template_name,
                                "message" => $value->message,
                                "message_sent_time" => $value->message_sent_time,
                                "custom_hours_after_event" => $value->custom_hours_after_event,
                            ]);
                        }
                    }
                }

                if ($eventData->event_setting->add_co_host == '1') {

                    $coHostList = $eventData->co_host_list;
                    if (!empty($coHostList)) {

                        foreach ($coHostList as $value) {

                            EventCoHost::create([
                                "event_id" => $eventId,
                                "user_id" => $value,
                            ]);
                        }
                    }

                    $guestcoHostList = $eventData->guest_co_host_list;
                    if (!empty($guestcoHostList)) {

                        foreach ($guestcoHostList as $value) {

                            EventGuestCoHost::create([
                                'event_id' => $eventId,
                                'first_name' => $value->first_name,
                                'last_name' => $value->last_name,
                                'email' => $value->email,
                                'country_code' => $value->country_code,
                                'phone_number' => $value->phone_number
                            ]);
                        }
                    }
                }

                if ($eventData->event_setting->gift_registry == '1') {
                    $giftRegistryList = $eventData->gift_registry_list;
                    if (!empty($giftRegistryList)) {

                        foreach ($giftRegistryList as $value) {

                            EventGiftRegistry::create([
                                'event_id' => $eventId,
                                'registry_recipient_name' => $value->registry_recipient_name,
                                'registry_link' => $value->registry_link,
                            ]);
                        }
                    }
                }

                if ($eventData->event_setting->events_schedule == '1') {
                    $eventsScheduleList = $eventData->events_schedule_list;
                    if (!empty($eventsScheduleList)) {

                        foreach ($eventsScheduleList as $value) {

                            EventSchedule::create([
                                'event_id' => $eventId,
                                'event_name' => $value->event_name,
                                'event_schedule' => $value->event_schedule,
                            ]);
                        }
                    }
                }


                if ($eventData->event_setting->podluck == '1') {
                    $podluckCategoryList = $eventData->podluck_category_list;
                    if (!empty($podluckCategoryList)) {

                        foreach ($podluckCategoryList as $value) {

                            $eventPodluck = EventPotluckCategory::create([
                                'event_id' => $eventId,
                                'category' => $value->category,
                                'quantity' => $value->quantity,
                            ]);

                            if (!empty($value->items)) {
                                $items = $value->items;

                                foreach ($items as $value) {

                                    EventPotluckCategoryItem::create([
                                        'event_id' => $eventId,
                                        'event_potluck_category_id' => $eventPodluck->id,
                                        'description' => $value->description,
                                        'quantity' => $value->quantity,
                                    ]);
                                }
                            }
                        }
                    }
                }
            }


            DB::commit();

            return response()->json(['message' => "Event Created Successfully"], 201);
        } catch (QueryException $e) {

            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function storeEventImage(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'event_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all(),
                'status' => 401,
            ]);
        }

        try {
            DB::beginTransaction();

            if (!empty($request->image)) {
                $images = $request->image;
                foreach ($images as $value) {

                    $image = $value;
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    Storage::disk('public')->putFileAs('event_images', $image, $imageName);

                    EventImage::create([
                        'event_id' => $request->event_id,
                        'image' => $imageName
                    ]);
                }
                DB::commit();

                return response()->json(['message' => "Event images stored successfully"], 201);
            }
        } catch (QueryException $e) {

            DB::rollBack();
            return response()->json(['error' => "something went wrong"], 500);
        }
    }

    public function EventList(Request $request)
    {

        $user  = Auth::guard('api')->user();

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        $event_date = "";
        if (isset($input['event_date']) && !empty($input['event_date'])) {
            $event_date = $input['event_date'];
        }
        $usercreatedAllEventList = Event::with(['event_image', 'user'])
            ->where('user_id', $user->id)
            ->when($event_date, function ($query, $event_date) {
                return $query->where('start_date', $event_date);
            })->get();


        $createdEventList = [];
        if (count($usercreatedAllEventList) != 0) {


            foreach ($usercreatedAllEventList as $value) {

                $eventDetail['id'] = $value->id;
                $eventDetail['event_name'] = $value->event_name;
                $eventDetail['host_profile'] = empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile);
                $eventDetail['host_name'] = $value->user->firstname . ' ' . $value->user->lastname;
                $eventDetail['event_images'] = [];

                foreach ($value->event_image as $values) {

                    $eventDetail['event_images'][] = asset('storage/event_images/' . $values->image);
                }
                $eventDetail['event_date'] = $value->start_date;
                $eventDetail['start_time'] = $value->rsvp_start_time;
                $eventDetail['rsvp_start_timezone'] = $value->rsvp_start_timezone;

                $total_accept_event_user = EventUserRsvp::where(['event_id' => $value->id, 'rsvp_status' => '1'])->count();
                $eventDetail['total_accept_event_user'] = $total_accept_event_user;

                $total_invited_user = EventInvitedUser::where(['event_id' => $value->id])->count();
                $eventDetail['total_invited_user'] = $total_invited_user;

                $total_refuse_event_user = EventUserRsvp::where(['event_id' => $value->id, 'rsvp_status' => '0'])->count();
                $eventDetail['total_refuse_event_user'] = $total_refuse_event_user;

                $total_notification = Notification::where(['event_id' => $value->id, 'user_id' => $user->id, 'notification_type' => '0'])->count();
                $eventDetail['total_notification'] = $total_notification;

                $createdEventList[] = $eventDetail;
            }
        }


        $userInvitedEventList = EventInvitedUser::with(['user', 'event' => function ($query) use ($event_date) {
            $query->when($event_date, function ($query, $event_date) {
                return $query->where('start_date', $event_date);
            })->with('event_image');
        }])->where('user_id', $user->id)->get();
        $invitedeventList = [];
        if (count($userInvitedEventList) != 0) {

            foreach ($userInvitedEventList as $value) {

                $eventDetail['id'] = $value->event->id;
                $eventDetail['event_name'] = $value->event->event_name;
                $eventDetail['host_profile'] = empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile);
                $eventDetail['host_name'] = $value->user->firstname . ' ' . $value->user->lastname;
                $eventDetail['event_images'] = [];

                foreach ($value->event->event_image as $values) {
                    $eventDetail['event_images'][] = asset('storage/event_images/' . $values->image);
                }

                $eventDetail['event_date'] = $value->event->start_date;
                $eventDetail['start_time'] = $value->event->rsvp_start_time;
                $eventDetail['rsvp_start_timezone'] = $value->event->rsvp_start_timezone;

                $rsvp_status = "";

                if ($value->event->rsvp_end_time != "" || $value->event->rsvp_end_time != NULL) {



                    $checkUserrsvp = EventUserRsvp::where(['user_id' => $user->id, 'event_id' => $value->event->id])->first();
                    if ($checkUserrsvp == 0) {
                        if ($value->event->rsvp_start_time <= strtotime(env('DATE')) && strtotime(env('DATE')) <= $value->event->rsvp_end_time) {
                            $rsvp_status = '0'; // rsvp button//
                        }
                    } else if ($checkUserrsvp[0]->rsvp_status == '1') {
                        $rsvp_status = '1'; // rsvp you'r going
                    } else if ($checkUserrsvp[0]->rsvp_status == '0') {
                        $rsvp_status = '2'; // rsvp you'r not going
                    }
                } else {

                    $startEventTime = $value->event->start_date;
                    $oneDayBefore = date('Y-m-d', strtotime('-1 day', strtotime($startEventTime)));
                    $svrp_end_time = strtotime($oneDayBefore . ' 12:00:00');


                    $checkUserrsvp = EventUserRsvp::where(['user_id' => $user->id, 'event_id' => $value->event->id])->get();

                    if (count($checkUserrsvp) == 0) {
                        if ($value->event->rsvp_start_time <= strtotime(env('DATE')) && strtotime(env('DATE')) <= $svrp_end_time) {
                            $rsvp_status = '0'; // rsvp button//
                        }
                    } else if ($checkUserrsvp[0]->rsvp_status == '1') {
                        $rsvp_status = '1'; // rsvp you'r going
                    } else if ($checkUserrsvp[0]->rsvp_status == '0') {
                        $rsvp_status = '2'; // rsvp you'r not going
                    }
                }



                $eventDetail['rsvp_status'] = $rsvp_status;

                $total_notification = Notification::where(['event_id' => $value->event->id, 'user_id' => $user->id, 'notification_type' => '0'])->count();
                $eventDetail['total_notification'] = $total_notification;

                $invitedeventList[] = $eventDetail;
            }
        }
        $eventList['all'] =  $createdEventList;
        $eventList['invited_to'] =  $invitedeventList;
        return response()->json(['data' => $eventList, 'message' => "All events"], 201);
    }

    public function sentRsvp(Request $request)
    {

        $user  = Auth::guard('api')->user();

        $input = $request->all();
        $validator = Validator::make($input, [
            'event_id' => 'required',
            'rsvp_status' => 'required',
            'adults' => 'required',
            'kids' => 'required',
            'message_to_host' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all(),
                'status' => 401,
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

            EventUserRsvp::create([
                'event_id' => $request->event_id,
                'user_id' => $user->id,
                'rsvp_status' => $request->rsvp_status,
                'adults' => $request->adults,
                'kids' => $request->kids,
                'message_to_host' => $request->message_to_host,
                'message_by_video' => $video
            ]);
            DB::commit();
            return response()->json(['message' => "Rsvp sent Successfully"], 201);
        } catch (QueryException $e) {

            DB::rollBack();
            return response()->json(['error' => "something went wrong"], 500);
        }
    }

    public function eventAbout(Request $request)
    {
        $user  = Auth::guard('api')->user();
        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        $validator = Validator::make($input, [
            'event_id' => ['required', 'exists:events,id']
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all(),
                'status' => 401,
            ]);
        }

        $eventDetail = Event::with(['user', 'event_image', 'event_schedule', 'event_co_host' => function ($query) {
            $query->with('user');
        }])->where('id', $input['event_id'])->first();

        // dd(($eventDetail->event_schedule->toArray()));

        $guestView = [];

        $eventInfo['guest_view'] =

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
            $coHostDetail['profile'] = (empty($hostValues->user->profile) || $hostValues->user->profile == NULL) ? "" : asset('storage/profile/' . $hostValues->user->profile);
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

        $totalEnvitedUser = EventInvitedUser::where(['event_id' => $eventDetail->id])->count();
        $eventattending = EventUserRsvp::select('SUM(adults) as total_adults', 'SUM(kids) as total_kids')->where(['rsvp_status' => '1', 'event_id' => $eventDetail->id])->get();
        $eventNotComing = EventUserRsvp::where(['rsvp_status' => '0', 'event_id' => $eventDetail->id])->count();

        $totalRsvp = EventUserRsvp::where(['event_id' => $eventDetail->id])->count();
        $pendingUser = $totalEnvitedUser - $totalRsvp;

        dd($eventattending);
        // $eventHostDetail['attending'] = count($eventattending);
        // $eventHostDetail['adults'] = $eventattending;
        // $eventHostDetail['kids'] = $eventattending;

        $eventInfo['host_view'] = "";
        return response()->json(['data' => $eventInfo, 'message' => "About event"], 200);
    }



    public function logout()
    {
        if (Auth::guard('api')->check()) {
            $patient = Auth::guard('api')->user();
            Token::where('user_id', $patient->id)->delete();
            return $this->sendResponse('logout', 'logout succesfully');
        }
    }
}
