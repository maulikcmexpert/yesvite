<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventGiftRegistry;
use App\Models\EventInvitedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as Exception;

class RsvpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($userId, $eventId)
    {
        $title = 'RSVP';
        $page = 'front.rsvp';


        $event_id =  $eventId;
        $user_id = $userId;
        $event = Event::with(['user', 'event_image', 'event_settings'])->where('id', decrypt($eventId))->first();

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

                $event['profile'] =  ($event->user->profile != null) ? asset('storage/profile/' . $event->user->profile) : asset('assets/front/image/Frame 1000005835.png');


                $giftRegistryDetails = [];
                if ($event->gift_registry_id != null || $event->gift_registry_id != "") {

                    if (!empty($event->gift_registry_id)) {
                        $giftregistry = explode(',', $event->gift_registry_id);

                        $giftregistryData = EventGiftRegistry::whereIn('id', $giftregistry)->get();
                        foreach ($giftregistryData as $value) {
                            $giftRegistryDetail['id'] = $value->id;
                            $giftRegistryDetail['registry_recipient_name'] = $value->registry_recipient_name;
                            $giftRegistryDetail['registry_link'] = $value->registry_link;
                            $giftRegistryDetails[] = $giftRegistryDetail;
                        }
                    }
                }
                return view('layout', compact(
                    'title',
                    'page',
                    'event',
                    'giftRegistryDetails',
                    'isInvited',
                    'event_id',
                    'user_id'
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

        $userId = decrypt($request->user_id);
        $eventId = decrypt($request->event_id);
        try {

            $checkEvent = Event::where(['id' => $eventId])->first();

            if ($checkEvent->end_date < date('Y-m-d')) {
                return redirect('rsvp/' . $request->user_id . '/' . $request->event_id)->with('error', "Event is past , you can't attempt RSVP");
            }
            DB::beginTransaction();




            $rsvpSent = EventInvitedUser::whereHas('user', function ($query) {

                $query->where('app_user', '1');
            })->where(['user_id' => $userId, 'event_id' => $eventId])->first();
            $rsvpSentAttempt = $rsvpSent->rsvp_status;

            if ($rsvpSent != null) {
                $rsvp_attempt = "";
                if ($rsvpSentAttempt == NULL) {
                    $rsvp_attempt =  'first';
                } else if ($rsvpSentAttempt == '0' && $request->rsvp_status == '1') {
                    $rsvp_attempt =  'no_to_yes';
                } else if ($rsvpSentAttempt == '1' && $request->rsvp_status == '0') {
                    $rsvp_attempt =  'yes_to_no';
                }

                $rsvpSent->event_id = $eventId;

                $rsvpSent->user_id = $userId;

                $rsvpSent->rsvp_status = $request->rsvp_status;

                $rsvpSent->adults = $request->adults;

                $rsvpSent->kids = $request->kids;

                $rsvpSent->message_to_host = $request->message_to_host;
                $rsvpSent->rsvp_attempt = $rsvp_attempt;

                $rsvpSent->read = '1';
                $rsvpSent->rsvp_d = '1';

                $rsvpSent->event_view_date = date('Y-m-d');

                $rsvpSent->save();

                $notificationParam = [

                    'sender_id' => $userId,
                    'event_id' => $eventId,
                    'rsvp_status' => $request->rsvp_status,
                    'kids' => $request->kids,
                    'adults' => $request->adults,
                    'rsvp_message' => $request->message_to_host,
                    'post_id' => "",
                    'rsvp_attempt' => $rsvp_attempt
                ];

                DB::commit();

                sendNotification('sent_rsvp', $notificationParam);

                return  redirect()->route('home')->with('success', 'Rsvp sent Successfully');
            }
            return redirect('rsvp/' . $request->user_id . '/' . $request->event_id)->with('error', 'Rsvp not sent');
        } catch (QueryException $e) {

            return redirect('rsvp/' . $request->user_id . '/' . $request->event_id)->with('error', 'DB error');
            DB::rollBack();
        } catch (\Exception $e) {

            return redirect('rsvp/' . $request->user_id . '/' . $request->event_id)->with('error', 'Something went wrong');
        }
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
