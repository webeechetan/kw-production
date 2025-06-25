<?php

namespace App\Livewire\Organizations;

use Livewire\Component;
use App\Models\Organization;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;

class Profile extends Component
{
    use WithFileUploads;

    public $organization;
    public $image;

    public function render()
    {
        return view('livewire.organizations.profile');
    }

    public function mount()
    {
        $this->organization = Organization::find(session('org_id'));
    }

    public function updatedImage()
    {
        
        $this->validate([
            'image' => 'image', // 1MB Max
        ]);

        $organization = Organization::find(session('org_id'));
        $organization->image = $this->image->store('organizations');
        $organization->save();
        $this->dispatch('success', 'Organization Image Updated Successfully');
    }

}
