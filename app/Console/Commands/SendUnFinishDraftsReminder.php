<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Mail\NotifyPendingInvitation;
use Illuminate\Support\Facades\Mail;

class SendUnFinishDraftsReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-un-finish-drafts-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unfinish Drafts Reminder to finish draft';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $eventData = Event::with(['user', 'event_schedule', 'event_image'])->where('is_draft_save', '1')->get();
        $currentDate = Carbon::now()->toDateString();
        if (count($eventData) != 0) {

            foreach ($eventData as $value) {

                $dateAfterSevenDays = Carbon::parse($value->created_at)->addDays(7)->toDateString();

                if ($dateAfterSevenDays > $currentDate) {
                    $event_time = "";
                    if ($value->event_schedule->isNotEmpty()) {

                        $event_time =  $value->event_schedule->first()->start_time;
                    }

                    $eventData = [
                        'event_name' => $value->event_name,
                        'event_image' => ($value->event_image->isNotEmpty()) ? $value->event_image[0]->image : "no_image.png",
                        'date' =>   date('l - M jS, Y', strtotime($value->start_date)),
                        'time' => $event_time,
                    ];

                    $invitation_email = new NotifyPendingInvitation($eventData);
                    Mail::to($value->user->email)->send($invitation_email);
                }
            }
        }
    }
}
