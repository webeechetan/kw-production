<?php

namespace App\Livewire\Teams;

use Livewire\Component;
use App\Models\Team;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Pipeline;
use App\Filters\{SearchFilter, UserFilter, SortFilter};

class ListTeam extends Component
{
    use WithPagination;

    protected $listeners = ['team-added' => 'refresh'];

    public $allTeams;
       
    public $query = '';

    public $sort = 'all';
    public $filter = 'all';
    public $byUser = 'all'; 


    public $team_members = [];
    public $team_projects = [];
    public $team_tasks = [];
    public $team_clients = [];
    public $team;
    public $users;

    public function render()
    {
        $this->allTeams = Team::count();
        $teams = Team::with('users');

        $filters = [
            new SearchFilter($this->query),
            new SortFilter($this->sort),
            new UserFilter($this->byUser, 'TEAM'),
        ];

        Pipeline::send($teams)
            ->through($filters)
            ->thenReturn();

        $teams->orderBy('name', 'asc');
        $teams = $teams->paginate(12);

        return view('livewire.teams.list-team',[
            'teams' => $teams,
        ]);
    } 


    public function mount(){
        $this->authorize('View Team');
        $this->users = User::orderBy('name')->get();

        $tour = session()->get('tour');
        if(request()->tour == 'close-team-tour'){
            // $tour['team_tour'] = false;
            unset($tour['team_tour']);
            session()->put('tour',$tour);
        }
    }

    public function refresh(){
        $this->mount();
    }


    public function search(){
        $this->resetPage();
    }

    public function doesAnyFilterApplied(){
        return  $this->byUser != 'all'  || $this->sort != 'all' || $this->filter != 'all';
    }


}

