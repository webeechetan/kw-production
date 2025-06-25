<div>
    <div class="client-tab">
        <div class="dashboard-head pb-0 mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <div class="dashboard-head-title-wrap">
                        @if($team->image)
                            <div class="avatar">
                                <img src="{{ asset('storage/'.$team->image) }}" alt="Avatar" class="avatar-img rounded-circle">
                            </div>
                        @else
                            <div class="avatar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ $team->name }}">{{$team->initials}}</div>
                        @endif
                        <div>
                            <h3 class="main-body-header-title mb-2">{{ $team->name }}</h3>
                            <div class="row align-items-center">
                                <div class="col-auto">
                                <span>
                                <i class="bx bx-user text-secondary"></i>
                                </span> Manager
                                </div>
                                <div class="col text-nowrap btn-batch">{{ $team->manager?->name ?? 'Not Added' }}
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>
                <div class="text-end col">
                    <div class="main-body-header-right">
                        @can('Edit Team')
                        <a href="#" class="btn-sm btn-border btn-border-success" wire:click="dispatchEditEvent({{$team->id}})" >
                            <span wire:loading wire:target="dispatchEditEvent">
                                <i class='bx bx-loader-alt bx-spin'></i>
                             </span>
                            <i class='bx bx-pencil'></i>
                             Edit
                        </a>
                        @endcan
                        <!-- Delete -->
                        @can('Delete Team')
                            <a href="#" class="btn-sm btn-border btn-border-danger" wire:click="forceDeleteTeam({{$team->id}})" wire:confirm="Are you sure you want to delete?"><i class='bx bx-trash'></i> Delete</a>
                        @endcan 
                    </div>
                </div>
            </div>
            <hr class="mb-0">
            <div class="tabNavigationBar-tab border_style">
                
                <a wire:navigate class="tabNavigationBar-item @if($currentRoute == 'team.profile')) active @endif" href="{{ route('team.profile', $team->id) }}"><i class='bx bx-line-chart'></i> Overview</a>
                <a wire:navigate class="tabNavigationBar-item @if($currentRoute =='team.projects')) active @endif" href="{{ route('team.projects', $team->id) }}"><i class='bx bx-objects-horizontal-left' ></i> Projects</a>
                <a wire:navigate class="tabNavigationBar-item @if($currentRoute =='team.tasks')) active @endif" href="{{ route('team.tasks', $team->id) }}"><i class='bx bx-layer' ></i> Tasks</a>
            </div>
        </div>
    </div>
</div>
