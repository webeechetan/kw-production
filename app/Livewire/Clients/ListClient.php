<?php

namespace App\Livewire\Clients;

use Livewire\Component;
use App\Models\Client;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Pipeline;
use App\Filters\{SearchFilter, StatusFilter, SortFilter};


class ListClient extends Component
{
    use WithPagination;

    // check for refreshClients event

    protected $listeners = ['refreshClients' => '$refresh'];

    public $allClients;
    public $activeClients;
    public $completedClients;
    public $archivedClients;

    public $query = '';

    //  filters & sorts

    public $sort = 'all';
    public $status = 'all';


    public $client_onboard_date;
    public $client_name;
    public $client_description;
    public $client_image;

    public $client;

    public function mount()
    {
        $this->authorize('View Client');
        $tour = session()->get('tour');
        if(request()->tour == 'close-client-tour'){
            // $tour['client_tour'] = false;
            unset($tour['client_tour']);
            session()->put('tour',$tour);
        }
    }

    public function render()
    {
        $clients = Client::query();

        $filters = [
            new SearchFilter($this->query,'CLIENT'),
            new SortFilter($this->sort),
            new StatusFilter($this->status, 'CLIENT'),
        ];

        
        $this->allClients = (clone $clients)->count();
        $this->activeClients = (clone $clients)->where('status','active')->count();
        $this->completedClients = (clone $clients)->where('status','completed')->count();
        $this->archivedClients = (clone $clients)->onlyTrashed()->count();

        $clients = Pipeline::send($clients)
        ->through($filters)
        ->thenReturn();
        
        $clients = $clients->with('projects','projects.users');
        
        $clients->orderBy('name','asc');
        
        $clients = $clients->paginate(12);
        
        return view('livewire.clients.list-client',[
            'clients' => $clients,
        ]);
    }


    public function emitEditEvent($clientId)
    {
        $this->dispatch('editClient', $clientId);
    }

    public function emitDeleteEvent($clientId)
    {
        $this->dispatch('deleteClient', $clientId);
    }

    public function emitRestoreEvent($clientId)
    {
        $this->dispatch('restoreClient', $clientId);
    }

    public function emitForceDeleteEvent($clientId)
    {
        $this->dispatch('forceDeleteClient', $clientId);
    }

    public function search()
    {
        $this->resetPage();
    }

    public function doesAnyFilterApplied(){
        return $this->sort != 'all' || $this->status != 'all';
    }


}
