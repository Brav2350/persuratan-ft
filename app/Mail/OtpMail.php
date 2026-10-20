<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $code,
        public string $purpose = 'registration'
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->purpose === 'activation'
                ? 'Kode Aktivasi Akun - '.config('app.name')
                : 'Kode Verifikasi Email - '.config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.otp');
    }
}