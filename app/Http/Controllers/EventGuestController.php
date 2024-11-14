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
    public function index(string $id){
        $user  = Auth::guard('web')->user();
      
        $event_id=$id;
        if ($event_id == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        try {
            $eventDetail = Event::with(['user', 'event_settings', 'event_image', 'event_schedule' => function ($query) {}])->where('id', $event_id)->first();
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
            })->where(['event_id' => $eventDetail->id, 'rsvp_status' => '1', 'rsvp_d' => '1'])->sum('adults');

            $kids = EventInvitedUser::whereHas('user', function ($query) {
                $query->where('app_user', '1');
            })->where(['event_id' => $eventDetail->id, 'rsvp_status' => '1', 'rsvp_d' => '1'])->sum('kids');

            $eventAboutHost['is_event_owner'] = ($eventDetail->user_id == $user->id) ? 1 : 0;
            $eventAboutHost['event_wall'] = $eventDetail->event_settings->event_wall;
            $eventAboutHost['guest_list_visible_to_guests'] = $eventDetail->event_settings->guest_list_visible_to_guests;
            $eventAboutHost['attending'] = $adults + $kids;
            $eventAboutHost['total_invitation'] =  count(getEventInvitedUser($event_id));
            $eventAboutHost['adults'] = (int)$adults;
            $eventAboutHost['kids'] =  (int)$kids;
            $eventAboutHost['not_attending'] = $eventNotComing;
            $eventAboutHost['pending'] = $pendingUser;
            $eventAboutHost['allow_limit'] = $eventDetail->event_settings->allow_limit;
            $eventAboutHost['adult_only_party'] = $eventDetail->event_settings->adult_only_party;
            $eventAboutHost['subscription_plan_name'] = ($eventDetail->subscription_plan_name != NULL) ? $eventDetail->subscription_plan_name : "";
            $eventAboutHost['subscription_invite_count'] = ($eventDetail->subscription_invite_count != NULL) ? $eventDetail->subscription_invite_count : 0;
            $eventAboutHost['is_past'] = ($eventDetail->end_date < date('Y-m-d')) ? true : false;

            $userRsvpStatusList = EventInvitedUser::query();
            $userRsvpStatusList->whereHas('user', function ($query) {
                $query->where('app_user', '1');
            })->where(['event_id' => $eventDetail->id, 'invitation_sent' => '1'])->get();
            // $selectedFilters = $request->input('filters');
            // if (!empty($selectedFilters) && !in_array('all', $selectedFilters)) {

            //     $userRsvpStatusList->where(function ($query) use ($selectedFilters) {
            //         foreach ($selectedFilters as $filter) {

            //             switch ($filter) {
            //                 case 'attending':
            //                     $query->orWhere('rsvp_status', '1');
            //                     break;
            //                 case 'not_attending':
            //                     $query->orWhere(function ($qury) {
            //                         $qury->where(['rsvp_status' => '0']);
            //                     });
            //                     break;
            //                 case 'no_reply':
            //                     $query->orWhere(function ($qury) {
            //                         $qury->where(['rsvp_status' => NULL]);
            //                     });
            //                     break;
            //             }
            //         }
            //     });
            // }
            $result = $userRsvpStatusList->get();
            $eventAboutHost['rsvp_status_list'] = [];
            if (count($result) != 0) {
                foreach ($result as $value) {
                    $rsvpUserStatus = [];
                    $rsvpUserStatus['id'] = $value->id;
                    $rsvpUserStatus['user_id'] = $value->user->id;
                    $rsvpUserStatus['first_name'] = $value->user->firstname;
                    $rsvpUserStatus['last_name'] = $value->user->lastname;
                    $rsvpUserStatus['username'] = $value->user->firstname . ' ' . $value->user->lastname;
                    $rsvpUserStatus['profile'] = (!empty($value->user->profile) || $value->user->profile != NULL) ? asset('storage/profile/' . $value->user->profile) : "";
                    $rsvpUserStatus['email'] = ($value->user->email != '') ? $value->user->email : "";
                    $rsvpUserStatus['phone_number'] = ($value->user->phone_number != '') ? $value->user->phone_number : "";
                    $rsvpUserStatus['prefer_by'] =  $value->prefer_by;
                    $rsvpUserStatus['kids'] = $value->kids;
                    $rsvpUserStatus['adults'] = $value->adults;
                    $rsvpUserStatus['rsvp_status'] =  ($value->rsvp_status != null) ? (int)$value->rsvp_status : NULL;
                    if ($value->rsvp_d == '0' && ($value->read == '1' || $value->read == '0') || $value->rsvp_status == null) {
                        $rsvpUserStatus['rsvp_status'] = 2; // no reply 
                    }
                    $rsvpUserStatus['read'] = $value->read;
                    $rsvpUserStatus['rsvp_d'] = $value->rsvp_d;
                    $rsvpUserStatus['invitation_sent'] = $value->invitation_sent;
                    $totalEvent =  Event::where('user_id', $value->user->id)->count();
                    $totalEventPhotos =  EventPost::where(['user_id' => $value->user->id, 'post_type' => '1'])->count();
                    $comments =  EventPostComment::where('user_id', $value->user->id)->count();
                    $rsvpUserStatus['user_profile'] = [
                        'id' => $value->user->id,
                        'profile' => empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile),
                        'bg_profile' => empty($value->user->bg_profile) ? "" : asset('storage/bg_profile/' . $value->user->bg_profile),
                        'app_user' =>  $value->user->app_user,
                        'gender' => ($value->user->gender != NULL) ? $value->user->gender : "",
                        'first_name' => $value->user->firstname,
                        'last_name' => $value->user->lastname,
                        'username' => $value->user->firstname . ' ' . $value->user->lastname,
                        'location' => ($value->user->city != NULL) ? $value->user->city : "",
                        'about_me' => ($value->user->about_me != NULL) ? $value->user->about_me : "",
                        'created_at' => empty($value->user->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($value->user->created_at))),
                        'total_events' => $totalEvent,
                        'visible' => $value->user->visible,
                        'total_photos' => $totalEventPhotos,
                        'comments' => $comments,
                        'message_privacy' =>  $value->user->message_privacy
                    ];
                    $eventAboutHost['rsvp_status_list'][] = $rsvpUserStatus;
                }
            }
            $getInvitedusers = getInvitedUsers($event_id);
            $eventAboutHost['invited_user_id'] = $getInvitedusers['invited_user_id'];
            $eventAboutHost['invited_guests'] = $getInvitedusers['invited_guests'];
            //  event about view //
            $getEventData = Event::with('event_schedule')->where('id', $event_id)->first();
            $eventAboutHost['remaining_invite_count'] = ($getEventData->subscription_invite_count != NULL) ? ($getEventData->subscription_invite_count - (count($eventAboutHost['invited_user_id']) + count($eventAboutHost['invited_guests']))) : 0;
           
            $totalEnvitedUser = EventInvitedUser::whereHas('user', function ($query) {
                $query->where('app_user', '1');
            })->where(['event_id' => $eventDetail->id])->count();
           
            $todayrsvprate = EventInvitedUser::whereHas('user', function ($query) {
                $query->where('app_user', '1');
            })->where(['rsvp_status' => '1', 'event_id' => $eventDetail->id])
                ->whereDate('created_at', '=', date('Y-m-d'))
                ->count();

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
            
            return compact('eventAboutHost'); 
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['status' => 0, 'message' => "error"]);
        }
    }
}
