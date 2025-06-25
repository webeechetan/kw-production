<?php

namespace App\Livewire\Projects\Components;

use Livewire\Component;
use App\Models\Project;

class CalendarView extends Component
{
    public $project;

    public function render()
    {
        return view('livewire.projects.components.calendar-view');
    }

    public function mount(Project $project)
    {
    }
}
