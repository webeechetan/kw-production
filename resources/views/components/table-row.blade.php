<div class="taskList_row edit-task" data-id="{{ $task->id }}"  wire:key="task-row-{{ $task->id }}">
    <div class="taskList_col taskList_col_title">
        <div wire:loading wire:target="emitEditTaskEvent({{ $task->id }})" class="card_style-loader">
            <div class="card_style-loader-wrap"><i class='bx bx-pencil text-primary me-2' ></i> Loading ...</div>
        </div>
        <div class="taskList_col_title_open edit-task" data-id="{{ $task->id }}"><i class="bx bx-chevron-right"></i></div>
        <div class="edit-task" data-id="{{ $task->id }}">
            <div class="mb-1">
                {{ Str::limit($task->name, 50) }}
            </div>
            <span class="text-xs text-light">Created date: {{  Carbon\Carbon::parse($task->created_at)->format('d M Y') }}</span>
        </div>
    </div>

    <div class="taskList_row_wrap text-center d-grid grid_col-repeat-6">
        <div class="taskList_col">
            @php
                $date_color = '';
                if($task->due_date < \Carbon\Carbon::now() && $task->status != 'completed'){
                    $date_color = 'text-danger';
                }
                
                if($task->status == 'completed'){
                    $date_color = 'text-success';
                }

                if(!$task->due_date){
                    $date_color = '';
                }
            @endphp
            <span class="{{ $date_color }}"> 
                @if($task->due_date)
                    {{ Carbon\Carbon::parse($task->due_date)->format('d M Y') }}
                @else
                    No Due Date
                @endif
            </span>
        </div>
        
        <div class="taskList_col"><span>{{ $task->project?->client?->name }}</span></div>
        
        <div class="taskList_col"><span>{{ $task->project?->name }}</span></div>
        
        <div class="taskList_col">
            <div class="avatarGroup avatarGroup-overlap">

                @php
                $plus_more_users = 0;
                    if(count($task['users']) > 3){
                        $plus_more_users = count($task['users']) - 3;
                    }
                @endphp

                @foreach($task['users']->take(3) as $user)
                    <x-avatar :user="$user" class="avatar-sm" />
                @endforeach

                @if($plus_more_users)
                <a href="#" class="avatarGroup-avatar">
                    <span class="avatar avatar-sm avatar-more">+{{$plus_more_users}}</span>
                </a>
            @endif       
            </div>
        </div>

        <div class="taskList_col">
            <div class="avatarGroup avatarGroup-overlap">
                {{  $task->assignedBy?->name }}
            </div>
        </div>
        
        <div class="taskList_col">
            {{-- <span class="btn-batch btn-batch-primary"> --}}

                <span class="btn-batch 
                    @if($task->status == 'pending') btn-batch-primary 
                    @elseif ($task->status == 'in_progress') btn-batch-secondary
                    @elseif( $task->status == 'in_review') btn-batch-warning
                    @elseif( $task->status == 'completed') btn-batch-success
                    @endif" 
                >

                @if($task->status == 'pending') Assigned @endif
                @if($task->status == 'in_progress') Accepted @endif
                @if($task->status == 'in_review') In Review @endif
                @if($task->status == 'completed') Completed @endif
            </span>
        </div>
    </div>
</div>