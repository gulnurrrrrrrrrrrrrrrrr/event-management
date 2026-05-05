<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventDeletedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $eventTitle,   // передаём строку, а не модель — она уже удалена
        public User   $organizer,
        public string $adminName,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ваше мероприятие было удалено',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.event-deleted',
        );
    }
}
