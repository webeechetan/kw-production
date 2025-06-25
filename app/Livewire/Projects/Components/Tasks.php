<?php

namespace App\Livewire\Projects\Components;

use Livewire\Component;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\Team;
use App\Helpers\ProjectTaskFilter;
use Illuminate\Support\Facades\Pipeline;
use App\Filters\{UserFilter, SearchFilter, StatusFilter, SortFilter, DateFilter};
use Livewire\WithPagination;

class Tasks extends Component
{ 
    use WithPagination;

    protected $listeners = ['saved' => 'refresh'];

    public $project;

    public $users = [];
    public $teams = [];
    public $totalTasks = 0;

    public $sort = 'all';
    public $filter = 'all';
    public $byUser = 'all';
    public $byTeam = 'all';
    public $status = 'all';

    public $startDate;
    public $dueDate;

    public $query = '';

    public function render() 
    {
        $filters = [
            new SearchFilter($this->query, 'PROJECT-TASKS'),
            new StatusFilter($this->status, 'PROJECT-TASKS'),
            new SortFilter($this->sort, 'PROJECT-TASKS'),
            new UserFilter($this->byUser, 'PROJECT-TASKS'),
            new DateFilter($this->startDate, $this->dueDate, 'PROJECT-TASKS')
        ];

        $query = $this->project->tasks();

        $tasks = Pipeline::send($query)
            ->through($filters)
            ->thenReturn()
            ->paginate(25);

        $this->totalTasks = $tasks->count();

        return view('livewire.projects.components.tasks',[
            'tasks' => $tasks
        ]);
    }

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->users = User::all();
        $this->teams = Team::all();
    }

    public function search(){
        $this->resetPage();
    }

    public function refresh()
    {
        $this->mount($this->project);
    }

    public function emitEditTaskEvent($id)
    {
        $this->dispatch('editTask', $id);
    }

    public function emitDeleteTaskEvent($id)
    {
        $this->dispatch('deleteTask', $id);
    }

    public function doesAnyFilterApplied(){
        if($this->sort != 'all' || $this->byUser != 'all' || $this->startDate || $this->dueDate || $this->status != 'all'){
            return true;
        }
        return false;
    }
}
