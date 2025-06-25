<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FiltersQueryParams extends Component
{
        public 
        $sort, 
        $status, 
        $byUser,
        $byProject,
        $byClient,
        $byTeam,
        $startDate, 
        $dueDate, 
        $users, 
        $projects, 
        $clients, 
        $teams, 
        $clearFilters;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $sort = null, 
        $status = null, 
        $byUser = null,
        $byProject = null,
        $byClient = null,
        $byTeam = null,
        $startDate = null, 
        $dueDate = null, 
        $users = null, 
        $projects = null, 
        $clients = null, 
        $teams = null,
        $clearFilters = null
        ){

        $this->sort = $sort;
        $this->status = $status;
        $this->byUser = $byUser;
        $this->byProject = $byProject;
        $this->byClient = $byClient;
        $this->byTeam = $byTeam;
        $this->startDate = $startDate;
        $this->dueDate = $dueDate;
        $this->users = $users;
        $this->projects = $projects;
        $this->clients = $clients;
        $this->teams = $teams;
        $this->clearFilters = $clearFilters;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.filters-query-params');
    }
}
