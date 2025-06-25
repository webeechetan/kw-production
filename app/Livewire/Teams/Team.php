<?php

namespace App\Livewire\Teams;

use Livewire\Component;
use App\Models\Team as TeamModel;

class Team extends Component
{
    public $team;

    public function render()
    {
        return view('livewire.teams.team');
    }

    public function mount(TeamModel $team){
        
        $this->authorize('View Team');
        $this->team = $team;
    }

}
