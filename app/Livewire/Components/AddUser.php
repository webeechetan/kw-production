<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Team;
use App\Helpers\Helper;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Notifications\UserWelcomeNotification;
use App\Notifications\InviteUser;
use App\Models\Organization;

class AddUser extends Component
{
    use WithFileUploads;

    protected $listeners = ['addUser','editUser'];

    public $name;
    public $email;
    public $password;
    public $designation;
    public $teams = [];
    public $main_team_id;
    public $role;

    public $roles = [];

    // for edit

    public $user;


    public function render()
    {
        return view('livewire.components.add-user');
    }

    public function mount()
    {
        $this->teams = Team::all();
        $this->roles = Role::where('org_id',session('org_id'))->get();
    }

    public function addUser(){
        if($this->user){
            $this->updateUser();
            return;
        } 

        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->password = Hash::make($this->password);
        $user->designation = $this->designation;
        $user->color = Helper::colors()[rand(0,5)];
        $user->org_id = session('org_id');
        $user->created_by = session('user')->id;
        $user->main_team_id = $this->main_team_id;
        $user->save();
        $user->notify(new UserWelcomeNotification($this->password));
        $this->role = (int)$this->role;
        $user->assignRole($this->role);
        $this->dispatch('success', 'User added successfully');
        $this->dispatch('saved');
        $this->dispatch('user-added');
        $this->resetForm();
    }

    public function editUser($user_id){
        setPermissionsTeamId(session('org_id'));

        $user = User::with('roles')->find($user_id);
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->designation = $user->designation;
        $this->main_team_id = $user->main_team_id;
        $this->role = $user->roles->first()->id ?? null;
        $this->dispatch('edit-user',[$this->main_team_id,$user->roles->pluck('id')->toArray()]);
    }

    public function updateUser(){
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$this->user->id,
        ]);

        $user = $this->user;
        $user->name = $this->name;
        $user->email = $this->email;
        $user->designation = $this->designation;
        $user->main_team_id = $this->main_team_id;
        $user->save();
        $this->role = (int)$this->role;
        setPermissionsTeamId(session('org_id'));
        $user->syncRoles([$this->role]);
        $this->dispatch('success', 'User updated successfully');
        $this->dispatch('saved');
        $this->dispatch('user-updated');
    }

    public function inviteUser(){
        $this->validate([
            'email' => 'required|email|unique:users,email'
        ]);

        $user = new User();
        $user->org_id = session('org_id');
        $user->name = strtok($this->email, '@');
        $user->email = $this->email;
        $user->created_by = session('user')->id;
        // generate random password which contains one uppercase, one lowercase, one number and one special character
        $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()'),0,8);
        $user->password = Hash::make($password);
        $user->save();
        $role = Role::where('org_id',session('org_id'))->where('name','Employee')->first();
        $user->assignRole($role->id);
        $org = Organization::find(session('org_id'));
        $user->notify(new InviteUser($org,$password));
        $this->dispatch('saved');
        $this->dispatch('user-added');
    }

    public function resetForm(){
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->designation = '';
        $this->main_team_id = '';
        $this->role = '';
        $this->user = null;
    }


}
