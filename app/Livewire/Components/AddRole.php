<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class AddRole extends Component
{
    public $role;

    public $role_name;

    public $permissions = [];

    public $client_permissions = [];
    public $project_permissions = [];
    public $team_permissions = [];
    public $task_permissions = [];
    public $user_permissions = [];
    public $role_permissions = [];

    // selected permissions

    public $user_selected_permissions = [];
    public $client_selected_permissions = [];
    public $project_selected_permissions = [];
    public $team_selected_permissions = [];
    public $task_selected_permissions = [];
    public $role_selected_permissions = [];


    public $selected_permissions = [];

    protected $listeners = ['editRole'];

    public function render()
    {
        return view('livewire.components.add-role');
    }

    public function mount(){
        $this->permissions = Permission::all();
        $this->client_permissions = Permission::where('group_name','Client Management')->get();
        $this->project_permissions = Permission::where('group_name','Project Management')->get();
        $this->team_permissions = Permission::where('group_name','Team Management')->get();
        $this->task_permissions = Permission::where('group_name','Task Management')->get();
        $this->user_permissions = Permission::where('group_name','User Management')->get();
        $this->role_permissions = Permission::where('group_name','Role Management')->get();
    }

    public function addRole(){

        if($this->role){
            $this->updateRole();
            return;
        }

        $this->validate([
            'role_name' => 'required'
        ]);

        $this->selected_permissions = array_merge($this->user_selected_permissions,$this->client_selected_permissions,$this->project_selected_permissions,$this->team_selected_permissions,$this->task_selected_permissions,$this->role_selected_permissions);

        // parse selected permissions to integer array

        $selected_permissions = [];

        foreach($this->selected_permissions as $permission){
            $selected_permissions[] = (int)$permission;
        }

        $role = Role::create(['name' => $this->role_name,'org_id' => session('org_id')]);

        $role->syncPermissions($selected_permissions);
        

        $this->dispatch('role-added');
    }

    public function editRole($role_id){
        $role = Role::find($role_id);
        $this->role = $role;
        $this->role_name = $role->name;

        $role_permissions = $role->permissions;

        $this->selected_permissions = $role_permissions->pluck('id')->toArray();

        $this->user_selected_permissions = $role_permissions->whereIn('group_name','User Management')->pluck('id')->toArray();
        $this->client_selected_permissions = $role_permissions->whereIn('group_name','Client Management')->pluck('id')->toArray();
        $this->project_selected_permissions = $role_permissions->whereIn('group_name','Project Management')->pluck('id')->toArray();
        $this->team_selected_permissions = $role_permissions->whereIn('group_name','Team Management')->pluck('id')->toArray();
        $this->task_selected_permissions = $role_permissions->whereIn('group_name','Task Management')->pluck('id')->toArray();
        $this->role_selected_permissions = $role_permissions->whereIn('group_name','Role Management')->pluck('id')->toArray();

        $this->dispatch('edit-role');
    }

    public function updateRole(){
        $this->validate([
            'role_name' => 'required'
        ]);

        $this->selected_permissions = array_merge($this->user_selected_permissions,$this->client_selected_permissions,$this->project_selected_permissions,$this->team_selected_permissions,$this->task_selected_permissions,$this->role_selected_permissions);

        // parse selected permissions to integer array

        $selected_permissions = [];

        foreach($this->selected_permissions as $permission){
            $selected_permissions[] = (int)$permission;
        }

        $this->role->update(['name' => $this->role_name]);

        $this->role->syncPermissions($selected_permissions);

        $this->dispatch('role-updated');
    }
}
