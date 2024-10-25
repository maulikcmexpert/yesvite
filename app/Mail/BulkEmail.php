<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;




class BulkEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public $message;
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
                        subject: 'this is a test mail from the yestive team',
                    );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {

        // return new Content(
        //     view: 'emails.thankyou',
        //     with: [
        //         'eventData' => $this->eventData
        //     ]
        // );


        return new Content(
                        view: 'emails.adminEmail',
                        with: ['details' => $this->message]
                    );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
// class BulkEmail extends Mailable
// {
//     use Queueable, SerializesModels;
//     public $message;

//     /**
//      * Create a new message instance.
//      *
//      * @return void
//      */
//     public function __construct($message)
//     {
//         $this->message = $message;
//     }

//     /**
//      * Get the message envelope.
//      *
//      * @return \Illuminate\Mail\Mailables\Envelope
//      */
//     public function envelope()
//     {
//         return new Envelope(
//             subject: 'this is a test mail from the yestive',
//         );
//     }

//     /**
//      * Get the message content definition.
//      *
//      * @return \Illuminate\Mail\Mailables\Content
//      */
//     public function content()
//     {
//         return new Content(
//             view: 'emails.adminEmail',
//             with: ['details' => $this->message]
//         );
//     }

//     /**
//      * Get the attachments for the message.
//      *
//      * @return array
//      */
//     public function attachments()
//     {
//         return [];
//     }

//     public function build()
//     {
//         return $this->view('emails.adminEmail')
//                     ->with('details', $this->message);
//     }
// }





