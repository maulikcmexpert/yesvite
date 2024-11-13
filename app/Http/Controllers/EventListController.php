<?php

namespace App\Http\Controllers;
use App\Models\{
    Event,
    EventPost,
    EventPostComment,
    EventInvitedUser,
    Notification,
    EventImage
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
    public function index()
    {
                $user  = Auth::guard('web')->user();

                $usercreatedAllEventList = Event::query();
                $usercreatedAllEventList->with(['event_image', 'event_settings', 'user', 'event_schedule'])
                    ->where('user_id', $user->id)
                    ->where('is_draft_save', '0');
                $usercreatedAllEventList->where('start_date', ">=", date('Y-m-d'));
                $usercreatedAllEventList->orderBy('start_date', 'ASC');
                $invitedEvents = EventInvitedUser::whereHas('user', function ($query) {
                    $query->where('app_user', '1');
                })->where('user_id', $user->id)->get()->pluck('event_id');
                $invitedEventsList = Event::query();
                $invitedEventsList->with(['event_image', 'event_settings', 'user', 'event_schedule'])
                    ->whereIn('id', $invitedEvents);
                $invitedEventsList->where('start_date', ">=", date('Y-m-d'));
                $invitedEventsList->where('is_draft_save', '0')
                    ->orderBy('start_date', 'ASC');
                $allEvent = $usercreatedAllEventList->union($invitedEventsList)->get();
                $totalCounts=0;
                $totalCounts += count($allEvent);
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
                        $eventDetail['event_images'] = ($images != null) ? asset('storage/event_images/' . $images->image) : "";
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
                    
                        $filter = [
                            'invited_to' => $totalInvited,
                            'hosting' => $totalHosting,
                            'need_to_rsvp' => $total_need_rsvp_event_count,
                            'past_event'=>$totalPastEventCount
                        ];  
                        
                    }
                   
                }


                $usercreatedPastEventList = Event::query();
                $usercreatedPastEventList->with(['event_image', 'event_settings', 'user', 'event_schedule'])
                    ->where('user_id', $user->id)
                    ->where('is_draft_save', '0');
                $usercreatedPastEventList->where('start_date', "<", date('Y-m-d'));
                $usercreatedPastEventList->orderBy('start_date', 'ASC');
                $invitedEvents = EventInvitedUser::whereHas('user', function ($query) {
                    $query->where('app_user', '1');
                })->where('user_id', $user->id)->get()->pluck('event_id');
                $invitedEventsList = Event::query();
                $invitedEventsList->with(['event_image', 'event_settings', 'user', 'event_schedule'])
                    ->whereIn('id', $invitedEvents);
                $invitedEventsList->where('start_date', "<", date('Y-m-d'));
                $invitedEventsList->where('is_draft_save', '0')
                    ->orderBy('start_date', 'ASC');
                $allEvent = $usercreatedPastEventList->union($invitedEventsList)->get()->take(10);
                $totalCounts=0;
                $totalCounts += count($allEvent);
                if (count($allEvent) != 0) {

                    foreach ($allEvent as $value) {
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
                        $eventPastDetail['event_wall'] = $value->event_settings->event_wall;
                        $eventPastDetail["guest_list_visible_to_guests"] = $value->event_settings->guest_list_visible_to_guests;
                        $eventPastDetail['event_potluck'] = $value->event_settings->podluck;
                        $eventPastDetail['guest_pending_count'] = getGuestRsvpPendingCount($value->id);
                        $eventPastDetail['adult_only_party'] = $value->event_settings->adult_only_party;
                        $eventPastDetail['host_name'] = $value->hosted_by;
                        $eventPastDetail['allow_limit'] = $value->event_settings->allow_limit;
                        $eventPastDetail['is_past'] = ($value->end_date < date('Y-m-d')) ? true : false;
                        $eventPastDetail['is_gone_time'] = $this->evenGoneTime($value->end_date);
                        $eventPastDetail['post_time'] =  $this->setpostTime($value->updated_at);
                        $eventPastDetail['kids'] = 0;
                        $eventPastDetail['adults'] = 0;

                        $checkRsvpDone = EventInvitedUser::where(['event_id' => $value->id, 'user_id' => $user->id])->first();
                        if ($checkRsvpDone != null) {
                            $eventPastDetail['kids'] = $checkRsvpDone->kids;
                            $eventPastDetail['adults'] = $checkRsvpDone->adults;
                        }
                        $images = EventImage::where('event_id', $value->id)->first();
                        $eventPastDetail['event_images'] = ($images != null) ? asset('storage/event_images/' . $images->image) : "";
                        $eventPastDetail['event_date'] = $value->start_date;

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
                    
                        $draftEvents = Event::where(['user_id' => $user->id, 'is_draft_save' => '1'])->orderBy('id', 'DESC')->get();
                        $draftEventArray = [];
                        if (!empty($draftEvents) && count($draftEvents) != 0) {
                            foreach ($draftEvents as $value) {
                                $eventDraftDetail['id'] = $value->id;
                                $eventDraftDetail['event_name'] = $value->event_name;
                                // $formattedDate = Carbon::createFromFormat('Y-m-d H:i:s', $value->updated_at)->format('F j, Y');
                                $formattedDate = Carbon::createFromFormat('Y-m-d H:i:s', $value->updated_at)->format('F j, Y - g:i A');
                                $eventDraftDetail['saved_date'] = $formattedDate;
                                $eventDraftDetail['step'] = ($value->step != NULL) ? $value->step : 0;
                                $draftEventArray[] = $eventDraftDetail;
                            }
                            $eventDraftdata= $draftEventArray;
                        } else {
                            $eventDraftdata= "";
                        }

                        
                        $filter = [
                            'invited_to' => $totalInvited,
                            'hosting' => $totalHosting,
                            'need_to_rsvp' => $total_need_rsvp_event_count,
                            'past_event'=>$totalPastEventCount
                        ];  
                        
                    }
                   
                }
                dd($filter['need_to_rsvp']);
                return compact('filter','eventList','eventPasttList','eventDraftdata');
    }

    public function evenGoneTime($enddate)
    {
        $eventEndDate = $enddate; 
        $currentDate = Carbon::today();
        $endDateTime = Carbon::parse($eventEndDate);
        $hoursElapsed = $endDateTime->diffInHours($currentDate, false); 
        return $hoursElapsed;
    }

    function setpostTime($dateTime)
    {
        $commentDateTime = $dateTime; 
        $commentTime = Carbon::parse($commentDateTime);
        $timeAgo = $commentTime->diffForHumans();
        return $timeAgo;
    }

}
