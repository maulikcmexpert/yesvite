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

        $event = Event::with(['event_image', 'event_settings'])->where('id', $eventId)->first();
        if ($event != null) {
            $isInvited = EventInvitedUser::where(['event_id' => $eventId, 'user_id' => $userId])->first();
            if ($isInvited != null) {


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
