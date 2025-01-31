<?php

namespace App\Http\Controllers;
use App\Models\{
    Event,
    EventPost,
    EventPostComment,
    EventInvitedUser,
    Notification,
    EventImage,
    User,
    EventPostCommentReaction,
    UserPotluckItem
};

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as Exception;
use Throwable;

use Illuminate\Http\Request;

class EventListController extends Controller
{
    public $per_page;
    public function __construct(){
        $this->per_page = 10;
    }
    public function index()
    {
                $user  = Auth::guard('web')->user();
                $eventList = [];
                // $pages = ($page != "") ? $page : 1;

                //upcoming_event
                $usercreatedList = Event::with(['user', 'event_settings', 'event_schedule'])->where('start_date', '>=', date('Y-m-d'))
                ->where('user_id', $user->id)
                ->where('is_draft_save', '0');
                // ->orderBy('start_date', 'ASC')  
                // ->get();
            $invitedEvents = EventInvitedUser::whereHas('user', function ($query) {
            $query->where('app_user', '1');
            })->where('user_id', $user->id)->get()->pluck('event_id');
            $invitedEventsList = Event::with(['event_image', 'user', 'event_settings', 'event_schedule'])
                ->whereIn('id', $invitedEvents)->where('start_date', '>=', date('Y-m-d'))
                ->where('is_draft_save', '0');
                // ->orderBy('start_date', 'ASC')
                // ->get();
            $allEvents = $usercreatedList->union($invitedEventsList);

            $allEvent = $allEvents
                        ->orderBy('start_date', 'asc')
                        ->offset(0)
                        ->limit($this->per_page) 
                        ->get();

                // $totalCounts += count($allEvent);
                // // Calculate offset based on current page and perPage
                // $offset = ($pages - 1) * $this->perPage;

                // $offset=$page*$this->perPage

                // $paginatedEvents =  collect($allEvent)->sortBy('start_date')->forPage($page, $this->perPage);
                // $paginatedEvents =  collect($allEvent)->sortBy('start_date');
                $totalEvent =  Event::where('user_id', $user->id)->count();
                $totalInvited = EventInvitedUser::whereHas('event', function ($query) {
                    $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'));
                })
               ->where('user_id', $user->id)->count();
               $totalHosting = Event::where(['is_draft_save' => '0', 'user_id' => $user->id])->where('start_date', '>=', date('Y-m-d'))->count();

               $usercreatedAllPastEventCount = Event::where(['is_draft_save' => '0', 'user_id' => $user->id])->where('end_date', '<', date('Y-m-d'));
               $invitedPastEvents = EventInvitedUser::whereHas('user', function ($query) {
                   $query->where('app_user', '1');
               })
               ->whereHas('event', function ($query) {
                   $query->whereNull('deleted_at'); // Filter events where deleted is null
               })->where('user_id', $user->id)->get()->pluck('event_id');
               // dd($total_past_event->toSql());

               $total_past_event = Event::where('end_date', '<', date('Y-m-d'))->whereIn('id', $invitedPastEvents)->where('is_draft_save', '0');
               $allPastEventC = $usercreatedAllPastEventCount->union($total_past_event)->orderByDesc('id')->get();
               $totalPastEventCount = count($allPastEventC);


               $total_need_rsvp_event_count = EventInvitedUser::whereHas('event', function ($query) {
                $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'));
            })->where(['user_id' => $user->id, 'rsvp_status' => NULL])->count();


            $totalDraftEvent =  Event::where(column: ['user_id' => $user->id, 'is_draft_save' => '1'])->count();

                if (count($allEvent) != 0) {

                    foreach ($allEvent as $value) {
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
                        $eventDetail['self_id']=$user->id;
                        $eventDetail['host_profile'] = empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile);
                        $eventDetail['message_to_guests'] = $value->message_to_guests;
                        $eventDetail['event_wall'] = (isset($value->event_settings->event_wall)&&$value->event_settings->event_wall!="")?$value->event_settings->event_wall:"";
                        $eventDetail["guest_list_visible_to_guests"] = (isset($value->event_settings->guest_list_visible_to_guests)&&$value->event_settings->guest_list_visible_to_guests!="")?$value->event_settings->guest_list_visible_to_guests:"";
                        $eventDetail['event_potluck'] =(isset($value->event_settings->podluck)&& $value->event_settings->podluck!="")? $value->event_settings->podluck:"";
                        $eventDetail['guest_pending_count'] = getGuestRsvpPendingCount($value->id);
                        $eventDetail['adult_only_party'] =(isset($value->event_settings->adult_only_party)&&$value->event_settings->adult_only_party!="")?$value->event_settings->adult_only_party:"";
                        $eventDetail['host_name'] = $value->hosted_by;
                        $eventDetail['host_firstname'] = $value->user->firstname;
                        $eventDetail['host_lastname'] = $value->user->lastname;
                        $eventDetail['allow_limit'] =(isset($value->event_settings->allow_limit)&& $value->event_settings->allow_limit)? $value->event_settings->allow_limit:"";
                        $eventDetail['is_past'] = ($value->end_date < date('Y-m-d')) ? true : false;
                        $eventDetail['is_gone_time'] = $this->evenGoneTime($value->end_date);
                        $eventDetail['post_time'] =  $this->setupcomingpostTime($value->updated_at);
                        $eventDetail['kids'] = 0;
                        $eventDetail['adults'] = 0;

                        $checkRsvpDone = EventInvitedUser::where(['event_id' => $value->id, 'user_id' => $user->id])->first();
                        if ($checkRsvpDone != null) {
                            $eventDetail['kids'] = $checkRsvpDone->kids;
                            $eventDetail['adults'] = $checkRsvpDone->adults;
                        }
                        $images = EventImage::where('event_id', $value->id)->first();
                        $eventDetail['event_images'] = ($images != null) ? asset('storage/event_images/' . $images->image) : "";
                        $eventDetail['event_date'] = $value->start_date;
                        $eventDetail['event_date_only'] = Carbon::parse($value->start_date)->format('d');
                        $eventDetail['event_date_mon'] = Carbon::parse($value->start_date)->format('d M'); // "21 Nov"
                        $eventDetail['event_month'] = Carbon::parse($value->start_date)->format('M'); // "21 Nov"
                        $eventDetail['event_day'] = Carbon::parse($value->start_date)->format('l'); // "Monday"

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
                            'total_photos' => $totalEventPhotos,
                            'visible' =>  $value->user->visible,
                            'comments' => $comments,
                        ];
                        $eventDetail['event_plan_name'] = $value->subscription_plan_name;

                        $totalInvited = EventInvitedUser::whereHas('event', function ($query) {
                            $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'));
                        })
                       ->where('user_id', $user->id)->count();
                        $totalHosting = Event::where(['is_draft_save' => '0', 'user_id' => $user->id])->where('start_date', '>=', date('Y-m-d'))->count();

                        $usercreatedAllPastEventCount = Event::where(['is_draft_save' => '0', 'user_id' => $user->id])->where('end_date', '<', date('Y-m-d'));
                        $invitedPastEvents = EventInvitedUser::whereHas('user', function ($query) {
                            $query->where('app_user', '1');
                        })
                        ->whereHas('event', function ($query) {
                            $query->whereNull('deleted_at'); // Filter events where deleted is null
                        })->where('user_id', $user->id)->get()->pluck('event_id');
                        // dd($total_past_event->toSql());

                        $total_past_event = Event::where('end_date', '<', date('Y-m-d'))->whereIn('id', $invitedPastEvents)->where('is_draft_save', '0');
                        $allPastEventC = $usercreatedAllPastEventCount->union($total_past_event)->orderByDesc('id')->get();
                        $totalPastEventCount = count($allPastEventC);

                        $total_need_rsvp_event_count = EventInvitedUser::whereHas('event', function ($query) {
                            $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'));
                        })->where(['user_id' => $user->id, 'rsvp_status' => NULL])->count();
                        $eventList[] = $eventDetail;


                        $upcomingEventCount = upcomingEventsCount($user->id);
                        $totalDraftEvent =  Event::where(column: ['user_id' => $user->id, 'is_draft_save' => '1'])->count();


                        $filter = [
                            'invited_to' => $totalInvited,
                            'hosting' => $totalHosting,
                            'need_to_rsvp' => $total_need_rsvp_event_count,
                            'past_event'=>$totalPastEventCount,
                        ];     
                    }
                }
                //upcoming_event


                //PastEvents
                    $usercreatedAllPastEventList = Event::query();
                    $usercreatedAllPastEventList->with(['event_image', 'event_settings', 'user', 'event_schedule'])->where(['user_id' => $user->id]);
                    $usercreatedAllPastEventList->where('end_date', '<', date('Y-m-d'));
                    $usercreatedAllPastEventList->where('is_draft_save', '0');

                    $invitedPastEvents = EventInvitedUser::whereHas('user', function ($query) {
                        $query->where('app_user', '1');
                    })->where('user_id', $user->id)->get()->pluck('event_id');

                    $invitedPastEventsList = Event::query();
                    $invitedPastEventsList->with(['event_image', 'event_settings', 'user', 'event_schedule'])->whereIn('id', $invitedPastEvents)->where('is_draft_save', '0');
                    $invitedPastEventsList->where('end_date', '<', date('Y-m-d'));
                    $invitedPastEventsList->where('is_draft_save', '0');

                    // $invitedPastEventsList->orderBy('start_date', 'ASC')->limit(10);

                    // Use union to combine the two query results
                    // $allPastEvents = $usercreatedAllPastEventList->union($invitedPastEventsList)->orderBy('id','asc')->get();


                    $allPastEventsQuery = $usercreatedAllPastEventList->union($invitedPastEventsList);
                    // dd($allPastEventsQuery->toSql());
                    $allPastEvents = $allPastEventsQuery
                        ->orderBy('start_date', 'asc')
                        ->offset(0)
                        ->limit(10) 
                        ->get();
                    // $allPastEvents =  collect($allPastEvent)->sortBy('start_date');

                $totalCounts=0;
                $totalCounts += count($allPastEvents);
                $eventPasttList=[];
                if (count($allPastEvents) != 0) {
                    foreach ($allPastEvents as $value) {
                        $eventPastDetail['id'] = $value->id;
                        $eventPastDetail['event_name'] = $value->event_name;
                        $eventPastDetail['is_event_owner'] = ($value->user->id == $user->id) ? 1 : 0;
                        $isCoHost = EventInvitedUser::where(['event_id' => $value->id, 'user_id' => $user->id])->first();
                        $eventPastDetail['is_notification_on_off']  = "";
                        if ($value->user->id == $user->id) {
                            $eventPastDetail['is_notification_on_off'] =  $value->notification_on_off;
                        } else {
                            $eventPastDetail['is_notification_on_off'] =  $isCoHost->notification_on_off;
                        }
                        $eventPastDetail['is_co_host'] = "0";
                        if ($isCoHost != null) {
                            $eventPastDetail['is_co_host'] = $isCoHost->is_co_host;
                        }
                        $eventPastDetail['user_id'] = $value->user->id;
                        $eventPastDetail['host_profile'] = empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile);
                        $eventPastDetail['message_to_guests'] = $value->message_to_guests;
                        $eventPastDetail['event_wall'] = isset($value->event_settings->event_wall)?$value->event_settings->event_wall:'';
                        $eventPastDetail["guest_list_visible_to_guests"] = isset($value->event_settings->guest_list_visible_to_guests)?$value->event_settings->guest_list_visible_to_guests:'';
                        $eventPastDetail['event_potluck'] = isset($value->event_settings->podluck)?$value->event_settings->podluck:'';
                        $eventPastDetail['guest_pending_count'] = getGuestRsvpPendingCount($value->id);
                        $eventPastDetail['adult_only_party'] = isset($value->event_settings->adult_only_party)?$value->event_settings->adult_only_party:'';
                        $eventPastDetail['host_name'] = $value->hosted_by;
                        $eventPastDetail['allow_limit'] =isset($value->event_settings->allow_limit)? $value->event_settings->allow_limit:'';
                        $eventPastDetail['is_past'] = ($value->end_date < date('Y-m-d')) ? true : false;
                        $eventPastDetail['is_gone_time'] = $this->evenGoneTime($value->end_date);
                        $eventPastDetail['post_time'] =  $this->setupcomingpostTime($value->updated_at);
                        $eventPastDetail['kids'] = 0;
                        $eventPastDetail['adults'] = 0;
                        $eventPastDetail['event_plan_name'] = $value->subscription_plan_name;


                        $checkRsvpDone = EventInvitedUser::where(['event_id' => $value->id, 'user_id' => $user->id])->first();
                        if ($checkRsvpDone != null) {
                            $eventPastDetail['kids'] = $checkRsvpDone->kids;
                            $eventPastDetail['adults'] = $checkRsvpDone->adults;
                        }
                        $images = EventImage::where('event_id', $value->id)->first();
                        $eventPastDetail['event_images'] = ($images != null) ? asset('storage/event_images/' . $images->image) : "";
                        $eventPastDetail['event_date'] = $value->start_date;
                        $eventPastDetail['event_date_only'] = Carbon::parse($value->start_date)->format('d');
                        $eventPastDetail['event_date_mon'] = Carbon::parse($value->start_date)->format('d M'); // "21 Nov"
                        $eventPastDetail['event_day'] = Carbon::parse($value->start_date)->format('l'); // "Monday"
                        $eventPastDetail['event_month'] = Carbon::parse($value->start_date)->format('M'); // "21 Nov"

                        $event_time = "-";
                        if ($value->event_schedule->isNotEmpty()) {
                            $event_time =  $value->event_schedule->first()->start_time;
                        }
                        $eventPastDetail['start_time'] =  $value->rsvp_start_time;
                        $eventPastDetail['rsvp_start_timezone'] = $value->rsvp_start_timezone;
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
                        $eventPastDetail['rsvp_status'] = $rsvp_status;
                        $total_accept_event_user = EventInvitedUser::whereHas('user', function ($query) {
                                $query->where('app_user', '1');
                        })->where(['event_id' => $value->id, 'rsvp_status' => '1', 'rsvp_d' => '1'])->count();

                        $eventPastDetail['total_accept_event_user'] = $total_accept_event_user;
                        $total_invited_user = EventInvitedUser::whereHas('user', function ($query) {
                            $query->where('app_user', '1');
                        })->where(['event_id' => $value->id])->count();

                        $eventPastDetail['total_invited_user'] = $total_invited_user;

                        $total_refuse_event_user = EventInvitedUser::whereHas('user', function ($query) {
                            $query->where('app_user', '1');
                        })->where(['event_id' => $value->id, 'rsvp_status' => '0', 'rsvp_d' => '1'])->count();

                        $eventPastDetail['total_refuse_event_user'] = $total_refuse_event_user;
                        $total_notification = Notification::where(['event_id' => $value->id, 'user_id' => $user->id, 'read' => '0'])->count();
                        $eventPastDetail['total_notification'] = $total_notification;
                        $eventPastDetail['event_detail'] = [];
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
                            $eventPastDetail['event_detail'] = $eventData;
                        }
                        $eventPasttList[] = $eventPastDetail;
                        //PastEvents


                      
                        
                    }
                   
                }
                //draftEvent
                $draftEvents = Event::where(['user_id' => $user->id, 'is_draft_save' => '1'])->orderBy('id', 'DESC')
                ->offset(0)
                ->limit(10)
                ->get();
                $draftEventArray = [];
                if (!empty($draftEvents) && count($draftEvents) != 0) {
                    foreach ($draftEvents as $value) {
                        $eventDraftDetail['id'] = $value->id;
                        $eventDraftDetail['event_name'] = ($value->event_name!="")?$value->event_name:"No name";
                        $eventDraftDetail['event_date_only'] = Carbon::parse($value->start_date)->format('d');
                        $eventDraftDetail['event_month'] = Carbon::parse($value->start_date)->format('M'); // "21 Nov"

                        // $formattedDate = Carbon::createFromFormat('Y-m-d H:i:s', $value->updated_at)->format('F j, Y');
                        $formattedDate = Carbon::createFromFormat('Y-m-d H:i:s', $value->updated_at)->format('F j, Y - g:i A');
                        $eventDraftDetail['saved_date'] = $formattedDate;
                        $eventDraftDetail['step'] = ($value->step != NULL) ? $value->step : 0;
                        $eventDraftDetail['event_plan_name'] = $value->subscription_plan_name;

                        if($value->user_id==$user->id){
                            $eventDraftDetail['is_host'] = "hosting"; 
                        }else{
                            $eventDraftDetail['is_host'] = ""; 
                        }

                        $draftEventArray[] = $eventDraftDetail;
                    }
                    $eventDraftdata= $draftEventArray;
                } else {
                    $eventDraftdata= [];
                }
                //draftEvent

                    //ProfileData
                            $totalEventOfYear=totalEventOfCurrentYear($user->id);
                            $upcomingEventCount = upcomingEventsCount($user->id);
                            $pendingRsvpCount = pendingRsvpCount($user->id);
                            $hostingCount = hostingCount($user->id);
                            $hostingCountCurrentMonth = hostingCountCurrentMonth($user->id);
                            // $hostingCountCurrentMonth = count($eventDatahosting);
                            $invitedToCount = invitedToCount($user->id);
                            $invitedToCountCurrentMonth= invitedToCountCurrentMonth($user->id);
                            $totalEventOfCurrentMonth = totalEventOfCurrentMonth($user->id);

                            $totalInvitedUpcoming = EventInvitedUser::whereHas('event', function ($query) {
                                $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'));
                            })->where('user_id', $user->id)
                            // ->where('is_co_host','0')
                            ->count();


                            // return $totalInvited;
                            // $invitedToCountCurrentMonth=count($invitedEventsListTo);
                            //ProfileData//

                            $profileData = [
                                'total_events' => $totalEvent,
                                'total_events_of_year' => $totalEventOfYear,
                                'total_events_of_current_month' => $totalEventOfCurrentMonth,   
                                'total_upcoming_events' => $upcomingEventCount,
                                'invitedTo_count' => $invitedToCount,
                                'invitedTo_count_upcoming' => $totalInvitedUpcoming,
                                'invitedTo_count_current_month' => $invitedToCountCurrentMonth,
                                'hosting_count' => $hostingCount,
                                'hosting_count_current_month'=>$hostingCountCurrentMonth,
                                'total_notification' => Notification::where(['user_id' => $user->id, 'read' => '0'])->count(),

                            ];

                            $filter = [
                                'invited_to' => $totalInvited,
                                'invitedTo_count_upcoming' => $totalInvitedUpcoming,
                                'hosting' => $totalHosting,
                                'need_to_rsvp' => $total_need_rsvp_event_count,
                                'past_event'=>$totalPastEventCount,
                                'total_upcoming'=>$upcomingEventCount,
                                'total_draft'=>$totalDraftEvent
                            ];  

                $event_calender_start=User::select('created_at')->where('id',$user->id)->get();
                // dd($event_calender_start);
                $currentYear = Carbon::now()->year;
                $currentMonth = Carbon::now()->month;  
                $profileYear = Carbon::parse($event_calender_start[0]->created_at)->format('Y');
                $profileMonth =  Carbon::parse($event_calender_start[0]->created_at)->format('m');
                $diffYear = $currentYear - $profileYear;
                if ($diffYear >= 2) {
                    $numMonths = 48;
                } elseif ($diffYear == 1) {
                    $numMonths = 36;
                } else {
                    $numMonths = 24;
                }
                if ($diffYear != 0) {
                    $diffmonth = ($profileMonth - 1 );
                } else {
                    $diffmonth = ($currentMonth - $profileMonth);
                }
    
                $startMonth=Carbon::parse($event_calender_start[0]->created_at)->format('Y-m');
                $startMonthCalender=Carbon::parse($event_calender_start[0]->created_at)->format('F Y');


                $eventcalender = Event::where(['is_draft_save' => '0', 'user_id' => $user->id]);
                $invitedEvents = EventInvitedUser::where('user_id', $user->id)->get()->pluck('event_id');
                $invitedEventsList = Event::whereIn('id', $invitedEvents)->where('is_draft_save', '0');
    

                $eventcalenderdata = $eventcalender->union($invitedEventsList)->get();

                $color = ['blue', 'green', 'yellow', 'orange'];
                $events_calender = [];
                
                $groupedEvents = [];
                foreach ($eventcalenderdata as $event) {
                    $groupedEvents[$event->start_date][] = $event;
                }
                foreach ($groupedEvents as $date => $eventsOnDate) {
                    $colorIndex = 0; 
                    usort($eventsOnDate, function ($a, $b) {
                        return strcmp($a->event_name, $b->event_name); 
                    });
                
                    foreach ($eventsOnDate as $event) {
                        $colorClass = $color[$colorIndex % count($color)];
                        $colorIndex++; 
                        $events_calender[] = [
                            'date' => $event->start_date,
                            'title' => $event->event_name,
                            'color' => $colorClass
                        ];
                    }
                }

                // $events_calender = [];
                // $color=['blue','orange','green','yellow'];
                // $colorIndex = 0;
              
                // foreach ($eventcalenderdata as $event) {
                //     $colorClass = $color[$colorIndex % count($color)];
                //     $colorIndex++;
                //     $events_calender[] = [
                //         'date' => $event->start_date, 
                //         'title' => $event->event_name,    
                //         'color' => $colorClass    
                //     ];
                // }

                $events_calender_json = json_encode($events_calender, JSON_UNESCAPED_SLASHES);

                // dd($eventList);
                // return compact('filter','eventList','eventPasttList','eventDraftdata');
                $js = ['event'];
                $title = 'Events';
                $page = 'front.events';
                return view('layout', compact('title','page','js','filter','eventList','eventPasttList','startMonthCalender','eventDraftdata','profileData','startMonth','numMonths','diffmonth','events_calender_json'));
    }   

    public function evenGoneTime($enddate)
    {
        $eventEndDate = $enddate; 
        $currentDate = Carbon::today();
        $endDateTime = Carbon::parse($eventEndDate);
        $hoursElapsed = $endDateTime->diffInHours($currentDate, false); 
        return $hoursElapsed;
    }

    // function setpostTime($dateTime)
    // {
    //     $commentDateTime = $dateTime; 
    //     $commentTime = Carbon::parse($commentDateTime);
    //     $timeAgo = $commentTime->diffForHumans();
    //     return $timeAgo;
    // }

    function setupcomingpostTime($updatedAt)
    {
        $now = Carbon::now(); // Current time
        $updatedTime =Carbon::parse($updatedAt); // Parse the updated_at value
    
        $diffInDays = $updatedTime->diffInDays($now);
    
        if ($diffInDays > 0) {
            return $diffInDays . 'd'; // Return in 'Xd' format
        }
    
        $diffInHours = $updatedTime->diffInHours($now);
    
        if ($diffInHours > 0) {
            return $diffInHours . 'h'; // Return in 'Xh' format
        }
    
        $diffInMinutes = $updatedTime->diffInMinutes($now);
    
        if ($diffInMinutes > 0) {
            return $diffInMinutes . 'm'; // Return in 'Xm' format
        }
    
        return 'just now'; // For moments less than a minute
    }
    public function fetchPastEvents(Request $request) {
        // dd(100);
        $limit = $request->input('limit'); // Default limit
        $offset = $request->input('offset');
        $get_current_month= $request->input('current_month');

        $user  = Auth::guard('web')->user();
        $usercreatedAllPastEventList = Event::query();
        $usercreatedAllPastEventList->with(['event_image', 'event_settings', 'user', 'event_schedule'])->where(['user_id' => $user->id]);
        $usercreatedAllPastEventList->where('end_date', '<', date('Y-m-d'));
        $usercreatedAllPastEventList->where('is_draft_save', '0');


        $invitedPastEvents = EventInvitedUser::whereHas('user', function ($query) {
            $query->where('app_user', '1');
        })->where('user_id', $user->id)->get()->pluck('event_id');

        $invitedPastEventsList = Event::query();
        $invitedPastEventsList->with(['event_image', 'event_settings', 'user', 'event_schedule'])->whereIn('id', $invitedPastEvents)->where('is_draft_save', '0');
        $invitedPastEventsList->where('end_date', '<', date('Y-m-d'));
        $invitedPastEventsList->where('is_draft_save', '0');

    
                              $allPastEventsQuery = $usercreatedAllPastEventList->union($invitedPastEventsList);

                              // Apply offset and limit
                              $allPastEvents = $allPastEventsQuery
                                  ->orderBy('start_date', 'asc') // Adjust ordering if needed
                                  ->offset($offset)
                                  ->limit($limit)
                                  ->get();
                          
                              // Check if there is more data
                              $hasMore = $allPastEventsQuery->count() > $offset + $limit;
        
        // Count total events without pagination for total count
        $totalCounts=0;
        $totalCounts += count($allPastEvents);
        $eventPasttList=[];
            if (count($allPastEvents) != 0) {
            foreach ($allPastEvents as $value) {
                $eventPastDetail['id'] = $value->id;
                $eventPastDetail['event_name'] = $value->event_name;
                $eventPastDetail['is_event_owner'] = ($value->user->id == $user->id) ? 1 : 0;
                $isCoHost = EventInvitedUser::where(['event_id' => $value->id, 'user_id' => $user->id])->first();
                $eventPastDetail['is_notification_on_off']  = "";
                if ($value->user->id == $user->id) {
                    $eventPastDetail['is_notification_on_off'] =  $value->notification_on_off;
                } else {
                    $eventPastDetail['is_notification_on_off'] =  $isCoHost->notification_on_off;
                }
                $eventPastDetail['is_co_host'] = "0";
                if ($isCoHost != null) {
                    $eventPastDetail['is_co_host'] = $isCoHost->is_co_host;
                }
                $eventPastDetail['user_id'] = $value->user->id;
                $eventPastDetail['host_profile'] = empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile);
                $eventPastDetail['message_to_guests'] = $value->message_to_guests;
                $eventPastDetail['event_wall'] =(isset($value->event_settings->event_wall)&&$value->event_settings->event_wall!="")?$value->event_settings->event_wall:"";
                $eventPastDetail["guest_list_visible_to_guests"] = $value->event_settings->guest_list_visible_to_guests;
                $eventPastDetail['event_potluck'] = $value->event_settings->podluck;
                $eventPastDetail['guest_pending_count'] = getGuestRsvpPendingCount($value->id);
                $eventPastDetail['adult_only_party'] = $value->event_settings->adult_only_party;
                $eventPastDetail['host_name'] = $value->hosted_by;
                $eventPastDetail['allow_limit'] = $value->event_settings->allow_limit;
                $eventPastDetail['is_past'] = ($value->end_date < date('Y-m-d')) ? true : false;
                $eventPastDetail['is_gone_time'] = $this->evenGoneTime($value->end_date);
                $eventPastDetail['post_time'] =  $this->setupcomingpostTime($value->updated_at);
                $eventPastDetail['kids'] = 0;
                $eventPastDetail['adults'] = 0;
                $eventPastDetail['event_plan_name'] = $value->subscription_plan_name;


                $checkRsvpDone = EventInvitedUser::where(['event_id' => $value->id, 'user_id' => $user->id])->first();
                if ($checkRsvpDone != null) {
                    $eventPastDetail['kids'] = $checkRsvpDone->kids;
                    $eventPastDetail['adults'] = $checkRsvpDone->adults;
                }
                $images = EventImage::where('event_id', $value->id)->first();
                $eventPastDetail['event_images'] = ($images != null) ? asset('storage/event_images/' . $images->image) : "";
                $eventPastDetail['event_date'] = $value->start_date;
                $eventPastDetail['event_date_mon'] = Carbon::parse($value->start_date)->format('d M'); // "21 Nov"
                $eventPastDetail['event_day'] = Carbon::parse($value->start_date)->format('l'); // "Monday"
                $eventPastDetail['event_date_only'] = Carbon::parse($value->start_date)->format('d');
                $eventPastDetail['event_month'] = Carbon::parse($value->start_date)->format('M'); // "21 Nov"
                $event_time = "-";
                if ($value->event_schedule->isNotEmpty()) {
                    $event_time =  $value->event_schedule->first()->start_time;
                }
                $eventPastDetail['start_time'] =  $value->rsvp_start_time;
                $eventPastDetail['rsvp_start_timezone'] = $value->rsvp_start_timezone;
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
                $eventPastDetail['rsvp_status'] = $rsvp_status;
                $total_accept_event_user = EventInvitedUser::whereHas('user', function ($query) {
                        $query->where('app_user', '1');
                })->where(['event_id' => $value->id, 'rsvp_status' => '1', 'rsvp_d' => '1'])->count();

                $eventPastDetail['total_accept_event_user'] = $total_accept_event_user;
                $total_invited_user = EventInvitedUser::whereHas('user', function ($query) {
                    $query->where('app_user', '1');
                })->where(['event_id' => $value->id])->count();

                $eventPastDetail['total_invited_user'] = $total_invited_user;

                $total_refuse_event_user = EventInvitedUser::whereHas('user', function ($query) {
                    $query->where('app_user', '1');
                })->where(['event_id' => $value->id, 'rsvp_status' => '0', 'rsvp_d' => '1'])->count();

                $eventPastDetail['total_refuse_event_user'] = $total_refuse_event_user;
                $total_notification = Notification::where(['event_id' => $value->id, 'user_id' => $user->id, 'read' => '0'])->count();
                $eventPastDetail['total_notification'] = $total_notification;
                $eventPastDetail['event_detail'] = [];
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
                    $eventPastDetail['event_detail'] = $eventData;
                }
                $eventPasttList[] = $eventPastDetail;
            }
        }
        
                return response()->json(['view' => view( 'front.event.event_list.past_event', compact('eventPasttList','get_current_month'))->render(),'has_more' => $hasMore,'offset'=>$offset,'limit'=>$limit],);

                
    }

    public function fetchDraftEvents(Request $request){
        $limit = $request->input('limit'); // Default limit
        $offset = $request->input('offset');
        $get_current_month= $request->input('current_month');

        $user  = Auth::guard('web')->user();

        $draftEvents = Event::where(['user_id' => $user->id, 'is_draft_save' => '1'])->orderBy('id', 'DESC')
        ->offset($offset)
        ->limit($limit)
        ->get();
                        $draftEventArray = [];
                        if (!empty($draftEvents) && count($draftEvents) != 0) {
                            foreach ($draftEvents as $value) {
                                $eventDraftDetail['id'] = $value->id;
                                $eventDraftDetail['event_name'] = ($value->event_name!="")?$value->event_name:"No name";;
                                // $formattedDate = Carbon::createFromFormat('Y-m-d H:i:s', $value->updated_at)->format('F j, Y');
                                $formattedDate = Carbon::createFromFormat('Y-m-d H:i:s', $value->updated_at)->format('F j, Y - g:i A');
                                $eventDraftDetail['saved_date'] = $formattedDate;
                                $eventDraftDetail['step'] = ($value->step != NULL) ? $value->step : 0;
                                $eventDraftDetail['event_plan_name'] = $value->subscription_plan_name;
                                $eventDraftDetail['event_date_only'] = Carbon::parse($value->start_date)->format('d');
                                $eventDraftDetail['event_month'] = Carbon::parse($value->start_date)->format('M'); 

                                if($value->user_id==$user->id){
                                    $eventDraftDetail['is_host'] = "hosting"; 
                                }else{
                                    $eventDraftDetail['is_host'] = ""; 
                                }

                                $draftEventArray[] = $eventDraftDetail;
                            }
                        //     $eventDraftdata= $draftEventArray;
                        // } else {
                        //     $eventDraftdata= "";
                        // }
                        }

                        return response()->json(['view' => view( 'front.event.event_list.draft_event', compact('draftEventArray','get_current_month'))->render()],);


    }
           
    public function fetchUpcomingEvents(Request $request){
        $limit = $request->input('limit'); // Default limit
        $offset = $request->input('offset');
        $get_current_month= $request->input('current_month');

        $user  = Auth::guard('web')->user();
                
        // $pages = ($page != "") ? $page : 1;

        //upcoming_event
        $usercreatedList = Event::with(['user', 'event_settings', 'event_schedule'])->where('start_date', '>=', date('Y-m-d'))
        ->where('user_id', $user->id)
        ->where('is_draft_save', '0');
        // ->orderBy('start_date', 'ASC')  
        // ->get();
    $invitedEvents = EventInvitedUser::whereHas('user', function ($query) {
    $query->where('app_user', '1');
    })->where('user_id', $user->id)->get()->pluck('event_id');
    $invitedEventsList = Event::with(['event_image', 'user', 'event_settings', 'event_schedule'])
        ->whereIn('id', $invitedEvents)->where('start_date', '>=', date('Y-m-d'))
        ->where('is_draft_save', '0');
        // ->orderBy('start_date', 'ASC')
        // ->get();
    $allEvents = $usercreatedList->union($invitedEventsList);
    $allEvent = $allEvents
                ->orderBy('start_date', 'asc')
                ->offset($offset)
                ->limit($limit) 
                ->get();
        // $totalCounts += count($allEvent);
        // // Calculate offset based on current page and perPage
        // $offset = ($pages - 1) * $this->perPage;

        // $offset=$page*$this->perPage

        // $paginatedEvents =  collect($allEvent)->sortBy('start_date')->forPage($page, $this->perPage);
        // $paginatedEvents =  collect($allEvent)->sortBy('start_date');
        $eventList = [];
        if (count($allEvent) != 0) {

            foreach ($allEvent as $value) {
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
                $eventDetail['self_id']=$user->id;

                $eventDetail['user_id'] = $value->user->id;
                $eventDetail['host_profile'] = empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile);
                $eventDetail['message_to_guests'] = $value->message_to_guests;
                $eventDetail['event_wall'] = (isset($value->event_settings->event_wall)&&$value->event_settings->event_wall!="")?$value->event_settings->event_wall:"";
                $eventDetail["guest_list_visible_to_guests"] = $value->event_settings->guest_list_visible_to_guests;
                $eventDetail['event_potluck'] = $value->event_settings->podluck;
                $eventDetail['guest_pending_count'] = getGuestRsvpPendingCount($value->id);
                $eventDetail['adult_only_party'] = $value->event_settings->adult_only_party;
                $eventDetail['host_name'] = $value->hosted_by;
                $eventDetail['host_firstname'] = $value->user->firstname;
                $eventDetail['host_lastname'] = $value->user->lastname;
                $eventDetail['allow_limit'] = $value->event_settings->allow_limit;
                $eventDetail['is_past'] = ($value->end_date < date('Y-m-d')) ? true : false;
                $eventDetail['is_gone_time'] = $this->evenGoneTime($value->end_date);
                $eventDetail['post_time'] =  $this->setupcomingpostTime($value->updated_at);
                $eventDetail['kids'] = 0;
                $eventDetail['adults'] = 0;

                $checkRsvpDone = EventInvitedUser::where(['event_id' => $value->id, 'user_id' => $user->id])->first();
                if ($checkRsvpDone != null) {
                    $eventDetail['kids'] = $checkRsvpDone->kids;
                    $eventDetail['adults'] = $checkRsvpDone->adults;
                }
                $images = EventImage::where('event_id', $value->id)->first();
                $eventDetail['event_images'] = ($images != null) ? asset('storage/event_images/' . $images->image) : "";
                $eventDetail['event_date'] = $value->start_date;
                $eventDetail['event_date_mon'] = Carbon::parse($value->start_date)->format('d M'); // "21 Nov"
                $eventDetail['event_day'] = Carbon::parse($value->start_date)->format('l'); // "Monday"
                $eventDetail['event_month'] = Carbon::parse($value->start_date)->format('M'); // "21 Nov"
                $eventDetail['event_date_only'] = Carbon::parse($value->start_date)->format('d');

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
                    'profile' => empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile),
                    'bg_profile' => empty($value->user->bg_profile) ? "" : asset('storage/bg_profile/' . $value->user->bg_profile),
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
                $eventDetail['event_plan_name'] = $value->subscription_plan_name;

                $totalInvited = EventInvitedUser::whereHas('event', function ($query) {
                    $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'));
                })->where('user_id', $user->id)->count();
                $totalHosting = Event::where(['is_draft_save' => '0', 'user_id' => $user->id])->where('start_date', '>=', date('Y-m-d'))->count();

                $usercreatedAllPastEventCount = Event::where(['is_draft_save' => '0', 'user_id' => $user->id])->where('end_date', '<', date('Y-m-d'));
                $invitedPastEvents = EventInvitedUser::whereHas('user', function ($query) {
                    $query->where('app_user', '1');
                })->where('user_id', $user->id)->get()->pluck('event_id');
                $total_past_event = Event::where('end_date', '<', date('Y-m-d'))->whereIn('id', $invitedPastEvents)->where('is_draft_save', '0');
                $allPastEventC = $usercreatedAllPastEventCount->union($total_past_event)->orderByDesc('id')->get();
                $totalPastEventCount = count($allPastEventC);

                $total_need_rsvp_event_count = EventInvitedUser::whereHas('event', function ($query) {
                    $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'));
                })->where(['user_id' => $user->id, 'rsvp_status' => NULL])->count();
                $eventList[] = $eventDetail;


                $upcomingEventCount = upcomingEventsCount($user->id);
                $totalDraftEvent =  Event::where(['user_id' => $user->id, 'is_draft_save' => '1'])->count();
            }}
            return response()->json(['view' => view( 'front.event.event_list.upcoming_event', compact('eventList','get_current_month'))->render()],);


    }

    public function SearchUpcomingEvent(Request $request){
        $get_current_month= $request->input('current_month');
        $user  = Auth::guard('web')->user();
        $eventName = $request->input('searchValue'); 
        $search_date=$request->input('search_date');
       
  

        if($search_date!=""){
                $usercreatedList = Event::with(['user', 'event_settings', 'event_schedule'])->where('start_date',$search_date)->where('start_date','>=',date('Y-m-d'))
                ->where('user_id', $user->id)
                ->where('is_draft_save', '0')
                ->where('event_name', 'LIKE', '%' . $eventName . '%');
                $invitedEvents = EventInvitedUser::whereHas('user', function ($query) {
                $query->where('app_user', '1');
                })->where('user_id', $user->id)->get()->pluck('event_id');
                $invitedEventsList = Event::with(['event_image', 'user', 'event_settings', 'event_schedule'])
                    ->whereIn('id', $invitedEvents)->where('start_date', $search_date)->where('start_date','>=',date('Y-m-d'))
                    ->where('is_draft_save', '0')
                    ->where('event_name', 'LIKE', '%' . $eventName . '%'); 
        }else{
            $usercreatedList = Event::with(['user', 'event_settings', 'event_schedule'])->where('start_date', '>=', date('Y-m-d'))
                ->where('user_id', $user->id)
                ->where('is_draft_save', '0')
                ->where('event_name', 'LIKE', '%' . $eventName . '%');
                $invitedEvents = EventInvitedUser::whereHas('user', function ($query) {
                $query->where('app_user', '1');
                })->where('user_id', $user->id)->get()->pluck('event_id');
                $invitedEventsList = Event::with(['event_image', 'user', 'event_settings', 'event_schedule'])
                    ->whereIn('id', $invitedEvents)->where('start_date', '>=', date('Y-m-d'))
                    ->where('is_draft_save', '0')
                    ->where('event_name', 'LIKE', '%' . $eventName . '%');
        }
    
        $allEvents = $usercreatedList->union($invitedEventsList);

    $allEvent = $allEvents
                ->orderBy('start_date', 'asc')
                ->get();

        $last_month = '';
        // dd(count($allEvent));
        $eventList=[];
        if (count($allEvent) != 0) {

            foreach ($allEvent as $value) {
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
                $eventDetail['host_profile'] = empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile);
                $eventDetail['message_to_guests'] = $value->message_to_guests;
                $eventDetail['event_wall'] = (isset($value->event_settings->event_wall)&&$value->event_settings->event_wall!="")?$value->event_settings->event_wall:"";;
                $eventDetail["guest_list_visible_to_guests"] = $value->event_settings->guest_list_visible_to_guests;
                $eventDetail['event_potluck'] = $value->event_settings->podluck;
                $eventDetail['guest_pending_count'] = getGuestRsvpPendingCount($value->id);
                $eventDetail['adult_only_party'] = $value->event_settings->adult_only_party;
                $eventDetail['host_name'] = $value->hosted_by;
                $eventDetail['host_firstname'] = $value->user->firstname;
                $eventDetail['host_lastname'] = $value->user->lastname;
                $eventDetail['allow_limit'] = $value->event_settings->allow_limit;
                $eventDetail['is_past'] = ($value->end_date < date('Y-m-d')) ? true : false;
                $eventDetail['is_gone_time'] = $this->evenGoneTime($value->end_date);
                $eventDetail['post_time'] =  $this->setupcomingpostTime($value->updated_at);
                $eventDetail['kids'] = 0;
                $eventDetail['adults'] = 0;

                $checkRsvpDone = EventInvitedUser::where(['event_id' => $value->id, 'user_id' => $user->id])->first();
                if ($checkRsvpDone != null) {
                    $eventDetail['kids'] = $checkRsvpDone->kids;
                    $eventDetail['adults'] = $checkRsvpDone->adults;
                }
                $images = EventImage::where('event_id', $value->id)->first();
                $eventDetail['event_images'] = ($images != null) ? asset('storage/event_images/' . $images->image) : "";
                $eventDetail['event_date'] = $value->start_date;
                $eventDetail['event_date_mon'] = Carbon::parse($value->start_date)->format('d M'); // "21 Nov"
                $eventDetail['event_day'] = Carbon::parse($value->start_date)->format('l'); // "Monday"
                $eventDetail['event_date_only'] = Carbon::parse($value->start_date)->format('d');
                $eventDetail['event_month'] = Carbon::parse($value->start_date)->format('M'); // "21 Nov"
                $last_month = Carbon::parse($value->start_date)->format('M');
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
                    'profile' => empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile),
                    'bg_profile' => empty($value->user->bg_profile) ? "" : asset('storage/bg_profile/' . $value->user->bg_profile),
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
                $eventDetail['event_plan_name'] = $value->subscription_plan_name;

                $totalInvited = EventInvitedUser::whereHas('event', function ($query) {
                    $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'));
                })->where('user_id', $user->id)->count();
                $totalHosting = Event::where(['is_draft_save' => '0', 'user_id' => $user->id])->where('start_date', '>=', date('Y-m-d'))->count();

                $usercreatedAllPastEventCount = Event::where(['is_draft_save' => '0', 'user_id' => $user->id])->where('end_date', '<', date('Y-m-d'));
                $invitedPastEvents = EventInvitedUser::whereHas('user', function ($query) {
                    $query->where('app_user', '1');
                })->where('user_id', $user->id)->get()->pluck('event_id');
                $total_past_event = Event::where('end_date', '<', date('Y-m-d'))->whereIn('id', $invitedPastEvents)->where('is_draft_save', '0');
                $allPastEventC = $usercreatedAllPastEventCount->union($total_past_event)->orderByDesc('id')->get();
                $totalPastEventCount = count($allPastEventC);

                $total_need_rsvp_event_count = EventInvitedUser::whereHas('event', function ($query) {
                    $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'));
                })->where(['user_id' => $user->id, 'rsvp_status' => NULL])->count();
                $eventList[] = $eventDetail;


                $upcomingEventCount = upcomingEventsCount($user->id);
                $totalDraftEvent =  Event::where(['user_id' => $user->id, 'is_draft_save' => '1'])->count();
            }}
             if(!empty($eventList)){
               $last_month = $eventList[0]['event_month'];
             }
            return response()->json(['view' => view( 'front.event.event_list.upcoming_event', compact('eventList','get_current_month'))->render(),'last_month'=>$last_month,'page'=>'upcoming']);


    }
    
    public function SearchDraftEvent(Request $request){
        $is_draft_page="";
        $is_draft_page= $request->input('is_draft_page');
        $search_date= $request->input('search_date');
        $get_current_month= $request->input('current_month');
        $user  = Auth::guard('web')->user();
        $eventName = $request->input('searchValue'); 
        if($search_date!=""){
            $draftEvents = Event::where(['user_id' => $user->id, 'is_draft_save' => '1'])->where('start_date',$search_date)->orderBy('id', 'DESC')
            ->get();
        }else{
            $draftEvents = Event::where(['user_id' => $user->id, 'is_draft_save' => '1'])->orderBy('id', 'DESC')
            ->where('event_name', 'LIKE', '%' . $eventName . '%')
            ->get();
        }
        $draftEventArray = [];
        $last_month = '';

        if (!empty($draftEvents) && count($draftEvents) != 0) {
            foreach ($draftEvents as $value) {
                $eventDraftDetail['id'] = $value->id;
                $eventDraftDetail['event_name'] = ($value->event_name!="")?$value->event_name:"No name";
                $eventDraftDetail['event_date_only'] = Carbon::parse($value->start_date)->format('d');
                $eventDraftDetail['event_month'] = Carbon::parse($value->start_date)->format('M'); // "21 Nov"

                // $formattedDate = Carbon::createFromFormat('Y-m-d H:i:s', $value->updated_at)->format('F j, Y');
                $formattedDate = Carbon::createFromFormat('Y-m-d H:i:s', $value->updated_at)->format('F j, Y - g:i A');
                $eventDraftDetail['saved_date'] = $formattedDate;
                $eventDraftDetail['step'] = ($value->step != NULL) ? $value->step : 0;
                $eventDraftDetail['event_plan_name'] = $value->subscription_plan_name;

                if($value->user_id==$user->id){
                    $eventDraftDetail['is_host'] = "hosting"; 
                }else{
                    $eventDraftDetail['is_host'] = ""; 
                }

                $draftEventArray[] = $eventDraftDetail;
            }
        //     $eventDraftdata= $draftEventArray;
        // } else {
        //     $eventDraftdata= "";
        }
        if(!empty($draftEventArray)){
            $last_month = $draftEventArray[0]['event_month'];
          }
        //   dd($last_month);
        if($is_draft_page!=""){
            return response()->json(['view' => view( 'front.event.event_list.search_draft_page', compact('draftEventArray','get_current_month'))->render(),"draft_count"=>count($draftEventArray)]);
        }
        return response()->json(['view' => view( 'front.event.event_list.draft_event', compact('draftEventArray','get_current_month'))->render(),'last_month'=>$last_month,'page'=>'draft' ]);

    }

    public function SearchPastEvent(Request $request){
        $eventName = $request->input('searchValue'); 

        $get_current_month= $request->input('current_month');

        $serach_date= $request->input('search_date');

        $user  = Auth::guard('web')->user();
    

        if($serach_date!=""){
            $usercreatedAllPastEventList = Event::query();
            $usercreatedAllPastEventList->with(['event_image', 'event_settings', 'user', 'event_schedule'])->where(['user_id' => $user->id]);
            $usercreatedAllPastEventList->where('start_date',$serach_date);
            $usercreatedAllPastEventList->where('start_date', '<', date('Y-m-d'));
            $usercreatedAllPastEventList->where('is_draft_save', '0');
    
    
            $invitedPastEvents = EventInvitedUser::whereHas('user', function ($query) {
                $query->where('app_user', '1');
            })->where('user_id', $user->id)->get()->pluck('event_id');
    
            $invitedPastEventsList = Event::query();
            $invitedPastEventsList->with(['event_image', 'event_settings', 'user', 'event_schedule'])->whereIn('id', $invitedPastEvents)->where('is_draft_save', '0');
            $invitedPastEventsList->where('start_date',$serach_date);
            $invitedPastEventsList->where('start_date', '<', date('Y-m-d'));
            $invitedPastEventsList->where('is_draft_save', '0')  ;

        }else{
            $usercreatedAllPastEventList = Event::query();
            $usercreatedAllPastEventList->with(['event_image', 'event_settings', 'user', 'event_schedule'])->where(['user_id' => $user->id]);
            $usercreatedAllPastEventList->where('end_date', '<', date('Y-m-d'));
            $usercreatedAllPastEventList->where('is_draft_save', '0')
            ->where('event_name', 'LIKE', '%' . $eventName . '%');
    
    
            $invitedPastEvents = EventInvitedUser::whereHas('user', function ($query) {
                $query->where('app_user', '1');
            })->where('user_id', $user->id)->get()->pluck('event_id');
    
            $invitedPastEventsList = Event::query();
            $invitedPastEventsList->with(['event_image', 'event_settings', 'user', 'event_schedule'])->whereIn('id', $invitedPastEvents)->where('is_draft_save', '0');
            $invitedPastEventsList->where('end_date', '<', date('Y-m-d'));
            $invitedPastEventsList->where('is_draft_save', '0')
            ->where('event_name', 'LIKE', '%' . $eventName . '%');  
        }
    
         $allPastEventsQuery = $usercreatedAllPastEventList->union($invitedPastEventsList);

                              // Apply offset and limit
         $allPastEvents = $allPastEventsQuery
                        ->orderBy('start_date', 'asc')
                        ->get();
                          
        $totalCounts=0;
        $totalCounts += count($allPastEvents);
        $eventPasttList=[];
        $last_month = '';

            if (count($allPastEvents) != 0) {
            foreach ($allPastEvents as $value) {
                $eventPastDetail['id'] = $value->id;
                $eventPastDetail['event_name'] = $value->event_name;
                $eventPastDetail['is_event_owner'] = ($value->user->id == $user->id) ? 1 : 0;
                $isCoHost = EventInvitedUser::where(['event_id' => $value->id, 'user_id' => $user->id])->first();
                $eventPastDetail['is_notification_on_off']  = "";
                if ($value->user->id == $user->id) {
                    $eventPastDetail['is_notification_on_off'] =  $value->notification_on_off;
                } else {
                    $eventPastDetail['is_notification_on_off'] =  $isCoHost->notification_on_off;
                }
                $eventPastDetail['is_co_host'] = "0";
                if ($isCoHost != null) {
                    $eventPastDetail['is_co_host'] = $isCoHost->is_co_host;
                }
                $eventPastDetail['user_id'] = $value->user->id;
                $eventPastDetail['host_profile'] = empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile);
                $eventPastDetail['message_to_guests'] = $value->message_to_guests;
                $eventPastDetail['event_wall'] = (isset($value->event_settings->event_wall)&&$value->event_settings->event_wall!="")?$value->event_settings->event_wall:"";;
                $eventPastDetail["guest_list_visible_to_guests"] = $value->event_settings->guest_list_visible_to_guests;
                $eventPastDetail['event_potluck'] = $value->event_settings->podluck;
                $eventPastDetail['guest_pending_count'] = getGuestRsvpPendingCount($value->id);
                $eventPastDetail['adult_only_party'] = $value->event_settings->adult_only_party;
                $eventPastDetail['host_name'] = $value->hosted_by;
                $eventPastDetail['allow_limit'] = $value->event_settings->allow_limit;
                $eventPastDetail['is_past'] = ($value->end_date < date('Y-m-d')) ? true : false;
                $eventPastDetail['is_gone_time'] = $this->evenGoneTime($value->end_date);
                $eventPastDetail['post_time'] =  $this->setupcomingpostTime($value->updated_at);
                $eventPastDetail['kids'] = 0;
                $eventPastDetail['adults'] = 0;
                $eventPastDetail['event_plan_name'] = $value->subscription_plan_name;


                $checkRsvpDone = EventInvitedUser::where(['event_id' => $value->id, 'user_id' => $user->id])->first();
                if ($checkRsvpDone != null) {
                    $eventPastDetail['kids'] = $checkRsvpDone->kids;
                    $eventPastDetail['adults'] = $checkRsvpDone->adults;
                }
                $images = EventImage::where('event_id', $value->id)->first();
                $eventPastDetail['event_images'] = ($images != null) ? asset('storage/event_images/' . $images->image) : "";
                $eventPastDetail['event_date'] = $value->start_date;
                $eventPastDetail['event_date_mon'] = Carbon::parse($value->start_date)->format('d M'); // "21 Nov"
                $eventPastDetail['event_day'] = Carbon::parse($value->start_date)->format('l'); // "Monday"
                $eventPastDetail['event_date_only'] = Carbon::parse($value->start_date)->format('d');
                $eventPastDetail['event_month'] = Carbon::parse($value->start_date)->format('M'); // "21 Nov"
                $last_month = Carbon::parse($value->start_date)->format('M');

                $event_time = "-";
                if ($value->event_schedule->isNotEmpty()) {
                    $event_time =  $value->event_schedule->first()->start_time;
                }
                $eventPastDetail['start_time'] =  $value->rsvp_start_time;
                $eventPastDetail['rsvp_start_timezone'] = $value->rsvp_start_timezone;
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
                $eventPastDetail['rsvp_status'] = $rsvp_status;
                $total_accept_event_user = EventInvitedUser::whereHas('user', function ($query) {
                        $query->where('app_user', '1');
                })->where(['event_id' => $value->id, 'rsvp_status' => '1', 'rsvp_d' => '1'])->count();

                $eventPastDetail['total_accept_event_user'] = $total_accept_event_user;
                $total_invited_user = EventInvitedUser::whereHas('user', function ($query) {
                    $query->where('app_user', '1');
                })->where(['event_id' => $value->id])->count();

                $eventPastDetail['total_invited_user'] = $total_invited_user;

                $total_refuse_event_user = EventInvitedUser::whereHas('user', function ($query) {
                    $query->where('app_user', '1');
                })->where(['event_id' => $value->id, 'rsvp_status' => '0', 'rsvp_d' => '1'])->count();

                $eventPastDetail['total_refuse_event_user'] = $total_refuse_event_user;
                $total_notification = Notification::where(['event_id' => $value->id, 'user_id' => $user->id, 'read' => '0'])->count();
                $eventPastDetail['total_notification'] = $total_notification;
                $eventPastDetail['event_detail'] = [];
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
                    $eventPastDetail['event_detail'] = $eventData;
                }
                $eventPasttList[] = $eventPastDetail;
            }

            if(!empty($eventPasttList)){
                $last_month = $eventPasttList[0]['event_month'];
            }
        }
        
                return response()->json(['view' => view( 'front.event.event_list.past_event', compact('eventPasttList','get_current_month'))->render(),'last_month' => $last_month,'page'=>'past'],);

    }

    public function EventFilter(Request $request){
        $page=$request->input('page');
        $is_hosting=$request->input('hosting');
        $invited_to=$request->input('invited_to');
        $need_rsvp_to=$request->input('need_to_rsvp');
        $user  = Auth::guard('web')->user();
        $get_current_month="";
        $event_date="";
        $end_event_date="";
        $search="";
        $month="";
        $year="";
        $last_month="";
        $eventList =[];

        if($is_hosting==1){
            $allEvent =  Event::with(['event_image', 'event_settings', 'user', 'event_schedule'])->where(['is_draft_save' => '0', 'user_id' => $user->id]);

            if($page=="upcoming"){
                $allEvent = $allEvent->where('start_date', '>=', date('Y-m-d'));
            }
            if($page=="past"){
                $allEvent = $allEvent->where('end_date', '<', date('Y-m-d'));
            }
            // ->where('start_date', '>=', date('Y-m-d'))
            // ->when($event_date || $end_event_date, function ($query) use ($event_date, $end_event_date) {
            //     return $query->whereBetween('start_date', [$event_date, $end_event_date]);
            // })->when($search != "", function ($query) use ($search) {
            //     return $query->where('event_name', 'like', "%$search%");
            // })->when($month && $year, function ($query) use ($month, $year) {
            //     return   $query->whereMonth('start_date', $month)->whereYear('start_date', $year);
            // })
            $allEvent = $allEvent->orderBy('start_date', 'ASC')->get();
        // ->paginate($this->perPage, ['*'], 'page', $page);
        // Make sure to handle the retrieved $userInvitedEventList accordingly

        $last_month="";

        if (count($allEvent) != 0) {
            foreach ($allEvent as $value) {
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
                $eventDetail['host_firstname'] = $value->user->firstname;
                $eventDetail['host_lastname'] = $value->user->lastname;
                $eventDetail['message_to_guests'] = $value->message_to_guests;
                $eventDetail['user_id'] = $value->user->id;
                $eventDetail['host_profile'] = empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile);
                $eventDetail['event_wall'] = (isset($value->event_settings->event_wall)&&$value->event_settings->event_wall!="")?$value->event_settings->event_wall:"";
                $eventDetail["guest_list_visible_to_guests"] =(isset($value->event_settings->guest_list_visible_to_guests)&& $value->event_settings->guest_list_visible_to_guests!="")? $value->event_settings->guest_list_visible_to_guests:"";
                $eventDetail['guest_pending_count'] = getGuestRsvpPendingCount($value->id);
                $eventDetail['event_potluck'] = (isset($value->event_settings->podluck)&&$value->event_settings->podluck!="")?$value->event_settings->podluck:"";
                $eventDetail['adult_only_party'] = (isset($value->event_settings->adult_only_party)&&$value->event_settings->podluck!="")?$value->event_settings->podluck:"";
                $eventDetail['host_name'] = $value->hosted_by;
                $eventDetail['is_past'] = ($value->end_date < date('Y-m-d')) ? true : false;
                $eventDetail['post_time'] =  $this->setupcomingpostTime($value->updated_at);
                $eventDetail['is_gone_time'] = $this->evenGoneTime($value->end_date);
                $eventDetail['allow_limit'] = (isset($value->event_settings->allow_limit)&&$value->event_settings->allow_limit!="")?$value->event_settings->allow_limit:"";
                $eventDetail['kids'] = 0;
                $eventDetail['adults'] = 0;

                $checkRsvpDone = EventInvitedUser::where(['event_id' => $value->id, 'user_id' => $user->id])->first();
                if ($checkRsvpDone != null) {
                    $eventDetail['kids'] = $checkRsvpDone->kids;
                    $eventDetail['adults'] = $checkRsvpDone->adults;
                }
                $images = EventImage::where('event_id', $value->id)->first();
                $eventDetail['event_images'] = ($images != null) ? asset('storage/event_images/' . $images->image) : "";
                $eventDetail['event_date'] = $value->start_date;
                $eventDetail['event_date_only'] = Carbon::parse($value->start_date)->format('d');
                $eventDetail['event_date_mon'] = Carbon::parse($value->start_date)->format('d M'); // "21 Nov"
                $eventDetail['event_month'] = Carbon::parse($value->start_date)->format('M'); // "21 Nov"
                $eventDetail['event_day'] = Carbon::parse($value->start_date)->format('l'); // "Monday"
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
                    'profile' => empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile),
                    'bg_profile' => empty($value->user->bg_profile) ? "" : asset('storage/bg_profile/' . $value->user->bg_profile),
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
                $eventDetail['event_plan_name'] = $value->subscription_plan_name;

                $eventList[] = $eventDetail;
            }
            if(!empty($eventList)){
                $last_month = $eventList[0]['event_month'];
            }
        }
     
        }

        if($invited_to==1){
                $allEvent = EventInvitedUser::whereHas('event', function ($query) use ($event_date, $end_event_date, $search, $month, $year,$page) {
                $query->where('is_draft_save', '0');
                $query->with(['event_image', 'event_settings', 'user', 'event_schedule']);
                if($page=="upcoming"){
                    $query->where('start_date', '>=', date('Y-m-d'))->orderBy('id', 'DESC');
                }
                if($page=="past"){
                    $query->where('end_date', '<', date('Y-m-d'))->orderBy('start_date', 'asc');
                }

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
            // dd($allEvent);
            if (count($allEvent) != 0) {
                foreach ($allEvent as $value) {
                    $eventDetail['id'] = $value->event->id;
                    $eventDetail['user_id'] = $value->event->user->id;
                    $eventDetail['event_name'] = $value->event->event_name;
                    $eventDetail['is_event_owner'] = ($value->event->user->id == $user->id) ? 1 : 0;
                    $eventDetail['is_co_host'] = $value->is_co_host;
                    $eventDetail['is_notification_on_off'] =  $value->notification_on_off;
                    $eventDetail['message_to_guests'] = $value->event->message_to_guests;
                    $eventDetail['host_profile'] = empty($value->event->user->profile) ? "" : asset('storage/profile/' . $value->event->user->profile);
                    $eventDetail['event_wall'] = (isset($value->event->event_settings->event_wall)&&$value->event->event_settings->event_wall!="")?$value->event->event_settings->event_wall:"";
                    $eventDetail["guest_list_visible_to_guests"] = (isset($value->event->event_settings->guest_list_visible_to_guests)&&$value->event->event_settings->guest_list_visible_to_guests!="")?$value->event->event_settings->guest_list_visible_to_guests:"";
                    $eventDetail['guest_pending_count'] = getGuestRsvpPendingCount($value->event->id);
                    $eventDetail['event_potluck'] = (isset($value->event->event_settings->podluck)&&$value->event->event_settings->podluck!="")?$value->event->event_settings->podluck:"";
                    $eventDetail['adult_only_party'] = (isset($value->event->event_settings->adult_only_party)&&$value->event->event_settings->adult_only_party!="")?$value->event->event_settings->adult_only_party:"";
                    $eventDetail['host_name'] = $value->event->hosted_by;
                    $eventDetail['host_firstname'] = $value->event->user->firstname;
                    $eventDetail['host_lastname'] = $value->event->user->lastname;
                    $eventDetail['is_past'] = ($value->event->end_date < date('Y-m-d')) ? true : false;
                    $eventDetail['post_time'] =  $this->setupcomingpostTime($value->event->updated_at);
                    $eventDetail['is_gone_time'] = $this->evenGoneTime($value->event->end_date);
                    $eventDetail['allow_limit'] = (isset($value->event->event_settings->allow_limit)&&$value->event->event_settings->allow_limit!="")?$value->event->event_settings->allow_limit:"";
                    $images = EventImage::where('event_id', $value->event->id)->first();
                    $eventDetail['event_images'] = "";

                    if (!empty($images)) {
                        $eventDetail['event_images'] = asset('storage/event_images/' . $images->image);
                    }
                    $eventDetail['kids'] = 0;
                    $eventDetail['adults'] = 0;
                    $checkRsvpDone = EventInvitedUser::where(['event_id' => $value->event->id, 'user_id' => $user->id])->first();
                    if ($checkRsvpDone != null) {
                        $eventDetail['kids'] = $checkRsvpDone->kids;
                        $eventDetail['adults'] = $checkRsvpDone->adults;
                    }

                    $eventDetail['event_date'] = $value->event->start_date;
                    $eventDetail['event_date_only'] = Carbon::parse($value->event->start_date)->format('d');
                    $eventDetail['event_date_mon'] = Carbon::parse($value->event->start_date)->format('d M'); // "21 Nov"
                    $eventDetail['event_month'] = Carbon::parse($value->event->start_date)->format('M'); // "21 Nov"
                    $eventDetail['event_day'] = Carbon::parse($value->event->start_date)->format('l'); // "Monday"
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
                        if ($checkUserrsvp->rsvp_status == NULL) {
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
                        'profile' => empty($value->event->user->profile) ? "" : asset('storage/profile/' . $value->event->user->profile),
                        'bg_profile' => empty($value->event->user->bg_profile) ? "" : asset('storage/bg_profile/' . $value->event->user->bg_profile),
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
                    $eventDetail['event_plan_name'] = $value->event->subscription_plan_name;

                    $eventList[] = $eventDetail;
                  
                }
                if(!empty($eventList)){
                    $last_month=$eventList[0]['event_month'];
                }
           
            }
        }

        if ($need_rsvp_to== 1) {
            $userNeedRsvpEventList = EventInvitedUser::whereHas('event', function ($query) use ($event_date, $end_event_date, $search, $month, $year,$page) {
               
               
                if($page=="upcoming"){
                    $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'))
                    ->with(['event_image', 'event_settings', 'user', 'event_schedule'])
                    ->orderBy('id', 'DESC');                }
                if($page=="past"){
                    $query->where('is_draft_save', '0')->where('end_date', '<', date('Y-m-d'))
                    ->with(['event_image', 'event_settings', 'user', 'event_schedule'])
                    ->orderBy('start_date', 'ASC');                }
                
           
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
                    $eventDetail['host_profile'] = empty($value->event->user->profile) ? "" : asset('storage/profile/' . $value->event->user->profile);
                    $eventDetail['event_wall'] = (isset($value->event->event_settings->event_wall)&&$value->event->event_settings->event_wall!="")?$value->event->event_settings->event_wall:"";
                    $eventDetail["guest_list_visible_to_guests"] = $value->event->event_settings->guest_list_visible_to_guests;
                    $eventDetail['guest_pending_count'] = getGuestRsvpPendingCount($value->event->id);
                    $eventDetail['event_potluck'] = (isset($value->event->event_settings->podluck)&&$value->event->event_settings->podluck!="")?$value->event->event_settings->podluck:"";
                    $eventDetail['adult_only_party'] = (isset($value->event->event_settings->adult_only_party)&&$value->event->event_settings->adult_only_party!="")?$value->event->event_settings->adult_only_party:"";
                    $eventDetail['host_name'] = $value->event->hosted_by;
                    $eventDetail['host_firstname'] = $value->event->user->firstname;
                    $eventDetail['host_lastname'] = $value->event->user->lastname;
                    $eventDetail['is_past'] = ($value->event->end_date < date('Y-m-d')) ? true : false;
                    $eventDetail['post_time'] =  $this->setupcomingpostTime($value->event->updated_at);
                    $eventDetail['is_gone_time'] = $this->evenGoneTime($value->event->end_date);
                    $eventDetail['allow_limit'] = (isset($value->event->event_settings->allow_limit)&&$value->event->event_settings->allow_limit!="")?$value->event->event_settings->allow_limit:"";
                    $images = EventImage::where('event_id', $value->event->id)->first();

                    $eventDetail['event_images'] = "";
                    if (!empty($images)) {
                        $eventDetail['event_images'] = asset('storage/event_images/' . $images->image);
                    }
                    $eventDetail['kids'] = 0;
                    $eventDetail['adults'] = 0;
                    $checkRsvpDone = EventInvitedUser::where(['event_id' => $value->event->id, 'user_id' => $user->id])->first();
                    if ($checkRsvpDone != null) {
                        $eventDetail['kids'] = $checkRsvpDone->kids;
                        $eventDetail['adults'] = $checkRsvpDone->adults;
                    }
                    $eventDetail['event_date'] = $value->event->start_date;
                    $eventDetail['event_date_only'] = Carbon::parse($value->event->start_date)->format('d');
                    $eventDetail['event_date_mon'] = Carbon::parse($value->event->start_date)->format('d M'); // "21 Nov"
                    $eventDetail['event_month'] = Carbon::parse($value->event->start_date)->format('M'); // "21 Nov"
                    $eventDetail['event_day'] = Carbon::parse($value->event->start_date)->format('l'); // "Monday"
                    // $event_time = "-";
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
                        if ($checkUserrsvp->rsvp_status == NULL) {
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
                        'profile' => empty($value->event->user->profile) ? "" : asset('storage/profile/' . $value->event->user->profile),
                        'bg_profile' => empty($value->event->user->bg_profile) ? "" : asset('storage/bg_profile/' . $value->event->user->bg_profile),
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

                    $eventDetail['event_plan_name'] = $value->event->subscription_plan_name;
                    $eventList[] = $eventDetail;
                 
                }
                if(!empty($eventList)){
                    $last_month=$eventList[0]['event_month'];
                }
                
            }
        }


        if($is_hosting==0 &&$invited_to==0 && $need_rsvp_to==0 ){
            if($page=="upcoming"){
                $usercreatedList = Event::with(['user', 'event_settings', 'event_schedule'])->where('start_date', '>=', date('Y-m-d'))
                ->where('user_id', $user->id)
                ->where('is_draft_save', '0');
                // ->orderBy('start_date', 'ASC')  
                // ->get();
                $invitedEvents = EventInvitedUser::whereHas('user', function ($query) {
                $query->where('app_user', '1');
                })->where('user_id', $user->id)->get()->pluck('event_id');
                $invitedEventsList = Event::with(['event_image', 'user', 'event_settings', 'event_schedule'])
                    ->whereIn('id', $invitedEvents)->where('start_date', '>=', date('Y-m-d'))
                    ->where('is_draft_save', '0');
                    // ->orderBy('start_date', 'ASC')
                    // ->get();
                $allEvents = $usercreatedList->union($invitedEventsList);
            }
            if($page=="past"){
                $usercreatedList = Event::with(['user', 'event_settings', 'event_schedule'])->where('end_date', '<', date('Y-m-d'))
                ->where('user_id', $user->id)
                ->where('is_draft_save', '0');
                // ->orderBy('start_date', 'ASC')  
                // ->get();
                $invitedEvents = EventInvitedUser::whereHas('user', function ($query) {
                $query->where('app_user', '1');
                })->where('user_id', $user->id)->get()->pluck('event_id');
                $invitedEventsList = Event::with(['event_image', 'user', 'event_settings', 'event_schedule'])
                    ->whereIn('id', $invitedEvents)->where('end_date', '<', date('Y-m-d'))
                    ->where('is_draft_save', '0');
                    // ->orderBy('start_date', 'ASC')
                    // ->get();
                $allEvents = $usercreatedList->union($invitedEventsList);
            }
        

            $allEvent = $allEvents
                        ->orderBy('start_date', 'asc')
                        ->offset(0)
                        ->limit(10) 
                        ->get();

                    if (count($allEvent) != 0) {
                        foreach ($allEvent as $value) {
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
                            $eventDetail['host_profile'] = empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile);
                            $eventDetail['event_wall'] = (isset($value->event_settings->event_wall)&&$value->event_settings->event_wall!="")?$value->event_settings->event_wall:"";
                            $eventDetail["guest_list_visible_to_guests"] =(isset( $value->event_settings->guest_list_visible_to_guests)&& $value->event_settings->guest_list_visible_to_guests!="")? $value->event_settings->guest_list_visible_to_guests:"";
                            $eventDetail['guest_pending_count'] = getGuestRsvpPendingCount($value->id);
                            $eventDetail['event_potluck'] = (isset($value->event_settings->podluck)&&$value->event_settings->podluck!="")?$value->event_settings->podluck:"";
                            $eventDetail['adult_only_party'] = (isset($value->event_settings->adult_only_party)&&$value->event_settings->adult_only_party!="")?$value->event_settings->adult_only_party:"";
                            $eventDetail['host_name'] = $value->hosted_by;
                            $eventDetail['host_firstname'] = $value->user->firstname;
                            $eventDetail['host_lastname'] = $value->user->lastname;
                            $eventDetail['is_past'] = ($value->end_date < date('Y-m-d')) ? true : false;
                            $eventDetail['post_time'] =  $this->setupcomingpostTime($value->updated_at);
                            $eventDetail['is_gone_time'] = $this->evenGoneTime($value->end_date);
                            $eventDetail['allow_limit'] = (isset($value->event_settings->allow_limit)&&$value->event_settings->allow_limit!="")?$value->event_settings->allow_limit:"";
                            $eventDetail['kids'] = 0;
                            $eventDetail['adults'] = 0;
            
                            $checkRsvpDone = EventInvitedUser::where(['event_id' => $value->id, 'user_id' => $user->id])->first();
                            if ($checkRsvpDone != null) {
                                $eventDetail['kids'] = $checkRsvpDone->kids;
                                $eventDetail['adults'] = $checkRsvpDone->adults;
                            }
                            $images = EventImage::where('event_id', $value->id)->first();
                            $eventDetail['event_images'] = ($images != null) ? asset('storage/event_images/' . $images->image) : "";
                            $eventDetail['event_date'] = $value->start_date;
                            $eventDetail['event_date_only'] = Carbon::parse($value->start_date)->format('d');
                            $eventDetail['event_date_mon'] = Carbon::parse($value->start_date)->format('d M'); // "21 Nov"
                            $eventDetail['event_month'] = Carbon::parse($value->start_date)->format('M'); // "21 Nov"
                            $eventDetail['event_day'] = Carbon::parse($value->start_date)->format('l'); // "Monday"
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
                                'profile' => empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile),
                                'bg_profile' => empty($value->user->bg_profile) ? "" : asset('storage/bg_profile/' . $value->user->bg_profile),
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
                            $eventDetail['event_plan_name'] = $value->subscription_plan_name;
            
                            $eventList[] = $eventDetail;
                            
                        }
                        if(!empty($eventList)){
                            $last_month = $eventList[0]['event_month'];
                        }
                      
                    }
        }


        if($page=="upcoming"){

            $collection = collect($eventList);
            $uniqueCollection = $collection->unique('id');
            $eventList = $uniqueCollection->values()->all();
           
            usort($eventList, function ($a, $b) {
                return strtotime($a['event_date']) - strtotime($b['event_date']);
            });

            // dd($eventList);

            return response()->json(['view' => view( 'front.event.event_list.upcoming_event', compact('eventList','get_current_month'))->render(),"page"=>$page,"last_month"=>$last_month]);
        }

        if($page=="past"){
            $collection = collect($eventList);
            $uniqueCollection = $collection->unique('id');
            $eventPasttList = $uniqueCollection->values()->all();
           
            usort($eventList, function ($a, $b) {
                return strtotime($a['event_date']) - strtotime($b['event_date']);
            });
            return response()->json(['view' => view( 'front.event.event_list.past_event', compact('eventPasttList','get_current_month'))->render(),"page"=>$page,"last_month"=>$last_month]);
        }

    }

    public function TotalMonthData(Request $request){

        $user  = Auth::guard('web')->user();
        $currentMonth = $request->input('current_month');

        list($month, $year) = explode(' ', $currentMonth);

        // dd($currentMonth);
        // $date = \DateTime::createFromFormat('F Y', $currentMonth);

        // if (!$date) {
        //     return response()->json(['error' => 'Invalid date format. Expected "F Y".'], 400);
        // }
    
        // $month = $month;  // 12
        // $year = $year; 

        // dd($month,$year);
        $month_data= TotalMonthData($user->id,$month,$year);
        // dd($data);

        return response()->json(['month_data'=>$month_data]);

    }
      
    public function EventFilterData(Request $request){
        $user  = Auth::guard('web')->user();
        
        $page= $request->input('page');

        $totalInvited = EventInvitedUser::whereHas('event', function ($query) use($page) {
            if($page=="upcoming"){
                $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'));
             }else{
                $query->where('is_draft_save', '0')->where('end_date', '<', date('Y-m-d'));
             }
        })->where('user_id', $user->id)->count();

        // $totalInvited = EventInvitedUser::whereHas('event', function ($query) use($input){
        //     $query->where('is_draft_save', '0')
        //     ->when($input['past_event'] == '1', function($que) {
        //         $que->where('end_date', '<', date('Y-m-d'));
        //     })    
        //     ->when($input['past_event'] == '0', function($que) {
        //         $que->where('start_date', '>=', date('Y-m-d'));
        //     });
        //     // ->where('start_date', '>=', date('Y-m-d'));
        // })->where('user_id', $user->id)->count();

        if($page=="upcoming"){
            $totalHosting = Event::where(['is_draft_save' => '0', 'user_id' => $user->id])->where('start_date', '>=', date('Y-m-d'))->count();
        }else{
            $totalHosting = Event::where(['is_draft_save' => '0', 'user_id' => $user->id])->where('end_date', '<', date('Y-m-d'))->count();
        }
        $total_need_rsvp_event_count = EventInvitedUser::whereHas('event', function ($query) use($page) {
            if($page=="upcoming"){
                $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'));
            }else{
                $query->where('is_draft_save', '0')->where('end_date', '<', date('Y-m-d'));
            }
        })->where(['user_id' => $user->id, 'rsvp_status' => NULL])->count();

        $filter = [
            'invited_to' => $totalInvited,
            'hosting' => $totalHosting,
            'need_to_rsvp' => $total_need_rsvp_event_count,
        ];     
        return response()->json($filter);
    }

    public function getEventDateList()
    {
        $user = Auth::guard('web')->user();
        $eventdata = Event::where(['is_draft_save' => '0', 'user_id' => $user->id])->get();
    
        $events = [];
        $color=['blue','orange','green','yellow'];
        $colorIndex = 0;
      
        foreach ($eventdata as $event) {
            $colorClass = $color[$colorIndex % count($color)];
            $colorIndex++;
            $events[] = [
                'date' => $event->start_date, 
                'name' => $event->event_name,    
                'color' => $colorClass    
            ];
        }
    
        return response()->json($events);
    }
    
    public function UpdateNotificationRead(Request $request){

        $user_id= $request->input('user_id');

        $notifications = Notification::where("user_id", $user_id)->get();

        foreach ($notifications as $notifcation) {
            $notifcation->read = "1";
            $notifcation->save();
        }
        


        $notifcationcount = Notification::where("user_id",$user_id)->where("read","0")->count();

        // dd($notifcationcount);

        

        return response()->json(['status'=>1,'count'=>$notifcationcount]);
        

    }

    public function notificationList(Request $request)
    {
        $user = Auth::guard('web')->user();
        // $rawData = $request->getContent();
        // $input = json_decode($rawData, true);
        // if ($input == null) {
        //     return response()->json(['status' => 0, 'message' => "Json invalid"]);
        // }
        $page = '1';
        $pages = ($page != "") ? $page : 1;
        $notificationData = Notification::query();
        $notificationData->with(['user', 'event', 'event.event_settings', 'sender_user', 'post' => function ($query) {
            $query->with(['post_image', 'event_post_poll'])
                ->withcount(['event_post_reaction', 'event_post_comment' => function ($query) {
                    $query->where('parent_comment_id', NULL);
                }]);
        }])->orderBy('id', 'DESC')->where(['user_id' => $user->id])->get();
        // ->where('notification_type', '!=', 'upload_post')->where('notification_type', '!=', 'photos')->where('notification_type', '!=', 'invite')
        // if (isset($input['filters']) && !empty($input['filters']) && !in_array('all', $input['filters'])) {

        //     $selectedEvents = $input['filters']['events'];
        //     $notificationTypes = $input['filters']['notificationTypes'];
        //     $activityTypes = $input['filters']['activityTypes'];

        //     $notificationData->where(function ($query) use ($selectedEvents, $notificationTypes, $activityTypes) {
        //         // Add conditions based on selected events
        //         if (!empty($selectedEvents)) {
        //             $query->whereIn('event_id', $selectedEvents);
        //         }

        //         // Add conditions based on notification types (read, unread)
        //         if (!empty($notificationTypes) && in_array('read', $notificationTypes)) {
        //             $query->orWhere('read', "1");
        //         }
        //         if (!empty($notificationTypes) && in_array('unread', $notificationTypes)) {
        //             $query->orWhere('read', "0");
        //         }

        //         // Add conditions based on activity types
        //         if (!empty($activityTypes)) {

        //             $query->whereIn('notification_type', $activityTypes);
        //         }
        //     });
        // }
        $notificationDatacount = $notificationData->count();
        $total_page = ceil($notificationDatacount / 10);
        $result = $notificationData->get();

        $notificationInfo = [];
            foreach ($result as $values) {
                if ($values->user_id == $user->id) {
                    $notificationDetail['event_name'] = $values->event->event_name;
                    $images = EventImage::where('event_id', $values->event->id)->first();

                    $notificationDetail['event_image'] = ($images != null) ? asset('storage/event_images/' . $images->image) : "";
                    // $eventDetail['event_images'] = ($images != null) ? asset('storage/event_images/' . $images->image) : "";

                    $notificationDetail['notification_id'] = $values->id;
                    $notificationDetail['notification_type'] = $values->notification_type;
                    $notificationDetail['user_id'] = $values->sender_id;
                    $notificationDetail['profile'] = (!empty($values->sender_user->profile) || $values->sender_user->profile != null) ? asset('storage/profile/' . $values->sender_user->profile) : "";
                    $notificationDetail['email'] = $values->sender_user->email;
                    $notificationDetail['first_name'] = $values->sender_user->firstname;
                    $notificationDetail['last_name'] = ($values->sender_user->lastname != null) ? $values->sender_user->lastname : "";
                    $notificationDetail['event_id'] = ($values->event_id != null) ? $values->event_id : 0;
                    $notificationDetail['post_id'] = ($values->post_id != null) ? $values->post_id : 0;
                    $notificationDetail['comment_id'] = ($values->comment_id != null) ? $values->comment_id : 0;
                    $comment_reply_id = EventPostComment::where('parent_comment_id', $values->comment_id)->orderBy('id', 'DESC')->select('id')->first();
                    $notificationDetail['reply_comment_id'] = 0;
                    $postCommentDetail =  EventPostComment::where(['id' => $values->comment_id])->first();
                    if (isset($postCommentDetail->main_parent_comment_id) && $postCommentDetail->main_parent_comment_id != null) {
                        $commentText = EventPostComment::where('id', $postCommentDetail->main_parent_comment_id)->first();
                        $notificationDetail['comment'] = ($commentText != null) ? $commentText->comment_text : "";
                        $notificationDetail['reply_comment_id'] = $values->comment_id;
                        $notificationDetail['comment_id'] = isset($postCommentDetail->main_parent_comment_id) ? $postCommentDetail->main_parent_comment_id : 0;
                        $notificationDetail['comment_reply'] = ($postCommentDetail != null) ? $postCommentDetail->comment_text : "";
                    } else {
                        $notificationDetail['comment'] = ($postCommentDetail != null) ? $postCommentDetail->comment_text : "";
                        $notificationDetail['comment_reply'] = "";
                    }
                    $notificationDetail['video'] = ($postCommentDetail != null && $postCommentDetail->video != null) ? asset('storage/comment_video' . $postCommentDetail->video) : "";
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
                    $notificationDetail['event_wall'] = (isset($values->event->event_settings->event_wall)&&$values->event->event_settings->event_wall!="")?$values->event->event_settings->event_wall:"";
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
                        $notificationDetail['video'] = ($values->rsvp_video != null && $values->rsvp_video != null) ? asset('storage/rsvp_video/' . $values->rsvp_video) : "";
                    }
                    if (isset($values->post->post_type) && $values->post->post_type == '1') {
                        if (isset($values->post->post_image[0]->type) && $values->post->post_image[0]->type == 'video') {
                            $notificationDetail['video'] = asset('storage/post_image/' . $values->post->post_image[0]->post_image);
                            $notificationDetail['media_type'] = $values->post->post_image[0]->type;
                        }
                    }
                    // if ($values->notification_type == 'reply_on_comment_post') {
                    //     $comment_reply_id = EventPostComment::where('parent_comment_id', $values->comment_id)->orderBy('id', 'DESC')->select('id')->first();
                    //     $notificationDetail['reply_comment_id'] = (isset($comment_reply_id->id) && $comment_reply_id->id != null) ? $comment_reply_id->id : 0;
                    // }
                    $notificationDetail['total_likes'] = (!empty($values->post->event_post_reaction_count)) ? $values->post->event_post_reaction_count : 0;
                    $notificationDetail['total_comments'] = (!empty($values->post->event_post_comment_count)) ? $values->post->event_post_comment_count : 0;
                    $postreplyCommentDetail =  EventPostComment::where(['user_id' => $values->sender_id, 'parent_comment_id' => $values->comment_id])->first();
                    // $notificationDetail['comment_reply'] = ($values->notification_type == 'reply_on_comment_post' && $postreplyCommentDetail != null) ? $postreplyCommentDetail->comment_text : "";
                    $notificationDetail['post_image'] = "";
                    $notificationDetail['media_type'] = "";
                    $notificationDetail['is_post_by_host'] = 0;
                    if (isset($values->post->user_id) && isset($values->event->user_id)) {
                        if ($values->post->user_id == $values->event->user_id) {
                            $notificationDetail['is_post_by_host'] = 1;
                        }
                    }
                    if (isset($values->post->post_type) && $values->post->post_type == '1' && isset($values->post->post_image[0]->type)) {
                        $notificationDetail['post_image'] = asset('storage/post_image/' . $values->post->post_image[0]->post_image);
                        if (isset($values->post->post_image[0]->type) &&  $values->post->post_image[0]->type == 'image') {
                            $notificationDetail['media_type'] = 'photo';
                        } elseif (isset($values->post->post_image[0]->type) && $values->post->post_image[0]->type == 'video') {
                            $notificationDetail['media_type'] = (isset($values->post->post_image[0]->type) && $values->post->post_image[0]->type != '') ? $values->post->post_image[0]->type : '';
                        }
                    }
                    $notificationDetail['post_type'] = "";
                    if (isset($values->post->post_type)) {
                        $notificationDetail['post_type'] = $values->post->post_type;
                    }
                    $notificationDetail['post_message'] = (!empty($values->post->post_message)) ? $values->post->post_message : "";
                    $notificationDetail['notification_message'] = $values->notification_message;
                    $notificationDetail['read'] = $values->read;
                    $notificationDetail['post_time'] = $this->setupcomingpostTime($values->created_at);
                    $notificationDetail['created_at'] = $values->created_at;
                    $checkrsvp =  EventInvitedUser::where(['user_id' => $values->user_id, 'event_id' => $values->event_id])->first();
                    if (!empty($checkrsvp)) {
                        $notificationDetail['rsvp_status'] =  (isset($checkrsvp->rsvp_status) || $checkrsvp->rsvp_status != null) ? $checkrsvp->rsvp_status : "";
                    } else {
                        $notificationDetail['rsvp_status'] = '';
                    }
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
                        'profile' => empty($values->sender_user->profile) ? "" : asset('storage/profile/' . $values->sender_user->profile),
                        'bg_profile' => empty($values->sender_user->bg_profile) ? "" : asset('storage/bg_profile/' . $values->sender_user->bg_profile),
                        'gender' => ($values->sender_user->gender != NULL) ? $values->sender_user->gender : "",
                        'username' => $values->sender_user->firstname . ' ' . $values->sender_user->lastname,
                        'location' => ($values->sender_user->city != NULL) ? $values->sender_user->city : "",
                        'about_me' => ($values->sender_user->about_me != NULL) ? $values->sender_user->about_me : "",
                        'created_at' => empty($values->sender_user->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($values->sender_user->created_at))),
                        'total_events' => $totalEvent,
                        'visible' => $values->sender_user->visible,
                        'comments' => $comments,
                    ];
                    $notificationInfo[$values->event_id] = $notificationDetail;
                }


                foreach($notificationInfo as $notify_data){
                    if ($values->event_id === $notify_data['event_id']) {
                        $final_data[$values->event->event_name][] = $notify_data; 
                    }
                }
            }
        $unreadCount = Notification::where(['user_id' => $user->id, 'read' => '0'])->count();
        // dd($notificationInfo);
        return response()->json(['status' => 1, 'unread_count' => $unreadCount,'data' => $final_data, 'message' => "Notification list"]);
    }


    public function mark_as_read(Request $request)
    {
        $eventId=$request->event_id;
        $user = Auth::guard('web')->user();
        $notify=Notification::where(['event_id' => $eventId, 'user_id' => $user->id])->update(['read' => '1']);

 
    }

    public function store_rsvp(Request $request)
    {
        try {
            $userId = $request->rsvp_user_id;
              $eventId = $request->rsvp_event_id;
              $adults =($request->rsvp_notification_adult!=null)?intval($request->rsvp_notification_adult) :0;
              $kids =  ($request->rsvp_notification_kids!=null)?intval($request->rsvp_notification_kids) :0;
             $rsvp_status= $request->rsvp_status;
            //   dd($userId,$eventId,$adults,$kids);
    $rsvpSent = EventInvitedUser::whereHas('user', function ($query) {
            // $query->where('app_user', '1');s
        })->where(['event_id' => $eventId,'user_id'=>$userId])->first();


$rsvpSentAttempt = $rsvpSent ? $rsvpSent->rsvp_status : "";
// dd($rsvpSentAttempt,$rsvp_status);
if ($rsvpSent != null) {
    $rsvp_attempt = "";
    if ($rsvpSentAttempt == NULL) { 
        $rsvp_attempt =  'first';
    } else if ($rsvpSentAttempt == '0' && $rsvp_status == '1') {
        $rsvp_attempt =  'no_to_yes';
    } else if ($rsvpSentAttempt == '1' && $rsvp_status == '0') {
        $rsvp_attempt =  'yes_to_no';
    }

    if($rsvpSentAttempt=="1"&&$rsvp_status=="1"){
        return response()->json(['status' => 3, 'text' => 'You have already done rsvp yes']);
    }

    if($rsvpSentAttempt=="0"&&$rsvp_status=="0"){
        return response()->json(['status' => 3, 'text' => 'You have already done rsvp No']);
    }
    $rsvpSent->event_id = $request->rsvp_event_id;

    $rsvpSent->user_id = $request->rsvp_user_id;
  
    $rsvpSent->rsvp_status = $request->rsvp_status;

    $rsvpSent->adults = $adults;

    $rsvpSent->kids = $kids;

    $rsvpSent->message_to_host = $request->rsvp_notification_message;
    $rsvpSent->rsvp_attempt = $rsvp_attempt;

    $rsvpSent->read = '1';
    $rsvpSent->rsvp_d = '1';

    $rsvpSent->event_view_date = date('Y-m-d');

    $rsvpSent->save();

    if ($rsvpSent->save()) {
        EventPost::where('event_id', $eventId)
            // ->where('user_id', $userId)
            ->where(function ($query) use ($userId) {
            
                    $query->where('user_id', $userId);
              
            })
        
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
    $notificationParam = [
        'sync_id'=>"",
        'sender_id' => $userId,
        'event_id' => $eventId,
        'rsvp_status' => $request->rsvp_status,
        'kids' =>  $kids,
        'adults' => $adults,
        'rsvp_video' => "",
        'rsvp_message' => $request->rsvp_notification_message,
        'post_id' => "",
        'rsvp_attempt' => $rsvp_attempt
    ];


        sendNotification('sent_rsvp', $notificationParam);   


    if ($request->rsvp_status == "1") {
        return response()->json(['status' => 1, 'text' => 'You are going to this event']);
        // return redirect()->to($url)->with('msg', 'You are going to this event');
    } elseif ($request->rsvp_status == "0") {
        return response()->json(['status' => 0, 'text' => 'You are not going to this event']);
        // return redirect()->to($url)->with('msg', 'You are going to this event');
    }
    
  
}

} catch (QueryException $e) {
} catch (\Exception $e) {
}



// foreach($a as $b){

//     $data[$b->event_id]="";

//     foreach($c as $d){

//     }
    }

    public function filter_search_event(Request $request){
        $eventName=$request->search_event;
        $eventList = [];
        $user_id  = Auth::guard('web')->user()->id;

        $eventData = EventInvitedUser::where(['user_id' => $user_id])->get();

        $eventList = [];
        
        foreach ($eventData as $val) {
    
        $eventDatas =   Event::select('id', 'event_name')->where('id', $val->event_id)->where('event_name', 'like', "%$eventName%")->get();
          
            foreach ($eventDatas as $vals) {
                $eventDetail['id'] = $vals->id;
                $eventDetail['event_name'] = $vals->event_name;
                $eventList[] = $eventDetail;
            }
        }
        if(empty($eventList)){
            return response()->json(['view' => 'No Data Found']);
        }
        return response()->json(['view' => view( 'front.notification.search_filter_event', compact('eventList'))->render()]);


    }
    public function reset_notification_eventId(){
        Session::forget('notification_event_ids');
    }
}

