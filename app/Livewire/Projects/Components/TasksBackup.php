<?php

namespace App\Livewire\Projects\Components;

use Livewire\Component;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\Team;
use Livewire\Attributes\On; 

class Tasks extends Component
{
    public $project;

    public $users = [];
    public $teams = [];

    public $sort = 'all';
    public $filter = 'all';
    public $byUser = 'all';
    public $byTeam = 'all';

    public function render()
    {

      
        return view('livewire.projects.components.tasks');
    }

    public function mount(Project $project)
    {
        $this->project = $project;

        $this->users = User::all();
        $this->teams = Team::all();


        if($this->sort == 'all'){
            $this->project->tasks = $this->project->tasks;
        }elseif($this->sort == 'a_z'){
            $this->project->tasks = $this->project->tasks->sortBy('name');
        }elseif($this->sort == 'z_a'){
            $this->project->tasks = $this->project->tasks->sortByDesc('name');
        }elseif($this->sort == 'newest'){
            $this->project->tasks = $this->project->tasks->sortByDesc('created_at');
        }elseif($this->sort == 'oldest'){
            $this->project->tasks = $this->project->tasks->sortBy('created_at');
        }

        if($this->byUser != 'all'){
            $this->project->tasks = $this->project->tasks->filter(function($task){
                return $task->users->contains('id',$this->byUser);
            });
        }

        if($this->byTeam != 'all'){
            $this->project->tasks = $this->project->tasks->filter(function($task){
                return $task->teams->contains('id',$this->byTeam);
            });
        }

        dd($this->project->tasks());
        $this->project->tasks()->paginate(5);
        

    }

    public function updatedByUser($value){
        $this->byUser = $value;
        return $this->redirect(route('project.profile',['id'=>$this->project->id,'sort'=>$this->sort,'filter'=>$this->filter,'byUser'=>$this->byUser,'byTeam'=>$this->byTeam]), navigate: true);
    }

    public function emitEditTaskEvent($id){
        $this->dispatch('editTask', $id);
    }

    public function emitDeleteTaskEvent($id){
        $this->dispatch('deleteTask', $id);
    }

}
