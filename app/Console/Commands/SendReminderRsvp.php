<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use Carbon\Carbon;
use App\Models\EventInvitedUser;

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
    }
}
