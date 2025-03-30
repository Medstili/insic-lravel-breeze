<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IncompleteWeeklyQuotaNotification extends Notification
{
    use Queueable;

    protected $patientName;
    protected $appointmentCount;
    protected $weeklyQuota;

    /**
     * Create a new notification instance.
     */
    public function __construct( string $patientName, int $appointmentCount, int $weeklyQuota)
    {
        $this->patientName = $patientName;
        $this->appointmentCount = $appointmentCount;
        $this->weeklyQuota = $weeklyQuota;

        
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'patient_name' => $this->patientName,
            'appointment_count' => $this->appointmentCount,
            'weekly_quota' => $this->weeklyQuota,
            'message' => "Patient {$this->patientName} did not complete their weekly quota. Only {$this->appointmentCount} appointment(s) scheduled out of {$this->weeklyQuota}.",
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

}
