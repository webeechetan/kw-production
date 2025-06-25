<div class="container">
    <!-- Dashboard Header -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a wire:navigate href="{{ route('project.index') }}"><i class='bx bx-line-chart'></i>{{ucfirst(Auth::user()->organization->name)}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">All Projects</li>
        </ol>
    </nav> 

    <div class="dashboard-head mb-3">
        <div class="row align-items-center">
            <div class="col d-flex align-items-center gap-3">
                <h3 class="main-body-header-title mb-0 @if($filter == 'archived') archived_content @endif">All Projects</h3>
                @can('Create Project')
                    <span class="text-light">|</span>
                    <a data-step="1" data-intro='Create your first project' data-position='right' data-bs-toggle="modal" data-bs-target="#add-project-modal" href="javascript:void(0);" class="btn-border btn-border-sm btn-border-primary"><i class="bx bx-plus"></i> Add Project</a>
                @endcan
            </div>
            <div class="col">
                <div class="main-body-header-right">
                    <form class="search-box" wire:submit="search" action="" data-step="2" data-intro='Search Project' data-position='bottom'>
                        <input wire:model.live.debounce.250ms="query"  type="text" class="form-control" placeholder="Search Projects...">
                        <button type="submit" class="search-box-icon" wire:loading.class="opacity-50">
                            <i class='bx bx-search me-1' wire:loading.class="bx-tada"></i> 
                            <span wire:loading.remove>Search</span>
                            <span wire:loading>Searcing...</span>
                        </button>
                    </form>
                    <div class="main-body-header-filters" data-step="3" data-intro='Filter Project' data-position='bottom'>
                        <div class="cus_dropdown" wire:ignore.self>
                            <div class="cus_dropdown-icon btn-border btn-border-secondary"><i class='bx bx-filter-alt' ></i> Filter</div>
                            <div class="cus_dropdown-body cus_dropdown-body-widh_l">
                                <div class="cus_dropdown-body-wrap">
                                    <div class="filterSort">
                                        <h5 class="filterSort-header"><i class='bx bx-sort-down text-primary' ></i> Sort By</h5>
                                        <ul class="filterSort_btn_group list-none">
                                            <li class="filterSort_item"><a wire:click="$set('sort','newest')" class="btn-batch  @if($sort == 'newest') active @endif">Newest</a></li>
                                            <li class="filterSort_item"><a wire:click="$set('sort','oldest')" class="btn-batch  @if($sort == 'oldest') active @endif">Oldest</a></li>
                                            <li class="filterSort_item"><a wire:click="$set('sort','a_z')" class="btn-batch  @if($sort == 'a_z') active @endif"> A To Z</a></li>
                                            <li class="filterSort_item"><a wire:click="$set('sort','z_a')" class="btn-batch  @if($sort == 'z_a') active @endif">Z To A</a></li>
                                        </ul>
                                        <h5 class="filterSort-header mt-4"><i class='bx bx-briefcase text-primary' ></i> Filter By Status</h5>
                                        <ul class="filterSort_btn_group list-none">
                                            <li class="filterSort_item"><a wire:click="$set('filter','all')" class="btn-batch @if($filter == 'all') active @endif">All</a></li>
                                            <li class="filterSort_item"><a wire:click="$set('filter','active')" class="btn-batch @if($filter == 'active') active @endif">Active</a></li>
                                            {{-- <li class="filterSort_item"><a wire:click="$set('filter','overdue')" class="btn-batch @if($filter == 'overdue') active @endif">Overdue</a></li> --}}
                                            <li class="filterSort_item"><a wire:click="$set('filter','completed')" class="btn-batch @if($filter == 'completed') active @endif">Completed</a></li>
                                            <li class="filterSort_item"><a wire:click="$set('filter','archived')" class="btn-batch @if($filter == 'archived') active @endif">Archived</a></li>
                                            
                                        </ul>
                                        <h5 class="filterSort-header mt-4"><i class='bx bx-briefcase text-primary' ></i> Filter By Client</h5>
                                        <select class="dashboard_filters-select w-100" wire:model.live="byClient" name="" id="">
                                            <option value="all">All</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id}}">{{ $client->name }}</option>
                                            @endforeach
                                        </select>
                                        <h5 class="filterSort-header mt-4"><i class='bx bx-objects-horizontal-left text-primary'></i> Filter By User</h5>
                                        <select class="dashboard_filters-select w-100" wire:model.live="byUser">
                                            <option value="all">All</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        <h5 class="filterSort-header mt-4"><i class='bx bx-briefcase text-primary' ></i> Filter By Teams</h5>
                                        <select class="dashboard_filters-select w-100" wire:model.live="byTeam" name="" id="">
                                            <option value="all">All</option>
                                            @foreach($teams as $team)
                                                <option value="{{ $team->id}}">{{ $team->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="dashboard_filters d-flex flex-wrap gap-4 align-items-center mb-4">
                <a class="@if($filter == 'all') active @endif" wire:click="$set('filter','all')">All <span class="btn-batch">{{ $allProjects}}</span></a>
                <a class="@if($filter == 'active') active @endif" wire:click="$set('filter','active')">Active <span class="btn-batch">{{ $activeProjects }}</span></a>
                <a class="@if($filter == 'overdue') active @endif" wire:click="$set('filter','overdue')">Overdue <span class="btn-batch">{{ $overdueProjects}}</span></a>
                <a class="@if($filter == 'completed') active @endif" wire:click="$set('filter','completed')">Completed <span class="btn-batch">{{ $completedProjects}}</span></a>
                <a class="@if($filter == 'archived') active @endif" wire:click="$set('filter','archived')">Archived <span class="btn-batch">{{ $archivedProjects }}</span></a>

            </div> 
        </div>
        <div class="col-md-6">
            @if($this->doesAnyFilterApplied())
                <x-filters-query-params 
                    :sort="$sort" 
                    :status="$filter" 
                    :byUser="$byUser" 
                    :byTeam="$byTeam"
                    :byClient="$byClient"
                    :users="$users" 
                    :teams="$teams"
                    :clients="$clients"
                    :clearFilters="route('project.index')"
                />
            @endif
        </div>
    </div>
    
    <div class="row">
        @if($projects->isNotEmpty())
            @foreach($projects as $project)
                <div class="col-md-4 mb-4">
                    <div class="card_style h-100">
                        <a href="{{ route('project.profile', $project->id ) }}" class="card_style-open"><i class='bx bx-chevron-right'></i></a>
                        <div class="card_style-project-head">
                            <div class="card_style-project-head-client"><span><i class='bx bx-briefcase-alt-2'></i></span> {{ $project->client?->name }} </div>
                            <h4><a href="{{ route('project.profile',$project->id) }}" wire:navigate>{{ $project->name }}</a> </h4>
                            <!-- Avatar Group -->
                            <div class="avatarGroup avatarGroup-lg avatarGroup-overlap mt-2">
                                @if(count($project->members) > 0)
                                    @php
                                        $plus_more_users = 0;
                                        if(count($project->members) > 7){
                                            $plus_more_users = count($project->members) - 7;
                                        }
                                    @endphp

                                    @foreach($project->members->take(7) as $user)
                                        <x-avatar :user="$user" class="avatar-sm" />
                                    @endforeach
                                    @if($plus_more_users)
                                        <a href="#" class="avatarGroup-avatar">
                                            <span class="avatar avatar-sm avatar-more">+{{$plus_more_users}}</span>
                                        </a>
                                    @endif
                                @else
                                    <div class="text-light">No Users Assigned</div>
                                @endif
                                
                            </div>
                        </div>

                        <div class="card_style-project-body mt-3">
                            <div class="card_style-tasks">
                                <div class="card_style-tasks-title mb-2"><span><i class='bx bx-objects-horizontal-left' ></i></span> {{ $project->tasks->count() }} {{ pluralOrSingular($project->tasks->count(),'Task') }} </div>
                                <div class="card_style-tasks-list">
                                    <div class="card_style-tasks-item card_style-tasks-item-pending"><span><i class='bx bx-objects-horizontal-center' ></i></span>
                                        {{ $project->tasks->where('status', 'pending')->where('due_date', '>', now())->count() }} Active
                                    </div>
                                    <div class="card_style-tasks-item card_style-tasks-item-overdue"><span><i class='bx bx-objects-horizontal-center' ></i></span> 
                                        {{ $project->tasks->where('due_date', '<', now())->where('status','!=','completed')->count() }} Overdue
                                    </div>
                                    <div class="card_style-tasks-item card_style-tasks-item-done"><span><i class='bx bx-objects-horizontal-center' ></i></span>
                                        {{ $project->tasks->where('status', 'completed')->count() }} Completed
                                    </div>
                                </div>
                            </div>
                            <div class="task_progress mt-3">
                                <div class="task_progress-head">
                                    <div class="task_progress-head-title">Progress</div>
                                    <div class="task_progress-head-days"><span><i class='bx bx-calendar-minus'></i></span> 
                                        @php
                                            $days = \Carbon\Carbon::parse($project->due_date)->diffInDays(now());
                                        @endphp
                                        @if($days > 0 && $project->due_date > now())
                                            {{ $days }} Days Left
                                        @elseif($days == 0)
                                            Today
                                        @else
                                            {{ abs($days) }} Days Overdue
                                        @endif
                                    </div>
                                </div>
                                <div class="task_progress-btm">
                                    <div class="progress w-100" role="progressbar" aria-label="Project Progress" aria-valuemin="0" aria-valuemax="100">
                                        <!-- @php
                                            $percentage = 0;
                                            $completed = $project->tasks->where('status', 'completed')->count();
                                            $total = $project->tasks->count();
                                            if($total > 0){
                                                $percentage = ($completed / $total) * 100;  
                                            }else{
                                                $percentage = 0;
                                            }
                                        @endphp -->
                                        <div class="progress-bar progress-success" style="width: {{$percentage}}%"><span class="progress-bar-text">{{ round($percentage)}}%</span></div>
                                    </div>
                                    <div class="task_progress-btm-date d-flex justify-content-between">
                                        <div><i class='bx bx-calendar' ></i> 
                                            @if($project->start_date)
                                                {{ \Carbon\Carbon::parse($project->start_date)->format('d M') }}
                                            @else
                                                No Start Date
                                            @endif
                                        </div>
                                        <div class="text-danger"><i class='bx bx-calendar' ></i> 
                                            @if($project->due_date)
                                                {{ \Carbon\Carbon::parse($project->due_date)->format('d M') }}
                                            @else
                                                No Due Date
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else 
        <div class="col-md-12 text-center">
            <img src="{{ asset('assets/images/'.'invite_signup_img.png') }}" width="150" alt="">
            {{-- <h4 class="text text-danger">No Projects found.</h4> --}}
            <h5 class="text text-light mt-3">No Projects found
                @if($query) 
                    with <span class="text-danger">"{{$query}}"</span>
                @endif
            </h5>
        </div>
        @endif
    </div>
    <hr>
    <!-- Pagination -->
    {{ $projects->links(data: ['scrollTo' => false]) }}
    
    <!-- Add Project Component -->
    <livewire:components.add-project @saved="$refresh" />
    
</div>

@php
    $tour = session()->get('tour');
@endphp

{{-- @if($tour['project_tour']) --}}
@if(isset($tour) && $tour != null && isset($tour['project_tour']))
    @assets
        <link href="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/minified/introjs.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/intro.min.js"></script>
    @endassets
@endif

{{-- @if($tour['project_tour']) --}}
@if(isset($tour) && $tour != null && isset($tour['project_tour']))
    @script
            <script>
                introJs()
                .setOptions({
                showProgress: true,
                })
                .onbeforeexit(function () {
                    location.href = "{{ route('project.index') }}?tour=close-project-tour";
                })
                .start();
            </script>
    @endscript
@endif