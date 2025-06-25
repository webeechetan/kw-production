<div class="container">
    <!-- Dashboard Header -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard', session('org_id') ) }}"><i class='bx bx-line-chart'></i> {{ ucfirst(Auth::user()->organization->name)  }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-6">
            <div class="box-item h-100">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <div class="welcome-box">
                            <h4 class="mb-1"><span class="fw-normal">Hi {{ Auth::guard(session('guard'))->user()->name
                                    }}</span>,</h4>
                            <h3><b>Welcome back!</b></h3>
                        </div>
                    </div>
                    <div class="col-md-5 text-end">
                        <div class="welcome-box-img">
                            {{-- <img src="{{ asset('') }}assets/images/welcome-img.png" alt="Kaykewalk Welcome" /> --}}
                            <img src="{{ asset('assets/images/welcome-img.png') }}" alt="Kaykewalk Welcome" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box-item">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Tasks Overview</h4>
                        <h6>Total Active Tasks: <span class="text-secondary font-500"> {{ $users_tasks->count()  }} </span></h6>
                        <div class="row text-center mt-3">
                            <div class="col">
                                <div id="my-task-chart" style="width: 70px; height: 70px; margin: auto;"></div>
                                <span>My Tasks</span>
                            </div>
                            <div class="col">
                                <div id="others-tasks-chart" style="width: 70px; height: 70px; margin: auto;"></div>
                                <span>Other Tasks</span>
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-6 border-start">
                        <div class="text-center">
                            <div id="progress-chart-gauge" style="width: 100%;height: 200px; margin-top: -15px; margin-bottom: -80px;"></div>
                            <div>
                                Progress
                                <b>
                                    <span class="text-success">
                                        {{ round($users_tasks->where('status','completed')->count() > 0 ?
                                        ($users_tasks->where('status','completed')->count() / $users_tasks->count()) * 100 :
                                        0)}}%
                                    </span>
                                </b>
                            </div>
                        </div>
                        <div class="row mt-2 task-details text-center">
                            <div class="col-md-6">
                                <h6><b>{{ $users_tasks->where('status','in_progress')->count()}}</b></h6>
                                <div>On Going</div>
                            </div>
                            <div class="col-md-6">
                                <h6><b>{{ $users_tasks->where('status','pending')->count()}}</b></h6>
                                <div>Unfinished</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mt-4">
            <div class="box-item h-100">
                <h4 class="text-xl">My Tasks</h4>
                <hr>
                <ul class="nav nav-pills nav-pills-xs mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-today-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-today" type="button" role="tab"
                            aria-controls="pills-today" aria-selected="false">
                            Today 
                            <span class="btn-batch">
                                {{ $users_tasks->where('due_date', now()->format('Y-m-d'))->where('status','!=','completed')->count();}}
                            </span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link " id="pills-upcoming-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-upcoming" type="button" role="tab"
                            aria-controls="pills-upcoming" aria-selected="true">Upcoming <span
                                class="btn-batch">{{ $users_tasks->where('due_date', '>',
                                now())->where('status','!=','completed')->count();}}</span></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-overdue-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-overdue" type="button" role="tab"
                            aria-controls="pills-overdue" aria-selected="false">Overdue <span
                                class="btn-batch">{{ $users_tasks->where('due_date', '<', now())->where('status','!=','completed')->
                                    count();}}</span></button>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active overflow-y scrollbar scrollbar-primary" id="pills-today" role="tabpanel"
                        aria-labelledby="pills-upcoming-tab" tabindex="0">
                        <div class="taskList scrollbar">
                            <div style="height: 538px;">
                                @foreach($users_tasks->where('due_date', now()->format('Y-m-d'))->where('status','!=','completed')->take(15) as $task)
                                <div class="taskList_row taskList_todos">
                                    <div class="row">
                                        <div class="col">
                                            <div class="taskList_col taskList_col_title">
                                                <div class="taskList_col_title_open" ><i
                                                        class="bx bx-chevron-right"></i>
                                                </div>
                                                <a href="{{ route('task.view', $task->id) }}">
                                                    <div>{{Str::limit($task->name, 25, '...')}}</div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="taskList_col"><span class="btn-batch ">{{ Carbon\Carbon::parse($task->due_date)->format('d M') }}</span></div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @if($users_tasks->where('due_date', '>', now())->count() > 10)
                        <div class="text-center mt-2"><a href="{{ route('task.index') }}" wire:navigate class="btn-link">See More</a></div>
                        @endif
                    </div>
                    <div class="tab-pane fade overflow-y scrollbar scrollbar-primary" id="pills-upcoming" role="tabpanel"
                        aria-labelledby="pills-upcoming-tab" tabindex="0">
                        <div class="taskList scrollbar">
                            <div style="height: 307px;">
                                @foreach($users_tasks->where('due_date', '>', now())->where('status','!=','completed')->take(15) as $task)
                                <div class="taskList_row taskList_todos">
                                    <div class="row">
                                        <div class="col">
                                            <div class="taskList_col taskList_col_title">
                                                <div class="taskList_col_title_open" ><i
                                                        class="bx bx-chevron-right"></i>
                                                </div>
                                                <a href="{{ route('task.view', $task->id) }}">
                                                    <div>{{Str::limit($task->name, 25, '...')}}</div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="taskList_col"><span class="btn-batch ">{{ Carbon\Carbon::parse($task->due_date)->format('d M') }}</span></div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @if($users_tasks->where('due_date', '>', now())->count() > 10)
                        <div class="text-center mt-2"><a href="{{ route('task.index') }}" wire:navigate class="btn-link">See More</a></div>
                        @endif
                    </div>
                    <div class="tab-pane fade overflow-y scrollbar scrollbar-primary" id="pills-overdue" role="tabpanel"
                        aria-labelledby="pills-overdue-tab" tabindex="0">

                        <div class="taskList scrollbar">
                            <div style="height: 307px;">
                                @foreach($users_tasks->where('due_date', '<', now())->where('status','!=','completed')->take(15) as $task)
                                <div class="taskList_row taskList_todos">
                                    <div class="row">
                                        <div class="col">
                                            <div class="taskList_col taskList_col_title">
                                                <div class="taskList_col_title_open" ><i
                                                        class="bx bx-chevron-right"></i>
                                                </div>
                                                <a href="{{ route('task.view', $task->id) }}">
                                                    <div>{{Str::limit($task->name, 25, '...')}}</div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="taskList_col"><span class="btn-batch ">{{ Carbon\Carbon::parse($task->due_date)->format('d M') }}</span></div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @if($users_tasks->where('due_date', '>', now())->count() > 10)
                        <div class="text-center mt-2"><a href="{{ route('task.index') }}" wire:navigate class="btn-link">See More</a></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 mt-4">
            <div class="box-item calendar-title">
                <!-- <h4>Calendar</h4> -->
                <div id="calendar"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mt-4">
            <div class="box-item h-100">
                <h4 class="text-xl">My Progress</h4>
                <hr>
                <div class="text-center" id="user-progress-pie-chart" style="width: 100%;height: 250px;"></div>
                <div class="row">
                    <div class="col">
                        <div class="row align-items-center">
                            <div class="col-md-auto pe-md-0">
                                <div class="text-primary"><i class="bx bxs-circle"></i></div>
                            </div>
                            <div class="col ps-md-2">
                                <h6 class="mb-0">Assigned Tasks</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-auto">
                        <h6 class="mb-0 text-primary"><b>{{ $users_tasks->where('status',
                                'pending')->count();}}</b></h6>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col">
                        <div class="row align-items-center">
                            <div class="col-md-auto pe-md-0">
                                <div class="text-secondary"><i class="bx bxs-circle"></i></div>
                            </div>
                            <div class="col ps-md-2">
                                <h6 class="mb-0">Accepted Tasks</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-auto">
                        <h6 class="mb-0 text-secondary"><b>{{ $users_tasks->where('status',
                                'in_progress')->count();}}</b></h6>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col">
                        <div class="row align-items-center">
                            <div class="col-md-auto pe-md-0">
                                <div class="text-warning"><i class="bx bxs-circle"></i></div>
                            </div>
                            <div class="col ps-md-2">
                                <h6 class="mb-0">In Review Tasks</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-auto">
                        <h6 class="mb-0 text-warning"><b>{{ $users_tasks->where('status',
                                'in_review')->count();}}</b></h6>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col">
                        <div class="row align-items-center">
                            <div class="col-md-auto pe-md-0">
                                <div class="text-danger"><i class="bx bxs-circle"></i></div>
                            </div>
                            <div class="col ps-md-2">
                                <h6 class="mb-0 text-danger">Overdue Tasks</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-auto">
                        <h6 class="mb-0 text-danger"><b>{{ $users_tasks->where('due_date', '<', now())->where('status','!=','completed')->
                                    count();}}</b></h6>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col">
                        <div class="row align-items-center">
                            <div class="col-md-auto pe-md-0">
                                <div class="text-success"><i class="bx bxs-circle"></i></div>
                            </div>
                            <div class="col ps-md-2">
                                <h6 class="mb-0">Completed Tasks</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-auto">
                        <h6 class="mb-0 text-success"><b>{{
                                $users_tasks->where('status','completed')->count();}}</b></h6>
                    </div>
                </div>
                <hr>
                <!-- <div class="row">
                    <div class="col">
                        <div class="row align-items-center">
                            <div class="col-md-auto pe-md-0">
                                <div class="text-secondary"><i class="bx bxs-circle"></i></div>
                            </div>
                            <div class="col ps-md-2">
                                <h6 class="mb-0">Active Projects</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-auto">
                        <h6 class="mb-0 text-secondary"><b>{{$active_projects}}</b></h6>
                    </div>
                </div> -->
            </div>
        </div>
        <div class="col-md-4 mt-4">
            <div class="box-item h-100">
                <h4 class="text-xl">Important Projects</h4>
                <hr>
                <div class="overflow-y scrollbar scrollbar-primary">
                    @foreach($mostImportantProjects as $project)
                    <div class="card_style bg-light spacing-between-20">
                        <a href="{{ route('project.profile', $project->id) }}" class="card_style-open"><i class='bx bx-chevron-right'></i></a>
                        <div class="row mb-2">
                            <div class="col-auto pe-md-1">
                                <x-avatar class="avatar-sm" :user="$project" />
                            </div>
                            <div class="col">
                                <div class="text-sm text-light"><i class="bx bx-briefcase-alt-2"></i> {{ $project->client->name}}</div>
                                <div class="mb-1 text-lg">
                                    <a href="{{ route('project.profile', $project->id) }}" wire:navigate>{{ $project->name }}</a>
                                </div>
                                <div class="text-danger text-sm"><i class="bx bx-calendar"></i> <span>{{ \Carbon\Carbon::parse($project->due_date)->format('M d Y')
                                    ?? 'No Due Date' }}</span></div>
                            </div>
                        </div>
                        <!-- Need to remove team from here -->
                        <div class="d-flex flex-wrap gap-2 mt-2 d-none">
                            @if($project->teams)
                            @foreach($project->teams as $team)
                            <a href="{{ route('team.profile', $team->id) }}" wire:navigate class="btn btn-success rounded-pill btn-xs text-uppercase">{{$team->name}}</a>
                            @endforeach
                            @endif
                        </div>
                        @php
                        $taskDone = $project->tasks->where('status', 'completed')->count();
                        $taskPending = $project->tasks->where('status', '!=', 'completed')->count();
                        $taskOverdue = $project->tasks->where('due_date', '<', now())->where('status', '!=', 'completed')->count();
                        $totalTask = $project->tasks->count();

                        if ($totalTask > 0) {
                        $percentage = ($taskDone / $totalTask) * 100;
                        } else {
                        $percentage = 0;
                        }
                        @endphp
                        <div class="progress mt-3">
                            <div class="progress-bar progress-secondary" role="progressbar" aria-label="Task Done"
                                style="width: {{$percentage}}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col"><span class="btn-batch btn-batch-secondary"><b>{{ $taskPending }}</b> <span>Pending {{ pluralOrSingular($taskPending,'Task') }} </span></span></div>
                            <div class="col text-end"><span class="btn-batch btn-batch-danger"><b>{{ $taskOverdue }}</b> <span>Overdue {{ pluralOrSingular($taskOverdue,'Task') }} </span></span></div>
                            
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-4 mt-3">
            <div class="box-item h-100">
                <h4 class="text-xl">Recent Comments</h4>
                <hr>
                <div class="scrollbar">
                    <div style="height: 540px;">
                        @foreach($recent_comments as $comment)
                            <div class="cmnt_item_row card_style bg-light">
                                <a href="{{ route('task.view', $comment->task_id) }}" class="card_style-open"><i class='bx bx-chevron-right'></i></a>
                                <div class="cmnt_item_user">
                                    <div class="cmnt_item_user_img">
                                        <x-avatar :user="$comment->user" class="avatar-sm" />
                                    </div>
                                    <div class="cmnt_item_user_name-wrap">
                                        <div class="cmnt_item_user_name">{{ $comment->user->name }}</div>
                                        <div class="cmnt_item_date">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <div class="mt-2 cmnt_item_task border-bottom pb-2"><i class="bx bx-task"></i> <a href="{{ route('task.view', $comment->task_id) }}">{{ $comment->task->name }}</a></div>
                                <div class="cmnt_item_user_text">
                                    <a class="text-light" href="{{ route('task.view', $comment->task_id) }}">
                                        {!! Str::limit(strip_tags($comment->comment), 25, '...') !!}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- pins --}}
    {{-- @if($myPins->count() > 0)
        <div class="row mt-4">
            <div class="col">
                <h4 class="text-xl">Pinned Projects</h4>
            </div>
        </div>
        <div class="row">
            @foreach($myPins as $pin)
                @php
                    $project = $pin->pinnable;
                @endphp
                <div class="col-md-4 mt-3">
                    <div class="card_style h-100">
                        <a href="{{ route('project.profile', $pin->pinnable_id) }}" class="card_style-open">
                            <i class="bx bx-chevron-right"></i>
                        </a>
                        <div class="card_style-project-head">
                            <div class="card_style-project-head-client"><span><i class="bx bx-briefcase-alt-2"></i></span> {{ $project->client?->name }}</div>
                            <h4>
                                <a href="{{ route('project.profile', $pin->pinnable_id) }}" wire:navigate>{{ $pin->project->name }}</a>
                            </h4>
                            <livewire:components.pin-button pinnable_type="App\Models\Project"  :pinnable_id="$project->id" />
                        </div>
                        <div class="card_style-project-body mt-3">
                            <div class="task_progress mt-3">
                                <div class="task_progress-btm">
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
        </div>
    @endif --}}
