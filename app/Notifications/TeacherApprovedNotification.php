<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeacherApprovedNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your ClassIQ Teacher Account is Approved')
            ->greeting('Hi '.$notifiable->name.',')
            ->line('Congratulations!!
            Welcome to ClassIQ workspace. Your teacher account on ClassIQ has been approved.')
            ->line('You can now log in and start creating courses and content.')
            ->action('Log in to ClassIQ', url('/teacher/login'))
            ->line('Thank you for being part of our instructor community!');
    }
}
