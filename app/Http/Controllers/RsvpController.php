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
    User
};

use App\Models\contact_sync;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as Exception;

class RsvpController extends Controller
{
    /**
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



    public function index($userId, $eventId)
    {
        $title = 'RSVP';
        $page = 'front.rsvp';
        $js = ['rsvp'];


        $event_id =  decrypt($eventId);
        $user_id = decrypt($userId);
        // dd($user_id, $event_id);
      
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
                        if ($eventDetail->start_date!=$eventDetail->end_date) {
                            $eventData[] = "Multiple Day Event";
                        }
                        if (empty($eventData)) {
                            $eventData[] = date('F d, Y', strtotime($eventDetail->start_date));
                            $numberOfGuest = EventInvitedUser::where('event_id', $eventDetail->id)->count();
                            $eventData[] = "Number of guests : " . $numberOfGuest;
                        }
                        if($coHosts!=NULL){
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

                    $sync_contact_user_id="";
                    $user_email=User::where('id',$user_id)->first();
                    if($user_email==""){
                        $user_email=contact_sync::where('id',$user_id)->first();
                        $email=$user_email->email;
                        $sync_contact_user_id=$user_id;
                        $user_id = User::where('email', $email)->first()->id;
                    }else{
                        $email=$user_email->email;
                    }
                   
                    
                    return view('layout', compact(
                        'title',
                        'page',
                        'js',
                        'eventInfo',
                        'event_id',
                        'user_id',
                        'sync_contact_user_id',
                        'email',
                        'getInvitedusers'
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

        // dd($request);
        $userId = decrypt($request->user_id);
        $eventId = decrypt($request->event_id);

        try {
        $checkEvent = Event::where(['id' => $eventId])->first();
        if ($checkEvent->end_date < date('Y-m-d')) {
            return redirect('rsvp/' . $request->user_id . '/' . $request->event_id)->with('error', "Event is past , you can't attempt RSVP");
        }
        DB::beginTransaction();
        $rsvpSent = EventInvitedUser::whereHas('user', function ($query) {
            $query->where('app_user', '1');
        })->where(['user_id' => $userId, 'event_id' => $eventId])->first();
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

            $rsvpSent->adults = (isset($request->adults) && $request->adults)?(int)$request->adults:0;

            $rsvpSent->kids = (isset($request->kids) && $request->kids)?(int)$request->kids:0;

            $rsvpSent->message_to_host = $request->message_to_host;
            $rsvpSent->rsvp_attempt = $rsvp_attempt;

            $rsvpSent->read = '1';
            $rsvpSent->rsvp_d = '1';

            $rsvpSent->event_view_date = date('Y-m-d');

            $rsvpSent->save();

            if ($rsvpSent->save()) {
                EventPost::where('event_id',$eventId)
                        ->where('user_id',$userId)
                        ->where('post_type','4')->delete();
                $postMessage = [];
                $postMessage = [
                    'status' => ($request->rsvp_status == '0') ? '2' : '1',
                    'adults' => (isset($request->adults) && $request->adults)?(int)$request->adults:0,
                    'kids' => (isset($request->kids) && $request->kids)?(int)$request->kids:0,
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

            $notificationParam = [

                'sender_id' => $userId,
                'event_id' => $eventId,
                'rsvp_status' => $request->rsvp_status,
                'kids' => $request->kids,
                'adults' => $request->adults,
                'rsvp_video' => "",
                'rsvp_message' => $request->message_to_host,
                'post_id' => "",
                'rsvp_attempt' => $rsvp_attempt
            ];

            DB::commit();

            sendNotification('sent_rsvp', $notificationParam);

            return  redirect()->route('front.home')->with('success', 'Rsvp sent Successfully');
        }
        return redirect('rsvp/' . $request->user_id . '/' . $request->event_id)->with('error', 'Rsvp not sent');
        } catch (QueryException $e) {

            return redirect('rsvp/' . $request->user_id . '/' . $request->event_id)->with('error', 'DB error');
            DB::rollBack();
        } catch (\Exception $e) {

            return redirect('rsvp/' . $request->user_id . '/' . $request->event_id)->with('error', 'Something went wrong');
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
       $user_id=decrypt($request->input('user_id'));
       $event_id=decrypt($request->input('event_id'));
       
    $rsvp = EventInvitedUser::whereHas('user', function ($query) {
        $query->where('app_user', '1');
    })->where(['user_id' => $user_id, 'event_id' => $event_id])->first();

    $rsvpStatus=$rsvp->rsvp_status;

    return response()->json(['status' => 1, 'rsvp_status' => $rsvpStatus]);

    }
}
