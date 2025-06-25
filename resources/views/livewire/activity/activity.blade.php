<div class="container">
    <!-- Dashboard Header -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a wire:navigate href="{{ route('activity.index') }}"><i class='bx bx-line-chart'></i>{{ucfirst(Auth::user()->organization->name) }}</a></li>
            <li class="breadcrumb-item"><a wire:navigate href="{{ route('activity.index') }}">All Activity</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $activity->name }}</li>
        </ol>
    </nav>

    <livewire:activity.components.activity-tabs :activity="$activity" />

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
                                <h5 class="title-md mb-0">{{ $activity->tasks()->where('status','!=','completed')->count() }}</h5>
                            </div>
                            <div class="col-auto">
                                <span class="font-400 text-grey">|</span>
                            </div>
                            <div class="col-auto">
                                <div class="states_style-text">{{ $activity->count() > 1 ? 'Active Activities' : 'Active Activity' }}</div>
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
                                        
                                        if($activity->tasks->count() > 0){
                                            $progress = ($activity->tasks->where('status', 'completed')->count() / $activity->tasks->count()) * 100;
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
                                <h5 class="title-md mb-0">{{$activity->users()->count()}}</h5>                                
                            </div>
                            <div class="col-auto">
                                <span class="font-400 text-grey">|</span>
                            </div>
                            <div class="col-auto">
                                <div class="states_style-text">Members</div>
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
                        <div class="col text-secondary">{{ $activity->createdBy?->name }}</div>                   
                    </div>
                </div>
                <div class="column-box font-500 mb-2">
                    <div class="row align-items-center">
                        <div class="col"><span><i class='bx bx-calendar-alt text-primary' ></i></span> Start Date</div>
                        <div class="col text-secondary">
                            @if($activity->start_date)
                                {{ \Carbon\Carbon::parse($activity->start_date)->format('d M-Y') }}
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
                            @if($activity->due_date)
                                {{ \Carbon\Carbon::parse($activity->due_date)->format('d M-Y') }} 
                            @else
                                <span class="text-danger">Not Set</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="column-box font-500 mb-2">
                    <div class="row align-items-center">
                        <div class="col"><span><i class='bx bx-time text-success'></i></span> Duration</div>
                        <div class="col text-secondary">{{ \Carbon\Carbon::parse($activity->due_date)->diffInDays($activity->start_date)}} Days</div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-6">
                    <div class="column-box mb-3">
                        <div class="column-head"><div class="column-title">Assigness</div></div> --}}
                        <!-- Avatar Group -->
                        {{-- <div class="avatarGroup mt-3"> --}}
                            {{-- @php
                                $usersCount = $project->users->count();  
                            @endphp --}}
                            {{-- @if(count($project->members) > 0)
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
                            @endif --}}
                        {{-- </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="column-box mt-3">
        <div wire:ignore class="project-description-container">
            <div class="column-title mb-2">Description <a href="javascript:" class="btn-link edit-description"><i class='bx bx-pencil'></i></a></div>
            <hr>
            <div class="project-description txtarea">
                {!! $activity->description ?? 'Not Added' !!}
            </div>
            <button class="btn btn-primary update-btn d-none mt-2" wire:click="updateDescription">Update</button>
        </div>
        {{-- <a href="javascript:" class="btn_link btn_link-primary">see more</a> --}}
    </div>


</div>

@script
    <script>
        $(document).ready(function(){
            $(".update-btn").click(function(){
                $(".project-description").summernote('destroy');
                $(".update-btn").toggleClass("d-none");
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
        });
    </script>
@endscript
