<div>
    <div wire:ignore class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header taskPane-dashbaord-head py-3 px-4">
            <div class="btn-list">
                <select class="form-select" name="" id="" wire:model="status">
                    <option value="" disabled selected>Select Status</option>
                    <option value="pending">Assigned</option>
                    <option value="in_progress">Accepted</option>
                    <option value="in_review">In-Review</option>
                    <option value="completed">Completed</option>
                </select> 
            </div>
            <div class="btn-list">
                <select class="form-select" wire:model="visibility">
                    <option selected disabled>Visibility</option>
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                </select> 
            </div> 
            <div class="taskPane-dashbaord-head-right">
                <span class="btn-batch btn-batch-danger d-none read-only-btn">Read Only</span>
                <button type="button" class="btn-icon add-attachments" data-bs-toggle="modal" data-bs-target="#attached-file-modal"><i class='bx bx-paperclip' style="transform: rotate(60deg);"></i></button>
                {{-- <button type="button" class="btn-icon task-sharable-link d-none"><i class='bx bx-share-alt ' ></i></button> --}}
                <button type="button" class="btn-icon view-task-btn d-none" wire:click="viewFullscree"><i class='bx bx-fullscreen'></i></button>
                {{-- <button type="button" wire:click="deleteTask" class="btn-icon delete-task-btn d-none"><i class='bx bx-trash'></i></button> --}}
                <a href="javascript:" wire:click="deleteTask" wire:confirm="Are you sure you want to delete?" class="btn-icon delete-task-btn d-none"><i class='bx bx-trash'></i></a>
                <button type="button" class="btn-icon" data-bs-dismiss="offcanvas" aria-label="Close"><i class='bx bx-x'></i></button>
            </div>
        </div>
        <div class="offcanvas-body scrollbar">
            <form class="taskPane px-4 py-3" method="POST" wire:submit="saveTask" enctype="multipart/form-data">
                <div class="taskPane-head">
                    <div class="taskPane-heading">
                        <div class="taskPane-heading-label"><i class='bx bx-notepad text-primary'></i> Task Heading</div>
                        <input required class="form-control form-control-typeStyle AddTask_title" wire:model="name" type="text" placeholder="Write a task name">
                        {{ $name }}
                    </div>
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="taskPane-body">
                    <div class="d-none assigner-tab">
                        <div class="taskPane-item d-flex flex-wrap mb-3">
                            <div class="taskPane-item-left"><div class="taskPane-item-label">Assignor </div></div>
                            <div class="taskPane-item-right">
                                <span class="select2-selection__choice__display assigner-name" id=""></span>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="taskPane-item d-flex flex-wrap mb-3" >
                        <div class="taskPane-item-left"><div class="taskPane-item-label">Assigned to</div></div>
                        <div class="taskPane-item-right" wire:ignore>
                            <select  class="task-users" multiple>
                                @foreach ($users as $user)
                                    <option data-image="{{ $user->image }}"  value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach 
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="taskPane-item d-flex flex-wrap mb-3">
                        <div class="taskPane-item-left"><div class="taskPane-item-label">Notify to</div></div>
                        <div class="taskPane-item-right">
                            <select name="" id="" class="task-notify-users" multiple>
                                @foreach ($users as $user)
                                    <option data-image="{{ $user->image }}"  value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach 
                            </select>
                        </div>
                    </div> 
                    <hr>
                    @if(!request()->routeIs('project.tasks'))
                    <div class="taskPane-item d-flex flex-wrap mb-3">
                        <div class="taskPane-item-left"><div class="taskPane-item-label">Project</div></div>
                        <div class="taskPane-item-right"> 
                            <select required  class="task-projects">
                                <option value="" selected disabled>Select Project</option>
                                @foreach ($projects as $p)
                                    @if($project_id && $project && $project->id == $p->id)
                                        <option value="{{ $p->id }}" data-client="{{$p->client->name}}" selected>{{ $p->name }}</option>
                                    @else
                                        @if($loop->first)
                                            <option value="{{ $p->id }}" data-client="{{$p->client->name}}">{{ $p->name }}</option>
                                        @else
                                            <option value="{{ $p->id }}" data-client="{{$p->client->name}}">{{ $p->name }}</option>
                                        @endif
                                    @endif
                                @endforeach
                            </select> 
                        </div>
                    </div>
                    @endif
                    <hr>
                    <div class="taskPane-item d-flex flex-wrap mb-3">
                        <div class="taskPane-item-left"><div class="taskPane-item-label">Due Date</div></div>
                        <div class="taskPane-item-right">
                            <a href="javascript:">
                                <div class="icon_rounded"><i class='bx bx-calendar' ></i></div>
                                <span class="btn_link task-due-date">No Due Date</span>
                            </a>
                        </div>
                    </div>
                    <hr>
                    <div class="taskPane-item mb-3">
                        <div class="taskPane-item-label mb-2">Description</div>
                        <div>
                            <textarea wire:model="description" id="editor" cols="30" rows="4" placeholder="Type Description"></textarea>
                        </div>
                    </div>
                    <div class="taskPane-item d-flex flex-wrap mb-3">
                        <div class="taskPane-item-left"><div class="taskPane-item-label">Email Notification</div></div>
                        <div class="taskPane-item-right">
                           <input class="email_notification" type="checkbox" wire:model="email_notification">
                        </div>
                    </div> 
                    <hr>
                    {{-- Add voice note --}}
                    <h5 class="cmnt_item_title mb-4"><span><i class='bx bxs-microphone text-primary'></i> Voice Notes</span><span class="text-sm"><i class='bx bxs-microphone text-secondary'></i> <span class="voice-notes-count">0</span> Voice Notes</span></h5>
                    <div class="taskPane-item d-flex flex-wrap mb-3 voice-notes-list d-none"></div>
                    <div class="my-4">
                        <div class="voice_note-wrap d-flex align-items-center justify-content-between gap-4">
                            <div class="font-500 text-secondary">Add Voice Note <span class="text-sm">| <span class="voie-note-duration">30</span>s</span></div>
                            <div class="voice_note voice_note-icon">
                                <span id="recordButton" class="text-secondary d-flex" title="Start"><i class='bx bxs-microphone' ></i></span>
                                <span id="stopButton" class="d-none d-flex" title="Stop"><i class='bx bx-stop-circle bx-tada bx-flip-vertical' ></i></span>
                            </div>
                        </div>
                        <div class="voice_note-preview"></div>
                    </div>
                    <hr>
                    <div class="taskPane-item mb-2">
                        <div class="taskPane-item-label mb-2"><a href="#"><i class="bx bx-paperclip text-secondary add-attachments" style="transform: rotate(60deg);"></i></a> <span class="task-attachment-count"> {{ count($attachments) }}</span> Attachments</div>
                        <input class="d-none attachments" type="file" wire:model="attachments" multiple id="formFile" />
                        <div class="attached_files d-none">
                            <a href="javascript:;" class="btn btn-sm btn-border-secondary" data-bs-toggle="modal" data-bs-target="#attachmentModal">View Attachments</a>
                        </div>
                        <div class="newly-attached d-none">
                            <ul class="newly-attached-list">

                            </ul>
                        </div>
                    </div>
                </div>
            </form>
            <div class="cmnt_sec p-4 d-none">
                <!-- Activity -->
                <div class="cmnt_item">
                    <h5 class="cmnt_item_title"><span><i class='bx bx-line-chart text-primary'></i> Activity</span><span class="text-sm"><i class='bx bx-comment-dots text-secondary'></i> <span class="total-comments">15</span> Comments</span></h5>
                    <div class="cmnt_item-tabs">
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-internal-tab" data-bs-toggle="tab" data-bs-target="#nav-internal" type="button" role="tab" aria-controls="nav-internal" aria-selected="true">Internal Comment <span class="text-sm ms-2"><i class='bx bx-comment-dots text-secondary'></i> <span class="task-comments-count">07</span></span></button>
                            <button class="nav-link" id="nav-client-tab" data-bs-toggle="tab" data-bs-target="#nav-client" type="button" role="tab" aria-controls="nav-client" aria-selected="false">Client Feedback <span class="text-sm ms-2"><i class='bx bx-comment-dots text-secondary'></i> <span class="client-comment-count">08</span></span></button>
                        </div>
                    </div>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-internal" role="tabpanel" aria-labelledby="nav-internal-tab" tabindex="0">
                            <div class="comment-rows mt-4"></div>
                            <div class="custComment">
                                <div class="custComment-wrap">
                                    <div class="custComment-editor p-0" wire:ignore>
                                        <textarea wire:model="comment" name="" id="comment_box" class="form-control mb-3" cols="30" rows="5"></textarea>
                                    </div>
                                    <button wire:click="saveComment('internal')" class="btn btn-sm btn-border-primary mt-3"><i class='bx bx-send'></i> Comment</button>
                                </div>
                            </div>
                            
                        </div>
                        <div class="tab-pane fade" id="nav-client" role="tabpanel" aria-labelledby="nav-client-tab" tabindex="0">
                            <div class="client-comment-rows mt-4"></div>
                            <div class="custComment">
                                <div class="custComment-wrap">
                                    <div class="custComment-editor p-0" wire:ignore>
                                        <textarea name="" id="internal_comment_box" class="form-control mb-3" cols="30" rows="5"></textarea>
                                    </div>
                                    <button wire:click="saveComment('client')" class="btn btn-sm btn-border-primary mt-3"><i class='bx bx-send'></i> Comment</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="offcanvas-footer">
            <div class="taskPane-footer-wrap py-3 px-4">
                <button type="button" wire:loading.attr="disabled" wire:click="saveTask" class="btn-lg btn-border btn-border-primary save-task-button w-100"><i class='bx bx-check'></i> 
                    <span wire:loading wire:target="attachments"> 
                        <i class='bx bx-loader-alt bx-spin'></i>
                    </span>
                    Save Task
                </button>
            </div>
        </div>
    </div>
    {{-- Attachments modal --}}
    <div class="modal fade" id="attachmentModal" tabindex="-1" role="dialog" aria-labelledby="attachmentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="attachmentModalLabel">Attachments</h5>
                    <div>
                        <i class="bx bx-data text-primary"></i> 
                        <span class="task-attachment-count">
                            @if($task)
                                {{ count($task->attachments) }}
                            @else
                                0
                            @endif
                        </span> 
                        Attachments
                    </div>
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

