<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActivationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $url;

    /**
     * Create a new message instance.
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Activate Your Account'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.activate'
        );
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Activate Your Account')
                    ->view('emails.activate')
                    ->with(['url' => $this->url]);
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
