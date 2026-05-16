<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Adoption;

class FeedbackGiven extends Mailable
{
    use Queueable, SerializesModels;

    public $adoption;

    public function __construct(Adoption $adoption)
    {
        $this->adoption = $adoption;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Feedback Given: ' . $this->adoption->curriculum->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.feedback-given',
        );
    }
}
