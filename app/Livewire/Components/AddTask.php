<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\ { Project, Task, User, Attachment, Comment, Client, Notification, VoiceNote};
use Livewire\WithFileUploads;
use App\Notifications\NewTaskAssignNotification;
use App\Notifications\UserMentionNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Js;
use App\Models\Scopes\MainClientScope;

class AddTask extends Component
{
    use WithFileUploads;

    protected $listeners = ['editTask','deleteTask','copy-link'=>'copyLink','resetForm'];

    public $project;
    public $task;
    public $name;
    public $description;
    public $due_date;
    public $projects = []; 
    public $users = [];
    public $task_users = []; 
    public $task_notifiers = [];
    public $project_id;
    public $status = 'pending'; 
    public $email_notification = true;
    public $voice_note;
    public $visibility = 'public';

    public $attachments = [];
    public $comment;
    public $comments = [];

    public $comment_id = null;



    public function render()
    {
        return view('livewire.components.add-task');
    }

    public function mount($project = null){
        $this->project = $project;
        $this->users = User::orderBy('name','asc')->get();
        // $this->projects = Project::all();
        // $this->projects = Project::whereHas('client',function($query){
        //     $query->whereNull('clients.deleted_at');
        // })->get();

        $this->projects = Project::whereHas('client', function($query) {
            $query->whereNull('clients.deleted_at');
        })
        ->orderBy('projects.name', 'asc')
        ->with('client')
        ->get();

        if($project){
            $this->project_id = $project->id;
        }
    }

    public function saveTask(){
        // dd($this->email_notification);
        if($this->task){
            $this->updateTask();
            return;
        }
        
        $validation_error_message = '';

        if(!$this->name){
            $validation_error_message .= 'Name is required. ';
        }

        if(!$this->project_id){
            $validation_error_message .= 'Project is required. ';
        }

        if(!$this->name || !$this->project_id){
            $this->dispatch('error',$validation_error_message);
            return;
        }

        $this->validate([
            'name' => 'required',
        ]);

        $task = new Task();
        $task->org_id = session('org_id');
        $task->assigned_by = Auth::user()->id;
        $task->name = $this->name;
        $task->description = $this->description;
        $task->due_date = $this->due_date;
        $task->project_id = $this->project_id;
        $task->status = $this->status;
        $task->visibility = $this->visibility;
        if($this->email_notification == true){
            $task->email_notification = 1;
        }
        if($this->email_notification == false){
            $task->email_notification = 0;
        }

 
        $task->save();
        $task->users()->sync($this->task_users);
        $task->notifiers()->sync($this->task_notifiers);
        
         // attach files to task from $this->attachments
        if($this->attachments){
            foreach($this->attachments as $attachment){
                $p = Project::find($this->project_id);
                $c = Client::withoutGlobalScope(MainClientScope::class)->find($p->client_id);
                $path = session('org_name').'/'.$c->name.'/'.$p->name;
                $path = $attachment->store($path);
                $at = new Attachment();
                $at->org_id = session('org_id');
                $at->name = $attachment->getClientOriginalName();
                $at->attachment_path = $path;
                $at->attachable_id = $task->id;
                $at->attachable_type = 'App\Models\Task';
                $at->save();
            }
        }
        $taskUrl = env('APP_URL').'/'.session('org_name').'/task/view/'.$task->id;
        $taskUsers = $this->task_users;
        $taskNotifiers = $this->task_notifiers;
        if(is_array($this->task_users)){
            $taskUsers = $this->task_users;
        }else{
            $taskUsers = [];
        }
        if(is_array($this->task_notifiers)){
            $taskNotifiers = $this->task_notifiers;
        }else{
            $taskNotifiers = [];
        }

        $users = array_merge($taskUsers,$taskNotifiers);
        $users = array_unique($users);

        $project = Project::find($this->project_id);
        $project->users()->syncWithoutDetaching($users);

        
        if($this->task_users){
            foreach($users as $user_id){
                if($user_id == Auth::user()->id){
                    continue;
                }
                $user = User::find($user_id);
                if($this->email_notification){
                    $sendAs = '';

                    if(in_array($user_id,$taskUsers)){
                        $sendAs = 'assigned';
                    }

                    if(in_array($user_id,$taskNotifiers)){
                        $sendAs = 'notifier';
                    }
                    $user->notify(new NewTaskAssignNotification($task,$taskUrl,$sendAs));
                }
                $notification = new Notification();
                $notification->user_id = $user->id;
                $notification->org_id = $task->org_id;
                $notification->action_by = Auth::user()->id;
                if(in_array($user_id,$taskUsers)){
                    $text = Auth::user()->name.' assigned you a new task in '. $task->project->name;
                }else{
                    $text = Auth::user()->name.' involved you in a new task in '. $task->project->name;
                }

                $notification->title = $text;
                $notification->message = $text;
                $notification->url = route('task.view', ['task' => $task->id]);
                $notification->save();
            }
        }

        $this->attachments = [];
        if($this->voice_note){
            $this->saveVoiceNoteToTask($task->id);
        }
        $this->task = null;
        $this->dispatch('task-added',$task);
        $this->dispatch('saved','Task saved successfully');
        $this->dispatch('success', 'Task Added successfully');

    }

