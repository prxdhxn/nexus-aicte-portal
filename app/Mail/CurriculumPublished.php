<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Curriculum;

class CurriculumPublished extends Mailable
{
    use Queueable, SerializesModels;

    public $curriculum;

    public function __construct(Curriculum $curriculum)
    {
        $this->curriculum = $curriculum;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Curriculum Published: ' . $this->curriculum->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.curriculum-published',
        );
    }
}
