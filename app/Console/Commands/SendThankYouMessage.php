<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\ThankYouEmail;
use App\Models\Event;
use App\Models\EventGreeting;
use App\Models\EventInvitedUser;
use Illuminate\Support\Carbon;

class SendThankYouMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:send-thank-you-message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Thank you message to guest after event.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $endedEvents =  Event::with('event_image')->where('end_date', '<', now())->get();

        foreach ($endedEvents as $event) {
            if (!empty($event->greeting_card_id)) {

                $greetingsCard = explode(',', $event->greeting_card_id);
                $eventcards =   EventGreeting::whereIn('id', $greetingsCard)->first();


                $currentDate = Carbon::now();

                $endDate = Carbon::parse($event->end_date);

                if ($endDate < $currentDate) {
                    $hoursDifference = $endDate->diffInHours($currentDate);

                    if ($hoursDifference == $eventcards->custom_hours_after_event) {

                        $invitedUsers = EventInvitedUser::with('user')->where(['event_id' => $event->id])->get();

                        foreach ($invitedUsers as $invitedUserVal) {
                            if ($invitedUserVal->user->app_user == '0') {
                                continue;
                            }


                            $eventData = [
                                'event_name' => $event->event_name,
                                'date' =>  date('l, M. jS', strtotime($event->start_date)),
                                'event_image' => ($event->event_image->isNotEmpty()) ? $event->event_image[0]->image : "no_image.png",

                            ];
                            $invitation_email = new ThankYouEmail($eventData);
                            $recipientEmail = $invitedUserVal->user->email;


                            Mail::to($recipientEmail)->send($invitation_email);
                        }
                    }
                }
            }
        }
    }
}
