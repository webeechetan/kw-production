<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use App\Helpers\Helper;
use Livewire\WithFileUploads;
use App\Models\Client;
use App\Models\Scopes\MainClientScope;

class AddProject extends Component
{
    use WithFileUploads;

    public $users = [];
    public $clients = [];
    
    public $project;
    public $client;
    
    public $client_id;
    public $project_name;
    public $project_description;
    public $project_due_date;
    public $project_start_date;
    public $image;
    public $project_users = [];
    
    protected $listeners = ['editProject', 'deleteProject','restoreProject','forceDeleteProject'];

    public function render()
    {
        return view('livewire.components.add-project');
    }

    public function mount()
    {
        $this->users = User::orderBy('name')->get();
        $this->clients = Client::orderBy('name')->get();
        if(request()->routeIs('client.projects')){
            $this->client = Client::withoutGlobalScope(MainClientScope::class)->find(request()->id);
            $this->client_id = $this->client->id;
        }
    }

    public function updatedClientId($value)
    {
        $this->client = Client::find($value);
    }


    public function addProject()
    {

        if($this->project){
            $this->updateProject();
            return;
        }

        $validation_error_message = '';

        if(!$this->project_name){
            $validation_error_message .= 'Name is required. ';
        }

        if(!$this->client_id){
            $validation_error_message .= 'Client is required. ';
        }

        if(!$this->project_name || !$this->client_id){
            $this->dispatch('error',$validation_error_message);
            return;
        }

        $this->validate([
            'project_name' => 'required',
        ]);

        $image  = '';

        if ($this->image) {
            $image = $this->image->store('images/projects');
            $image = str_replace('public/', '', $image);
        }

        $project = new Project();
        $project->org_id = session('org_id');
        $project->client_id = $this->client->id;
        $project->description = $this->project_description;
        $project->name = $this->project_name;
        $project->start_date = $this->project_start_date;
        $project->due_date = $this->project_due_date;
        $project->image = $image;
        $project->created_by = session('user')->id;
        try {
            if($project->save()){
                // create a folder for the project inside its client folder
                
                $path = 'storage/'. session('org_name') . '/' . $this->client->name . '/' . $this->project_name;
                $path = public_path($path);
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                if(count($this->project_users) > 0){
                    $project->users()->sync($this->project_users);
                }

                if(!in_array(auth()->user()->id, $this->project_users)){
                    $project->users()->attach(session('user')->id);
                }

                $this->dispatch('success', 'Project added successfully');
                $this->dispatch('project-added', $project);
                $this->dispatch('saved');
                $this->resetForm();
            }

        } catch (\Exception $e) {
            $this->dispatch('error', 'Error adding project');
            return;
        }
    }

    public function editProject($id)
    {
        $this->project = Project::find($id);
        // dd($this->project);
        $this->client_id = $this->project->client_id;
        $this->project_name = $this->project->name;
        $this->project_description = $this->project->description;
        $this->project_due_date = $this->project->due_date;
        $this->project_start_date = $this->project->start_date;
        $this->project_users = $this->project->users->pluck('id')->toArray();
        $this->dispatch('edit-project', $this->project);
    }

    public function updateProject(){

        $validation_error_message = '';

        if(!$this->project_name){
            $validation_error_message .= 'Name is required. ';
        }

        if(!$this->client_id){
            $validation_error_message .= 'Client is required. ';
        }

        if(!$this->project_name || !$this->client_id){
            $this->dispatch('error',$validation_error_message);
            return;
        }

        $this->validate([
            'project_name' => 'required',
        ]);

        $image  = '';

        if ($this->image) {
            $image = $this->image->store('images/projects');
            // remove public from the path as we need to store only the path in the db
            $image = str_replace('public/', '', $image);
        }else{
            $image = $this->project->image;
        }

        $this->project->update([
            'description' => $this->project_description,
            'name' => $this->project_name,
            'start_date' => $this->project_start_date,
            'due_date' => $this->project_due_date,
            'image' => $image,
            'client_id' => $this->client_id,
        ]);

        if(count($this->project_users) > 0){
            $this->project->users()->sync($this->project_users);
        }

        $this->dispatch('project-added', $this->project);
        $this->resetForm();

        $this->dispatch('success', 'Project updated successfully.');

    }

    public function deleteProject($id)
    {
        $project = Project::find($id);
        $project->delete();
        $this->dispatch('success', 'Project deleted successfully.');
        $this->dispatch('saved');
    }

    public function restoreProject($id)
    {
        $project = Project::withTrashed()->find($id);
        $project->restore();
        $this->dispatch('success', 'Project restored successfully.');
        $this->dispatch('saved');
    }

    public function resetForm(){
        $this->client_id = null;
        $this->project_name = '';
        $this->project_due_date = null;
        $this->project_description = '';
        $this->project_start_date = null;
        $this->project = null;
        $this->image = null;
    }

    public function removeImage(){
        if($this->project){
            $this->project->image = null;
            $this->project->save();
        }else{
            $this->project = null;
        }
    }
}
