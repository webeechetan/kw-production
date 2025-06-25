<?php

namespace App\Livewire\Tasks;

use Livewire\Component;
use App\Models\{Task, User , Project , Client, Team, Attachment, Comment};
use Livewire\WithFileUploads;
use App\Notifications\UserMentionNotification;
use Illuminate\Support\Facades\Auth;
use App\Models\VoiceNote;



class View extends Component
{
    use WithFileUploads;

    public $task;
    public $users = [];
    public $selectedUsers = [];
    public $selectedNotifiers = [];
    public $projects = [];
    public $project_id;
    public $name;
    public $description;
    public $due_date;
    public $status;
    public $attachments = [];
    public $comment;
    public $voice_note;

    protected $listeners = ['comment_deleted' => '$refresh'];

    public function render()
    {
        return view('livewire.tasks.view')->title($this->task->name);
    }

    public function mount(Task $task)
    {
        $this->task = $task;
        $this->name = $task->name;
        $this->selectedUsers = $task->users->pluck('id')->toArray();
        $this->selectedNotifiers = $task->notifiers->pluck('id')->toArray();
        $this->project_id = $task->project_id;
        $this->users = User::orderBy('name')->get();
        $this->projects = Project::orderBy('name')->get();
        $this->description = $task->description;
        $this->due_date = $task->due_date;
        $this->status = $task->status;
    }

    public function saveTask(){
        $this->validate([
            'name' => 'required',
        ]);

        $this->task->update([
            'name' => $this->name,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'status' => $this->status,
            'project_id' => $this->project_id,
        ]);

        $this->task->users()->sync($this->selectedUsers);
        $this->task->notifiers()->sync($this->selectedNotifiers);

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
                $at->attachable_type = 'App\Models\Task';
                $at->save();
            }
        } 

        $this->dispatch('success','Task updated successfully');
        $this->saveVoiceNoteToTask($this->task->id);

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
        
        return $this->redirect(route('task.view', $this->task->id));

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
        $voiceNote->save();
        return $this->redirect(route('task.view', $this->task->id));

    }

    public function deleteComment($id){
        $comment = Comment::find($id);
        $comment->delete();
        $this->dispatch('comment_deleted',$id);
    }

    public function updateComment($id){
        $comment = Comment::find($id);
        $comment->comment = $this->comment;
        $comment->save();

    }
}  
