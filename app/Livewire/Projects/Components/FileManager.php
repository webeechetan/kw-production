<?php

namespace App\Livewire\Projects\Components;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Project;

class FileManager extends Component
{
    use WithFileUploads;

    public $project;

    public function render()
    {
        return view('livewire.projects.components.file-manager');
    }

    public function mount(Project $project)
    {
        $this->project = $project;
    }
}
