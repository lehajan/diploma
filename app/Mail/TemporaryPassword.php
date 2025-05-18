<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TemporaryPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $tempPassword;

    public function __construct($user, $tempPassword)
    {
        $this->user = $user;
        $this->tempPassword = $tempPassword;
    }

    public function build()
    {
        return $this->subject('Восстановление пароля')
            ->view('emails.temporary_password');
    }

//    public function envelope(): Envelope
//    {
//        return new Envelope(
//            subject: 'Temporary Password Mail',
//        );
//    }

//    public function content(): Content
//    {
//        return new Content(
//            view: 'view.name',
//        );
//    }
//
//    public function attachments(): array
//    {
//        return [];
//    }
}
