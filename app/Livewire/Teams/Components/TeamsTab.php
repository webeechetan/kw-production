<?php

namespace App\Livewire\Teams\Components;

use Livewire\Component;
use App\Models\Team;

class TeamsTab extends Component
{
    public $team;
    public $currentRoute;

    public function render()
    {
        return view('livewire.teams.components.teams-tab');
    }

    public function mount()
    {
        $this->currentRoute = request()->route()->getName();
    }

    public function forceDeleteTeam($id)
    {
        $this->authorize('Delete Team');
        $team = Team::withTrashed()->find($id);
        $team->forceDelete();
        $this->dispatch('success', 'Team deleted successfully.');
        $this->redirect(route('team.index'),navigate:true);
    }

    public function dispatchEditEvent($id){
        $this->dispatch('editTeam',$id);
    }
    
}