</div>
@php
    $tour = session()->get('tour');
    $events = [];
    foreach($users_tasks as $task){
        $color = '';
        if($task->status == 'completed'){
            $color = '#4F9F54';
        }else{
            if($task->due_date < now()){ 
                $color='red' ; 
            }else{ 
                if($task->status == 'in_progress'){ 
                    $color='#e7bf1d';
                }elseif ($task->status == 'in_review') {
                    $color='#7407ff';
                }else{
                    $color='#48914d';
                }

            } 
        } 
        $events[]=[ 'title'=> $task->name,
            'start' => $task->due_date,
            'url' => route('task.view', $task->id),
            'backgroundColor' => $color,
        ];
    }

    @endphp

    @assets
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://fastly.jsdelivr.net/npm/echarts@5.5.1/dist/echarts.min.js"></script>
    @if(isset($tour) && $tour != null && isset($tour['main_tour']))
    <link href="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/minified/introjs.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/intro.min.js"></script>
    @endif
    @endassets

    @script
    <script>
        $(document).ready(function () {

            // my-task-chart

            var dom = document.getElementById('my-task-chart');
            var myChart = echarts.init(dom, null, {
                renderer: 'canvas',
                useDirtyRect: false
            });

            var app = {};

            var option;

            option = {
                tooltip: {
                    trigger: 'item'
                },
                legend: {
                    top: '5%',
                    left: 'center',
                    show:false
                },
                series: [
                    {
                    name: 'My Tasks',
                    type: 'pie',
                    radius: ['40%', '70%'],
                    avoidLabelOverlap: false,
                    itemStyle: {
                        color : function(params) {
                            var colorList = ['#48914D','#ddd'];
                            return colorList[params.dataIndex]
                        }
                    },
                    label: {
                        show: false,
                        position: 'center'
                    },
                    emphasis: {
                        label: {
                        show: false,
                        fontSize: 40,
                        fontWeight: 'bold'
                        }
                    },
                    labelLine: {
                        show: false
                    },
                    data: [
                        {value: {{ $myTasks->where('status','completed')->count() }}, name: 'Completed'},
                        {value: {{ $myTasks->where('status','!=','completed')->count() }}, name: 'Pending'}
                    ]
                    }
                ]
            };

            if (option && typeof option === 'object') {
                myChart.setOption(option);
            }

            window.addEventListener('resize', myChart.resize);

            // others-tasks-chart

            var dom = document.getElementById('others-tasks-chart');
            var myChart = echarts.init(dom, null, {
                renderer: 'canvas',
                useDirtyRect: false
            });

            var app = {};

            var option;

            option = {
                tooltip: {
                    trigger: 'item'
                },
                legend: {
                    top: '5%',
                    left: 'center',
                    show:false
                },
                series: [
                    {
                    name: 'Others Tasks',
                    type: 'pie',
                    radius: ['40%', '70%'],
                    avoidLabelOverlap: false,
                    label: {
                        show: false,
                        position: 'center'
                    },
                    itemStyle: {
                        color : function(params) {
                            var colorList = ['#48914D','#ddd'];
                            return colorList[params.dataIndex]
                        }
                    },
                    emphasis: {
                        label: {
                        show: false,
                        fontSize: 40,
                        fontWeight: 'bold'
                        }
                    },
                    labelLine: {
                        show: false
                    },
                    data: [
                        {value: {{ $otherTasks->where('status','completed')->count() }}, name: 'Completed'},
                        {value: {{ $otherTasks->where('status','!=','completed')->count() }}, name: 'Pending'}
                    ]
                    }
                ]
            };

            if (option && typeof option === 'object') {
                myChart.setOption(option);
            }

            window.addEventListener('resize', myChart.resize);



            // calendar

            var events = @json($events);
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: events,
                dayMaxEventRows: true,
                moreLinkClick: 'popover',
                moreLinkClassNames: 'calendar-more-link-btn',

            });
            calendar.render();

            // progress pie chart

            var dom = document.getElementById('progress-chart-gauge');
            var myChart = echarts.init(dom, null, {
                renderer: 'canvas',
                useDirtyRect: false
            });
            var app = {};

            var option;

            option = {
                series: [
                    {
                        type: 'gauge',
                        startAngle: 180,
                        endAngle: 0,
                        min: 0,
                        max: 100,
                        splitNumber: 10,
                        itemStyle: {
                            color: '#4F9F54',
                            shadowColor: 'rgba(0,138,255,0.45)',
                        },
                        progress: {
                            show: true,
                            roundCap: false,
                            width: 18,
                            itemStyle: {
                                color: '#4F9F54',
                            },
                        },
                        pointer: {
                            icon: 'path://M2090.36389,615.30999 L2090.36389,615.30999 C2091.48372,615.30999 2092.40383,616.194028 2092.44859,617.312956 L2096.90698,728.755929 C2097.05155,732.369577 2094.2393,735.416212 2090.62566,735.56078 C2090.53845,735.564269 2090.45117,735.566014 2090.36389,735.566014 L2090.36389,735.566014 C2086.74736,735.566014 2083.81557,732.63423 2083.81557,729.017692 C2083.81557,728.930412 2083.81732,728.84314 2083.82081,728.755929 L2088.2792,617.312956 C2088.32396,616.194028 2089.24407,615.30999 2090.36389,615.30999 Z',
                            length: '80%',
                            width: 16,
                            offsetCenter: [0, '5%'],
                            
                        },
                        axisLine: {
                            roundCap: false,
                            lineStyle: {
                                width: 18
                            }
                        },
                        axisTick: {
                            show: false
                        },
                        splitLine: {
                            show: false
                        },
                        axisLabel: {
                            show: false
                        },
                        title: {
                            show: false
                        },
                        detail: {
                            show:false
                        },
                        data: [
                            {
                                value:  {{ round($users_tasks->where('status','completed')->count() > 0 ?
                                    ($users_tasks->where('status','completed')->count() / $users_tasks->count()) * 100 :
                                    0)}}                               
                            }
                        ]
                    }
                ]
            };

            if (option && typeof option === 'object') {
                myChart.setOption(option);
            }
            window.addEventListener('resize', myChart.resize);


            // user progress pie chart

            var dom = document.getElementById('user-progress-pie-chart');

            var myChart = echarts.init(dom, null, {
                renderer: 'canvas',
                useDirtyRect: false
            });

            var app = {};

            var option;

            option = {
                color: [
                    '#f83890', 
                    '#7407ff', 
                    '#e7bf1d', 
                    '#dc3545',
                    '#48914d'
                ],
                tooltip: {
                    trigger: 'item',
                    formatter: '{b}: {c} ({d}%)',
                },
                legend: {
                    show: false
                },
                series: [
                    {
                    name: 'Your Progress',
                    type: 'pie',
                    radius: ['40%', '70%'],
                    avoidLabelOverlap: false,
                    label: {
                        show: false,
                        position: 'center'
                    },
                    emphasis: {
                        label: {
                        show: true,
                        fontSize: 20,
                        fontWeight: 'bold'
                        }
                    },
                    labelLine: {
                        show: false
                    },
                    data: [
                        {value: {{$users_tasks->where('status', 'pending')->where('status','!=','completed')->count()}}, name: 'Assigned Tasks'},
                        {value: {{$users_tasks->where('status', 'in_progress')->where('status','!=','completed')->count()}}, name: 'Accepted Tasks'},
                        {value: {{$users_tasks->where('status', 'in_review')->where('status','!=','completed')->count()}}, name: 'In Review Tasks'},
                        {value: {{$users_tasks->where('due_date', '<', now())->where('status','!=','completed')->count()}}, name: 'Overdue Tasks'},
                        {value: {{$users_tasks->where('status','completed')->count()}}, name: 'Completed Tasks'}

                    ]
                    }
                ]
                };

            if (option && typeof option === 'object') {
                myChart.setOption(option);
            }
            window.addEventListener('resize', myChart.resize);


        });
    </script>
    @if(isset($tour) && $tour != null && isset($tour['main_tour']))
    <script>
        introJs()
            .setOptions({
                showProgress: true,
            })
            .onbeforeexit(function () {
                location.href = "{{ route('dashboard') }}?tour=close-main-tour";
            })
            .start();
    </script>
    @endif
    @endscript