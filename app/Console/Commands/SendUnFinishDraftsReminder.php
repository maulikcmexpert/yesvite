<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;
use Carbon\Carbon;

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
        $eventData = Event::with('user')->where('is_draft_save', '1')->get();

        if (count($eventData) != 0) {

            foreach ($eventData as $value) {
                $dateAfterSevenDays = Carbon::parse($value->created_at)->addDays(7);
            }
        }
    }
}
