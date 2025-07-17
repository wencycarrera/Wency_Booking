<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewBookingCreated extends Notification
{
    use Queueable;

    public $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable)
    {
        return ['database'];  // store notification in DB for later use
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "New booking created: '{$this->booking->title}' by {$this->booking->user->name}.",
            'booking_id' => $this->booking->id,
        ];
    }
}
