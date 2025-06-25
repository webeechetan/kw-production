<div class="container">
    <!-- Dashboard Header -->
    <div class="row align-items-center">
        <div class="col-md-6 mb-3">
            <nav aria-label="breadcrumb">
                 <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard') }}"><i class='bx bx-line-chart'></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a wire:navigate href="{{ route('project.index') }}">All Projects</a></li>
                    <li class="breadcrumb-item"><a wire:navigate href="{{ route('project.profile',$task->project->id) }}">{{$task->project->name}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Task View</li>
                </ol>        
            </nav>
        </div>
        <div class="col-md-6 mb-3 text-end">
            <a href="#" class="btn-border btn-border-danger btn-sm"><i class='bx bx-trash'></i> Delete</a>
        </div>
    </div>
    <div class="column-box">
        <div class="taskPane-dashbaord py-2 px-4">
            <div class="taskPane-dashbaord-head">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex gap-3">
                            <div class="cus_dropdown">
                                <div class="cus_dropdown-icon btn-batch"><i class='bx bx-globe'></i> Public <span><i class='bx bx-chevron-down' ></i></span></div>
                                <div class="cus_dropdown-body cus_dropdown-body_left cus_dropdown-body-widh_s">
                                    <div class="cus_dropdown-body-wrap">
                                        <ul class="cus_dropdown-list">
                                            <li><a href="javascript:" class="active"><i class='bx bx-globe me-2' ></i> Public</a></li>
                                            <li><a href="javascript:"><i class='bx bx-lock me-2' ></i> Private</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="cus_dropdown-icon btn-batch"><i class='bx bx-check'></i> Mark Completed</div> --}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="taskPane-dashbaord-head-right">
                            <button type="button" class="btn-icon" data-bs-toggle="modal" data-bs-target="#attached-file-modal"><i class='bx bx-paperclip' style="transform: rotate(90deg);"></i></button>
                            <button type="button" class="btn-icon task-sharable-link" data-id="{{ $task->id }}"><i class='bx bx-share-alt' ></i></button>
                            <button wire:click="saveTask" type="button" class="btn-border btn-border-secondary"><i class='bx bx-check' ></i> Update Task</button>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <form class="taskPane" method="POST" wire:submit="saveTask" enctype="multipart/form-data">
                <div class="taskPane-body">
                    <div class="row">
                        <div class="col-md-8 pe-md-5">
                            <div class="taskPane-heading mb-4">
                                <div class="taskPane-heading-label"><i class='bx bx-notepad text-primary'></i> Task Heading</div>
                                <input class="form-control form-control-typeStyle AddTask_title" wire:model="name" type="text" placeholder="Write a task name">
                            </div>
                            <div class="taskPane-item assigner-tab d-flex flex-wrap mb-3">
                                <div class="taskPane-item-left"><div class="taskPane-item-label">Assignor </div></div>
                                <div class="taskPane-item-right">
                                    <span class="select2-selection__choice__display" id="">{{ $task->assignedBy->name }}</span>
                                </div>
                            </div>
                            <hr>
                            <div class="taskPane-item d-flex flex-wrap mb-3">
                                <div class="taskPane-item-left"><div class="taskPane-item-label">Assigned to</div></div>
                                <div class="taskPane-item-right" wire:ignore>
                                    <select name="" id="" class="task-users" multiple>
                                        <option value="" disabled>Select User</option>
                                        @foreach($users as $user)
                                            <option 
                                                @if(in_array($user->id, $selectedUsers)) selected @endif
                                            value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="taskPane-item d-flex flex-wrap mb-3">
                                <div class="taskPane-item-left"><div class="taskPane-item-label">Notify to</div></div>
                                <div class="taskPane-item-right"  wire:ignore>
                                    <select name="" id="" class="task-notify-users" multiple>
                                        <option value="" disabled>Select User</option>
                                        @foreach($users as $user)
                                            <option 
                                                @if(in_array($user->id, $selectedNotifiers)) selected @endif
                                            value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="taskPane-item d-flex flex-wrap mb-3">
                                <div class="taskPane-item-left"><div class="taskPane-item-label">Project</div></div>
                                <div class="taskPane-item-right">
                                    <select wire:model="project_id" id="" class="task-projects">
                                        <option value="" disabled>Select Project</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <hr>
                        </div>
                        <div class="col-md-4">
                            <div class="taskPane-item d-flex flex-wrap align-items-center">
                                <div class="taskPane-item-left">
                                    <div class="taskPane-item-label"><i class='bx bx-coin-stack text-primary' ></i> Status</div>
                                </div>
                                <div class="taskPane-item-right text-end">
                                    <div class="cus_dropdown-body-wrap">
                                        <select name="" id="" wire:model="status" class="form-control">
                                            <option value="pending">Pending</option>
                                            <option value="in_progress">In Progress</option>
                                            <option value="in_review">In Review</option>
                                            <option value="completed">Completed</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="taskPane-item d-flex flex-wrap mb-3">
                                <div class="taskPane-item-left">
                                    <div class="taskPane-item-label"><i class='bx bx-calendar text-secondary' ></i> Due Date</div>
                                </div>
                                <div class="taskPane-item-right text-end" wire:ignore>
                                    <a href="javascript:"><span class="btn_link due_date">{{$due_date}}</span></a>
                                </div>
                            </div>
                            {{-- <div class="taskPane-calenderView due_date">{{$due_date}}</div> --}}
                        </div>
                    </div>
                </div>
            </form>
            <div class="taskPane-item mb-3">
                <div class="taskPane-item-label mb-3">Description</div>
                <div wire:ignore>
                    <textarea wire:model="description" id="editor" cols="30" rows="4" placeholder="Add Notes">{{ $description }}</textarea>
                </div>
            </div>
            <div class="taskPane-item mb-3">
                <div class="d-none">
                    <input type="file" id="file" wire:model="attachments" multiple class="form-control">
                </div>
                <div class="taskPane-item-label mb-3 add-new-attachments"><a ><i class="bx bx-paperclip text-secondary" style="transform: rotate(60deg);"></i></a> {{$task->attachments->count()}} Attachments</div>
                @if($task->attachments->count() > 0)
                    <div class="attached_files ">
                        <a data-bs-toggle="modal" data-bs-target="#attachmentModal">View Attachments</a>
                    </div>
                @endif
            </div>
            <div class="voice_note-preview">
                <div class="cmnt_sec pt-4">
                    
                    <!-- Activity -->
                    <div class="cmnt_item">
                        <h5 class="cmnt_item_title"><span><i class='bx bxs-microphone text-primary'></i> Voice Notes</span><span class="text-sm"><i class='bx bxs-microphone text-secondary'></i> {{ $task->voiceNotes->count() }} Voice Notes</span></h5>
                        <div class="my-4">
                            <div class="voice_note-preview"></div>
                            <div class="voice_note-wrap d-flex align-items-center justify-content-between gap-4">
                                <div class="font-500 text-secondary">Add Voice Note <span class="text-sm">| <span class="voie-note-duration">30</span>s</span></div>
                                <div class="voice_note voice_note-icon">
                                    <span id="recordButton" class="text-secondary d-flex" title="Start"><i class='bx bxs-microphone' ></i></span>
                                    <span id="stopButton" class="d-none d-flex" title="Stop"><i class='bx bx-stop-circle bx-tada bx-flip-vertical' ></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="cmnt_item-tabs">
                            <div class="taskPane-item d-flex flex-wrap mb-3 voice-notes-list gap-3">
                                @foreach($task->voiceNotes as $note)
                                    <div class="voice-notes-item">
                                        <div class="voice-notes-item-user d-flex gap-2 mb-2">
                                            <div class="voice-notes-item-user-img">
                                                <x-avatar :user="$note->user" />
                                            </div>
                                            <div class="voice-notes-item-user-name-wrap">
                                                <div class="voice-notes-item-user-name text-sm">{{ $note->user->name }}</div>
                                                <div class="voice-notes-item-date text-xs">{{ $note->created_at->diffForHumans() }}</div>
                                            </div>  
                                        </div>
                                        <div class="voice-notes-item-audio">
                                            <audio controls="">
                                                <source src="{{ env('APP_URL') }}/{{$note->path}}" type="audio/wav">
                                            </audio>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="cmnt_sec pt-4">
                <!-- Activity -->
                <div class="cmnt_item">
                    <h5 class="cmnt_item_title"><span><i class='bx bx-line-chart text-primary'></i> Comments</span><span class="text-sm"><i class='bx bx-comment-dots text-secondary'></i> {{ $task->comments->count() }} Comments</span></h5>
                    <div class="cmnt_item-tabs">
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-internal-tab" data-bs-toggle="tab" data-bs-target="#nav-internal" type="button" role="tab" aria-controls="nav-internal" aria-selected="true">Internal Feedback <span class="text-sm btn-batch btn-batch-secondary ms-3"><i class='bx bx-comment-dots text-secondary'></i> {{ $task->comments->where('type','internal')->count() }} Comments</span></button>
                            <button class="nav-link" id="nav-client-tab" data-bs-toggle="tab" data-bs-target="#nav-client" type="button" role="tab" aria-controls="nav-client" aria-selected="false">Client Feedback <span class="text-sm btn-batch btn-batch-secondary ms-3"><i class='bx bx-comment-dots text-secondary'></i> {{ $task->comments->where('type','client')->count() }} Comments</span></button>
                        </div>
                    </div>
                    <div class="tab-content" id="nav-tabContent" wire:ignore>
                        <div class="tab-pane fade show active" id="nav-internal" role="tabpanel" aria-labelledby="nav-internal-tab" tabindex="0">
                            @foreach($task->comments->where('type','internal') as $comment)
                                <div class="cmnt_item_row comment-box-{{$comment->id}}">
                                    <div class="cmnt_item_user">
                                        <div class="cmnt_item_user_img">
                                            <x-avatar :user="$comment->user" />
                                        </div>
                                        <div class="cmnt_item_user_name-wrap">
                                            <div class="cmnt_item_user_name">{{ $comment->user->name }}</div>
                                            <div class="edit-comment-section-{{ $comment->id }} d-none"></div>
                                            <div class="cmnt_item_date">{{ $comment->created_at->diffForHumans() }}</div>
                                            <div class="cmnt_item_user_text comment-{{ $comment->id }}">{!! $comment->comment !!}</div>
                                        </div>
                                        <div class="cmnt_item_user-edit btn-list">
                                            <a data-id="{{ $comment->id }}" class="btn_link edit-comment"><i class='bx bx-pencil' ></i></a>
                                            <a class="btn_link" wire:click="deleteComment({{$comment->id}})">
                                                <i class='bx bx-trash' wire:loading.remove wire:target="deleteComment({{$comment->id}})"></i>
                                                <span wire:loading wire:target="deleteComment({{$comment->id}})">
                                                    ...
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="custComment">
                                <div class="custComment-editor" wire:ignore>
                                    <textarea name="" id="comment_box" cols="30" rows="5"></textarea>
                                </div>
                                <button wire:click="saveComment" class="btn btn-sm btn-secondary mt-3"><i class='bx bx-send'></i> Comment</button>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-client" role="tabpanel" aria-labelledby="nav-client-tab" tabindex="0">
                            @foreach($task->comments->where('type','client') as $comment)
                                <div class="cmnt_item_row comment-box-{{$comment->id}}">
                                    <div class="cmnt_item_user">
                                        <div class="cmnt_item_user_img">
                                            <x-avatar :user="$comment->user" />
                                        </div>
                                        <div class="cmnt_item_user_name-wrap">
                                            <div class="cmnt_item_user_name">{{ $comment->user->name }}</div>
                                            <div class="edit-comment-section-{{ $comment->id }} d-none"></div>
                                            <div class="cmnt_item_date">{{ $comment->created_at->diffForHumans() }}</div>
                                            <div class="cmnt_item_user_text comment-{{ $comment->id }}">{!! $comment->comment !!}</div>
                                        </div>
                                        <div class="cmnt_item_user-edit btn-list">
                                            <a data-id="{{ $comment->id }}" class="btn_link edit-comment"><i class='bx bx-pencil' ></i></a>
                                            <a class="btn_link" wire:click="deleteComment({{$comment->id}})">
                                                <i class='bx bx-trash' wire:loading.remove wire:target="deleteComment({{$comment->id}})"></i>
                                                <span wire:loading wire:target="deleteComment({{$comment->id}})">
                                                    ...
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="custComment">
                                <div class="custComment-editor" wire:ignore>
                                    <textarea name="" id="client_comment_box" cols="30" rows="5"></textarea>
                                    <div class="custComment-attachments"><i class="bx bx-paperclip" style="transform: rotate(90deg);"></i></div>
                                </div>
                                <button wire:click="saveComment('client')" class="btn btn-sm btn-secondary mt-3"><i class='bx bx-send'></i> Comment</button>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="taskPane-footer-wrap pt-3 mt-4">
                <button type="button" wire:click="saveTask" class="btn-border btn-border-secondary ms-auto"><i class='bx bx-check' ></i> Update Task</button>
            </div>
        </div>   
    </div>
    <div class="modal fade" id="attachmentModal" tabindex="-1" role="dialog" aria-labelledby="attachmentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="attachmentModalLabel">Attachments</h5>
                    <div><i class="bx bx-data text-primary"></i> <span class="task-attachment-count">1</span> Attachments</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body attachements-body mt-4 mb-4">
                <div class="attached_files px-4">
                    @if($task)
                        @foreach($task->attachments as $attach)
                        <div class="attached_files-item">
                            @if($attach->can_view)
                                <div class="attached_files-item-preview">
                                    <a target="_blank" href="{{ env('APP_URL') }}/storage/{{$attach->attachment_path}}"><img class="attached_files-item-thumb" src="{{ env('APP_URL') }}/storage/{{$attach->attachment_path}}" alt="" /></a>
                                </div>
                            @else
                                <div class="attached_files-item-preview">
                                    <a target="_blank" href="{{ env('APP_URL') }}/storage/{{$attach->attachment_path}}"><img class="attached_files-item-thumb" src="{{$attach->thumbnail}}" alt="" /></a>
                                </div>
                            @endif
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

