<?php

namespace App\Livewire\Teams\Components;

use Livewire\Component;
use Carbon\Carbon;
use Livewire\Attributes\On; 
use App\Models\{ Team, Project, Task, User, Client };


class Projects extends Component
{  


    public $project_start_date = null;
    public $project_due_date = null;

    
    public $startDate = null; 
    public $dueDate = null;

    public $filter = 'all';
    public $team;
    public $clients;
    public $users;
    public $users_projects = [];
    public $query = '';
    public $sort = 'all';

    public $byClient = 'all';
    public $byUser = 'all';
    public $status = 'all';

    public $project;
 
    public $projects= [];

    // filters

    public $filterByClient = null;
    public $filterByUser = null;
    public $start_date = null;
    public $end_date = null;

    public function render()
    {
       return view('livewire.teams.components.projects');
    }
    

    public function mount(Team $team){
        $this->team = $team;
        $this->clients = Client::orderBy('name', 'asc')->get();
        $this->users = User::orderBy('name', 'asc')->get();
        $this->projects = $this->team->projects;
    }

    public function updatedFilterByClient($value){
        $client = Client::find($value);
        $this->users = $client->users;
    }

    public function doesAnyFilterApplied(){
        // return $this->byClient != 'all' || $this->byUser != 'all' || $this->status != 'all' || $this->startDate || $this->dueDate || $this->filter != 'all';
        return $this->byClient != 'all' || $this->byUser != 'all' || $this->status != 'all' || $this->project_start_date || $this->project_due_date || $this->filter != 'all';
    }
}
