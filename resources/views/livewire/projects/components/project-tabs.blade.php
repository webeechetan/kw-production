<div>
    <div class="dashboard-head pb-0 mb-4 @if($project->trashed()) archived_content @endif">
        <div class="row align-items-center">
            <div class="col">
                <div class="dashboard-head-title-wrap">
                    @if($project->image)
                        <div class="avatar avatar-lg">
                            <img src="{{ asset('storage/'.$project->image) }}" alt="Avatar" class="avatar-img rounded-circle">
                        </div>
                    @else
                        <div class="avatar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{$project->name}}">{{$project->initials}}</div>
                    @endif
                    <div>
                        <h3 class="main-body-header-title mb-2">{{ $project->name }}</h3>
                        <div class="text-sm text-uppercase"> {{ $project->client->name ?? session('org_name') }}</div>
                    </div>
                </div>
            </div>
            <div class="text-end col">
                <div class="main-body-header-right">
                    <livewire:components.pin-button pinnable_type="App\Models\Project"  :pinnable_id="$project->id" />
                    @can('Edit Project')
                    <!-- Edit -->
                    <div class="cus_dropdown">

                        @if($project->trashed())
                            <div class="cus_dropdown-icon btn-border btn-border-danger">Archived <i class='bx bx-chevron-down'></i></div>
                        @elseif($project->status == 'completed')
                            <div class="cus_dropdown-icon btn-border btn-border-success">Completed <i class='bx bx-chevron-down'></i></div>
                        @else
                            <div class="cus_dropdown-icon btn-border btn-border-success">Active <i class='bx bx-chevron-down'></i></div>
                        @endif

                        <div class="cus_dropdown-body cus_dropdown-body-widh_s">
                            <div class="cus_dropdown-body-wrap">
                                <ul class="cus_dropdown-list">
                                    <li><a href="javascript:" wire:click="changeProjectStatus('active')" @if(!$project->trashed() && $project->status != 'completed' ) class="active" @endif>Active</a></li>
                                    <li><a href="javascript:" wire:click="changeProjectStatus('completed')" @if($project->status == 'completed') class="active" @endif>Completed</a></li>
                                    <li><a href="javascript:" wire:click="emitDeleteProjectEvent({{$project->id}})" @if($project->trashed()) class="active" @endif>Archived</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <a href="javascript:" wire:click="emitEditProjectEvent({{ $project->id }})" class="btn-sm btn-border btn-border-secondary">
                        <span wire:loading wire:target="emitEditProjectEvent">
                            <i class='bx bx-loader-alt bx-spin'></i>
                        </span>
                        <i class='bx bx-pencil'></i> 
                        Edit
                    </a>
                    @endcan
                    @can('Delete Project')
                    {{-- <a href="javascript:" wire:click.confirm="emitDeleteProjectEvent({{ $project->id }})" class="btn-sm btn-border btn-border-danger"><i class='bx bx-trash'></i> Delete</a> --}}
                    <a href="javascript:" wire:click="forceDeleteProject({{ $project->id }})" wire:confirm="Are you sure you want to delete?" class="btn-sm btn-border btn-border-danger"><i class='bx bx-trash'></i> Delete</a>
                    @endcan
                </div>
            </div>
        </div>
        <hr class="mb-0">
        <div class="tabNavigationBar-tab border_style">
           
            <a wire:navigate class="tabNavigationBar-item @if($currentRoute == 'project.profile') active @endif" href="{{ route('project.profile', $project->id) }}"><i class='bx bx-line-chart'></i> Overview</a>
            @if(!$project->trashed()) 
            <a wire:navigate class="tabNavigationBar-item @if($currentRoute == 'project.tasks') active @endif" href="{{route ('project.tasks', $project->id) }}"><i class='bx bx-layer' ></i> Tasks</a>
            <a wire:navigate class="tabNavigationBar-item @if($currentRoute =='project.file-manager') active @endif" href="{{route('project.file-manager',$project->id)}}"><i class='bx bx-objects-horizontal-left' ></i> File Manager</a>
            <a wire:navigate class="tabNavigationBar-item @if($currentRoute == 'project.calendar') active @endif" href="{{route('project.calendar',$project->id)}}"><i class='bx bx-calendar' ></i> Calendar</a>
            @endif
        </div>
    </div>
    <livewire:components.add-project @saved="$refresh" />
</div>
