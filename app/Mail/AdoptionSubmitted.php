<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Adoption;
use App\Models\Curriculum;

class AdoptionSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $adoption;
    public $curriculum;

    public function __construct(Adoption $adoption, Curriculum $curriculum)
    {
        $this->adoption = $adoption;
        $this->curriculum = $curriculum;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Adoption Submitted: ' . $this->curriculum->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.adoption-submitted',
        );
    }
}
