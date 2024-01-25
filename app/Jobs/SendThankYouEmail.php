<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\ThankYouEmail;
use App\Models\Event;
use App\Models\EventGreeting;
use App\Models\EventInvitedUser;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class SendThankYouEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Job started');


        $endedEvents =  Event::where('end_date', '<', now())->get();
        foreach ($endedEvents as $event) {
            if (!empty($event->greeting_card_id)) {

                $greetingsCard = explode(',', $event->greeting_card_id);
                $eventcards =   EventGreeting::whereIn('id', $greetingsCard)->first();

                if ($eventcards->message_sent_time == '0') {

                    $currentDate = Carbon::now();

                    $endDate = Carbon::parse($event->end_date);

                    $hoursDifference = $endDate->diffInHours($currentDate);
                    if ($hoursDifference == 0) {

                        $invitedUsers = EventInvitedUser::with('user')->where('event_id', $event->id)->get();
                        foreach ($invitedUsers as $invitedUserVal) {

                            Mail::to($invitedUserVal->user->email)->send(new ThankYouEmail($event));
                        }
                    }
                } else if ($eventcards->message_sent_time == '1') {
                    $currentDate = Carbon::now();

                    $endDate = Carbon::parse($event->end_date);

                    $hoursDifference = $endDate->diffInHours($currentDate);

                    if ($hoursDifference == $eventcards->custom_hours_after_event) {
                        $invitedUsers = EventInvitedUser::with('user')->where('event_id', $event->id)->get();
                        foreach ($invitedUsers as $invitedUserVal) {

                            Mail::to($invitedUserVal->user->email)->send(new ThankYouEmail($event));
                        }
                    }
                }
            }
        }
        Log::info('Job Finished');
    }
}
