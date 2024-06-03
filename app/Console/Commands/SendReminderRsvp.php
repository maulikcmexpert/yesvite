<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use Illuminate\Support\Facades\Mail;
use App\Models\EventInvitedUser;
use App\Mail\NewRsvpsReminderMail;
use Carbon\Carbon;

class SendReminderRsvp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-reminder-rsvp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reminder RSVP';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $eventData = Event::with(['user', 'event_schedule', 'event_image'])
            ->where('start_date', '>', now())
            ->where(['is_draft_save' => '0'])
            ->get();

        // Get the current date
        $currentDate = Carbon::now()->toDateString();

        // Iterate over the events and calculate the day difference
        $eventsWithDayDifference = $eventData->map(function ($event) use ($currentDate) {
            // Convert event start_date to a Carbon instance
            $eventStartDate = Carbon::parse($event->start_date);

            // Calculate the difference in days
            $daysDifference = Carbon::parse($currentDate)->diffInDays($eventStartDate, false);

            // Add the days difference to the event object (optional)
            $event->days_difference = $daysDifference;

            return $event;
        });

        foreach ($eventsWithDayDifference as $value) {
            if ($value->days_difference <= 4) {
                $invitedUser = EventInvitedUser::with('user')->where('event_id', $value->id)->where('rsvp_status', '!=', '1')->get();
                if (count($invitedUser) != 0) {

                    foreach ($invitedUser as $val) {
                        $eventData = [
                            'event_name' => $value->event_name,

                        ];

                        $invitation_email = new NewRsvpsReminderMail($eventData);
                        $invitation_email->from('notification@yesvite.com', 'Yesvite');
                        Mail::to($val->user->email)->send($invitation_email);
                    }
                }
            }
        }
    }
}
