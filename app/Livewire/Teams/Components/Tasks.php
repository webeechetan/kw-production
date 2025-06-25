<?php

namespace App\Livewire\Teams\Components;

use Livewire\Component;
use App\Helpers\Helper;
use App\Models\Team;
use Carbon\Carbon;
use Livewire\Attributes\Lazy;
use App\Models\{ Project, User, Client, Task };
use Illuminate\Support\Facades\Pipeline;
use App\Filters\{ClientFilter, ProjectFilter, UserFilter, SearchFilter, StatusFilter, SortFilter, DateFilter};
use Livewire\WithPagination;


class Tasks extends Component
{

    use WithPagination;
 
    public $totalTasks = 0;
    public $perPage = 15;
    public $startDate = null; 
    public $dueDate = null;
    public $allTasks = 0;
    public $assignedTasks = 0;
    public $acceptedTasks = 0;
    public $inReviewTasks = 0;
    public $completedTasks = 0;
    public $overdueTasks = 0;

    public $query = '';
    public $sort = 'all';

    public $team;
    public $byClient = 'all';
    public $byProject = 'all';
    public $byUser = 'all';
    public $filter = 'all';
    public $status = 'all';

    public $users = [];
    public $projects = [];
    public $clients = []; 
    public $teams = [];

    public $tasks_for_count = [];


    public function render()
    {
        $filters = [
            new StatusFilter($this->status,'TEAM-TASKS'),
            new SearchFilter($this->query, 'TEAM-TASKS'),
            new ClientFilter($this->byClient, 'TEAM-TASKS'),
            new ProjectFilter($this->byProject, 'TEAM-TASKS'),
            new UserFilter($this->byUser, 'TEAM-TASKS'),
            new DateFilter($this->startDate, $this->dueDate, 'TEAM-TASKS'),
        ];

        $query = $this->team->tasksQuery;

        $this->allTasks = (clone $query)->count();
        $this->assignedTasks = (clone $query)->where('status', 'pending')->count();        
        $this->acceptedTasks = (clone $query)->where('status', 'accepted')->count();
        $this->inReviewTasks = (clone $query)->where('status', 'in_review')->count();
        $this->completedTasks = (clone $query)->where('status', 'completed')->count();
        $this->overdueTasks = (clone $query)->where('due_date', '<', Carbon::now())->where('status','!=','completed')->count();

        $tasks = Pipeline::send($query)
            ->through($filters)
            ->thenReturn();


        $tasks = $tasks->paginate(25);

        return view('livewire.teams.components.tasks',[
            'tasks' => $tasks
        ]);
    } 

  
    public function mount(Team $team) {
        $this->doesAnyFilterApplied();
        $this->users = User::orderBy('name')->get();
        $this->projects = Project::orderBy('name')->get();
        $this->clients = Client::orderBy('name')->get();
        $this->teams = Team::orderBy('name')->get();
    }

    public function search(){

    }

    public function doesAnyFilterApplied(){
        return $this->byClient != 'all' || $this->byProject != 'all' || $this->byUser != 'all' || $this->status != 'all' || $this->startDate || $this->dueDate;
    }

}
