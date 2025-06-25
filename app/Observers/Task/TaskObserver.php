<?php

namespace App\Observers\Task;
use App\Models\Task;
use App\Notifications\TaskStatusChangeNotification;
use App\Models\Activity;
use App\Models\Organization;
use App\Models\Webhook;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Events\NotificationEvent;
use Illuminate\Support\Facades\Log;

class TaskObserver
{
    
    public function updating(Task $task){
        // check for if task status is changed
        if($task->project_id == null){
            return;
        }

        if($task->isDirty('status')){
            $oldStatus = $task->getOriginal('status');
            $newStatus = $task->status;

            if($oldStatus == 'completed'){
                $oldStatus = 'Completed';
            }else if($oldStatus == 'in_progress'){
                $oldStatus = 'In-Progress';
            }else if($oldStatus == 'in_review'){
                $oldStatus = 'In-Review';
            }else if($oldStatus == 'pending'){
                $oldStatus = 'Pending';
            }else{
                $oldStatus = 'Not Started';
            }
    
            if($newStatus == 'completed'){
                $newStatus = 'Completed';
            }else if($newStatus == 'in_progress'){
                $newStatus = 'In-Progress';
            }else if($newStatus == 'in_review'){
                $newStatus = 'In-Review';
            }else if($newStatus == 'pending'){
                $newStatus = 'Pending';
            }else{
                $newStatus = 'Not Started';
            }


            $changedBy = auth()->guard(session('guard'))->user();
            $taskUrl = env('APP_URL').'/'. session('org_name').'/task/view/'.$task->id;
            $users = array_merge([$task->assigned_by],$task->users->pluck('id')->toArray(),$task->notifiers->pluck('id')->toArray());
            $users = array_unique($users);
            
            foreach($users as $user){
                $user = User::find($user);
                if($user->id == $changedBy->id){
                    continue;
                }
                if($task->email_notification){
                    $user->notify(new TaskStatusChangeNotification($task, $oldStatus, $newStatus, $changedBy, $taskUrl));
                }
                $notification = new Notification();
                $notification->user_id = $user->id;
                $notification->org_id = $task->org_id;
                $notification->action_by = Auth::user()->id;

                $notification->title = $changedBy->name.' changed the status of task '.$task->name .' from '.$oldStatus.' to '.$newStatus;
                $notification->message = $changedBy->name.' changed the status of task '.$task->name .' from '.$oldStatus.' to '.$newStatus;
                $notification->url = route('task.view', ['task' => $task->id]);
                $notification->save();
                event(new NotificationEvent($user, Auth::user(), $notification));

                
            }



            $activity = new Activity();
            $activity->org_id = $task->org_id;
            $activity_text = 'Changed the status of task <b>'.$task->name .'</b> from <b>'.$oldStatus.'</b> to <b>'.$newStatus. '</b>';
            $activity->text = $activity_text;
            $activity->activityable_id = $task->project_id;
            $activity->activityable_type = 'App\Models\Project';
            $activity->created_by = auth()->guard(session('guard'))->user()->id;
            $activity->save(); 
            
        }

        $changedValues = $task->getDirty();
        $fields_to_ignore = ['updated_at','created_at','id','org_id','assigned_by','project_id','status','email_notification','task_order','description','client_id','visibility'];
        $fields_to_translate = ['name' => 'Name', 'due_date' => 'Due Date', 'email_notification' => 'Email Notification'];
        if(count($changedValues) > 0){
            foreach($changedValues as $key => $value){
                if(in_array($key, $fields_to_ignore)){
                    continue;
                }
                $activity = new Activity();
                $activity->org_id = $task->org_id;
                $activity_text = 'Changed the <b>'.$fields_to_translate[$key].'</b> of task <b>'.$task->name .'</b> from <b>'.$task->getOriginal($key).'</b> to <b>'.$value. '</b>';
                $activity->text = $activity_text;
                $activity->activityable_id = $task->project_id;
                $activity->activityable_type = 'App\Models\Project';
                $activity->created_by = auth()->guard(session('guard'))->user()->id;
                $activity->save(); 

                $users = array_merge([$task->assigned_by],$task->users->pluck('id')->toArray(),$task->notifiers->pluck('id')->toArray());
                $users = array_unique($users);
                $taskUrl = env('APP_URL').'/'. session('org_name').'/task/view/'.$task->id;
                foreach($users as $user_id){
                    if($user_id == Auth::user()->id){
                        continue;
                    }
                    $user = User::find($user_id);
                    $notification = new Notification();
                    $notification->user_id = $user->id;
                    $notification->org_id = $task->org_id;
                    $notification->action_by = Auth::user()->id;
                    $notification->title = Auth::user()->name.' updated the '.$fields_to_translate[$key].' of task '.$task->name;
                    $notification->message = Auth::user()->name.' updated the '.$fields_to_translate[$key].' of task '.$task->name;
                    $notification->url = $taskUrl;
                    $notification->save();
                    event(new NotificationEvent($user, Auth::user(), $notification));
                }
            }
        }


    }

    public function created(Task $task)
    {
        // create activity
        if($task->project_id == null){
            return;
        }
        $activity = new Activity();
        $activity->org_id = $task->org_id;
        $activity_text = 'Created a task '.$task->name;
        $activity->text = $activity_text;
        $activity->activityable_id = $task->project_id;
        $activity->activityable_type = 'App\Models\Project';
        $activity->created_by = auth()->guard(session('guard'))->user()->id;
        $activity->save();

        $org_id = $task->org_id;

        $org = Organization::find($org_id);

        $webhooks = $org->webhooks;

        $webhooks = $webhooks->where('for','task')->where('type','outgoing')->where('event','created')->where('status','active');

        foreach($webhooks as $webhook){
            $webhook->send($task);
        }


    }

    public function updated(Task $task)
    {
        // create activity
        if($task->project_id == null){
            return;
        }
        

        // $task->load('project');
        // $task->load('users');
        // $task->load('notifiers');

        // Log::info('Task: '.json_encode($task));
        // Log::info('Task Project: '.json_encode($task->project));
        // Log::info('Task Users: '.json_encode($task->users));
        // Log::info('Task Notifiers: '.json_encode($task->notifiers));


        // $taskUsers = $task->users->pluck('id')->toArray();

        // Log::info('Task Users: '.json_encode($taskUsers));

        // $taskNotifiers = $task->notifiers->pluck('id')->toArray();

        // Log::info('Task Notifiers: '.json_encode($taskNotifiers));

        // $taskCreator = $task->assigned_by;

        // Log::info('Task Creator: '.json_encode($taskCreator));

        // $users = array_merge($taskUsers, $taskNotifiers, [$taskCreator]);

        // $users = array_unique($users);

        // $project = $task->project;

        // $project->users()->syncWithoutDetaching($users);

        // Log::info('Task Users: '.json_encode($taskUsers));
    }



}
