<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskStatusChangeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $task;
    public $oldStatus;
    public $newStatus;
    public $changedBy;
    public $taskUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct($task, $oldStatus, $newStatus, $changedBy, $taskUrl)
    {
        $this->task = $task;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->changedBy = $changedBy;
        $this->taskUrl = $taskUrl;
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
        
        return (new MailMessage)
            ->view('mails.task-status-change', 
            [
                'task' => $this->task, 
                'user' => $notifiable, 
                'oldStatus' => $this->oldStatus, 
                'newStatus' => $this->newStatus, 
                'changedBy' => $this->changedBy,
                'taskUrl' => $this->taskUrl
            ]
        );
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
