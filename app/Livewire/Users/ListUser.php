<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use App\Models\Team;
use App\Models\Project;
use Illuminate\Support\Facades\Pipeline;
use App\Filters\{ClientFilter, ProjectFilter, UserFilter, SearchFilter, StatusFilter, SortFilter, DateFilter, TeamFilter};
use Livewire\WithPagination;



class ListUser extends Component
{

    use WithPagination;

     public $allUsers;
     public $activeUsers;
     public $archivedUsers;

     public $query = '';


     public $byTeam = 'all';
     public $byUser = 'all'; 
     public $byProject = 'all';

 
     public $teams = [] ;
     public $projects = [] ;


    // //  filters & sorts

      public $sort = 'all';
      public $filter = 'all';


      public $user;
  

    public function render()
    {

        $users = User::query();
        $users->orderBy('name');

        $this->allUsers = (clone $users)->count();
        $this->activeUsers = (clone $users)->where('status', 'active')->count();
        $this->archivedUsers = (clone $users)->where('status', 'archived')->count();

        $users = Pipeline::send($users)
            ->through([
                new SearchFilter($this->query),
                new SortFilter($this->sort),
                new StatusFilter($this->filter),
                new TeamFilter($this->byTeam, 'USER'),
                new ProjectFilter($this->byProject),
            ])
            ->thenReturn()
            ->paginate(9);


        return view('livewire.users.list-user',[
            'users' => $users,
        ]);
        
    }

    public function mount(){

        $this->authorize('View User');
        $this->teams = Team::orderBy('name')->get();
        $this->projects = Project::orderBy('name')->get();

        $tour = session()->get('tour');
        if(request()->tour == 'close-user-tour'){
            // $tour['user_tour'] = false;
            unset($tour['user_tour']);
            session()->put('tour',$tour);
        }
    }

    public function search()
    {
        $this->resetPage();
    }  

    public function doesAnyFilterApplied(){
        return $this->byTeam != 'all' || $this->byProject != 'all' || $this->sort != 'all' || $this->filter != 'all';
    }



}
