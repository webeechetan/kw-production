<?php

namespace App\Livewire\Tasks;

use Livewire\Component;
use App\Models\Task;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use App\Models\Team;
use Livewire\WithPagination;
use App\Helpers\Filter;
use Livewire\Attributes\Session;


class ListTask extends Component
{
    use WithPagination;

    protected $listeners = ['saved' => 'refresh'];

    public $allTasks;
    public $activeTasks;
    public $completedTasks;
    public $archivedTasks; 
    public $perPage = 15;
    public $userTotalTasks;
    public $managerTotalTasks;
    public $totalTasks;

    // auth 

    public $auth_user_id;

    public $tasks;

    // Add task Form
    public $name;
    public $description;
    public $mentioned_users= [];
    
    
    public $query = '';
    
    public $sort = 'all';
    public $filter = 'all';
    public $byProject = 'all';
    public $byClient = 'all';
    public $byUser = 'all';
    // public $byTeam = 'all';
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

    public $currentRoute;
    public $ViewTasksAs = 'user';
    
    #[Session] 
    public $assignedByMe = false;

    public function render()
    {  
        return view('livewire.tasks.list-task');
    }

    public function loadMore(){

        $this->perPage += 5;
        $this->mount();
    }

    public function updatedAssignedByMe($value)
    {
        $this->mount();
    }

    public function mount()
    {
            $this->doesAnyFilterApplied();
            $this->authorize('View Task');

            if(!($this->currentRoute)){
                $this->currentRoute = request()->route()->getName();
            }
            $this->auth_user_id = auth()->guard(session('guard'))->user()->id;
            if($this->byClient != 'all'){
                $this->projects = Project::where('client_id', $this->byClient)->orderBy('name', 'asc')->get();
            }else{
                $this->projects = Project::orderBy('name', 'asc')->get();
            }
                    
            if($this->byProject != 'all'){
                $this->users = Project::find($this->byProject)->members;
            }else{
                $this->users = User::orderBy('name', 'asc')->get();
            } 

            // dd($this->projects);
            $this->teams = Team::orderBy('name', 'asc')->get();
            $this->clients = Client::orderBy('name', 'asc')->get();
            // Fetch all tasks from the database
            $statuses = ['pending', 'in_progress', 'in_review', 'completed'];
            $baseQuery = null;

            if ($this->ViewTasksAs == 'manager') {
                $manager_team = auth()->user()->myTeam;
                $team_users = $manager_team->users()->pluck('users.id')->toArray();

                $baseQuery = function ($status) use ($team_users) {
                    return Task::where('status', $status)
                        ->whereHas('users', function($q) use ($team_users) {
                            $q->whereIn('user_id', $team_users);
                        })
                        ->where('name', 'like', '%' . $this->query . '%');
                };
            } else {
                $baseQuery = function ($status) {
                    return Task::tasksByUserType($this->assignedByMe, true)
                        ->where('status', $status)
                        ->where('name', 'like', '%' . $this->query . '%');
                };
            }

            $this->tasks = [];
            foreach ($statuses as $status) {
                $this->tasks[$status] = $this->applySort($baseQuery($status))
                    ->take($this->perPage)
                    ->get();
            }

            if ($this->doesAnyFilterApplied()) {
                $this->totalTasks = $this->applySort(
                    Task::tasksByUserType($this->assignedByMe, true)
                        ->where('name', 'like', '%' . $this->query . '%')
                )->count();
            } else {
                $this->totalTasks = Task::tasksByUserType($this->assignedByMe, true)->count();
            }


            $tour = session()->get('tour');
            if(request()->tour == 'close-task-tour'){
                // $tour['task_tour'] = false;
                unset($tour['task_tour']);
                session()->put('tour',$tour);
            }

    }

    public function refresh()
    {
        $this->mount();
    }

    public function updatedSort($value)
    {
        // dd($value);
        $this->mount();
    }

    public function updatedViewTasksAs($value)
    {
        $this->mount();
    }


    public function updateTaskOrder($list)
    {

        foreach ($list as $item) {
            if($item['value'] == 'pending'){
                foreach($item['items'] as $t){
                    $task = Task::find($t['value']);
                    $task->status = 'pending';
                    $task->task_order = $t['order'];
                    $task->save();
                }
            }
            if($item['value'] == 'in_progress'){
                foreach($item['items'] as $t){
                    $task = Task::find($t['value']);
                    $task->status = 'in_progress';
                    $task->task_order = $t['order'];
                    $task->save();
                }
            }

            if($item['value'] == 'in_review'){
                foreach($item['items'] as $t){
                    $task = Task::find($t['value']);
                    $task->status = 'in_review';
                    $task->task_order = $t['order'];
                    $task->save();
                }
            }

            if($item['value'] == 'completed'){
                foreach($item['items'] as $t){
                    $task = Task::find($t['value']);
                    $task->status = 'completed';
                    $task->task_order = $t['order'];
                    $task->save();
                }
            }
        }

        $this->tasks = [
            'pending' => $this->applySort(Task::tasksByUserType($this->assignedByMe)->where('status','pending')->orderBy('task_order'))->limit($this->perPage)->get(),
            'in_progress' => $this->applySort(Task::tasksByUserType($this->assignedByMe)->where('status','in_progress')->orderBy('task_order'))->limit($this->perPage)->get(),
            'in_review' => $this->applySort(Task::tasksByUserType($this->assignedByMe)->where('status','in_review')->orderBy('task_order'))->limit($this->perPage)->get(),
            'completed' => $this->applySort(Task::tasksByUserType($this->assignedByMe)->where('status','completed')->orderBy('task_order'))->limit($this->perPage)->get(),
        ];
    }

    public function emitEditTaskEvent($id){
        $this->dispatch('editTask', $id);
    }

    public function search()
    {
        $this->mount();
    }


    public function updatedByClient($value)
    {
        $this->projects = Project::where('client_id', $value)->get();
        $this->mount();
    }

    public function updatedByProject($value)
    {
        $this->mount();
    }

    public function updatedStartDate($value)
    {
        $this->mount();
    }

    public function updatedDueDate($value)
    {
        $this->mount();
    }

    public function updatedByUser($value)
    {
        $this->mount();
    }

    public function updatedStatus($value)
    {
        $this->mount();
    }

    public function applySort($query)
    {
        return Filter::filterTasks(
            $query, 
            $this->byProject, 
            $this->byClient, 
            $this->byUser, 
            $this->sort, 
            $this->startDate, 
            $this->dueDate, 
            $this->status
        );
    }

    public function doesAnyFilterApplied(){
        if($this->sort != 'all' || $this->byProject != 'all' || $this->byClient != 'all' || $this->byUser != 'all' || $this->startDate || $this->dueDate || $this->status != 'all'){
            return true;
        }
        return false;
    }
}
