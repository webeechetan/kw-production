<div class="container">
    <!-- Dashboard Header -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a wire:navigate href="{{ route('dashboard') }}">
                <i class='bx bx-line-chart'></i>{{ ucfirst(Auth::user()->organization->name) }}</a>
            </li> 
            <li class="breadcrumb-item">
                <a wire:navigate href="{{ route('team.index') }}">All Team</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Tech Team</li>
        </ol>
    </nav>
    <livewire:teams.components.teams-tab :team="$team" @saved="$refresh"/> 
    <!--- Dashboard Body --->


    <div class="row">
        <div class="col-md-8">
            <div class="btn-list">
                <a wire:click="$set('status', 'all')" class="btn-border btn-border-primary @if($status == 'all') active @endif">
                    {{ $allTasks }} 
                    <span>|</span> 
                     All
                </a>
                <a wire:click="$set('status', 'pending')" class="btn-border btn-border-primary @if($status == 'pending') active @endif">{{ $assignedTasks }} <span>|</span> Assigned</a>
                <a wire:click="$set('status', 'in_progress')" class="btn-border btn-border-secondary @if($status == 'in_progress') active @endif">{{ $acceptedTasks }} <span>|</span> Accepted</a>
                <a wire:click="$set('status', 'in_review')" class="btn-border btn-border-warning @if($status == 'in_review') active @endif">{{ $inReviewTasks }} <span>|</span> In Review</a>
                <a wire:click="$set('status', 'completed')" class="btn-border btn-border-success @if($status == 'completed') active @endif">{{ $completedTasks }} <span>|</span> Completed</a>
                <a wire:click="$set('status', 'overdue')" class="btn-border btn-border-danger @if($status == 'overdue') active @endif">
                    {{ $overdueTasks }}
                    <span>|</span> Overdue</a>
            </div>
        
           
        
        </div>
        <div class="col-md-4 text-end">
            <div class="cus_dropdown" wire:ignore.self>
                <div class="cus_dropdown-icon btn-border btn-border-secondary"><i class="bx bx-filter-alt"></i> Filter</div>
                <div class="cus_dropdown-body cus_dropdown-body-widh_l">
                    <div class="cus_dropdown-body-wrap">
                        <div class="filterSort">
                            <h5 class="filterSort-header mt-4"><i class="bx bx-calendar-alt text-primary"></i> Filter By Date</h5>
                            <div class="row align-items-center mt-2">
                                <div class="col mb-4 mb-md-0" wire:ignore>
                                    <a href="javascript:;" class="btn w-100 btn-sm btn-border-secondary start_date ">
                                        <i class="bx bx-calendar-alt"></i> Start Date
                                    </a>
                                </div>
                                <div class="col-auto text-center font-500 mb-4 mb-md-0 px-0">
                                    To
                                </div>
                                <div class="col" wire:ignore>
                                    <a href="javascript:;" class="btn w-100 btn-sm btn-border-danger due_date ">
                                        <i class="bx bx-calendar-alt"></i> Due Date
                                    </a>
                                </div>
                            </div> 
                            <h5 class="filterSort-header mt-4"><i class="bx bx-briefcase text-primary"></i> Filter By Clients</h5>
                            <select class="dashboard_filters-select mt-2 w-100" wire:model.live="byClient" id="">
                                <option value="all" disabled>Select Client</option>
                                @foreach($clients as $client)
                                    <option value="{{$client->id}}">{{$client->name}}</option>
                                @endforeach
                            </select>
                            <h5 class="filterSort-header mt-4"><i class="bx bx-objects-horizontal-left text-primary"></i> Filter By Projects</h5>
                            <select class="dashboard_filters-select mt-2 w-100" wire:model.live="byProject" name="" id="">
                                <option value="all" disabled>Select Project</option>
                                @foreach($projects as $project)
                                <option value="{{$project->id}}">{{$project->name}}</option>
                                @endforeach
                            </select>
                            <h5 class="filterSort-header mt-4"><i class="bx bx-user text-primary"></i> Filter By User</h5>
                            <select class="dashboard_filters-select mt-2 w-100" wire:model.live="byUser" name="" id="">
                                <option value="all" disabled>Select User</option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
    <hr class="space-sm">
    <div class="col-md-12 d-flex justify-content-end">
        <form class="search-box search-box-float-style" method="POST" wire:submit.prevent="search">
            <span class="search-box-float-icon"><i class='bx bx-search'></i></span>
            <input type="text" wire:model="query" class="form-control" placeholder="Search Task...">
        </form>
    </div>
    
    <div class="column-box">
    @if($this->doesAnyFilterApplied())
            <x-filters-query-params 
                :sort="$sort" 
                :status="$status" 
                :byUser="$byUser" 
                :byClient="$byClient"
                :byProject="$byProject"
                :startDate="$startDate" 
                :dueDate="$dueDate" 
                :users="$users" 
                :teams="$teams"
                :clients="$clients"
                :projects="$projects" 
                :clearFilters="route('team.tasks',$team)"
            />
        @endif
        <div class="taskList-dashbaord_header">
            <div class="taskList-dashbaord_header_title taskList_col ms-2">Task Name</div>
            <div class="taskList-dashbaord_header_wrap text-center d-grid grid_col-repeat-6">
                <div class="taskList-dashbaord_header_title taskList_col">Due Date</div>
                <div class="taskList-dashbaord_header_title taskList_col">Client</div>
                <div class="taskList-dashbaord_header_title taskList_col">Projects</div>
                <div class="taskList-dashbaord_header_title taskList_col">Assignee</div>
                <div class="taskList-dashbaord_header_title taskList_col">Assignor</div>
                <div class="taskList-dashbaord_header_title taskList_col">Status</div>
            </div>
        </div>
       
        <div class="taskList scrollbar">
            <div>
                @foreach($tasks as $task)
                    @if(!$task->project)
                        @continue
                    @endif
                    <x-table-row :task="$task"/>
                @endforeach

                @if(!$tasks)
                    <div class="col-md-12 text-center">
                        <img src="{{ asset('assets/images/'.'invite_signup_img.png') }}" width="150" alt="">
                        <h5 class="text text-light mt-3">No Tasks found</h5>
                    </div>
                @endif
            </div>
        </div>
        <!-- Pagination -->
        <div class="pagination-wrap">
            <div class="col-md-12 d-flex justify-content-end">
                {{ $tasks->links() }}
            </div>
        </div>
    </div>
    <livewire:components.add-team @saved="$refresh" />
</div>

@script
    <script>
        $(document).ready(function() {
            flatpickr('.start_date', {
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr, instance) {
                    $(".start_date").html(dateStr);
                    @this.set('startDate', dateStr);
                },
            });


            flatpickr('.due_date', {
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr, instance) {
                    $(".due_date").html(dateStr);
                    @this.set('dueDate', dateStr);
                },
            });

            });
    </script>
@endscript