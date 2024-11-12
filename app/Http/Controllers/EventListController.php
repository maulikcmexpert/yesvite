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
                // Use union to combine the results of the two queries
                $allEvent = $usercreatedAllEventList->union($invitedEventsList)->get();
                $totalCounts=0;
                $totalCounts += count($allEvent);
                // Calculate offset based on current page and perPage
                // $offset = ($pages - 1) * $this->perPage;
                // $paginatedEvents =  collect($allEvent)->sortBy('start_date')->forPage($page, $this->perPage);
                // $paginatedEvents =  collect($allEvent)->sortBy('start_date');
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

                        $eventList[] = $eventDetail;
                    }
                }

                foreach ($eventList as $event) {
                    $eventDetails = $event['event_detail'];
            
                    foreach ($eventDetails as $detail) {
                    //    $data[]=$detail[];
                       dd($detail->id);
                    }
                }
                dd($data);
    }

    public function evenGoneTime($enddate)
    {

        $eventEndDate = $enddate; 
        // Get current date
        $currentDate = Carbon::today();
        // Get end date of the event
        $endDateTime = Carbon::parse($eventEndDate);
        // Calculate the difference in hours
        $hoursElapsed = $endDateTime->diffInHours($currentDate, false); // Passing false for negative value
        return $hoursElapsed;
    }

    function setpostTime($dateTime)
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
