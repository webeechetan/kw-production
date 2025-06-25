<div class="container">
    <!-- Dashboard Header -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a wire:navigate href="{{ route('team.index')}}"><i class='bx bx-line-chart'></i>{{ ucfirst(Auth::user()->organization->name) }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">All Teams</li>
        </ol>
    </nav>
    <div class="dashboard-head">
        <div class="row align-items-center">
            <div class="col d-flex align-items-center gap-3">
                <h3 class="main-body-header-title mb-0">All Teams</h3>
                <span class="text-light">|</span> 
                @can('Create Team')
                    <a data-step="1" data-intro='Create your first team' data-position='right' data-bs-toggle="modal" data-bs-target="#add-team-modal" href="javascript:void(0);" class="btn-border btn-border-sm btn-border-primary"><i class="bx bx-plus"></i> Add Team</a>
                @endcan
            </div>
            <div class="text-end col">
                <div class="main-body-header-right">
                    <form class="search-box" wire:submit="search" action="" data-step="2" data-intro='Search team' data-position='right'>
                        <input wire:model.live.debounce.250ms="query"  type="text" class="form-control" placeholder="Search Teams...">
                        <button type="submit" class="search-box-icon" wire:loading.class="opacity-50">
                            <i class='bx bx-search me-1' wire:loading.class="bx-tada"></i> 
                            <span wire:loading.remove>Search</span>
                            <span wire:loading>Searcing...</span>
                        </button>
                    </form>
                    <div class="main-body-header-filters" data-step="3" data-intro='Filter team' data-position='bottom'>
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
                                        <h5 class="filterSort-header"><i class='bx bx-objects-horizontal-left text-primary'></i> Filter By User</h5>
                                        <select class="form-control" wire:model.live="byUser">
                                            <option value="all">All</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name}}</option>
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

    <div class="row mt-4">

        <div class="col-md-6">
            <div class="dashboard_filters d-flex flex-wrap gap-4 align-items-center mb-4">
                <a class="@if($filter == 'all') active @endif" wire:click="$set('filter','all')">All <span class="btn-batch">{{ $allTeams }}</span></a>
            </div>
        </div>
        <div class="col-md-6">

            @if($this->doesAnyFilterApplied())
            <x-filters-query-params 
                :sort="$sort" 
                :status="$filter" 
                :byUser="$byUser"
                :users="$users" 
                :teams="$teams"
                :clearFilters="route('team.index')"
            />
        @endif
        </div>
        
        @if($teams->isNotEmpty())
            @foreach($teams as $team)

                <div class="col-md-4 mb-4">
                    <div class="card_style card_style-team">
                        <a href="{{ route('team.profile',$team->id) }}" class="card_style-open"><i class='bx bx-chevron-right'></i></a>
                        {{-- <div class="card_style-star_active"><span class="text-success"><i class='bx bxs-star' ></i></span></div> --}}
                        <div class="card_style-list_head">
                            
                            @if($team->image)
                                <div class="avatar"><img src="{{ asset('storage/'.$team->image) }}" alt="" class="img-fluid" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ $team->name }}"></div>
                            @else
                                <div class="avatar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ $team->name }}">{{ $team->initials }}</div>
                            @endif
                            <div class="card_style-team-profile-content">
                                <h4 class="mb-2"><a wire:navigate href="{{ route('team.profile',$team->id) }}">{{ $team->name }}</a></h4>
                                <div class="mb-2">
                                    {{-- <span class="font-500"><i class='bx bx-user text-success' ></i> Manager</span> @if($team->manager)<span class="btn-batch ms-2">{{ $team->manager?->name }}</span> @endif --}}

                                    <span class="font-500 me-3"><i class='bx bx-user text-success' ></i> Manager</span> 
                                    @if($team->manager)
                                        @if($team->manager)
                                            {{-- <span class="btn-batch ms-2">{{ $team->manager?->name }}</span>--}}
                                            <a href="javascript:;"class="avatar avatar-orange avatar-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{$team->manager?->name}}"> 
                                                {{ $team->manager?->initials ?? 'NA' }}
                                            </a>
                                        @endif
                                    @else
                                        <div class="text-light">No Manager Assigned</div>
                                    @endif
                                </div>
                                <div class="avatarGroup avatarGroup-overlap">
                                    @if(count($team->users) > 0)
                                        @php
                                            $plus_more_users = 0;
                                            if(count($team->users) > 7){
                                                $plus_more_users = count($team->users) - 7;
                                            }
                                        @endphp
                                        @foreach($team->users->take(7) as $user)
                                            <x-avatar :user="$user"  />
                                        @endforeach

                                        @if($plus_more_users)
                                        <a href="#" class="avatarGroup-avatar">
                                                <span class="avatar avatar-sm avatar-more">+{{$plus_more_users}}</span>
                                            </a>
                                        @endif
                                    @else
                                    <div class="text-light">No User Assigned</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <hr>
                        
                        <div class="card_style-options">
                            <div class="card_style-options-head">
                                <span>
                                    <i class='bx bx-layer text-secondary' ></i>
                                </span> 
                                {{-- 15 Projects  --}}
                                @php
                                    $project_text = 'Projects';    
                                @endphp
                                @if($team->projects->count() > 1)
                                    @php
                                        $project_text = 'Projects';    
                                    @endphp
                                @else
                                    @php
                                        $project_text = 'Project';    
                                    @endphp
                                @endif
                                {{ $team->projects->count() }} {{ $project_text }}
                                <span class="text-dark-grey ms-1">|</span>
                                <span href="#" class="text-secondary"> <i class='bx bx-briefcase-alt-2' ></i></span> 
                                {{-- 5 Clients  --}}
                                @php
                                    $client_text = 'Clients';
                                @endphp
 
                                @if($team->clients->count() > 1)
                                    @php
                                        $client_text = 'Clients';    
                                    @endphp
                                @else
                                    @php
                                        $client_text = 'Client';    
                                    @endphp
                                @endif
                                {{ $team->clients->count() }} {{ $client_text }}        
                            
                                <span class="text-dark-grey ms-1">|</span> 
                                <span href="#"><i class='bx bx-objects-horizontal-left text-primary' ></i></span>
                                {{-- 60 Tasks --}}
                                @php
                                    $task_text = 'Tasks';
                                @endphp

                                @if($team->tasks->count() > 1)
                                    @php
                                        $task_text = 'Tasks';    
                                    @endphp
                                @else
                                    @php
                                        $task_text = 'Task';    
                                    @endphp
                                @endif
                                {{ $team->tasks->count() }} {{ $task_text }}  
                            </div>
                            <div class="card_style-options-head">
                                
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-md-12 text-center">               
                <img src="{{ asset('assets/images/'.'invite_signup_img.png') }}" width="150" alt="">
                <h5 class="text text-light mt-3">No Teams found 
                    @if($query) 
                        with <span class="text-danger">"{{$query}}"</span>
                    @endif
                </h5>
            </div>
        @endif
        <div class="pagintaions mt-4">
            {{ $teams->links(data: ['scrollTo' => false]) }}
        </div>
    </div>

    <!-- Team Modal -->

    <livewire:components.add-team />
    
</div>



@php
    $tour = session()->get('tour');
@endphp

{{-- @if($tour['team_tour']) --}}
@if(isset($tour) && $tour != null && isset($tour['team_tour']))
    @assets
        <link href="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/minified/introjs.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/intro.min.js"></script>
    @endassets
@endif

{{-- @if($tour['team_tour']) --}}
@if(isset($tour) && $tour != null && isset($tour['team_tour']))
    @script
            <script>
                introJs()
                .setOptions({
                showProgress: true,
                })
                .onbeforeexit(function () {
                    location.href = "{{ route('team.index') }}?tour=close-team-tour";
                })
                .start();
            </script>
    @endscript
@endif

