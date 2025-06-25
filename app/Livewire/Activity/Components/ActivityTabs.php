<?php

namespace App\Livewire\Activity\Components;

use Livewire\Component;
use App\Models\Activity;
use App\Models\OrganizationActivity;
class ActivityTabs extends Component
{
    protected $listeners = ['activity-added'=>'refresh'];

    public $activity;
    public $currentRoute;
    

    public function render()
    {
        return view('livewire.activity.components.activity-tabs');
    }

    public function mount(){
        $this->currentRoute = request()->route()->getName();
    }

    public function emitEditActivityEvent($activity)
    {
        $this->dispatch('editActivity', $activity);
    }

    public function forceDeleteActivity($id)
    {

        $org_activity = OrganizationActivity::withTrashed()->find($id);
        $org_activity->forceDelete();
        $this->dispatch('success', 'Activity deleted successfully.');
        $this->redirect(route('activity.index'),navigate:true);
    }


    public function refresh(){
        $this->activity = OrganizationActivity::find($this->activity->id);
    }
    
}
