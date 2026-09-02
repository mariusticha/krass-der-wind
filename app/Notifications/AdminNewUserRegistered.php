<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class AdminNewUserRegistered extends Notification // implements ShouldQueue
{
    // use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $user) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $verifyUrl = URL::temporarySignedRoute(
            'admin.users.verify',
            now()->addDays(7),
            ['user' => $this->user->id],
        );

        return (new MailMessage())
            ->subject('New user pending admin verification')
            ->greeting('A new user has registered')
            ->line("Name: {$this->user->name}")
            ->line("Email: {$this->user->email}")
            ->line("Instrument: {$this->user->instrument}")
            ->action('Verify user', $verifyUrl)
            ->line('This verification link expires in 7 days.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
        ];
    }
}
