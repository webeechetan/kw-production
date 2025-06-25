<?php

namespace App\Livewire\Roles;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class RoleView extends Component
{
    public $role;
    public $role_users = [];
    
    public function render()
    {
        $this->authorize('View Role');
        return view('livewire.roles.role-view');
    }

    public function mount(Role $role)
    {
        $this->role = $role;
        $this->role_users = User::role($role->name)->get();
    }

    public function emitEditRoleEvent($role_id){
        $this->dispatch('editRole',$role_id);
    }
}
