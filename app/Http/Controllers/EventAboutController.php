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
    EventPostImage
};

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as Exception;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class EventAboutController extends Controller
{
    public function index(String $id)
    {

        $title = 'event about';
        $page = 'front.event_wall.event_about';
        $user  = Auth::guard('web')->user();
        $js = ['event_about_rsvp'];
        $event = decrypt($id);
        if ($event == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        try {
            $eventDetail = Event::with(['user', 'event_image', 'event_schedule',  'event_settings' => function ($query) {
                $query->select('event_id', 'podluck', 'allow_limit', 'adult_only_party');
            }, 'event_invited_user' => function ($query) {
                $query->where('is_co_host', '0')->with('user');
            }])->where('id', $event)->first();
            // dd($eventDetail);
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
            $eventDetails['end_date'] = $eventDetail->end_date;
            $eventDetails['end_time'] = $eventDetail->rsvp_end_time;

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
                if (empty($eventData)) {
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
            $rsvpSent = EventInvitedUser::whereHas('user', function ($query) {
                $query->where('app_user', '1');
            })->where(['user_id' => $user->id, 'event_id' => $event])->first();
            // $rsvpSentAttempt = $rsvpSent->rsvp_status;
            // dd($rsvpSent);
            // if ($rsvpSent != null) {
            //     $rsvp_attempt = "";
            //     if ($rsvpSentAttempt == NULL) {
            //         $rsvp_attempt =  'first';
            //     } else if ($rsvpSentAttempt == '0' && $request->rsvp_status == '1') {
            //         $rsvp_attempt =  'no_to_yes';
            //     } else if ($rsvpSentAttempt == '1' && $request->rsvp_status == '0') {
            //         $rsvp_attempt =  'yes_to_no';
            //     }
            // }
            $login_user_id  = $user->id;
            $current_page = "about";

            return view('layout', compact('page', 'title', 'js', 'login_user_id','eventInfo', 'event', 'rsvpSent', 'eventDetails', 'current_page', 'eventInfo'));
            // return compact('event','eventDetails') ;// return compact('eventInfo');
            // return response()->json(['status' => 1, 'data' => $eventInfo, 'message' => "About event"]);
        } catch (QueryException $e) {

            DB::rollBack();

            // return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }

    public function sentRsvpData(Request $request)

    {
        // dd($request);
        $user  = Auth::guard('web')->user()->id;
        // dd($user);
        $input = $request->all();

        // $request->validate([
        //     'event_id' => 'required',
        //     'user_id' => 'required',
        //     'rsvp_status' => 'required|in:0,1',
        //     'adults' => 'nullable|integer|min:0',
        //     'kids' => 'nullable|integer|min:0',
        //     'message_to_host' => 'nullable|string|max:255',
        //     'message_by_video' => 'nullable|file|mimes:mp4,mov,avi|max:20480',
        // ]);





        $checkEvent = Event::where(['id' => $request->event_id])->first();

        if ($checkEvent->end_date < date('Y-m-d')) {
            // return response()->json(['status' => 0, 'message' => "Event is past , you can't attempt RSVP"]);
            return redirect()->back()->with('error', 'Event is past , you cant attempt RSVP!');
        }


        $video = "";
        // dd(1);

        // if (!empty($request->message_by_video)) {



        //     $video = $request->message_by_video;

        //     $videoName = time() . '_' . $video->getClientOriginalName();
        //     $video->move(public_path('storage/rsvp_video'), $videoName);


        //     $video = $videoName;
        // }



        $rsvpSent = EventInvitedUser::whereHas('user', function ($query) {
            $query->where('app_user', '1');
        })->where(['user_id' => $user, 'event_id' => $request->event_id])->first();
        $rsvpSentAttempt = $rsvpSent->rsvp_status;
        // dd($rsvpSent);
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

            $rsvpSent->user_id = $user;

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
            //if rsvp_status is 0 then No, and rsvp_status is 1 then Yes
            if ($rsvpSent->save()) {
                $postMessage = [];
                $postMessage = [
                    'status' => ($request->rsvp_status == '0') ? '2' : '1',
                    'adults' => $request->adults,
                    'kids' => $request->kids
                ];
                $creatEventPost = new EventPost;
                $creatEventPost->event_id = $request->event_id;
                $creatEventPost->user_id = $request->user_id;
                $creatEventPost->post_message = json_encode($postMessage);
                $creatEventPost->post_privacy = "1";
                $creatEventPost->post_type = "4";
                $creatEventPost->commenting_on_off = "0";
                $creatEventPost->is_in_photo_moudle = "0";
                $creatEventPost->save();
                // dd($creatEventPost);
            }
            // if ($user->id == $request->user_id) {
            //     $notificationParam = [

            //         'sender_id' => $user->id,
            //         'event_id' => $request->event_id,
            //         'rsvp_status' => $request->rsvp_status,
            //         'kids' => $request->kids,
            //         'adults' => $request->adults,
            //         'rsvp_video' => $video,
            //         'rsvp_message' => $request->message_to_host,
            //         'post_id' => "",
            //         'rsvp_attempt' => $rsvp_attempt
            //     ];
            //     // sendNotification('sent_rsvp', $notificationParam);
            // }




        }
        return redirect()->back()->with('success', 'rsvp updated successfully!');
    }
}
