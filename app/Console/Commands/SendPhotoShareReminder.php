<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;
use App\Mail\PhotoShareReminderMail;
use App\Models\Event;
use App\Models\EventInvitedUser;
use Illuminate\Support\Carbon;

class SendPhotoShareReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-photo-share-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Reminder message for photo sharing to guest';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $endedEvents =  Event::with(['user', 'event_image'])->where('end_date', '<', now())->get();

        foreach ($endedEvents as $event) {



            $currentDate = Carbon::now();

            $endDate = Carbon::parse($event->end_date);

            if ($endDate < $currentDate) {
                $hoursDifference = $endDate->diffInHours($currentDate);

                if ($hoursDifference == 24) {

                    $invitedUsers = EventInvitedUser::with('user')->where(['event_id' => $event->id])->get();

                    foreach ($invitedUsers as $invitedUserVal) {
                        if ($invitedUserVal->user->app_user == '0') {
                            continue;
                        }


                        $eventData = [
                            'event_name' => $event->event_name,
                            'host_name' => $event->user->firstname . ' ' . $event->user->firstname,

                        ];
                        $invitation_email = new PhotoShareReminderMail($eventData);
                        $recipientEmail = $invitedUserVal->user->email;


                        Mail::to($recipientEmail)->send($invitation_email);
                    }
                }
            }
        }
    }
}
