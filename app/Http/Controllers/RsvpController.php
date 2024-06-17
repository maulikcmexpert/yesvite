<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventInvitedUser;
use Illuminate\Http\Request;

class RsvpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($userId, $eventId)
    {
        $title = 'RSVP';
        $page = 'front.rsvp';

        $event = Event::with(['event_image', 'event_settings'])->where('id', decrypt($eventId))->first();

        if ($event != null) {

            $isInvited = EventInvitedUser::where(['event_id' => decrypt($eventId), 'user_id' => decrypt($userId)])->first();
            if ($isInvited != null) {

                if ($event->event_settings) {
                    $eventData = [];

                    if ($event->event_settings->allow_for_1_more == '1') {
                        $eventData[] = "Can Bring Guests ( limit " . $event->event_settings->allow_limit . ")";
                    }
                    if ($event->event_settings->adult_only_party == '1') {
                        $eventData[] = "Adults Only";
                    }
                    if ($event->rsvp_by_date_set == '1') {
                        $eventData[] = 'RSVP By :- ' . date('F d, Y', strtotime($event->rsvp_by_date));
                    }
                    if ($event->event_settings->podluck == '1') {
                        $eventData[] = "Event Potluck";
                    }
                    if ($event->event_settings->gift_registry == '1') {
                        $eventData[] = "Gift Registry";
                    }
                    if (empty($eventData)) {
                        $eventData[] = date('F d, Y', strtotime($event->start_date));
                        $numberOfGuest = EventInvitedUser::where('event_id', $event->id)->count();
                        $eventData[] = "Number of guests : " . $numberOfGuest;
                    }
                    $event['event_detail'] = $eventData;
                }


                return view('layout', compact(
                    'title',
                    'page',
                    'event'
                ));
            }
            return redirect('home')->with('error', 'You are not connect with this event');
        }
        return redirect('home')->with('error', 'You are not inivted in this event');
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
        //
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
}
