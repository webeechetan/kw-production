<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use App\Models\Scopes\OrganizationScope;

class UserMentionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $task;
    public $comment;

    /**
     * Create a new notification instance.
     */
    public function __construct($task , $comment = null)
    {
        Log::warning('UserMentionNotification C');
        $this->task = $task;
        $this->comment = $comment;
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
        $mentioner = User::withoutGlobalScope(OrganizationScope::class)->find($this->comment->user_id);
        Log::info($mentioner); 
        // dd($mentioner);
        return (new MailMessage)->view(
            'mails.mention-user-mail',
            [
                'task' => $this->task,
                'user' => $notifiable, 
                'comment' => $this->comment,
                'mentioner' => $mentioner,
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
