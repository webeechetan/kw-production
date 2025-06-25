<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Pipeline;
use App\Filters\{ClientFilter, UserFilter, TeamFilter, SearchFilter, StatusFilter, SortFilter};
use App\Models\Pin;
use Illuminate\Support\Facades\Cache;

class ListProject extends Component
{
    use WithPagination;


    public $allProjects = 0;
    public $activeProjects = 0;
    public $completedProjects = 0;
    public $archivedProjects = 0;
    public $overdueProjects = 0;
    public $pinnedProjects = [];

    // public $projects= [];

    public $teams = [] ;
    public $clients = [] ;

    public $project_all_count =[]; 

    public $query = '';
 
    public $sort = 'all';
    public $filter = 'all';
    public $byTeam = 'all';
    public $byUser = 'all';
    public $byClient = 'all';
    public $users;

    public function render()
    {   
        $role = Auth::user()->roles->first()->name;
        $projects = Project::userProjects($role);

        $projects = $projects->whereNotIn('client_id', [Auth::user()->organization->mainClient->id]);
        $projects = $projects->whereHas('client', function ($query) {
            $query->where('clients.deleted_at','=', null);
        });

        $projects = $projects->with('client','users','tasks');

        $this->allProjects = (clone $projects)->count();
        $this->activeProjects = (clone $projects)->where('status','active')->count();
        $this->completedProjects = (clone $projects)->where('status','completed')->count();
        $this->archivedProjects = (clone $projects)->onlyTrashed()->count();
        $this->overdueProjects = (clone $projects)->where('due_date','<', Carbon::today())->count();

        $filters = [
            new SearchFilter($this->query,'PROJECT'),
            new SortFilter($this->sort,'PROJECT'),
            new ClientFilter($this->byClient,'PROJECT'),
            new UserFilter($this->byUser,'PROJECT'),
            new TeamFilter($this->byTeam,'PROJECT'),
            new StatusFilter($this->filter,'PROJECT'),
        ];

        $projects = Pipeline::send($projects)
        ->through($filters)
        ->thenReturn();
        
        $projects->orderBy('name','asc');
        $projects = $projects->paginate(12);

        return view('livewire.projects.list-project',[
            'projects' => $projects
        ]);
    }

    public function mount(){
        
       
        $this->authorize('View Project');

        $this->project_all_count =  Project::all();
        $this->users = User::orderBy('name')->get();
        $this->clients = Client::orderBy('name')->get();
        $this->teams = Team::orderBy('name')->get();

        $tour = session()->get('tour');
        if(request()->tour == 'close-project-tour'){
            // $tour['project_tour'] = false;
            unset($tour['project_tour']);
            session()->put('tour',$tour);
        }

    }

    public function search(){
        $this->resetPage();
    }

    // public function updatedByClient($value){
    //     $this->byUser = $value;
    //     $this->redirect(route('project.index',['sort'=>$this->sort,'filter'=>$this->filter,'byClient'=>$this->byClient,'byUser'=>$this->byUser]), navigate: true);
    // }

    public function emitEditEvent($projectId)
    {
        $this->dispatch('editProject', $projectId);
    }

    public function emitDeleteEvent($projectId)
    {
        $this->dispatch('deleteProject', $projectId);
    }

    public function emitRestoreEvent($projectId)
    {
        $this->dispatch('restoreProject', $projectId);
    }

    public function emitForceDeleteEvent($projectId)
    {
        $this->dispatch('forceDeleteProject', $projectId);
    }

    public function doesAnyFilterApplied(){

        if($this->byTeam != 'all' || $this->byUser != 'all' || $this->byClient != 'all' || $this->sort != 'all' || $this->filter != 'all'){
            return true;
        }
        return false;
    }

}