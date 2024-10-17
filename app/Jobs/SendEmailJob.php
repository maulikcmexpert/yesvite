<?php

namespace App\Jobs;

use App\Mail\BulkEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $user;
    protected $details;
    /**
     * Create a new job instance.
     */
    public function __construct($user, $details)
    {
        
        dd(1);
        $this->user = $user;
        $this->details = $details;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user->email)->send(new BulkEmail($this->details));
    }
}
