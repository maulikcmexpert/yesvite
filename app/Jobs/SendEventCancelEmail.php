<?php

namespace App\Jobs;

use App\Mail\CancelEventMail;
use App\Mail\CancelEventMaill;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEventCancelEmail implements ShouldQueue
{
    // use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    // protected $user;
    // protected $details;

    // /**
    //  * Create a new job instance.
    //  */
    // public function __construct($user, $details)
    // {
    //     $this->user = $user;
    //     $this->details = $details;
    // }

    // /**
    //  * Execute the job.
    //  */
    // public function handle()
    // {
    //     Mail::to($this->user->email)->send(new CancelEventMail($this->details));
    // }

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    // public $timeout = 300;
    public $timeout = 600; // Set timeout in seconds (10 minutes)

    protected $data;
    protected $email;
    protected $templateData;
    /**
     * Create a new job instance.
     */
    public function __construct($data,$templateData)
    {
        // dd($templateData);
        $this->data = $data;
        $this->email = $this->data;
        $this->templateData = $templateData;
    }

    // protected $batchSize = 3 0; // Adjust the batch size as needed

    // public function __construct($data)
    // {
    //     dd($data);
    //     // $this->email = array_filter((array) $email, function ($e) {
    //     //     return filter_var($e, FILTER_VALIDATE_EMAIL) && !empty($e);
    //     // });

    //     // if (empty($this->email)) {
    //     //     throw new \Exception("Invalid email: No valid email addresses provided.");
    //     // }

    //     // $this->message = $message;
    //     // dd($message);
    //     $this->email = $this->data[0];
    //     $this->templateData = $this->data[1];

    // }

    public function handle(): void
    {

        dd($this->email);
        foreach ($this->email as $emails) {
            try {
                // Send the email using the BulkEmail Mailable
                if($emails!=""){
                    Mail::to($emails)->send(new CancelEventMail($this->templateData));
                }

                // Mail::to('prakashmanat24@gmail.com')
                // ->bcc($emails) // Send to each batch of 30 via BCC
                // ->send(new BulkEmail($this->message));
            } catch (\Exception $e) {
                // dd($e->getMessage());
                // Log the error for troubleshooting (don't use dd() in jobs)
                Log::error("Failed to send email to $emails: " . $e->getMessage());

                // Optionally, you can store failed emails or implement a retry mechanism
            }
        }
    }
}
