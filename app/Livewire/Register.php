<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Organization;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Permission;
use App\Helpers\Helper;
use App\Models\Scopes\OrganizationScope;



class Register extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $password;
    public $user_name;
    public $industry_type;

    // registration journey properties
    public $companysize;
    public $step = 1;
    public $image;
    public $memberemail;
    public $organization;

    public function render()
    {
        return view('livewire.register')->layout('auth.register');
    }

    public function register()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:organizations',
            'password' => 'required|min:6',
            'user_name' => 'required',
        ]);

        $organization = new Organization();
        $organization->name = $this->name;
        $organization->email = $this->email;
        $organization->password = Hash::make($this->password);

        try {
            $organization->save();
            // create folder for organization
            $path = public_path('storage/'.$organization->name);
            if(!file_exists($path)){
                mkdir($path, 0777, true);
            }
            $user = new User();
            $user->name = $this->user_name;
            $user->email = $this->email;
            $user->password = Hash::make($this->password);
            $user->org_id = $organization->id;
            $user->is_owner = true;
            $user->save();

            $client = new Client();
            $client->name = $this->name;
            $client->org_id = $organization->id;
            $client->is_main = true;
            $client->created_by = $user->id;
            $client->use_brand_name = false;
            $client->onboard_date = now();
            $client->save();

            $roles = [
                'Admin' => [
                    'Create Client',
                    'Edit Client',
                    'Delete Client',
                    'View Client',
                    'Create Project',
                    'Edit Project',
                    'Delete Project',
                    'View Project',
                    'Create User',
                    'Edit User',
                    'Delete User',
                    'View User',
                    'Create Team',
                    'Edit Team',
                    'Delete Team',
                    'View Team',
                    'Create Task',
                    'Edit Task',
                    'Delete Task',
                    'View Task',
                    'Create Role',
                    'Edit Role',
                    'Delete Role',
                    'View Role',
                ],
                'HR' => [
                    'Create User',
                    'Edit User',
                    'Delete User',
                    'View User',
                ],
                'Manager' => [
                    'Create Project',
                    'Edit Project',
                    'Delete Project',
                    'View Project',
                    'Create Team',
                    'Edit Team',
                    'Delete Team',
                    'View Team',
                    'Create Task',
                    'Edit Task',
                    'Delete Task',
                    'View Task',
                ],
                'Team Member' => [
                    'View Project',
                    'View Team',
                    'View Task',
                ],
            ];
    
            $admin_id  = null;
    
            foreach ($roles as $role => $permissions) {
                $role = Role::create(['name' => $role, 'org_id' => $organization->id]);
                if($role->name == 'Admin'){
                    $admin_id = $role->id;
                }
                $role->syncPermissions(Permission::whereIn('name', $permissions)->get());
            }

            setPermissionsTeamId($organization->id);
            $user->assignRole($admin_id);
            $tour = [
                'main_tour' => 'true',
                'client_tour' => 'true',
                'project_tour' => 'true',
                'team_tour' => 'true',
                'task_tour' => 'true',
                'user_tour' => 'true',
                'role_tour' => 'true',
                'email_tour' => $organization->email,
            ];
            Auth::login($user);
            session()->put('newly_registered',true);
            session()->put('guard','web');
            session()->put('org_id',User::withoutGlobalScope(OrganizationScope::class)->where('email',$this->email)->first()->org_id);
            $org_name = Organization::find(session('org_id'))->name;
            $org_name = str_replace(' ','-',$org_name);
            session()->put('org_name',$org_name);
            session()->put('user',User::withoutGlobalScope(OrganizationScope::class)->where('email',$this->email)->first());
            session()->put('tour',$tour);            

        } catch (\Throwable $th) {
            dd($th);
            return $this->alert('error', 'Something went wrong, please try again later');
        }


        
         $this->step = 2;
        //  return $this->redirect(route('login'),navigate: true);
    }


    public function registerStepOne(){
        
        $user = Auth::user();
        $organization = Organization::where('id', $user->org_id)->first();
        if($this->image){
            $image = $organization->image = $this->image->store('images/organizations');
            $image = str_replace('public/', '', $image);
            $organization->image = $image;
            $organization->save();
            $this->dispatch('success', 'Company size added');
            return $this->redirect(session('org_name') .'/dashboard');
        }
    }

    // public function registerStepTwo()
    // {
    //     $this->validate([
    //         'memberemail'=>'required|email',
    //     ]);

    //     $user = Auth::user();
    //     $organization = Organization::where('id', $user->org_id)->first();
    //     $organization->memberemail = $this->memberemail;
    //     $this->dispatch('success', 'Member email  added');

    //     $this->step = 4;    
    // }


}
