<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use App\Models\EventInvitedUser;

class Dashboard extends Controller
{

    function checkEmailverifyhtml()
    {
        $userDetails = User::where('id', 39)->first();

        $number = 4512; // Replace with your four-digit number

        $digit1 = substr($number, 0, 1); // Extract first digit
        $digit2 = substr($number, 1, 1); // Extract second digit
        $digit3 = substr($number, 2, 1); // Extract third digit
        $digit4 = substr($number, 3, 1); // Extract fourth digit


        $userData = [
            'username' => $userDetails->firstname . ' ' . $userDetails->lastname,
            'email' => $userDetails->email,
            'digit1' => $digit1,
            'digit2' => $digit2,
            'digit3' => $digit3,
            'digit4' => $digit4
        ];
        return view('emails.forgotpasswordMail', compact('userData'));
    }

    function index(Request $req)
    {

        $title = 'Dashboard';
        $page = 'admin.dashboard.dashboard';
        $total_users = User::where('account_type', '0')->count();
        $total_professional_users = User::where('account_type', '1')->count();
        $total_events = Event::count();


        $total_held_events_avg = 0;

        $total_held_events = Event::where('end_date', '<', date('Y-m-d'))->count();
        if ($total_held_events != 0) {

            $total_held_events_avg = $total_held_events / $total_events * 100;
        }
        $js = "admin.dashboard.dashboardjs";
        return view('admin.includes.layout', compact(
            'title',
            'page',
            'total_users',
            'total_professional_users',
            'total_events',
            'total_held_events',
            'total_held_events_avg',

            'js'
        ));
    }

    public function getUpcomingEvent(Request $request)
    {


        $perPage = 4;
        if (isset($request->date)) {
            $eventdate = date('Y-m-d', strtotime($request->date));

            $totalUpcomingEvents = Event::where('start_date', '>', date('Y-m-d'))
                ->with([
                    'event_invited_user' => function ($query) {
                        $query->whereHas('user', function ($q) {
                            $q->where('app_user', '1');
                        })->where('rsvp_status', '1');
                    }
                ])
                ->withCount([
                    'event_invited_user as total_adults' => function ($query) {
                        $query->selectRaw('SUM(adults) as total_adults')->whereHas('user', function ($q) {
                            $q->where('app_user', '1');
                        })->where('rsvp_status', '1');
                    },
                    'event_invited_user as total_kids' => function ($query) {
                        $query->selectRaw('SUM(kids) as total_kids')->whereHas('user', function ($q) {
                            $q->where('app_user', '1');
                        })->where('rsvp_status', '1');
                    }
                ])
                ->where('start_date', $eventdate)
                ->paginate($perPage, ['*'], 'page', 1);
        } else {
            $totalUpcomingEvents = Event::where('start_date', '>', date('Y-m-d'))
                ->with([
                    'event_invited_user' => function ($query) {
                        $query->whereHas('user', function ($q) {
                            $q->where('app_user', '1');
                        })->where('rsvp_status', '1');
                    }
                ])
                ->withCount([
                    'event_invited_user as total_adults' => function ($query) {
                        $query->selectRaw('SUM(adults) as total_adults')->whereHas('user', function ($q) {
                            $q->where('app_user', '1');
                        })->where('rsvp_status', '1');
                    },
                    'event_invited_user as total_kids' => function ($query) {
                        $query->selectRaw('SUM(kids) as total_kids')->whereHas('user', function ($q) {
                            $q->where('app_user', '1');
                        })->where('rsvp_status', '1');
                    }
                ])
                ->paginate($perPage, ['*'], 'page', 1);
        }


        $html = "";

        foreach ($totalUpcomingEvents as $value) {

            $totalInvited = EventInvitedUser::with(['user' => function ($query) {
                $query->where('app_user', '1');
            }])->where(['event_id' => $value->id])->count();
            $avg = 0;
            if ($totalInvited != 0) {

                $avg = $value->total_adults + $value->total_kids / $totalInvited;
            }
            $html .= '<div class="col-xl-6 col-lg-12 col-md-12 col-sm-6 mb-4">
            <div class="align-items-start event-list">
                <div class="event-list-date">
                    <h2>' . date('d', strtotime($value->start_date)) . '</h2>
                    <h5>' . date('D', strtotime($value->start_date)) . '</h5>
                </div>
                <div class="event-list-content">
                    <h6 class=""><a class="text-black" href="">' . $value->event_name . '</a></h6>
                    <ul class="d-flex justify-content-between">
                        <li>User RSVP</li>
                        <li>' . $value->total_adults + $value->total_kids . '/' . $totalInvited . '</li>
                    </ul>
                    <div class="progress mb-0" style="height:4px; width:100%;">
                        <div class="progress-bar bg-warning progress-animated" style="width:' . $avg . '%;" role="progressbar">
                            <span class="sr-only">' . $avg . '% Complete</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
        }

        echo $html;
    }
}