@assets
<script src="{{env('APP_URL')}}/js/recorder.js"></script>
@endassets

@push('scripts')
    <script>


        
    URL = window.URL || window.webkitURL;

    var gumStream; 
    var rec; 
    var input; 
    var AudioContext = window.AudioContext || window.webkitAudioContext;
    var audioContext;

    var recordButton = document.getElementById("recordButton");
    var stopButton = document.getElementById("stopButton");

    recordButton.addEventListener("click", startRecording);
    stopButton.addEventListener("click", stopRecording);

    function startRecording() {
        $("#stopButton").removeClass('d-none');
        $("#recordButton").addClass('d-none');
        // play start.mp3 sound
        var audio = new Audio("{{ env('APP_URL') }}/audio/start.mp3");
        audio.play();

        var constraints = { audio: true, video: false };
        recordButton.disabled = true;
        navigator.mediaDevices
            .getUserMedia(constraints)
            .then(function (stream) {
                console.log(
                    "getUserMedia() success, stream created, initializing Recorder.js ..."
                );
                audioContext = new AudioContext();
                gumStream = stream;
                input = audioContext.createMediaStreamSource(stream);
                rec = new Recorder(input, { numChannels: 1 });
                rec.record();

                voiceNoteTimer()

            })
            .catch(function (err) {
                recordButton.disabled = false;
            });
    }



    function stopRecording() {
        $("#stopButton").addClass('d-none');
        $("#recordButton").removeClass('d-none');
        recordButton.disabled = false;
        rec.stop();
        gumStream.getAudioTracks()[0].stop();
        rec.exportWAV(createDownloadLink);

        // reset timer
        $(".voie-note-duration").html(30);
        // stop timer
        clearInterval(intrvalId);

        // play stop.mp3 sound
        var audio = new Audio("{{ env('APP_URL') }}/audio/start.mp3");
        audio.play();

    }

    function createDownloadLink(blob) {
        // save the audio file to server
        var reader = new FileReader();
        reader.readAsDataURL(blob);
        reader.onloadend = function () {
            var base64data = reader.result;
            @this.set('voice_note', base64data);
            dispatchEvent(new CustomEvent('voice-note-added', { detail: base64data }));
        };
        $(".voice_note-preview").html('');
        var url = URL.createObjectURL(blob);
        var au = document.createElement("audio");
        au.controls = true;
        au.src = url;
        $(".voice_note-preview").append(au);
        rec.clear();
        
    }

    
    // let intrvalId = null;

    if(typeof intrvalId === 'undefined'){
        var intrvalId = null;
    }

    function voiceNoteTimer(){
        let time = 30;
        let timer = setInterval(() => {
            time--;
            $(".voie-note-duration").html(time);
            if(time == 0){
                clearInterval(timer);
                stopButton.click();
                $(".voie-note-duration").html(30);
            }
        }, 1000);

        intrvalId = timer;

    }

        // clear all fields when offcanvas is closed or dismissed 

        $(".bx-window-close").click(function(){
            location.reload();
        });

        function resetTaskCanvas(){

            @this.set('task',null);
            @this.resetForm();
            $(".taskPane").trigger('reset');
            $(".task-users").val(null).trigger('change');
            $(".task-notify-users").val(null).trigger('change');
            $(".task-projects").val(null).trigger('change');
            $(".task-due-date").text('No Due Date');
            $('#editor').summernote('code', '');
            $(".attachments").val(null);
            @this.set('attachments', []);
            $(".task-attachment-count").html(0);
            $(".comment-rows").html('');
            $(".client-comment-rows").html('');
            $(".task-comments-count").html(0);
            $(".client-comment-count").html(0);
            $(".voice-notes-count").html(0);
            $(".cmnt_sec").addClass('d-none');
            $(".delete-task-btn").addClass('d-none');
            $(".view-task-btn").addClass('d-none');
            $(".task-sharable-link").addClass('d-none');
            $(".attached_files").addClass('d-none');
            $('.taskPane-heading-label').html('Add Task');
            $('.save-task-button').html('Save Task');
            $(".email_notification").prop('checked', false);
            $(".voice_note-preview").html('');
            $(".voice-notes-list").addClass('d-none');
            $(".task-attachment-count").html(0);
            $(".assigner-tab").addClass('d-none');
            $(".newly-attached").addClass('d-none');

        }

        $('#offcanvasRight').on('hidden.bs.offcanvas', function () {
            @this.set('task',null);
            resetTaskCanvas();
        });

        document.addEventListener('task-added', event => {
            $('#offcanvasRight').offcanvas('hide');
            @this.set('task',null);
            resetTaskCanvas();
        });

    


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


            function initPlugins(){
                $("#editor").summernote({
                    height: 150,
                    hint: {
                        mentions: users_for_mention,
                        match: /\B@(\w*)$/,
                        search: function (keyword, callback) {
                            callback($.grep(this.mentions, function (item) {
                                return item.toLowerCase().indexOf(keyword.toLowerCase()) == 0;
                            }));
                        },
                        template : function (item) {
                            return '<span class="mention_users_list" data-id=" ' + users[users_for_mention.indexOf(item)].id + ' ">' + item + '</span> ';
                        },
                        content: function (item) {
                            let a = document.createElement('a');
                            a.href =  "{{ env('APP_URL') }}/" + "{{ session('org_name') }}" +'/user/view/' + users[users_for_mention.indexOf(item)].id;
                            a.innerText = '@' + item;
                            a.dataset.id = users[users_for_mention.indexOf(item)].id;
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
                        },
                        
                    }
                });

                $("#comment_box").summernote({
                    height: 150,
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
                            a.dataset.id = users[users_for_mention.indexOf(item)].id;
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
                            @this.set('comment', contents);
                        }
                    }
                });

                $("#internal_comment_box").summernote({
                    height: 150,
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
                            a.dataset.id = users[users_for_mention.indexOf(item)].id;
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
                            @this.set('comment', contents);
                        }
                    }
                });  
            }

            $('.add-attachments').on('click', function(){
                $('.attachments').click();
            });

            $(".attachments").change(function(){
                $(".task-attachment-count").html(this.files.length);
                $(".newly-attached").removeClass('d-none');
                $(".newly-attached-list").html('');
                for (let i = 0; i < this.files.length; i++) {
                    const file = this.files[i];
                    let file_name = file.name;
                    let file_size = file.size;
                    let file_size_in_kb = file_size / 1024;
                    let file_size_in_mb = file_size_in_kb / 1024;
                    let size = '';
                    if(file_size_in_mb > 1){
                        size = file_size_in_mb.toFixed(2) + ' MB';
                    }else if(file_size_in_kb > 1){
                        size = file_size_in_kb.toFixed(2) + ' KB';
                    }else{
                        size = file_size + ' Bytes';
                    }
                    let preview_url = URL.createObjectURL(file);
                    let preview_thumb = '';

                    if(file.type.includes('image')){
                        preview_thumb = `<img src="${preview_url}" class="" alt="" height="100" width="100px">`;
                    }else if(file.type.includes('video')){
                        preview_thumb = `<video src="${preview_url}" class="" alt=""></video>`;
                    }else{
                        preview_thumb = `<img height="100" width="100px" src="{{ env('APP_URL') }}/assets/images/icons/file-icon.png" class="" alt="">`;
                    }

                    let file_html = `<li class="newly-attached-item">
                        <span class="newly-attached-item-name">${file_name}</span>
                        <span class="newly-attached-item-size">${size}</span>
                        <span class="newly-attached-item-remove" data-id="${i}"><i class='bx bx-x'></i></span>
                        <div class="newly-attached-item-preview">
                            ${preview_thumb}
                        </div>
                    </li>`;
                    $(".newly-attached-list").append(file_html);
                }

            });

            $(document).on('click', '.newly-attached-item-remove', function(){
                let id = $(this).data('id');
                let files = $(".attachments")[0].files;
                let dt = new DataTransfer();
                for (let i = 0; i < files.length; i++) {
                    if (i != id) {
                        dt.items.add(files[i]);
                    }
                }
                $(".attachments")[0].files = dt.files;
                $(".task-attachment-count").html(dt.files.length);
                $(this).parent().remove();
                let attachments = [];
                for (let i = 0; i < dt.files.length; i++) {
                    attachments.push(files[i]);
                }
            });

            

            setTimeout(() => {
                initPlugins();
            }, 1000);

            // Select2
            $('.task-users').select2({
                placeholder: "Select User",
                allowClear: true,
            });

            $(".task-projects").select2({
                dropdownParent: $('#offcanvasRight'),
                placeholder: "Select Project",
                allowClear: true,
                templateResult: taskProjectsFormatter,
                templateSelection: taskProjectsFormatter
            });

            function taskProjectsFormatter (project) {
                if (!project.id) {
                    return project.text;
                }

                let client = project.element.getAttribute('data-client');

                var $project = $(
                    '<div class="task-project-w_c"> <span>'+ project.text +'</span><span class="task-project-w"> ' + client + '</span></div>'
                );
                return $project;
            };

            $(".task-projects").on('change', function (e) {
                var data = $(".task-projects").val();
                @this.set('project_id', data);
            });

            $(".task-users").on('change', function (e) {
                var data = $(".task-users").val();
                @this.set('task_users', data);
            });

            $('.task-notify-users').select2({
                placeholder: "Select User",
                allowClear: true,
            });

            $(".task-notify-users").on('change', function (e) {
                var data = $(".task-notify-users").val();
                @this.set('task_notifiers', data);
            });

            

            flatpickr(".task-due-date", {
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr, instance) {
                    $('.task-due-date').text(dateStr);
                    @this.set('due_date', dateStr);
                }
            });

            function createInitials(name){
                let words = name.split(' ');
                if(words.length == 1){
                    return name.substring(0, 2);
                }
                return words[0][0] + words[1][0];
            }

            document.addEventListener('read-only', event => {
                let form_items = $(".taskPane").find('input, select, textarea');
                if(event.detail[0] == true){
                    toggleFormItems(true);
                }else{
                    toggleFormItems(false);
                }
            });


            function toggleFormItems(prop){
                let form_items = $(".taskPane").find('input, select, textarea');

                form_items.each(function(){
                    $(this).attr('disabled',prop);
                });
                if(prop == true){
                    $(".read-only-btn").removeClass('d-none');
                }else{
                    $(".read-only-btn").addClass('d-none');
                }

                $(".save-task-button").attr('disabled',prop);
            }

            
            document.addEventListener('edit-task', event => {

                $(".comment-rows").html('');
                $(".client-comment-rows").html('');

                
                let task = event.detail[0];
                let role = "{{ auth()->user()->hasRole('Admin') }}";
                let user_id = {{ auth()->user()->id }}

                // task creator and admin can only delete the task

                if(role || task.assigned_by.id == user_id){
                    $(".delete-task-btn").removeClass('d-none');
                }else{
                    $(".delete-task-btn").addClass('d-none');
                }

                $(".cmnt_sec").removeClass('d-none');
                $(".view-task-btn").removeClass('d-none');
                $(".task-sharable-link").removeClass('d-none');
                $(".attached_files").removeClass('d-none');
                $(".assigner-tab").removeClass('d-none');
                $(".assigner-name").html(task.assigned_by.name);
                $('.taskPane-heading-label').html('Edit Task');
                $('.save-task-button').html('Update Task');
                let task_users = event.detail[0].users;
                let task_notifiers = event.detail[0].notifiers;
                let task_users_ids = [];
                let task_notifiers_ids = [];

                task_users.forEach(user => {
                    task_users_ids.push(user.id);
                });

                task_notifiers.forEach(user => {
                    task_notifiers_ids.push(user.id);
                });

                $("#editor").summernote('code', task.description);

                $('.task-users').val(task_users_ids).trigger('change');

                $('.task-notify-users').val(task_notifiers_ids).trigger('change');

                $('.task-projects').val(event.detail[0].project_id).trigger('change');

                if(event.detail[0].email_notification){
                    $(".email_notification").prop('checked', true);
                }else{
                    $(".email_notification").prop('checked', false);
                }

                if(event.detail[0].due_date){
                    $('.task-due-date').text(event.detail[0].due_date);
                }else{
                    $('.task-due-date').text('No Due Date');
                }
                $(".task-attachment-count").html(event.detail[0].attachments.length);
                
                let internal_comments_count = 0;

                event.detail[0].comments.forEach(comment => {
                    if(comment.type == 'internal'){
                        internal_comments_count++;
                    }
                });

                // render voice notes

                let voice_notes = event.detail[0].voice_notes

                $(".voice-notes-list").html('');
                $(".voice-notes-count").html(voice_notes.length);
                voice_notes.forEach(voice_note => {
                    let date = new Date(voice_note.created_at);
                    let options = { day: 'numeric', month: 'long', year: 'numeric' };
                    let formattedDate = date.toLocaleDateString('en-GB', options);

                    let voice_note_html = `<div class="voice-notes-item">
                        <div class="voice-notes-item-user d-flex gap-2 mb-2">
                            <div class="voice-notes-item-user-img">
                                ${
                                    voice_note.user.image ? `<img class="avatar avatar-sm rounded-circle" src="{{ env('APP_URL') }}/storage/${voice_note.user.image}">` 
                                    : 
                                    `<span class="avatar avatar-sm avatar-yellow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="${voice_note.user.name}">${createInitials(voice_note.user.name)}</span>`
                                }
                            </div>
                            <div class="voice-notes-item-user-name-wrap">
                                <div class="voice-notes-item-user-name text-sm">${voice_note.user.name}</div>
                                <div class="voice-notes-item-date text-xs">${formattedDate}</div>
                            </div>
                        </div>
                        <div class="voice-notes-item-audio">
                            <audio controls>
                                <source src="{{ env('APP_URL') }}/${voice_note.path}" type="audio/wav">
                            </audio>
                        </div>
                    </div>`;

                    $(".voice-notes-list").append(voice_note_html);
                });

                $(".voice-notes-list").removeClass('d-none');



                $(".task-comments-count").html(internal_comments_count);

                $(".client-comment-count").html(event.detail[0].comments.length - internal_comments_count);

                $(".total-comments").html(event.detail[0].comments.length);

                event.detail[0].comments.forEach(comment => {
                    let comment_type = comment.type;
                    const date = new Date(comment.created_at);
                    const options = { day: 'numeric', month: 'long', year: 'numeric' };
                    const formattedDate = date.toLocaleDateString('en-GB', options);

                    let comment_html = `<div class="cmnt_item_row">
                        <div class="cmnt_item_user">
                            <div class="cmnt_item_user_img">
                                ${
                                    comment.user.image ? `<img class="rounded-circle" src="{{ env('APP_URL') }}/storage/${comment.user.image}">` 
                                    : 
                                    `<span class="avatar avatar-sm avatar-yellow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="${comment.user.name}">
                                        ${createInitials(comment.user.name)}
                                    </span>`
                                }
                            </div>
                            <div class="cmnt_item_user_name-wrap">
                                <div class="cmnt_item_user_name">
                                    ${comment.user.name} 
                                    <div class="cmnt_item_actions">
                                        ${
                                            comment.user.id == {{ auth()->user()->id }} ? `<span wire:click="deleteComment(${comment.id})"  class="delete-comment text-link text-link-danger text-cursor" data-id="${comment.id}"><i class='bx bx-trash'></i></span>` : ''
                                        }
                                        
                                        ${
                                            comment.user.id == {{ auth()->user()->id }} ? `<span class="edit-comment text-link text-link-success text-cursor" data-id="${comment.id}"><i class='bx bx-pencil'></i></span>` : ''
                                        }
                                    </div>
                                </div>
                                <div class="cmnt_item_date">${formattedDate}</div>
                                <div class="edit-comment-section-${comment.id} d-none mt-3"></div>
                                <div class="cmnt_item_user_text comment-${comment.id}">${comment.comment}</div>
                            </div>
                        </div>
                    </div>`;

                    if(comment_type == 'internal'){
                        $('.comment-rows').append(comment_html);
                    }else{
                        $('.client-comment-rows').append(comment_html);
                    }
                });


                $('#offcanvasRight').offcanvas('show');

            })

            // edit-comment

            $(document).on('click', '.edit-comment', function(){
                let id = $(this).data('id');
                let comment = $(".comment-"+id).html();
                $('.comment-'+id).html('');
                let edit_comment_html = `<div class="edit-comment-section-${id}">
                    <textarea class="form-control" id="edit_comment_box_${id}" cols="30" rows="5">${comment}</textarea>
                    <button class="btn btn-sm btn-border-primary update-comment-btn" data-id="${id}">Update</button>
                </div>`;
                $(".edit-comment-section-"+id).html(edit_comment_html);
                $(".edit-comment-section-"+id).removeClass('d-none');
                $(".edit-comment-section-"+id).find('textarea').summernote({
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
                            a.dataset.id = users[users_for_mention.indexOf(item)].id;
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
                            @this.set('comment', contents);
                        }
                    }
                });
            });

            $(document).on('click', '.update-comment-btn', function(){
                let id = $(this).data('id');
                let comment = $(".edit-comment-section-"+id).find('textarea').val();
                // destroy the summernote editor instance
                $(".edit-comment-section-"+id).find('textarea').summernote('destroy');
                $(".edit-comment-section-"+id).addClass('d-none');
                $(".edit-comment-section-"+id).html('');
                $(".comment-"+id).html(comment);
                @this.updateComment(id, comment);
                dispatchEvent(new CustomEvent('comment-updated', { detail: comment }));

            });

            // file-attached
            document.addEventListener('file-attached', event => {
                $(".task-attachment-count").html(event.detail.length);
                $('#attached-file-modal').modal('hide');
            });

            // comment-added 
            if (!window.hasAddedCommentListener) {

                document.addEventListener('comment-added', event => {

                    const date = new Date(event.detail[0].created_at);
                    const options = { day: 'numeric', month: 'long', year: 'numeric' };
                    const formattedDate = date.toLocaleDateString('en-GB', options);


                    let comment_html = `<div class="cmnt_item_row">
                        <div class="cmnt_item_user">
                            <div class="cmnt_item_user_img">
                                ${
                                    event.detail[0].user.image ? `<img class="rounded-circle" src="{{ env('APP_URL') }}/storage/${event.detail[0].user.image}">` : `<span class="avatar avatar-sm avatar-yellow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="${event.detail[0].user.name}">${createInitials(event.detail[0].user.name)}</span>`
                                }
                            </div>
                            <div class="cmnt_item_user_name-wrap">
                                <div class="cmnt_item_user_name">
                                    ${event.detail[0].user.name}
                                    <div class="cmnt_item_actions">
                                        ${
                                            event.detail[0].user.id == {{ auth()->user()->id }} ? `<span wire:click="deleteComment(${event.detail[0].id})"  class="delete-comment text-link text-link-danger text-cursor" data-id="${event.detail[0].id}"><i class='bx bx-trash'></i></span>` : ''
                                        }
                                        ${
                                            event.detail[0].user.id == {{ auth()->user()->id }} ? `<span class="edit-comment text-link text-link-success text-cursor" data-id="${event.detail[0].id}"><i class='bx bx-pencil'></i></span>` : ''
                                        }
                                    </div>
                                </div>
                                 <div class="edit-comment-section-${event.detail[0].id} d-none"></div>
                                <div class="cmnt_item_date">${formattedDate}</div>
                                <div class="cmnt_item_user_text comment-${event.detail[0].id}">${event.detail[0].comment}</div>
                            </div>
                        </div>
                    </div>`;
                    

                    if(event.detail[0].type == 'internal'){
                        $(".task-comments-count").html(parseInt($(".task-comments-count").html()) + 1);
                        $('.comment-rows').append(comment_html);
                        $('#comment_box').summernote('code', '');
                    }else{
                        $(".client-comment-count").html(parseInt($(".client-comment-count").html()) + 1);
                        $('.client-comment-rows').append(comment_html);
                        $('#internal_comment_box').summernote('code', '');
                    }
    
                    $(".total-comments").html(parseInt($(".total-comments").html()) + 1);


                });
                // Mark that the listener is added
                window.hasAddedCommentListener = true;
            }
            

    </script>
@endpush
