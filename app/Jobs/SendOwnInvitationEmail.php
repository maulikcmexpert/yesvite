<?php

namespace App\Jobs;

use App\Mail\OwnInvitationEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOwnInvitationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $email;
    protected $templateData;
    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->email = $this->data[0];
        $this->templateData = $this->data[1];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
         $invitation_email = new OwnInvitationEmail($this->templateData);
        Mail::to($this->email)->send($invitation_email);
    }
}
