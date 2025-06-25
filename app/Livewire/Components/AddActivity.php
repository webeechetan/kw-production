<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\OrganizationActivity;
use Livewire\WithFileUploads;

class AddActivity extends Component
{
    use WithFileUploads;

    protected $listeners = ['editActivity'];

    public $activity;
    public $name;
    public $description;
    public $activity_image;
    public $start_date;
    public $end_date;
    public $status;

    public function render()
    {
        return view('livewire.components.add-activity');
    }

    public function addActivity(){

        if($this->activity){
            $this->updateActivity();
            return;
        }

        $this->validate([
            'name' => 'required',
        ]);

        $activity = new OrganizationActivity();
        $activity->org_id = session('org_id');
        $activity->name = $this->name;
        $activity->description = $this->description;
        $activity->start_date = $this->start_date;
        $activity->due_date = $this->end_date;
        $activity->created_by = session('user')->id;
        if($this->activity_image){
            $image = $this->activity_image->store('images/activites');
            $image = str_replace('public/', '', $image);
            $activity->image = $image;
        }
        $activity->save();
        $this->dispatch('success', 'Activity Added Successfully');
        $this->dispatch('activity-added', $activity);
        $this->resetFrom();

    }

    public function editActivity($id){
        $this->activity = OrganizationActivity::find($id);
        $this->name = $this->activity->name;
        $this->description = $this->activity->description;
        $this->start_date = $this->activity->start_date;
        $this->end_date = $this->activity->due_date;
        $this->status = $this->activity->status;
        $this->dispatch('edit-activity', $this->activity);
    }

    public function updateActivity(){
        $this->validate([
            'name' => 'required',
        ]);

        $this->activity->name = $this->name;
        $this->activity->description = $this->description;
        $this->activity->start_date = $this->start_date;
        $this->activity->due_date = $this->end_date;
        $this->activity->status = $this->status;
        if($this->activity_image){
            $image = $this->activity_image->store('images/activites');
            $image = str_replace('public/', '', $image);
            $this->activity->image = $image;
        }
        $this->activity->save();
        $this->dispatch('success', 'Activity Updated Successfully');
        $this->dispatch('activity-added', $this->activity);
        $this->resetFrom();
    }

    public function resetFrom(){
        $this->name = null;
        $this->description = null;
        $this->activity_image = null;
        $this->start_date = null;
        $this->end_date = null;
        $this->status = null;
    }


}
