<div>
    <div class="dashboard-head pb-0 mb-4">
        <div class="row align-items-center">
            <div class="col">
                <div class="dashboard-head-title-wrap">
                    <div class="avatar avatar-lg">
                        <x-avatar :user="$activity" />
                    </div>
                    <div>
                        <h3 class="main-body-header-title mb-2">{{ $activity->name }}  </h3>
                    </div>
                </div>
            </div>
            <div class="text-end col">
                <div class="main-body-header-right">
                    <a href="javascript:" wire:click="emitEditActivityEvent({{ $activity->id }})" class="btn-sm btn-border btn-border-secondary">
                        <span wire:loading wire:target="emitEditActivityEvent">
                            <i class='bx bx-loader-alt bx-spin'></i>
                        </span>
                        <i class='bx bx-pencil'></i> 
                        Edit
                    </a>
                    
                    @can('Delete Project')
                    <a href="javascript:" wire:click="forceDeleteActivity({{ $activity->id}})" wire:confirm="Are you sure you want to delete?" class="btn-sm btn-border btn-border-danger"><i class='bx bx-trash'></i> Delete</a>
                    @endcan
                </div>
            </div>
        </div>
        <hr class="mb-0">
        <div class="tabNavigationBar-tab border_style">
            <a wire:navigate class="tabNavigationBar-item @if($currentRoute == 'activity.profile') active @endif" href="{{ route('activity.profile', $activity->id) }}"><i class='bx bx-line-chart'></i> Overview</a>
            <a wire:navigate class="tabNavigationBar-item @if($currentRoute == 'activity.tasks') active @endif" href="{{ route('activity.tasks',$activity->id) }}"><i class='bx bx-layer' ></i> Tasks</a>
        </div>
    </div>
    <livewire:components.add-activity @saved="$refresh" />
</div>
