<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Client;
use App\Models\Team;
use App\Models\Project;

class VoiceControl extends Component
{
    public $team;

    public function render()
    {
        return view('livewire.components.voice-control');
    }

    public function createClient($name)
    {
        $client = new Client();
        $client->org_id = session('org_id');
        $client->name = $name;
        $client->save();
        $this->dispatch('command-success','Client created successfully');
        $this->dispatch('client-created',$client);
    }

    public function createProject($project_name,$client_name)
    {
        $client = Client::where('name',$client_name)->first();
        if(!$client){
            $this->dispatch('command-error','Client not found');
            return;
        }
        $project = new Project();
        $project->org_id = session('org_id');
        $project->client_id = $client->id;
        $project->name = $project_name;
        $project->created_by = session('user')->id;
        $project->save();
        $this->dispatch('command-success','Project created successfully');
        $this->dispatch('project-created',$project);
    }

    public function getTeamStats($team_name){
        $this->team = Team::with('users')->where('name',$team_name)->first();
        if(!$this->team){
            $this->dispatch('command-error','Team not found');
            return;
        }
        $this->dispatch('command-success','Team found');
        $this->dispatch('team-stats',$this->team);
    }

    public function viewClient($client_name){
        $client = Client::where('name',$client_name)->first();
        if(!$client){
            $this->dispatch('command-error','Client not found');
            return;
        }
        $this->redirect(route('client.profile',$client->id),navigate:true);
        $this->dispatch('command-success','Client found');
        $this->dispatch('client-view',$client);
    }
}
