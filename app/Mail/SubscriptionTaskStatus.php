<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// This Mailable class is used to send status of each scheduled cron job to administration
// It will be sent once a day at 00:00 UTC
// The class is called in Console/Commands/SubscriptionPaymentTask.php
class SubscriptionTaskStatus extends Mailable
{
    use Queueable, SerializesModels;
    public $charge;
    public $cancelled;
    public $outdated;
    public $errors;
    public $currentDate;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($charge, $cancelled, $outdated, $errors, $currentDate)
    {
        $this->charge = $charge;
        $this->cancelled = $cancelled;
        $this->outdated = $outdated;
        $this->errors = $errors;
        $this->currentDate = $currentDate;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Daily subscription payment status',
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
            view: 'email/subscriptiontaskstatus',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
