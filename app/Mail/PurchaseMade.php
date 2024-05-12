<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

// This Mailable class is used to send an email to administration when a purchase is made in the portal
// The class is called in CheckoutController.php success method.

class PurchaseMade extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     * @param string $file_path The path to the invoice to attach
     * @return void
     */
    public function __construct($file_path)
    {
        $this->file_path = $file_path;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'A purchase has been made in the 7Sense portal',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'email/purchasemade',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [
            Attachment::fromPath($this->file_path)
                    ->as("customer_invoice.pdf")
        ];
    }
}
