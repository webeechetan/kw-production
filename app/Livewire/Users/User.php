<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User as UserModel;
use App\Models\Project;
use App\Models\Client;
use App\Models\UserDetail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class User extends Component
{
    use WithFileUploads;

    protected $listeners = ['social-link-added' => 'refresh'];

    public $user;
    public $user_clients = [];
    public $user_projects = [];

    public $bio;
    public $skills;
    public $user_image;
    public $socialLink;

    public $current_password;
    public $new_password;

    public function render()
    {
        return view('livewire.users.user');
    }

    public function mount($user_id)
    {
        $this->user = UserModel::where('id' , $user_id)->withTrashed()->first();
        $users_task_array = $this->user->tasks->groupBy('project_id');
        $this->user_clients = Project::whereIn('id',$users_task_array->keys())->get()->groupBy('client_id');
        $this->user_clients = Client::whereIn('id',$this->user_clients->keys())->get();
        $this->user_projects = Project::whereIn('id',$users_task_array->keys())->get();
        
    }

    public function refresh(){
        $this->mount($this->user->id);
    }

    public function emitEditUserEvent($user_id){
        $this->dispatch('editUser', $user_id);
    }

    public function changePassword(){
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8',
        ]);

        if(password_verify($this->current_password, $this->user->password)){
            $this->user->password = Hash::make($this->new_password);
            $this->user->save();
            $this->current_password = '';
            $this->new_password = '';
            $this->dispatch('success', 'Password changed successfully.');
        }else{
            $this->dispatch('error', 'Current password is incorrect.');
        }
    }

    public function changeUserStatus($status)
    {
        if($status == 'archived'){
            $this->user->delete();
            $this->redirect(route('user.index'),navigate:true);
            // $this->dispatch('success', 'Client archived successfully.');
        }else{
            $this->user->restore();
            $this->dispatch('success', 'User status updated successfully.');
        }
        $this->redirect(route('user.profile', $this->user->id),navigate:true);
    }

    public function forceDeleteUser($userId)
    {
        // $user = UserModel::withTrashed()->find($userId);
        // $user->forceDelete();
        // $this->dispatch('success', 'User deleted successfully.');
        // $this->redirect(route('user.index'),navigate:true);

    }

    public function updateBio(){
        $user_details = UserDetail::where('user_id', $this->user->id)->first();
        if($user_details){
            $user_details->bio = $this->bio;
            $user_details->save();
        }else{
            $user_details = new UserDetail();
            $user_details->user_id = $this->user->id;
            $user_details->bio = $this->bio;
            $user_details->save();
        }
    }

    public function updateSkills(){
        $user_details = UserDetail::where('user_id', $this->user->id)->first();
        if($user_details){
            $user_details->skills = $this->skills;
            $user_details->save(); 
        }else{
            $user_details = new UserDetail();
            $user_details->user_id = $this->user->id;
            $user_details->skills = $this->skills;
            $user_details->save();
        }
        $this->mount($this->user->id);
    }

    public function updateDob($dob){
        $user_details = UserDetail::where('user_id', $this->user->id)->first();
        if($user_details){
            $user_details->dob = $dob;
            $user_details->save();
        }else{
            $user_details = new UserDetail();
            $user_details->user_id = $this->user->id;
            $user_details->dob = $dob;
            $user_details->save();
        }
    }

    public function saveCroppedImage($tmpUploadedFileName)
    {
        $user = UserModel::find($this->user->id);
        if($tmpUploadedFileName){
            if($user->image){
                Storage::disk('public')->delete($user->image);
            }
            $this->user_image->storeAs('images/users', $tmpUploadedFileName, 'public');
            $user->image = 'images/users/'.$tmpUploadedFileName;
            $user->save();
            $this->dispatch('success', 'Profile image updated successfully.');
            $this->refresh();
        }


    }

    public function updateSocialLink($type){
        $user_details = UserDetail::where('user_id', $this->user->id)->first();
        if($user_details){
            $user_details->$type = $this->socialLink;
            $user_details->save();
        }else{
            $user_details = new UserDetail();
            $user_details->user_id = $this->user->id;
            $user_details->$type = $this->socialLink;
            $user_details->save();
        }
        $this->dispatch('social-link-added');
        $this->dispatch('success', 'Social Link Added');
    }
}
