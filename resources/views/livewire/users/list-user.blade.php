<div class="container">
    <!-- Dashboard Header -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a wire:navigate href="{{ route('user.index')}}"><i class='bx bx-line-chart'></i>{{ucfirst(Auth::user()->organization->name)}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">All Users</li>
        </ol>
    </nav>
    <div class="dashboard-head mb-4">
        <div class="row align-items-center">
            <div class="col d-flex align-items-center gap-3">
                <h3 class="main-body-header-title mb-0 @if($filter == 'archived') archived_content @endif">All Users</h3>
                <span class="text-light">|</span>
                @can('Create User')
                <a data-step="1" data-intro='Create your first user' data-position='right' data-bs-toggle="modal" data-bs-target="#add-user-modal" href="javascript:void(0);" class="btn-border btn-border-sm btn-border-primary"><i class="bx bx-plus"></i> Add User</a>
                @endcan
                <!-- <a wire:navigate href="{{ route('user.add') }}" href="javascript:void(0);" class="btn-border btn-border-sm btn-border-primary"><i class="bx bx-plus"></i> Add User</a> -->
            </div>
            <div class="text-end col">
                <div class="main-body-header-right">
                    <form class="search-box" wire:submit="search" action="" data-step="2" data-intro='Search User' data-position='bottom'>
                        <input wire:model.live.debounce.250ms="query"  type="text" class="form-control" placeholder="Search Users...">
                        <button type="submit" class="search-box-icon" wire:loading.class="opacity-50">
                            <i class='bx bx-search me-1' wire:loading.class="bx-tada"></i> 
                            <span wire:loading.remove>Search</span>
                            <span wire:loading>Searcing...</span>
                        </button>
                    </form>
                    <div class="main-body-header-filters" data-step="3" data-intro='Filter User' data-position='bottom'>
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
                                        <hr>
                                        <h5 class="filterSort-header"><i class='bx bx-briefcase text-primary' ></i> Filter By Status</h5>
                                        <ul class="filterSort_btn_group list-none">
                                            <li class="filterSort_item"><a wire:click="$set('filter','all')" class="btn-batch @if($filter == 'all') active @endif">All</a></li>
                                            <li class="filterSort_item"><a wire:click="$set('filter','active')" class="btn-batch @if($filter == 'active') active @endif">Active</a></li>
                                            <li class="filterSort_item"><a wire:click="$set('filter','archived')" class="btn-batch @if($filter == 'archived') active @endif">Archived</a></li>
                                        </ul>
                                        {{-- <hr>
                                        <h5 class="filterSort-header"><i class='bx bx-briefcase text-primary' ></i> Filter By Teams</h5>
                                        <select class="dashboard_filters-select w-100" wire:model.live="byTeam" name="" id="">
                                            <option value="all">All</option>
                                            @foreach($teams as $team)
                                                <option value="{{ $team->id}}">{{ $team->name }}</option>
                                            @endforeach
                                        </select>
                                        <hr>
                                        <h5 class="filterSort-header"><i class='bx bx-objects-horizontal-left text-primary'></i> Filter By Projects</h5>
                                        <select class="dashboard_filters-select w-100" wire:model.live="byProject" name="" id="">
                                            <option value="all">All</option> 
                                            @foreach($projects as $project)
                                                <option value="{{ $project->id}}">{{ $project->name }}</option>
                                                
                                            @endforeach
                                        </select> --}}
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
                <a class="@if($filter == 'all') active @endif" wire:click="$set('filter','all')">All <span class="btn-batch">{{ $allUsers}}</span></a>
                <a class="@if($filter == 'active') active @endif" wire:click="$set('filter','active')">Active <span class="btn-batch">{{ $activeUsers }}</span></a>
                <a class="@if($filter == 'archived') active @endif" wire:click="$set('filter','archived')">Archived <span class="btn-batch">{{ $archivedUsers }}</span></a>
            </div>
        </div>

        <div class="col-md-6">
            @if($this->doesAnyFilterApplied())
                <x-filters-query-params 
                    :sort="$sort" 
                    :status="$filter" 
                    :byProject="$byProject" 
                    :byTeam="$byTeam"
                    :users="$users" 
                    :teams="$teams"
                    :projects="$projects"
                    :clearFilters="route('user.index')"
                /> 
            @endif
        </div>

        @if($users->isNotEmpty())
        @foreach($users as $user)
            <div class="col-md-4 mb-4">
                <div class="card_style card_style-user h-100">
                    <a href="{{ route('user.profile',$user->id) }}" class="card_style-open"><i class='bx bx-chevron-right'></i></a>
                    <div class="card_style-user-head">
                        <div class="card_style-user-profile-img">
                            @if($user->image)
                                <span class="avatar avatar-xl"><img src="{{ env('APP_URL') }}/storage/{{ $user->image }}" alt="{{ $user->name }}"></span>
                            @else
                                <span class="avatar avatar-xl avatar-{{$user->color}}">{{ $user->initials }}</span>
                            @endif
                        </div>
                        <div class="card_style-user-profile-content mt-2">
                            <h4><a wire:navigate href="{{ route('user.profile',$user->id) }}">{{ $user->name }}</a></h4>
                            <div class="d-flex align-items-center justify-content-center">
                                <i class='bx bx-envelope me-1 text-secondary' ></i> 
                                @if($user->email)
                                    {{ $user->email }}
                                @else
                                    <div class="text-light">No Email Added</div>
                                @endif
                                
                            </div>
                            <div class="d-flex align-items-center justify-content-center mt-2">
                                <i class='bx bx-briefcase me-1 text-primary'></i> 
                                @if($user->designation)
                                    {{ $user->designation }} 
                                @else
                                    <div class="text-light">No Designation Assigned</div>
                                @endif
                            </div>
                            <div class="d-flex align-items-center justify-content-center mt-2">
                                <i class='bx bx-sitemap me-1 text-secondary'></i> 
                                @if(!$user->mainTeam)
                                    <div class="text-light">No Team Assigned</div>
                                @else
                                   {{ $user->mainTeam->name }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card_style-user-body mt-3">
                        <div class="card_style-options d-none">
                            <div class="card_style-options-head"><span><i class='bx bx-layer text-secondary' ></i></span> 30 Projects <span class="text-dark-grey ms-1">|</span> 5 Clients</div>
                            <div class="card_style-options-list d-flex">
                                <div class="text-primary">Active <span class="btn-batch-bg btn-batch-bg-primary">10 Projects</span></div>
                                <div class="text-success ms-4">Completed <span class="btn-batch-bg btn-batch-bg-success">5 Projects</span></div>
                            </div>
                        </div>
                        <div class="card_style-tasks text-center">
                            <div class="card_style-tasks-title">
                                <span><i class='bx bx-objects-horizontal-left' ></i></span> 
                                @if($user->tasks->count() > 0)
                                    {{ $user->tasks->count() }}  
                                    {{ $user->tasks->count() >1 ? 'Tasks' : 'Task'}}
                                @else
                                <span class="text-light">No Task Assigned</span>
                                @endif
                            </div>
                            <div class="card_style-tasks-list justify-content-center mt-2">
                                <div class="card_style-tasks-item card_style-tasks-item-pending"><span><i class='bx bx-objects-horizontal-center' ></i></span>
                                {{-- {{
                                    $user->tasks->where(function($query) {
                                        $query->where('status', 'pending')->orWhere('status', 'in_progress')->orWhere('status', 'in_review');
                                    })->count()
                                }} Active
                                 --}}
                                 {{ 
                                    $user->tasks->filter(function($task) {
                                        return in_array($task->status, ['pending', 'in_progress', 'in_review']);
                                    })->count() 
                                }} Active
                            
                            </div>
                                <div class="card_style-tasks-item card_style-tasks-item-overdue"><span><i class='bx bx-objects-horizontal-center' ></i></span>{{ $user->tasks->where('due_date', '<', now())->where('status','!=','completed')->count() }} Overdue</div>
                                <div class="card_style-tasks-item card_style-tasks-item-done"><span><i class='bx bx-objects-horizontal-center' ></i></span>{{ $user->tasks->where('status', 'completed')->count() }}  Completed</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach 
        @else 
        <div class="col-md-12 text-center">
            <img src="{{ asset('assets/images/'.'invite_signup_img.png') }}" width="150" alt="">
            <h5 class="text text-light mt-3">No Users found 
               @if($query) 
                 with  <span class="text-danger">"{{$query}}"</span>
               @endif
            </h5>
        </div>
        @endif

        <!-- Pagination -->
        {{ $users->links(data: ['scrollTo' => false]) }}
    </div>

    <!-- User Modal Component -->

    <livewire:components.add-user  @saved="$refresh" />
    
</div>

@php
    $tour = session()->get('tour');
@endphp

{{-- @if($tour['user_tour']) --}}
@if(isset($tour) && $tour != null && isset($tour['user_tour']))
    @assets
        <link href="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/minified/introjs.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/intro.min.js"></script>
    @endassets
@endif

{{-- @if($tour['user_tour']) --}}
@if(isset($tour) && $tour != null && isset($tour['user_tour']))
    @script
            <script>
                introJs()
                .setOptions({
                showProgress: true,
                })
                .onbeforeexit(function () {
                    location.href = "{{ route('user.index') }}?tour=close-user-tour";
                })
                .start();
            </script>
    @endscript
@endif
