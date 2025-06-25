<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Team;
use App\Models\User;

class AddTeam extends Component
{
    use WithFileUploads;

    protected $listeners = ['editTeam', 'editTeam'];

    public $name;
    public $image;
    public $team_manager;

    public $users = [];

    // edit 

    public $team;

    public function render()
    {
        return view('livewire.components.add-team');
    }

    public function mount(){
        $this->users = User::orderBy('name')->get();
    }

    public function addTeam(){
        $this->authorize('Create Team');
        if($this->team){
            $this->updateTeam();
            return;
        } 

        $this->validate([
            'name' => "required"
        ]);

        $team = new Team();
        $team->org_id = session('org_id');
        $team->name = $this->name; 
        if($this->team_manager){
            $team->manager_id = $this->team_manager; 
        }

        if ($this->image) {
            $image = $this->image->store('images/teams');
            $image = str_replace('public/', '', $image);
            $team->image = $image; 
        }

        if($team->save()){
            $this->dispatch('success', 'Team added successfully');
            $this->dispatch('team-added');
            $this->dispatch('saved');
            $this->resetForm();
            $team = null;
        }

        
    }

    public function editTeam($id){
        $this->authorize('Edit Team');
        $this->team = Team::find($id);
        $this->name = $this->team->name;
        $this->team_manager = $this->team->manager_id;
        $this->dispatch('editTeamEvent',$this->team);
    }

    public function updateTeam(){
        $this->authorize('Edit Team');
        $this->validate([
            'name' => "required"
        ]);

        $team = Team::find($this->team->id);
        $team->name = $this->name; 
        $team->manager_id = $this->team_manager; 

        if ($this->image) {
            $image = $this->image->store('images/teams');
            $image = str_replace('public/', '', $image);
            $team->image = $image;
        }

        if($team->save()){
            $this->dispatch('success', 'Team updated successfully');
            $this->dispatch('team-updated');
            $this->dispatch('saved');
        }

    }

    public function resetForm(){
        $this->team = null;
        $this->name = '';
        $this->team_manager = '';
        $this->image = null;
        
    }

    public function removeImage(){
        if($this->team){
            $this->team->image = null;
            $this->team->save();
            $this->image = null;
        }else{
            $this->image = null;
        }
    }

}
