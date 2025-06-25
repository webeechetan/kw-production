<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Organization;
use Livewire\WithFileUploads;

class RegistrationJourney extends Component
{
    use WithFileUploads;

    public $companysize;
    public $step = 1;
    public $image;
    public $logo;
    public $memberemail;
    public $organization;

    public function render()
    {
        return view('livewire.registration-journey');
    }

    public function registerstepone(){

        $this->validate([
            'companysize'=>'required|integer',
        ]);


        $user = Auth::user();
        $organization = Organization::where('id', $user->org_id)->first();
        $organization->companysize = $this->companysize;
        
        $organization->save();
        $this->dispatch('success', 'Company size added');

        $this->step = 2;

    }

    public function registersteptwo()
    {
        $this->validate([
            'memberemail'=>'required|email',
        ]);

        $user = Auth::user();
        $organization = Organization::where('id', $user->org_id)->first();
        $organization->memberemail = $this->memberemail;
        $organization->save();
        $this->dispatch('success', 'Member email  added');

        $this->step = 3;    
    }

    public function dashboard()
    {

        $user = Auth::user();
        $organization = Organization::where('id', $user->org_id)->first();

        
            $image = $this->logo->store('images/organizations');
            $image = str_replace('public/', '', $image);
            $organization->image = $image;
            $organization->save();
        
            
       
        return redirect()->route('dashboard');
    }
}
