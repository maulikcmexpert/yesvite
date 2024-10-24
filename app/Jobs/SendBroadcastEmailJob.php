<?php

namespace App\Jobs;

use App\Mail\BulkEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

// class SendBroadcastEmailJob implements ShouldQueue
// {
//     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

//     public $tries = 5;  // Number of retries before the job is considered failed
//     public $timeout = 120; // Timeout in seconds

//     protected $email;
//     protected $message;

//     /**
//      * Create a new job instance.
//      */
//     public function __construct($email, $message)
//     {
//         // Filter valid emails and ensure the array is not empty
//         $this->email = array_filter((array) $email, function ($e) {
//             return filter_var($e, FILTER_VALIDATE_EMAIL) && !empty($e);
//         });

//         if (empty($this->email)) {
//             throw new \Exception("Invalid email: No valid email addresses provided.");
//         }

//         $this->message = $message;
//         // dd($this->email);
//     }

//     /**
//      * Execute the job.
//      */
//     public function handle()
//     {
//         try {
//             // Send the email using the BulkEmail Mailable with BCC for bulk sending
//             Mail::to('vimal.cmexpertise@gmail.com') // Main recipient (could be a fixed address)
//                 ->bcc($this->email) // BCC the entire array of emails
//                 ->send(new BulkEmail($this->message));

//         } catch (\Swift_TransportException $e) {
//             // Handle mail transport-related exceptions
//             Log::error("Mail transport error while sending bulk email: " . $e->getMessage());
//         } catch (\Exception $e) {
//             // Log any other exceptions
//             Log::error("Failed to send bulk email: " . $e->getMessage());
//         }
//     }
// }


class SendBroadcastEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    // public $timeout = 300;
    public $timeout = 300; // Set timeout in seconds (10 minutes)

    protected $email;
    protected $message;
    
    protected $batchSize = 30; // Adjust the batch size as needed

    public function __construct($email, $message)
    {
        $this->email = array_filter((array) $email, function ($e) {
            return filter_var($e, FILTER_VALIDATE_EMAIL) && !empty($e);
        });

        if (empty($this->email)) {
            throw new \Exception("Invalid email: No valid email addresses provided.");
        }

        $this->message = $message;
        // dd($message);
    }

    public function handle()
    {
        foreach ($this->email as $emails) {
            try {
                // Send the email using the BulkEmail Mailable
                if($emails!=""){
                    Mail::to($emails)->send(new BulkEmail($this->message));
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

