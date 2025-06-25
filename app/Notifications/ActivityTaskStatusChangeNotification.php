<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\OrganizationActivityTask as Task;
use App\Models\Scopes\OrganizationScope;

class ActivityTaskStatusChangeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $activitytask;
    public $oldStatus;
    public $newStatus;
    public $changedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct($activitytask, $oldStatus, $newStatus, $changedBy)
    {
        $this->activitytask = $activitytask;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->changedBy = $changedBy;

        //  dd($this->oldStatus);
        // dd($this->newStatus);
        // dd($this->changedBy);
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
        ->view('mails.activity-task-status-change', ['task' => $this->activitytask, 'user' => $notifiable, 'oldStatus' => $this->oldStatus, 'newStatus' => $this->newStatus, 'changedBy' => $this->changedBy]);
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
