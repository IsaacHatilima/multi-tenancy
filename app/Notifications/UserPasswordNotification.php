<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private User $user;

    private string $password;

    private object $tenant;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $password, $tenant)
    {
        $this->user = $user;
        $this->password = $password;
        $this->tenant = $tenant;
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
        tenancy()->initialize($this->tenant);

        $urlWithoutScheme = preg_replace('#^https?://#', '', route('login'));

        $finalUrl = parse_url(route('login'), PHP_URL_SCHEME).'://'.tenant()->domain->domain.'.'.$urlWithoutScheme;

        return (new MailMessage)
            ->subject('Account Password')
            ->greeting('Hello '.$this->user->profile->first_name.'!')
            ->line('Your account has been created on. Click the link below to login.')
            ->line('Your account password is: '.$this->password)
            ->action('Login', $finalUrl)
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
