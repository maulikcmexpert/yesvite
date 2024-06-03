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
        $eventData = Event::with(['user', 'event_schedule', 'event_image'])
            ->where('start_date', '>', now())
            ->where(['is_draft_save' => '0'])->get();

        $currentDate = Carbon::now()->toDateString();
        $eventsWithDayDifference = $eventData->map(function ($event) use ($currentDate) {
            // Calculate the difference in days
            $daysDifference = $currentDate->diffInDays(Carbon::parse($event->start_date), false);

            // Add the days difference to the event object (optional)
            $event->days_difference = $daysDifference;

            return $event;
        });

        dd($eventsWithDayDifference);
        $title = 'Home';
        $page = 'front.homefront';
        return view('layout', compact(
            'title',
            'page',
        ));
    }
}
