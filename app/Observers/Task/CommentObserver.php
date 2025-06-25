<?php

namespace App\Observers\Task;
use App\Models\Task;
use App\Models\Comment;
use App\Models\Notification;
use App\Notifications\Task\NewCommentNotification;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class CommentObserver
{
    public function created(Comment $comment){
        $task = Task::find($comment->task_id);
        $users = $task->users;
        $users = $users->merge($task->notifiers);
        $taskUrl = env('APP_URL').'/'.session('org_name').'/task/view/'.$task->id;
        $users->each(function($user) use ($comment, $task, $taskUrl){
            if($user->id == auth()->guard(session('guard'))->user()->id){
                return;
            }
            $user->notify(new NewCommentNotification($comment,$task,$taskUrl));
            $notification = new Notification();
            $notification->user_id = $user->id;
            $notification->action_by = Auth::user()->id;
            $notification->org_id = $task->org_id;
            $notification->title = 'You have a new comment on task '.$task->name;
            $notification->message = 'You have a new comment on task '.$task->name;
            $notification->url = route('task.view', ['task' => $task->id]);
            $notification->save();
        });

        $mentioned_users = explode(',',$comment->mentioned_users);
        foreach($mentioned_users as $user_id){
            $user = User::find($user_id);
            if($user){
                $notification = new Notification();
                $notification->user_id = $user->id;
                $notification->action_by = Auth::user()->id;
                $notification->org_id = $task->org_id;
                $notification->title = 'You have been mentioned in a comment on task '.$task->name;
                $notification->message = 'You have been mentioned in a comment on task '.$task->name;
                $notification->url = route('task.view', ['task' => $task->id]);
                $notification->save();

            }
        }

        $activity = new Activity();
        $activity->org_id = $task->org_id;
        $activity_text ='Commented on task <b>'.$task->name.'</b>';
        $activity->text = $activity_text;
        $activity->activityable_id = $task->project_id;
        $activity->activityable_type = 'App\Models\Project';
        $activity->created_by = auth()->guard(session('guard'))->user()->id;
        $activity->save();
        
    }
}
