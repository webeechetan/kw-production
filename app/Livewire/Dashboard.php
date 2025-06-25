<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\Task;
use App\Models\Comment;
use App\Models\Pin;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Dashboard extends Component
{

    public $mostImportantProjects = [];
    public $users_tasks = [];
    public $active_projects = [];
    public $recent_comments = [];
    public $myPins = [];
    public $myTasks = [];
    public $otherTasks = [];

    public function render()
    {
        return view('livewire.dashboard');
    } 

    public function mount(){

        $assignedByMe = Task::assignedByMe()->get();
        $markedToMe = Task::markedToMe()->get();
        $myTasks = Task::tasksByUserType()->get();
        $this->myTasks = $myTasks;
        $this->otherTasks = $assignedByMe->merge($markedToMe);
        $this->users_tasks = $assignedByMe->merge($markedToMe)->merge($myTasks);
        $this->mostImportantProjects = $this->getMostImportantProjects();
        $this->active_projects = Auth::user()->projects->where('status','!=','completed')->count();
        $tour = session()->get('tour');
        if(request()->tour == 'close-main-tour'){
            // $tour['main_tour'] = false;
            unset($tour['main_tour']);
            session()->put('tour',$tour);
        } 
        $this->getRecentComments();
        $this->myPins = Pin::where('user_id',Auth::id())->where('pinnable_type','App\Models\Project')->get();
    }

    // nearest due date and more pending tasks are most important projects

    public function getMostImportantProjects(){ 
        if(Auth::user()->hasRole('Admin')){
            $projects = Project::where('status','!=','completed')
            ->where('due_date','!=',null)
            ->orderBy('due_date',"DESC")->limit(10)->get();
        }else{
            $projects = Auth::user()->projects->where('status','!=','completed')
            ->where('due_date','!=',null)->limit(10)->get();
        }
        
        $mostImportantProjects = [];

        foreach($projects as $project){
            $project->pending_tasks = $project->tasks()->where('status','!=','completed')->count();
            $mostImportantProjects[] = $project;
        }

        $mostImportantProjects = collect($mostImportantProjects)->sortByDesc('pending_tasks')->take(2);

        return $mostImportantProjects;
       
    }

    public function getRecentComments(){
        // get 5 recent comments of the user tasks
        $task_ids = [];
        $task_ids = $this->users_tasks->pluck('id')->toArray();
        $this->recent_comments = Comment::whereIn('task_id',$task_ids)->orderBy('created_at','desc')->limit(5)->get();
    }

}
