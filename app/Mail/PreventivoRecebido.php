<?php

namespace App\Mail;

use App\Models\Preventivo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PreventivoRecebido extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Preventivo $preventivo) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nuova richiesta di preventivo - '.$this->preventivo->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.preventivo-recebido',
            with: [
                'preventivo' => $this->preventivo,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
