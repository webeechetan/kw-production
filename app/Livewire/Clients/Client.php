<?php

namespace App\Livewire\Clients;

use Livewire\Component;
use App\Models\Client as ClientModel;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\User;
use App\Helpers\Helper;
use App\Models\Task;
use Livewire\WithFileUploads;
use App\Models\Scopes\MainClientScope;
use Illuminate\Support\Facades\Auth;

class Client extends Component
{
    use WithFileUploads;

    public $client;
    public $client_id;

    public $active_projects;
    public $completed_projects;
    public $overdue_projects;
    public $archived_projects;

    public $description;

    public $project = null;
    public $project_name;
    public $project_start_date;
    public $project_due_date;
    public $project_image;
    public $project_description;

    public $client_teams = [];
    public $client_users = [];


    public function render()
    {
        return view('livewire.clients.client');
    }

    public function mount($id)
    {
        $main_client_id = Auth::user()->organization->mainClient->id;
        if($main_client_id != $id){
            $this->authorize('View Client');
        }

        $this->client_id = $id;
        $this->client = ClientModel::withoutGlobalScope(MainClientScope::class)->withTrashed()->with('projects')->find($id);
        $this->description = $this->client->description;
        $this->active_projects = $this->client->projects()->where('status', 'active')->get();
        $this->completed_projects = $this->client->projects()->where('status', 'completed')->get();
        $this->overdue_projects = $this->client->projects()->where('status', 'overdue')->get();
        $this->archived_projects = $this->client->projects()->where('status', 'archived')->get();
        
        $this->client_users = $this->client->users;

        $this->client_teams = $this->client->teams;

        
    }

    public function updateDescription(){
        $this->client->description = $this->description;
        $this->client->save();
        $this->dispatch('success', 'Client description updated successfully.');
    }

}
