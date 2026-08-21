<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GuardianAbsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $studname;
    public $date;
    public $guardian;
    public $absCount;
    public $absCountWeek;
    public $instructor;
    public $instructor_mail;


    /**
     * Create a new message instance.
     */
    public function __construct($studname, $date, $guardian,
    $absCount, $absCountWeek, $instructor, $instructor_mail)
    {
        $this->studname = $studname;
        $this->date = $date;
        $this->guardian = $guardian;
        $this->absCount = $absCount;
        $this->absCountWeek = $absCountWeek;
        $this->instructor = $instructor;
        $this->instructor_mail = $instructor_mail;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Student Absence Notice',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.guardianabsmail',
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
