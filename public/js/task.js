

        
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

        console.log("recordButton clicked");
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

                console.log("Recording started");
            })
            .catch(function (err) {
                recordButton.disabled = false;
            });
    }



    function stopRecording() {
        console.log("stopButton clicked");
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
        console.log("Recording stopped");
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
            @this.set('task', null);
        }

        $('#offcanvasRight').on('hidden.bs.offcanvas', function () {
            resetTaskCanvas();
        });

        document.addEventListener('task-added', event => {
            $('#offcanvasRight').offcanvas('hide');
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
                            return '<span class="mention_users_list" data-id=" ' + users[users_for_mention.indexOf(item)].id + ' ">' + item + '</span>';
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
                        }
                    }
                });

                $("#comment_box").summernote({
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

                $("#internal_comment_box").summernote({
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
                    let file_html = `<li class="newly-attached-item">
                        <span class="newly-attached-item-name">${file_name}</span>
                        <span class="newly-attached-item-size">${size}</span>
                        <span class="newly-attached-item-remove" data-id="${i}"><i class='bx bx-x'></i></span>

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
            });

            function taskProjectsFormatter (project) {
                if (!project.id) {
                    return project.text;
                }

                let client = project.element.getAttribute('data-client');

                var $project = $(
                    '<div> <span class="task-project-client">'+ client +'</span> ' + project.text + '</div>'
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
                let form_items = 
                console.log(event.detail);
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
                console.log(task)
                $(".cmnt_sec").removeClass('d-none');
                $(".delete-task-btn").removeClass('d-none');
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
                                    ${
                                        comment.user.id == {{ auth()->user()->id }} ? `<span wire:click="deleteComment(${comment.id})"  class="delete-comment" data-id="${comment.id}"><i class='bx bx-trash'></i></span>` : ''
                                    }
                                    
                                    ${
                                        comment.user.id == {{ auth()->user()->id }} ? `<span class="edit-comment" data-id="${comment.id}"><i class='bx bx-pencil'></i></span>` : ''
                                    }
                                </div>
                                <div class="edit-comment-section-${comment.id} d-none"></div>
                                <div class="cmnt_item_date">${formattedDate}</div>
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
                console.log('edit comment');
                let id = $(this).data('id');
                let comment = $(this).closest('.cmnt_item_user_name-wrap').find('.cmnt_item_user_text').html();
                let edit_comment_html = `<div class="edit-comment-section-${id}">
                    <textarea class="form-control" id="edit_comment_box_${id}" cols="30" rows="5">${comment}</textarea>
                    <button class="btn btn-sm btn-border-primary update-comment-btn" data-id="${id}" wire:click="updateComment(${id})">Update</button>
                </div>`;
                console.log(edit_comment_html);
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
                                    ${
                                        event.detail[0].user.id == {{ auth()->user()->id }} ? `<span wire:click="deleteComment(${event.detail[0].id})"  class="delete-comment" data-id="${event.detail[0].id}"><i class='bx bx-trash'></i></span>` : ''
                                    }
                                    ${
                                        event.detail[0].user.id == {{ auth()->user()->id }} ? `<span class="edit-comment" data-id="${event.detail[0].id}"><i class='bx bx-pencil'></i></span>` : ''
                                    }
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
            