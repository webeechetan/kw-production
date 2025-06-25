<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Project;
use App\Models\User;

class Calendar extends Component
{
    public $id;
    public $for;
    public $events = [];

    /**
     * Create a new component instance.
     */
    public function __construct($for,$id)
    {
        $this->id = $id;
        $this->for = $for;
        if($for == 'project'){
            $this->events = $this->getEventsForProject($id);
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.calendar');
    }

    public function getEventsForProject($id)
    {
        $events = [];
        $tasks = Project::find($id)->tasks;
        foreach($tasks as $task){
            $color = '';
            if($task->status == 'completed'){
                $color = 'green';
            }else{
                if($task->due_date < now()){ 
                    $color='red' ; 
                }else{ 
                    $color='green' ; 
                } 
            } 

            $events[]=[   
                'title'=> $task->name,
                'start' => $task->due_date,
                'url' => route('task.view', $task->id),
                'backgroundColor' => $color,
            ];
        }

        $project_start_event = [
            'title' => 'Project Start',
            'start' => Project::find($id)->start_date,
            'url' => route('project.profile', $id),
            'backgroundColor' => 'blue',
            'eventColor' => 'blue',
        ];

        $project_end_event = [
            'title' => 'Project End',
            'start' => Project::find($id)->due_date,
            'url' => route('project.profile', $id),
            'backgroundColor' => 'green',
            'eventColor' => 'green',
        ];

        array_push($events, $project_start_event, $project_end_event);

        return $events;
    }


}