@script
    <script>

        window.addEventListener('voice-note-added', function(event) {
            @this.set('voice_note', event.detail);
            @this.saveVoiceNoteToTask({{ $task->id }});
        });
        window.addEventListener('comment_deleted', function(event) {
            let deleted_comment_id = event.detail;
            $(".comment-box-"+deleted_comment_id).hide();
        });

        document.addEventListener('comment-added', event => {
            $('#comment_box').summernote('code', '');
        });

        // detect if user came on this page from a browser back button
        window.addEventListener('popstate', function(event) {
            location.reload();
        });

        $(document).ready(function(){

            if(typeof users_for_mention === 'undefined'){
                var users_for_mention = [];
                var users = @json($users);
                users.forEach(user => {
                    users_for_mention.push(user.name);
                });
            }else{
                var users_for_mention = users_for_mention;
                var users = @json($users);
            }

            // copy link to clipboard

            $('.task-sharable-link').on('click', function(){
                var id = $(this).data('id');
                var url = "{{ env('APP_URL') }}" + "/{{ session('org_name') }}" + "/task/view/" + id;
                navigator.clipboard.writeText(url).then(function() {
                    console.log('Async: Copying to clipboard was successful!');
                    toastr.success('Task sharbable link copied to clipboard');
                }, function(err) {
                    console.error('Async: Could not copy text: ', err);
                });
            }); 

            $('#comment_box').summernote(
                {
                    height: 200,
                    hint: {
                        mentions: users_for_mention,
                        match: /\B@(\w*)$/,
                        search: function (keyword, callback) {
                            callback($.grep(this.mentions, function (item) {
                                return item.toLowerCase().indexOf(keyword.toLowerCase()) == 0;
                            }));
                        },
                        template : function (item) {
                            return '<span class="mention_user" data-id=" ' + users[users_for_mention.indexOf(item)].id + ' ">' + item + '</span>';
                        },
                        content: function (item) {
                            let a = document.createElement('a');
                            a.href =  "{{ env('APP_URL') }}/" + "{{ session('org_name') }}" +'/user/view/' + users[users_for_mention.indexOf(item)].id;
                            a.innerText = '@' + item;
                            return a;
                        },    
                    },
                    toolbar: [
                        ['font', ['bold', 'underline']],
                        ['para', ['ul', 'ol']],
                        ['insert', ['link']],
                        ['fm-button', ['fm']],
                    ],
                    callbacks: {
                        onChange: function(contents, $editable) {
                            @this.set('comment', contents);
                        }
                    }
                }
            );

            $('#client_comment_box').summernote(
                {
                    hint: {
                        mentions: users_for_mention,
                        match: /\B@(\w*)$/,
                        search: function (keyword, callback) {
                            callback($.grep(this.mentions, function (item) {
                                return item.toLowerCase().indexOf(keyword.toLowerCase()) == 0;
                            }));
                        },
                        template : function (item) {
                            return '<span class="mention_user" data-id=" ' + users[users_for_mention.indexOf(item)].id + ' ">' + item + '</span>';
                        },
                        content: function (item) {
                            let a = document.createElement('a');
                            a.href =  "{{ env('APP_URL') }}/" + "{{ session('org_name') }}" +'/user/view/' + users[users_for_mention.indexOf(item)].id;
                            a.innerText = '@' + item;
                            return a;
                        },    
                    },
                    toolbar: [
                        ['font', ['bold', 'underline']],
                        ['para', ['ul', 'ol']],
                        ['insert', ['link']],
                        ['fm-button', ['fm']],
                    ],
                    callbacks: {
                        onChange: function(contents, $editable) {
                            @this.set('comment', contents);
                        }
                    }
                }
            );

            $("#editor").summernote({
                height: 200,
                hint: {
                        mentions: users_for_mention,
                        match: /\B@(\w*)$/,
                        search: function (keyword, callback) {
                            callback($.grep(this.mentions, function (item) {
                                return item.toLowerCase().indexOf(keyword.toLowerCase()) == 0;
                            }));
                        },
                        template : function (item) {
                            return '<span class="mention_user" data-id=" ' + users[users_for_mention.indexOf(item)].id + ' ">' + item + '</span>';
                        },
                        content: function (item) {
                            let a = document.createElement('a');
                            a.href =  "{{ env('APP_URL') }}/" + "{{ session('org_name') }}" +'/user/view/' + users[users_for_mention.indexOf(item)].id;
                            a.innerText = '@' + item;
                            return a;
                        },    
                    },
                    toolbar: [
                        ['font', ['bold', 'underline']],
                        ['para', ['ul', 'ol']],
                        ['insert', ['link']],
                    ],
                callbacks: {
                        onChange: function(contents, $editable) {
                            @this.set('description', contents);
                        }
                }
            });

            $('.task-users').select2({
                placeholder: 'Select User',
                allowClear: true
            });

            $(".task-users").on('change', function (e) {
                var data = $(".task-users").val();
                @this.set('selectedUsers', data);
            });

            $('.task-notify-users').select2({
                placeholder: 'Select Notify User',
                allowClear: true
            });

            $(".task-notify-users").on('change', function (e) {
                var data = $(".task-notify-users").val();
                @this.set('selectedNotifiers', data);
            });

            $('.task-projects').select2({
                placeholder: 'Select Project',
                allowClear: true
            });

            $(".task-projects").on('change', function (e) {
                var data = $(".task-projects").val();
                @this.set('project_id', data);
            });

            flatpickr(".due_date", {
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr, instance) {
                    $('.due_date').text(dateStr);
                    @this.set('due_date', dateStr);
                    
                },
                inline: true,
                defaultDate: "{{ $due_date }}"
            });

            $(".add-new-attachments").on('click', function(){
                $("#file").click();
            });

            document.addEventListener('comment-added', event => {
                $('#comment_box').summernote('code', '');
            });

            $(".cus_dropdown-icon").click(function(){
                $(this).parent().toggleClass("active");
            });

            $(".select_ul li").click(function(){
                var currentele = $(this).html();
                $(".default_option li").html(currentele);
                $(this).parents(".select_wrap").removeClass("active");
            });
        });
    </script>
@endscript
