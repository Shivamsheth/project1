<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EmailVerificationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
        // Generate OTP if not already generated
        if (!$notifiable->otp) {
            $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $notifiable->update([
                'otp' => $otp,
                'otp_expires_at' => now()->addMinutes(10),
            ]);
        }

        return (new MailMessage)
            ->subject('Email Verification - Your OTP')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your email verification OTP is:')
            ->line('')
            ->line('**' . $notifiable->otp . '**')
            ->line('')
            ->line('This OTP will expire in 10 minutes.')
            ->line('Please use this OTP to verify your email address.')
            ->line('')
            ->line('If you did not create an account, please ignore this email.')
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
