<div class="container">
    <!-- Dashboard Header -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a wire:navigate href="{{ route('project.index') }}"><i class='bx bx-line-chart'></i>{{ ucfirst(Auth::user()->organization->name) }}</a></li>
            <li class="breadcrumb-item"><a wire:navigate href="{{ route('project.index') }}">All Projects</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $project->name }}</li>
        </ol>
    </nav>
    
    <livewire:projects.components.project-tabs :project="$project" />

    <!-- Dashboard Body -->
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="column-box states_style-success">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="states_style-icon"><i class='bx bx-layer' ></i></div>                        
                    </div>
                    <div class="col">
                        <div class="row align-items-center g-2">
                            <div class="col-auto">
                                <h5 class="title-md mb-0">{{ $projectTasks->where('status','!=','completed')->count() }}</h5>
                            </div>
                            <div class="col-auto">
                                <span class="font-400 text-grey">|</span>
                            </div>
                            <div class="col-auto">
                                <div class="states_style-text">Active {{ pluralOrSingular($projectTasks->count() ,'Task') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="column-box states_style-progress">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="states_style-icon"><i class='bx bx-layer' ></i></div>                        
                    </div>
                    <div class="col">
                        <div class="row align-items-center g-2">
                            <div class="col-auto">
                                <h5 class="title-md mb-0">
                                    @php
                                        $progress = 0;
                                        
                                        if($projectTasks->count() > 0){
                                            $progress = ($projectTasks->where('status', 'completed')->count() / $projectTasks->count()) * 100;
                                        }
                                    @endphp
                                    {{ round($progress) }} <span class="text-md">%</span>
                                </h5>
                            </div>
                            <div class="col-auto">
                                <span class="font-400 text-grey">|</span>
                            </div>
                            <div class="col-auto">
                                <div class="states_style-text">Progress</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="column-box states_style-active">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="states_style-icon"><i class='bx bx-layer' ></i></div>                        
                    </div>
                    <div class="col">
                        <div class="row align-items-center g-2">
                            <div class="col-auto">
                                <h5 class="title-md mb-0">{{$projectUsers->count()}}</h5>
                            </div>
                            <div class="col-auto">
                                <span class="font-400 text-grey">|</span>
                            </div>
                            <div class="col-auto">
                                <div class="states_style-text"> {{ pluralOrSingular($projectUsers->count(),'Member') }}</div>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="column-box mb-3">
                <div class="column-box font-500 bg-light mb-2">
                    <div class="row align-items-center">
                        <div class="col"><span><i class='bx bx-layer text-secondary' ></i></span> Created By</div>
                        <div class="col text-secondary">{{ $project->createdBy?->name }}</div>                   
                    </div>
                </div>
                <div class="column-box font-500 mb-2">
                    <div class="row align-items-center">
                        <div class="col"><span><i class='bx bx-calendar-alt text-primary' ></i></span> Start Date</div>
                        <div class="col text-secondary">
                            @if($project->start_date)
                                {{ \Carbon\Carbon::parse($project->start_date)->format('d M-Y') }}
                            @else
                                <span class="text-danger">Not Set</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="column-box font-500 bg-light mb-2">
                    <div class="row align-items-center">
                        <div class="col"><span><i class='bx bx-calendar text-primary' ></i></span> Due Date</div>
                        <div class="col project-due-date">
                            @if($project->due_date)
                                {{ \Carbon\Carbon::parse($project->due_date)->format('d M-Y') }} 
                            @else
                                <span class="text-danger">Not Set</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="column-box font-500 mb-2">
                    <div class="row align-items-center">
                        <div class="col"><span><i class='bx bx-time text-success'></i></span> Duration</div>
                        <div class="col text-secondary">{{ \Carbon\Carbon::parse($project->due_date)->diffInDays($project->start_date)}} Days</div>
                    </div>
                </div>
                <div class="column-box font-500 bg-light">
                    <div class="row">
                        <div class="col"><span><i class='bx bx-layer text-primary'></i></span> Attachments</div>
                        <div class="col">
                            <div class="d-flex align-items-center flex-wrap"><a wire:navigate href="{{ route('project.file-manager',$project->id) }}" class="ms-3 btn_link btn_link-border btn_link-sm">Add</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-6">
                    <div class="column-box">
                        <div class="column-head row align-items-center">
                            <div class="col">
                                <div class="column-title">Recent Activity</div>
                            </div>
                            <div class="col-auto">In this week</div>
                        </div>
                        <div class="activity-recent mt-3">
                            <div class="activity-recent-scroll custom_scrollbar">
                                @foreach($project->activities()->latest()->paginate(10) as $activity)
                                    <div class="activity row space-last_child_0">
                                        <div class="activity-profile col-auto pe-0">
                                            <x-avatar :user="$activity->createdBy" />
                                        </div>
                                        <div class="activity-text col">
                                            <div class="mb-0 font-500">{{ $activity->createdBy->name }}</div>
                                            <span class="text-sm">{!! $activity->text !!}</span>
                                        </div>
                                        <div class="activity-timeline col-auto text-sm"><span>{{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</span></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="column-box mb-3">
                        <div class="column-head">
                            <div class="column-title">Assignees 
                                <a href="javascript:" class="ms-3 btn_link btn_link-border btn_link-sm assign-new-user">
                                    Add
                                </a> 
                            </div>
                        </div>
                        <div class="assign-new-user-col d-none">
                            <select class="users" name="" id="" multiple>
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option 
                                        @if($project->users->contains($user->id))
                                            @selected(true)
                                        @endif
                                    value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Avatar Group -->
                        <div class="avatarGroup mt-3">
                            @php
                                $usersCount = $project->users->count();  
                            @endphp
                            @if(count($project->members) > 0)
                                @foreach($project->members as $user)
                                    @if($loop->index > 10)
                                        @break
                                    @endif
                                    @if($user->image)
                                        <a href="javascript:;" class="avatar avatar-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="{{$user->name}}">
                                            <img alt="avatar" src="{{ asset('storage/'.$user->image) }}" class="rounded-circle">
                                        </a>
                                    @else
                                        <a href="javascript:;" class="avatar avatar-sm avatar-{{$user->color}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{$user->name}}">{{ $user->initials }}</a>
                                    @endif
                                @endforeach
                                @if($usersCount > 10)
                                    <a href="javascript:" class="avatar avatar-sm" wire-key="project-user-more">
                                        +{{ $usersCount - 10 }}
                                    </a>
                                @endif
                            @else
                                <span>Not added</span>
                            @endif
                        </div>
                    </div>
                    <div class="column-box">
                        <div class="column-head"><div class="column-title">Teams</div></div>
                        <div class="btn-list mt-3">
                            @if(count($projectTeams) > 0)
                                @foreach($projectTeams as $team)
                                    <a href="javascript:" class="btn-batch">{{ $team->name }} </a>
                                @endforeach
                            @else
                                <span>Not added</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="column-box mt-3">
        <div wire:ignore class="project-description-container">
            <div class="column-title mb-2">Description <a href="javascript:" class="btn-link edit-description"><i class='bx bx-pencil'></i></a></div>
            <hr>
            <div class="project-description txtarea">
                {!! $project->description ?? 'Not Added' !!}
            </div>
            <button class="btn btn-primary update-btn d-none mt-2" wire:click="updateDescription">Update</button>
        </div>
        {{-- <a href="javascript:" class="btn_link btn_link-primary">see more</a> --}}
    </div>

</div>
@script

<script>
    flatpickr(".change-due-date", {
        dateFormat: "Y-m-d",
        defaultDate: "{{ $project->due_date }}",
        onChange: function(selectedDates, dateStr, instance) {
            $(".project-due-date").html(dateStr);
            @this.changeDueDate(dateStr);
        }
    });

    $(".update-btn").click(function(){
        $(".project-description").summernote('destroy');
        $(".update-btn").toggleClass("d-none");
    });

    document.addEventListener('project-added', event => {
        $(".project-description").html(event.detail[0].description);
    });

    $(".edit-description").click(function(){

        $(".update-btn").toggleClass("d-none");

        $(".project-description").summernote({
            height: 200,
            toolbar: [
                ['font', ['bold', 'underline']],
                ['para', ['ul', 'ol']],
                ['insert', ['link']],
                ['fm-button', ['fm']],
            ],
            callbacks: {
                onChange: function(contents, $editable) {
                    @this.set('description', contents)
                }
            },
        });
    });

    // destroy summernote when clicked outside

    $(document).on("click", function(event) {
        let l = $(event.target).closest(".project-description-container").length
        if(!l){
            $(".project-description").summernote('destroy')
        }
        
    });

    $(".users").select2({
        placeholder: "Select User",
        allowClear: true
    });
    
    $(".assign-new-user").click(function(){
        $(".assign-new-user-col").toggleClass("d-none");
        $(".users").select2({
            placeholder: "Select User",
            allowClear: true
        });
    });

    $(".users").change(function(){
        var users = $(this).val();
        console.log(users);
        @this.syncUsers(users);
    });  

    // user-synced
    document.addEventListener('user-synced', event => {
        $(".users").select2({
            placeholder: "Select User",
            allowClear: true
        });
    });    
</script>
@endscript
