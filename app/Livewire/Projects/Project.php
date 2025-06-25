<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\Project as ProjectModel;
use App\Models\User;
use App\Models\Task;
use App\Models\Team;
use PDO;

class Project extends Component
{

    protected $listeners = ['project-added' => 'refresh'];

    public $project;
    public $users;

    public $projectTasks;
    public $projectUsers;
    public $projectTeams = [];
    public $description;

    public function render()
    {
        return view('livewire.projects.project');
    }

    public function mount($id){ 
        $this->project = ProjectModel::withTrashed()->with('client')->find($id);
        $this->users = User::all();
        $this->projectTasks =  Task::where('project_id',$id)->get();
        $this->projectUsers = $this->project->members;
        $tasks = Task::whereIn('project_id', [$this->project->id])->get();
        $task_users = []; 
        foreach ($tasks as $task) {
            $task_users = array_merge($task_users, $task->users->pluck('id')->toArray());
        }

        $this->projectTeams = $this->project->teams;
 
    }

    public function refresh(){
        $this->mount($this->project->id);
    }

    public function changeDueDate($date){
        $this->project->due_date = $date;
        $this->project->save();
        // $this->dispatch('success','Due date changed successfully');
        $this->redirect(route('project.profile',['id'=>$this->project->id]), navigate: true);
    }

    public function updateDescription(){
        $this->project->description = $this->description;
        $this->project->save();
    }

    public function syncUsers($users){
        $this->project->users()->sync($users);
        $this->dispatch('user-synced','Users synced successfully');
    }
}
 