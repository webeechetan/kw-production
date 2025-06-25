<?php

namespace App\Notifications\Task;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Scopes\OrganizationScope;
use App\Models\User;   

class NewCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $comment;
    public $task;
    public $taskUrl;
 
    /**
     * Create a new notification instance.
     */
    public function __construct($comment, $task, $taskUrl)
    {
        $this->comment = $comment;
        $this->task = $task;
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
        $mentioner = User::withoutGlobalScope(OrganizationScope::class)->find($this->comment->user_id);

        return (new MailMessage)
            ->view('mails.comment-notification-mail', 
            [
                'task' => $this->task, 
                'user' => $notifiable, 
                'comment' => $this->comment,
                'taskUrl' => $this->taskUrl,
                'mentioner' => $mentioner
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