    public function saveComment($type = 'internal'){
        if($this->comment_id){
            $this->updateComment($this->comment_id,$this->comment);
            return;
        }
        // dd($this->comment);
        $this->validate([
            'comment' => 'required'
        ]);

        $comment = new Comment();
        $comment->task_id = $this->task->id;
        $comment->user_id = auth()->guard(session('guard'))->user()->id;
        $comment->comment = $this->comment;
        $comment->created_by = session('guard');
        $mentioned_users = [];

        preg_match_all('/data-id="(\d+)"/', $this->comment, $matches);
        $mentioned_users = $matches[1]; 

        $mentioned_users = array_unique($mentioned_users);

        $comment->mentioned_users = implode(',',$mentioned_users);
        $comment->type = $type;
        $comment->save();
        foreach($mentioned_users as $user_id){
            $user = User::find($user_id);
            if($user){
                $user->notify(new UserMentionNotification($this->task , $comment));
            }
        }
        $this->comments = $this->task->comments;
        $this->dispatch('comment-added',Comment::with('user')->find($comment->id));
        $this->comment = '';
        
    }

    public function updateComment($id,$newComment){
        $comment = Comment::find($id);
        $comment->comment = $newComment;
        $comment->save();
        // $this->dispatch('comment-added',Comment::with('user')->find($comment->id));
        $this->comment = '';
        $this->comment_id = null;
    }

    public function editTask($id){
        $this->task = Task::with('users','notifiers','attachments','comments.user','voiceNotes.user','assignedBy')->find($id);
        $this->name = $this->task->name;
        $this->description = $this->task->description;
        $this->due_date = $this->task->due_date;
        $this->task_users = $this->task->users->pluck('id')->toArray();
        $this->task_notifiers = $this->task->notifiers->pluck('id')->toArray();
        $this->comments = $this->task->comments;
        $this->email_notification = $this->task->email_notification;
        $this->status = $this->task->status;
        $this->visibility = $this->task->visibility;
        $this->project_id = $this->task->project_id;
        $this->dispatch('edit-task',$this->task);
        // user ids of who created a task and who are assigned to a task and who are notifiers of a task
        $user_ids_who_can_edit_task = array_merge([$this->task->assigned_by],$this->task->users->pluck('id')->toArray(),$this->task->notifiers->pluck('id')->toArray());
        $user_ids_who_can_edit_task = array_unique($user_ids_who_can_edit_task);
        $admins = User::role('Admin')->pluck('id')->toArray();
        $user_ids_who_can_edit_task = array_merge($user_ids_who_can_edit_task,$admins);
        $user_ids_who_can_edit_task = array_unique($user_ids_who_can_edit_task);

        if(!in_array(Auth::user()->id,$user_ids_who_can_edit_task) ){
            $this->dispatch('read-only',true);
        }else{
            $this->dispatch('read-only',false);
        }
    }

    public function updateTask(){
        $this->validate([
            'name' => 'required',
        ]);
        $validation_error_message = '';

        if(!$this->name){
            $validation_error_message .= 'Name is required. ';
        }

        if(!$this->project_id){
            $validation_error_message .= 'Project is required. ';
        }

        if(!$this->name || !$this->project_id){
            $this->dispatch('error',$validation_error_message);
            return;
        }

        $this->task->name = $this->name; 
        $this->task->description = $this->description;
        $this->task->due_date = $this->due_date;
        $this->task->status = $this->status;
        $this->task->project_id = $this->project_id;
        $this->task->email_notification = $this->email_notification;
        $this->task->visibility = $this->visibility;
        $this->task->save();
        $this->task->users()->sync($this->task_users);
        $this->task->notifiers()->sync($this->task_notifiers);

        $taskUsers = $this->task_users;
        $taskNotifiers = $this->task_notifiers;
        if(is_array($this->task_users)){
            $taskUsers = $this->task_users;
        }else{
            $taskUsers = [];
        }
        if(is_array($this->task_notifiers)){
            $taskNotifiers = $this->task_notifiers;
        }else{
            $taskNotifiers = [];
        }

        $users = array_merge($taskUsers,$taskNotifiers);
        $users = array_unique($users);

        $project = Project::find($this->task->project_id);
        $project->users()->syncWithoutDetaching($users);


        // attach files to task from $this->attachments
        if($this->attachments){ 
            foreach($this->attachments as $attachment){
                $p = Project::find($this->task->project_id);
                $c = Client::withoutGlobalScope(MainClientScope::class)->find($p->client_id);
                $path = session('org_name').'/'.$c->name.'/'.$p->name;
                $path = $attachment->store($path);
                $at = new Attachment();
                $at->org_id = session('org_id');
                $at->name = $attachment->getClientOriginalName();
                $at->attachment_path = $path;
                $at->attachable_id = $this->task->id;
                $at->attachable_type = 'App\Models\Task';
                $at->save();
            }
        }

        $this->dispatch('task-added',$this->task);
        if($this->voice_note){
            $this->saveVoiceNoteToTask($this->task->id);
        }

        $this->attachments = [];
        $this->task = null; 

        $this->dispatch('success', 'Task updated successfully');
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

    public function saveVoiceNoteToTask($task_id){
        $this->validate([
            'voice_note' => 'required'
        ]);
        $audio_data = $this->voice_note;
        $base64Audio = str_replace('data:audio/wav;base64,', '', $audio_data);
        $audio_binary = base64_decode($base64Audio);
        $audio_name = 'audio_'.time().'.wav';
        $path = 'storage/voice_notes/'.$audio_name;
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        file_put_contents($path, $audio_binary); 
        
        $voiceNote = new VoiceNote();
        $voiceNote->org_id = session('org_id');
        $voiceNote->user_id = Auth::user()->id;
        $voiceNote->voice_noteable_id = $task_id;
        $voiceNote->voice_noteable_type = 'App\Models\Task';
        $voiceNote->path = $path;
        if($voiceNote->save()){
            return $voiceNote;
        }
        return false;
        
       
    }

    public function deleteComment($id){
        $comment = Comment::find($id);
        $comment->delete();
        $this->comments = $this->task->comments;
        $this->editTask($this->task->id);
    }

    public function resetForm(){
       $this->task = null;
    }
    
}
