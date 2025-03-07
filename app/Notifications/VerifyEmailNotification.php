<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private User $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

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
        $verificationUrl = route('verification.verify', [
            'id' => $notifiable->getKey(),
        ]);

        if (tenant()) {
            $urlWithoutScheme = preg_replace('#^https?://#', '', $verificationUrl);

            $finalUrl = parse_url($verificationUrl, PHP_URL_SCHEME).'://'.tenant()->domain->domain.'.'.$urlWithoutScheme;
        } else {
            $finalUrl = $verificationUrl;
        }

        return (new MailMessage)
            ->subject('Email Verification')
            ->greeting('Hello '.$this->user->profile->first_name.'!')
            ->line('Your account has been created on. Click the link below to verify your email address.')
            ->action('Verify Email', $finalUrl)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
