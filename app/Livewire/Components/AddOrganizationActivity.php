<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\ { Project, OrganizationActivityTask as Task, User, Attachment, ActivityTaskComment as Comment, Client};
use Livewire\WithFileUploads;
use App\Notifications\NewActivityTaskAssignNotification;
use App\Notifications\UserMentionNotification;
use Livewire\Attributes\Js;

class AddOrganizationActivity extends Component
{
    use WithFileUploads;

    protected $listeners = ['editTask','deleteTask','copy-link'=>'copyLink'];

    public $project;
    public $task;
    public $name;
    public $description;
    public $due_date;
    public $projects = [];
    public $users = [];
    public $task_users;
    public $task_notifiers;
    public $project_id;
    public $status = 'pending'; 

    public $attachments = [];
    public $comment;
    public $comments = [];
    public $activity;

    public function render()
    {
        return view('livewire.components.add-organization-activity');
    }

    public function mount($project = null){
        $this->project = $project;
        $this->users = User::all();
        $this->projects = Project::all();
        if($project){
            $this->project_id = $project->id;
        }
    }

    public function saveTask(){
        if($this->task){
            $this->updateTask();
            return;
        }
        
        $validation_error_message = '';

        if(!$this->name){
            $validation_error_message .= 'Name is required. ';
            $this->dispatch('error',$validation_error_message);
        }

        $this->validate([
            'name' => 'required',
        ]);

        $task = new Task();
        $task->org_id = session('org_id');
        $task->assigned_by = auth()->guard(session('guard'))->user()->id;
        $task->name = $this->name;
        $task->description = $this->description;
        $task->due_date = $this->due_date;
        $task->organization_activity_id = $this->activity->id;
        $task->status = $this->status;
        $task->save();
        $task->users()->sync($this->task_users);
        $task->notifiers()->sync($this->task_notifiers);
         // attach files to task from $this->attachments
        if($this->attachments){
            foreach($this->attachments as $attachment){
                
                $path = session('org_name').'/activity/'. $this->activity->name;
                // check if folder exists
                if(!file_exists(public_path('storage/'.$path))){
                    mkdir(public_path('storage/'.$path),0777,true);
                }

                $path = $attachment->store($path);
                $at = new Attachment();
                $at->org_id = session('org_id');
                $at->name = $attachment->getClientOriginalName();
                $at->attachment_path = $path;
                $at->attachable_id = $task->id;
                $at->attachable_type = 'App\Models\OrganizationActivityTask';
                $at->save();
            }
        }
 
        if($this->task_users){
            foreach($this->task_users as $user_id){
                $user = User::find($user_id);
                $user->notify(new NewActivityTaskAssignNotification($task));
            }
        }

        $this->attachments = [];
        
        $this->dispatch('saved','Task saved successfully');
    }

    public function saveComment($type = 'internal'){
        // dd($this->comment);
        $this->validate([
            'comment' => 'required'
        ]);

        $comment = new Comment();
        $comment->task_id = $this->task->id;
        $comment->user_id = auth()->guard(session('guard'))->user()->id;
        $comment->comment = $this->comment;
        $comment->created_by = session('guard');

        // take out mentioned users from description and save them in mentioned_users array

        $mentioned_users = [];

        // remove paragraph tags from description

        $temp_comment = str_replace('<p>','',$this->comment);
        $temp_comment = str_replace('</p>','',$temp_comment);
        $temp_comment = strip_tags($temp_comment);


        // convert description to array of words

        $comment_array = explode(' ',$temp_comment);

        // check if any word starts with @

        foreach($comment_array as $word){
            if(substr($word,0,1) == '@'){
                $user_name = substr($word,1);
                $user_name = str_replace('_',' ',$user_name);
                $mentioned_users[] = $user_name;
            }
        }

        $mentioned_users = array_unique($mentioned_users);

        $mentioned_users = array_map('trim',$mentioned_users);

        $mentioned_users = array_filter($mentioned_users);

        // get user ids from mentioned users

        $mentioned_user_ids = User::whereIn('name',$mentioned_users)->pluck('id')->toArray();

        $comment->mentioned_users = implode(',',$mentioned_user_ids);
        $comment->type = $type;
        $comment->save();
        foreach($mentioned_user_ids as $user_id){
            $user = User::find($user_id);
            if($user){
                $user->notify(new UserMentionNotification($this->task , $comment));
            }
        }
        $this->comments = $this->task->comments;
        $this->dispatch('comment-added',Comment::with('user')->find($comment->id));
        $this->comment = '';
        
    }

    public function editTask($id){
        $this->task = Task::with('users','notifiers','attachments','comments.user')->find($id);
        $this->name = $this->task->name;
        $this->description = $this->task->description;
        $this->due_date = $this->task->due_date;
        $this->task_users = $this->task->users->pluck('id')->toArray();
        $this->task_notifiers = $this->task->notifiers->pluck('id')->toArray();
        $this->comments = $this->task->comments;
        $this->status = $this->task->status;
        $this->dispatch('edit-task',$this->task);
    }

    public function updateTask(){
        $this->validate([
            'name' => 'required',
        ]);
        $this->task->name = $this->name; 
        $this->task->description = $this->description;
        $this->task->due_date = $this->due_date;
        $this->task->status = $this->status;
        $this->task->save();
        $this->task->users()->sync($this->task_users);
        $this->task->notifiers()->sync($this->task_notifiers);
        // attach files to task from $this->attachments
        if($this->attachments){ 
            foreach($this->attachments as $attachment){
                $p = Project::find($this->task->project_id);
                $c = Client::find($p->client_id);
                $path = session('org_name').'/'.$c->name.'/'.$p->name;
                $path = $attachment->store($path);
                $at = new Attachment();
                $at->org_id = session('org_id');
                $at->name = $attachment->getClientOriginalName();
                $at->attachment_path = $path;
                $at->attachable_id = $this->task->id;
                $at->attachable_type = 'App\Models\OrganizationActivityTask';
                $at->save();
            }
        }

        $this->attachments = [];
        $this->task = null; 

        $this->dispatch('saved','Task updated successfully');
    }

    public function changeProjectStatus($id){
        $project = Project::find($id);
        $project->status = !$project->status;
    }

    public function deleteTask(){

       
        $this->task->delete();
        $this->dispatch('success','Task deleted successfully');
        $this->dispatch('saved','Task deleted successfully');
    }

    public function viewFullscree(){
        $this->redirect(route('task.view',$this->task->id));
    }
    
}

