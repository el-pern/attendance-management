<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InstreqMail extends Mailable
{
    use Queueable, SerializesModels;

    public $admin;
    public $instructor;
    public $inst_request;
    public $student_name;
    public $student_id;
    public $reason;

    /**
     * Create a new message instance.
     */
    public function __construct($admin, $instructor, $inst_request,
    $student_name, $student_id, $reason)
    {
        $this->admin = $admin;
        $this->instructor = $instructor;
        $this->inst_request = $inst_request;
        $this->student_name = $student_name;
        $this->student_id = $student_id;
        $this->reason = $reason;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Instructor Request')
                    ->view('emails.instreqmail');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Instructor Request',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.instreqemail',
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
