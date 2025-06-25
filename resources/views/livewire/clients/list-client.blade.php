<div class="container">
    <!-- Dashboard Header -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a wire:navigate href="{{ route('client.index') }}"><i class='bx bx-line-chart'></i> {{ ucfirst(Auth::user()->organization->name) }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">All Clients</li>
        </ol>
    </nav>

    <div class="dashboard-head mb-3">
        <div class="row align-items-center">
            <div class="col d-flex align-items-center gap-3">
                <h3 class="main-body-header-title mb-0 @if($status == 'archived') archived_content @endif">All Clients</h3>
                <span class="text-light">|</span>
                @can('Create Client')
                    <a data-step="1" data-intro='Create your first client' data-position='right' data-bs-toggle="modal" data-bs-backdrop="false" data-bs-target="#add-client-modal" href="javascript:void(0);" class="btn-border btn-border-sm btn-border-primary"><i class="bx bx-plus"></i> Add Client</a>
                @endcan
            </div>
            <div class="col">
                <div class="main-body-header-right">
                    <form class="search-box" wire:submit="search" action="" data-step="2" data-intro='Search Client' data-position='bottom'>
                        <input wire:model.live.debounce.250ms="query" type="text" class="form-control" placeholder="Search Client">
                        <button type="submit" class="search-box-icon" wire:loading.class="opacity-50">
                            <i class='bx bx-search me-1' wire:loading.class="bx-tada"></i> 
                            <span wire:loading.remove>Search</span>
                            <span wire:loading>Searcing...</span>
                        </button>
                    </form>
                    <div class="main-body-header-filters" data-step="3" data-intro='Filter Client' data-position='bottom'>
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
                                            <li class="filterSort_item"><a wire:click="$set('status','all')" class="btn-batch @if($status == 'all') active @endif">All</a></li>
                                            <li class="filterSort_item"><a wire:click="$set('status','active')" class="btn-batch @if($status == 'active') active @endif">Active</a></li>
                                            <li class="filterSort_item"><a wire:click="$set('status','completed')" class="btn-batch @if($status == 'completed') active @endif">Completed</a></li>
                                            <li class="filterSort_item"><a wire:click="$set('status','archived')" class="btn-batch @if($status == 'archived') active @endif">Archived</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Body -->
    <div class="row mb-2">
        <div class="col-md-6">
            <div class="dashboard_filters d-flex flex-wrap gap-4 align-items-center mb-4">
                <a class="@if($status == 'all') active @endif" wire:click="$set('status','all')">All <span class="btn-batch">{{ $allClients }}</span></a>
                <a class="@if($status == 'active') active @endif" wire:click="$set('status','active')">Active <span class="btn-batch">{{ $activeClients }}</span></a>
                <a class="@if($status == 'completed') active @endif" wire:click="$set('status','completed')">Completed <span class="btn-batch">{{ $completedClients }}</span></a>
                <a class="@if($status == 'archived') active @endif" wire:click="$set('status','archived')">Archived <span class="btn-batch">{{ $archivedClients }}</span></a>
            </div>
        </div>
        <div class="col-md-6">

            @if($this->doesAnyFilterApplied())
                <x-filters-query-params 
                    :sort="$sort" 
                    :status="$status" 
                    :clients="$clients"
                    :clearFilters="route('client.index')"
                />
            @endif
        </div>

        @if($clients->isNotEmpty())
            @foreach($clients as $client)
            @php
                $activeProjects = $client->projects->where('status', 'active');
                $completedProjects = $client->projects->where('status', 'completed');
            @endphp
            <div class="col-md-4 mb-4"  wire:key="{{ $client->id }}">
                <div class="card_style card_style-client h-100">
                    <div wire:loading wire:target="emitDeleteEvent({{ $client->id }})" class="card_style-loader">
                        <div class="card_style-loader-wrap"><i class='bx bx-trash text-danger me-2' ></i> Removing ...</div>
                    </div>
                    <a href="{{ route('client.profile', $client->id ) }}" class="card_style-open"><i class='bx bx-chevron-right'></i></a>
                    <div class="card_style-head card_style-client-head mb-3">
                        @if($client->image)
                            <div class="avatar"><img src="{{ asset('storage/'.$client->image) }}" alt="" class="img-fluid" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ $client->name }}"></div>
                        @else
                            <div class="avatar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ $client->name }}">{{ $client->initials }}</div>
                        @endif
                        <div>
                            <h4 class="mb-1">
                                <a wire:navigate href="{{ route('client.profile', $client->id ) }}">
                                    {{ Str::limit($client->visible_name, 20, '...') }}
                                </a>
                            </h4>
                            <div class="btn-withIcon"><i class='bx bx-layer text-secondary' ></i> {{ $client->projects->count() }} {{ pluralOrSingular($client->projects->count(),'Project') }}</div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap">
                        <h4 class="text-md mb-0">Active <span class="btn-batch-bg btn-batch-bg-secondary ms-2">{{count($activeProjects)}} {{ pluralOrSingular(count($activeProjects),'Project') }}</span></h4>
                        <span class="px-2 text-grey">|</span>
                        <h4 class="text-md mb-0">Completed <span class="btn-batch-bg btn-batch-bg-success ms-2">{{count($completedProjects)}} {{ pluralOrSingular(count($completedProjects) ,'Project') }}</span></h4>
                    </div>
                    <hr class="my-2">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <h4 class="text-md mb-0"><i class="bx bx-user text-primary"></i> {{ pluralOrSingular(count($client->users),'User') }}</h4> 
                        </div>
                        <div class="col">
                            <div class="avatarGroup avatarGroup-overlap"> 
                                @php
                                    $allUsers = [];
                                    foreach($client->projects as $project){
                                        foreach($project->users as $user){
                                            $allUsers[] = $user;
                                        }
                                    }
                                    $allUsers = collect($allUsers)->unique('id');
                                @endphp

                                @if(count($allUsers) > 0)
                                    @php
                                        $plus_more_users = 0;
                                        if(count($allUsers) > 7){
                                            $plus_more_users = count($allUsers) - 7;
                                        }
                                    @endphp
                                    @foreach(collect($allUsers)->take(7) as $user)
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
                    </div>
                </div>
            </div>
            @endforeach
            @else
            <div class="col-md-12 text-center">
                <img src="{{ asset('assets/images/'.'invite_signup_img.png') }}" width="150" alt="">
                <h5 class="text text-light mt-3">No clients found
                    @if($query) 
                        with <span class="text-danger">"{{$query}}"</span>
                    @endif
                </h5>
            </div>
        @endif
    </div>
    <!-- Pagination -->
    {{ $clients->links(data: ['scrollTo' => false]) }}

    <!-- Client Modal Component -->
    <livewire:components.add-client @saved="$refresh" />
</div>

@php
    $tour = session()->get('tour');
@endphp

{{-- @if($tour['client_tour']) --}}
@if(isset($tour) && $tour != null && isset($tour['client_tour']))
    @assets
        <link href="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/minified/introjs.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/intro.min.js"></script>
    @endassets
@endif

{{-- @if($tour['client_tour']) --}}
@if(isset($tour) && $tour != null && isset($tour['client_tour']))
    @script
            <script>
                introJs()
                .setOptions({
                showProgress: true,
                })
                .onbeforeexit(function () {
                    location.href = "{{ route('client.index') }}?tour=close-client-tour";
                })
                .start();
            </script>
    @endscript
@endif