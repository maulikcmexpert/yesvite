<?php

namespace App\Jobs;

use App\Mail\BulkEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendBroadcastEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $email;
    protected $message;
    /**
     * Create a new job instance.
     */
    public function __construct($email, $message)
    {
        $this->email = $email;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Mail::to($this->email)->send(new BulkEmail($this->details));

        Mail::raw($this->message, function ($mail) {
            $mail->to($this->email)
                ->subject('Send Broadcast Mail');
        });
    }
}


// class SendBroadcastEmailJob implements ShouldQueue
// {
//     use Queueable, InteractsWithQueue, SerializesModels;

//     protected $email;
//     protected $message;

//     /**
//      * Create a new job instance.
//      *
//      * @param string $email
//      * @param string $message
//      */
//     public function __construct($email, $message)
//     {
//         $this->email = $email;
//         $this->message = $message;
//     }

//     /**
//      * Execute the job.
//      *
//      * @return void
//      */
//     public function handle()
//     {
//         try {
//             // Send the email using Laravel's Mail facade
//             Mail::raw($this->message, function ($mail) {
//                 $mail->to($this->email)
//                     ->subject('Send Broadcast Mail');
//             });

//         } catch (\Exception $e) {
//             dd($e->getMessage());
//             Log::error('Failed to send email to ' . $this->email . ': ' . $e->getMessage());
//         }
//     }
// }
