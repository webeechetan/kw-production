<?php

namespace App\Livewire\Tasks;

use Livewire\Component;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Models\Team;
use App\Notifications\NewTaskAssignNotification;


class AddTask extends Component
{
    public $name;
    public $description;
    public $dueDate;

    public $users;
    public $projects;
    public $teams;
    public $project_id;
    public $user_ids;

    public function render()
    {
        return view('livewire.tasks.add-task');
    }

    public function mount(){
        $this->users = User::all();
        $this->projects = Project::all();
        $this->teams = Team::all();
    }

    public function store(){
        $this->validate([
            'name' => 'required'
        ]);

        $task = new Task();
        $task->org_id = session('org_id');
        $task->assigned_by = auth()->guard(session('guard'))->user()->id;
        $task->project_id = $this->project_id;
        $task->name = $this->name;
        $task->description = $this->description;
        $task->due_date = $this->dueDate;
        $task->status = 'pending';
        // $task->when_completed_notify = $this->when_completed_notify;
        $task->save();

        $task->users()->attach($this->user_ids);
        foreach($this->user_ids as $user_id){
            $user = User::find($user_id);
            // $user->notify(new NewTaskAssignNotification($task));
        }
        session()->flash('message','Task created successfully');
        $this->redirect(route('task.index'),navigate:true);
    }
}
