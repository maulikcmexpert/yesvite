<?php

namespace App\Http\Controllers;

use App\Models\Event;
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
        return view('layout', compact(
            'title',
            'page',
            'event'
        ));
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
