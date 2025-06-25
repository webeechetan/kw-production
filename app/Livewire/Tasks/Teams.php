<?php

namespace App\Livewire\Tasks;

use Livewire\Component;
use App\Models\Team;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Models\Client;
use Livewire\WithPagination;
use App\Helpers\Filter;
use Illuminate\Support\Facades\Pipeline;
use App\Filters\{ProjectFilter, ClientFilter, UserFilter, TeamFilter, SearchFilter, StatusFilter, SortFilter, DateFilter};


class Teams extends Component
{
    use WithPagination;

    public $allTasks;
    public $pendingTasks;
    public $inProgressTasks;
    public $inReviewTasks;
    public $completedTasks;
    public $overdueTasks;

    // auth 

    public $auth_user_id;

    public $tasks_count = [];

    // Add task Form
    public $name;
    public $description;
    public $mentioned_users= [];
    public $currentRoute;
    
    public $query = '';
    public $sort = 'all';
    public $filter = 'all';
    public $byProject = 'all';
    public $byClient = 'all';
    public $byUser = 'all';
    public $byTeam = 'all';
    public $startDate;
    public $dueDate; 
    public $status = 'all';
 


    public $teams = [];
    public $users = [];
    public $clients = [];
    public $projects;
    // public $teams;
    public $project_id; 
    public $user_ids;

    public $view_form = false;
    public $edit_task = false;

    // edit task 

    public $task;
 
    // comment

    public $comment;
    public $comments;

    public $ViewTasksAs = 'user';
    public $assignedByMe = false;


    public function render()
    {
        $this->doesAnyFilterApplied();
        $this->authorize('View Task');
        $this->auth_user_id = auth()->guard(session('guard'))->user()->id;
            
        $tasks = Task::with('assignedBy','project','users','project.client');

        
        
        $this->allTasks = (clone $tasks)->count();
        $this->pendingTasks = (clone $tasks)->where('status', 'pending')->count();
        $this->inProgressTasks = (clone $tasks)->where('status', 'in_progress')->count();
        $this->inReviewTasks = (clone $tasks)->where('status', 'in_review')->count();
        $this->completedTasks = (clone $tasks)->where('status', 'completed')->count();
        $this->overdueTasks = (clone $tasks)->where('due_date', '<', now())->where('status', '!=', 'completed')->count();
        
        $tasks = Pipeline::send($tasks)
            ->through([
                new SearchFilter($this->query),
                new StatusFilter($this->status, 'TASK-TEAMS'),
                new SortFilter($this->sort),
                new ClientFilter($this->byClient),
                new ProjectFilter($this->byProject),
                new UserFilter($this->byUser, 'TASK-TEAMS'),
                new TeamFilter($this->byTeam, 'TASK-TEAMS'),
                new DateFilter($this->startDate, $this->dueDate,'TASK-TEAMS'),
            ])
            ->thenReturn();

        $tasks = $tasks->paginate(25);

        return view('livewire.tasks.teams',[
            'tasks' => $tasks,
        ]);
    }

      
    public function mount()
    {
        if(!($this->currentRoute)){
            $this->currentRoute = request()->route()->getName();
        }
        $this->clients = Client::orderBy('name')->get();
        $this->projects = Project::orderBy('name')->get();
        $this->teams = Team::orderBy('name')->get();
        $this->users = User::orderBy('name')->get();
    }

    public function updatedAssignedByMe($value)
    {
        $this->mount();
    }

    public function updatedViewTasksAs($value)
    {
        $this->mount();
    }

    public function emitEditTaskEvent($id){
        $this->dispatch('editTask', $id);
    }

    public function search()
    {
        $this->mount();
    }


    public function updatedByTeam($value)
    {
        $this->mount();
        if($this->byTeam != 'all'){
            $this->projects = Team::find($this->byTeam)->projects;
        }
    }

    public function doesAnyFilterApplied(){

        if($this->sort != 'all' || $this->byProject != 'all' || $this->byClient != 'all' || $this->byUser != 'all' || $this->startDate || $this->dueDate || $this->status != 'all'){
            return true;
        }
        return false;
    }

}
