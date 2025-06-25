<?php

namespace App\Livewire\Users;

use App\Models\Organization;
use Livewire\Component;
use App\Models\User;
use App\Models\Project;
use App\Models\Team;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Notifications\UserWelcomeNotification;
use App\Notifications\InviteUser;
use App\Helpers\Helper;

class AddUser extends Component
{
    use WithFileUploads, WithPagination;

    public $name;
    public $email;
    public $password;
    public $teams = [];
    public $team_ids;
    public $image;

    public function render()
    {
        return view('livewire.users.add-user');
    }

    public function mount()
    {
        $this->teams = Team::all();
    }

    public function store()
    {

        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ]);

        $user = new User();
        $user->org_id = session('org_id');
        $user->name = $this->name;
        $user->email = $this->email;

        if($this->image){
            $this->validate([
                'image' => 'image|max:1024', // 1MB Max
            ]);

            $image = $this->image->store('public/images/users');
            // remove public from the path as we need to store only the path in the db
            $image = str_replace('public/', '', $image);
            
            $user->image = $image;
        }else{
            $user->image = Helper::createAvatar($user->name,'users');
        }


        $user->password = Hash::make($this->password);

        try{
            $user->save();
            $user->teams()->attach($this->team_ids); 
            $user->notify(new UserWelcomeNotification($this->password));
        }catch(\Exception $e){
            session()->flash('error','Something went wrong');
        }

        session()->flash('success','User created successfully');

        return $this->redirect(route('user.index'), navigate:true);
    }

    public function inviteUser(){
        $this->validate([
            'email' => 'required|email|unique:users,email'
        ]);

        $user = new User();
        $user->org_id = session('org_id');
        $user->name = $this->email;
        $user->email = $this->email;
        $user->password = Hash::make(rand(100000,999999));
        $user->save();
        $org = Organization::find(session('org_id'));
        $user->notify(new InviteUser($org));
        session()->flash('success','User invited successfully');
        return $this->redirect(route('user.index'), navigate:true);
    }
}
