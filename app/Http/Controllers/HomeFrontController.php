<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Mail\NotifyPendingInvitation;
use Illuminate\Support\Facades\Mail;

class HomeFrontController extends Controller
{
    public function index()
    {

        $eventData = Event::with(['user', 'event_schedule', 'event_image'])->where('is_draft_save', '1')->get();
        $currentDate = Carbon::now()->toDateString();
        if (count($eventData) != 0) {

            foreach ($eventData as $value) {

                $dateAfterSevenDays = Carbon::parse($value->created_at)->addDays(7)->toDateString();

                if ($dateAfterSevenDays > $currentDate) {
                    $event_time = "";
                    if ($value->event_schedule->isNotEmpty()) {

                        $event_time =  $value->event_schedule->first()->start_time;
                    }

                    $eventData = [
                        'event_name' => $value->event_name,
                        'event_image' => ($value->event_image->isNotEmpty()) ? $value->event_image[0]->image : "no_image.png",
                        'date' =>   date('l - M jS, Y', strtotime($value->start_date)),
                        'time' => $event_time,
                    ];

                    $invitation_email = new NotifyPendingInvitation($eventData);
                    Mail::to($value->user->email)->send($invitation_email);
                }
            }
        }


        // $title = 'Home';
        // $page = 'front.homefront';
        // return view('layout', compact(
        //     'title',
        //     'page',
        // ));
    }
}
