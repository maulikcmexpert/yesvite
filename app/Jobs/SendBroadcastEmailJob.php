<?php

namespace App\Jobs;

use App\Mail\BulkEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


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
    // public function handle()
    // {
        // Mail::to($this->email)->send(new BulkEmail($this->details));

       
    public function handle()
    {
        // Validate email address
        $validator = Validator::make(['email' => $this->email], [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            \Log::error('Invalid email address: ' . $this->email);
            return;
        }

        // Retry logic (optional)
        $retries = 0;
        $maxRetries = 3;
        while ($retries < $maxRetries) {
            try {
                // Send the email using Laravel's Mail facade
                Mail::raw($this->message, function ($mail) {
                    $mail->to($this->email)
                         ->subject('Send Broadcast Mail');
                });

                break; // Exit the loop if successful
            } catch (\Exception $e) {
                \Log::error('Failed to send email to ' . $this->email . ': ' . $e->getMessage());
                $retries++;
                sleep(60); // Wait for 60 seconds before retrying
            }
        }

        if ($retries >= $maxRetries) {
            \Log::error('Failed to send email to ' . $this->email . ' after ' . $maxRetries . ' attempts');
        }
    }
}
