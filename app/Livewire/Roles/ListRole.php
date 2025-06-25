<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;


class ListRole extends Component
{

    use WithPagination;

    public $query = '';

    public $roles = [];

    public function render()
    {
        
        return view('livewire.roles.list-role',[
            'roles' => $this->roles,
        ]);
        //return view('livewire.roles.list-role');
    }

    public function mount(){
        $this->authorize('View Role');
        $this->roles = Role::where('org_id',session('org_id'))->get();

        $tour = session()->get('tour');
        if(request()->tour == 'close-role-tour'){
            // $tour['role_tour'] = false;
            unset($tour['role_tour']);
            session()->put('tour',$tour);
        }

    }

    public function emitEditRoleEvent($role_id){
        $this->dispatch('editRole',$role_id);
    }

    public function search(){
        $this->resetPage();
    }
}
