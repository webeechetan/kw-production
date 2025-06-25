<?php

namespace App\Livewire\Projects\Components;

use Livewire\Component;
use App\Models\Project;

class ProjectTabs extends Component
{
    public $project;
    public $currentRoute;

    protected $listeners = ['project-added' => 'refresh'];

    public function render()
    {
        $this->authorize('View Project');
        return view('livewire.projects.components.project-tabs');
    }


    // call when project-added event is emitted
    public function mount(Project $project)
    {
        $this->project = $project;
        $this->currentRoute = request()->route()->getName();
    }

    public function emitEditProjectEvent($id){
        $this->dispatch('editProject',$id);
    }

    public function emitDeleteProjectEvent($id){

        $this->dispatch('deleteProject',$id);
    }

    public function forceDeleteProject($projectId)
    {
        
        $project = Project::withTrashed()->find($projectId);
        $project->forceDelete();
        $this->dispatch('success', 'Project deleted successfully.');
        $this->redirect(route('project.index'),navigate:true);
    }

    public function changeProjectStatus($status){
        $this->project->status = $status;
        if($this->project->trashed()){
            $this->project->restore();
        }
        $this->project->save();
        $this->dispatch('success','Project status changed successfully');
    }
}
