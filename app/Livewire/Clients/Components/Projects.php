<?php

namespace App\Livewire\Clients\Components;

use Livewire\Component;
use App\Models\Client;
use App\Models\Scopes\MainClientScope;
use Carbon\Carbon;
use Database\Factories\ClientFactory;
use Livewire\Attributes\On; 
use Illuminate\Support\Facades\Auth;


class Projects extends Component
{
    public $id;
    public $client;
    public $projects;
    public $filter = 'all';
    public $sort = 'all';

    public $project_no_count;

    protected $listeners = ['project-added' => 'refresh', 'project-updated' => 'refresh', 'project-deleted' => 'refresh'];

    public function render()
    {
        return view('livewire.clients.components.projects');
    }

    public function refresh(){
        $this->mount($this->id);
    }

    public function mount($id)
    {
        $main_client_id = Auth::user()->organization->mainClient->id;
        if($main_client_id != $id){
            $this->authorize('View Client');
        }
        $this->id = $id;
        $this->client = Client::withoutGlobalScope(MainClientScope::class)->find($id);
        $this->projects = $this->client->projects;
        $this->project_no_count = $this->client->projects;
    }

    public function updatedFilter()
    {
        if($this->filter == 'overdue')
        {
            $this->projects = $this->client->projects()->where('due_date', '<', Carbon::now())->get();
        }else{
            if($this->filter != 'all'){
                if($this->filter == 'archived')
                {
                    $this->projects = $this->client->projects()->where('deleted_at', '!=', null)->get();
                }else{
                    $this->projects = $this->client->projects()->where('status', $this->filter)->get(); 
                }
            }else{
                $this->projects = $this->client->projects;
            }
        }
    }
    
    public function emitEditEvent($id)
    {
        $this->dispatch('editProject', $id);
    }

    public function emitDeleteEvent($id)
    {
        $this->dispatch('deleteProject', $id);
    }

}
