<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;
use App\Models\Task;
use App\Models\Scopes\OrganizationScope;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Models\Notification as NotificationModel;
use Illuminate\Support\Str;

class NewTaskAssignNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $task;
    public $taskUrl;
    public $sendAs;

    /**
     * Create a new notification instance.
     */
    public function __construct($task, $taskUrl, $sendAs = 'assigned')
    {
        $this->task = $task;
        $this->taskUrl = $taskUrl;
        $this->task->load('assignedBy');
        $this->sendAs = $sendAs;
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
        $task = Task::withoutGlobalScope(OrganizationScope::class)->where('id',$this->task->id)->first();
        $assignedBy = User::withoutGlobalScope(OrganizationScope::class)->where('id',$this->task->assigned_by)->first();
        $project = Project::withoutGlobalScope(OrganizationScope::class)->where('id',$this->task->project_id)->first();

        $subject = '';

        if($this->sendAs == 'assigned'){
            $subject = $assignedBy->name.' assigned you a new task in '.$project->name. ' ⬇️';
        }else{
            $subject = $assignedBy->name.' involved you in a new task in '.$project->name. ' ⬇️';
        }

        return (new MailMessage)
            ->view('mails.task-assigned-notification-mail', 
            [
                'task' => $task, 
                'user' => $notifiable,
                'assignedBy' => $assignedBy,
                'project' => $project,
                'taskUrl' => $this->taskUrl,
                'sendAs' => $this->sendAs
            ]
        )->subject($subject);

        
    }

    public function toSlack($notifiable)
    {
        return (new SlackMessage)
        ->from('Ghost', ':ghost:')
        ->to('#task_notifications')
        ->success()
        ->content('Hey there :smile: '.$this->task->assignedBy->name.' assigned you a new task '.$this->task->name)
        ->attachment(function ($attachment) {
            $attachment->title('Task Details')
                        ->fields([
                            'Task Name' => $this->task->name,
                            'Task Description' => $this->task->description,
                            'Assigned By' => $this->task->assignedBy->name,
                            'Assigned To' => $this->task->users->pluck('name')->implode(', '),
                        ]);
            $attachment->footer('KaykeWalk')
                        ->timestamp($this->task->created_at);
        });

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