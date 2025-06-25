<div class="column-box p-3" style="margin-bottom:150px;">
    <div class="files-head column-head d-flex flex-wrap align-items-center">
        <div>
            <h5 class="column-title mb-0">Files & Folders</h5>
        </div>
        <div class="files-options ms-auto">
            <div class="text-light">
                {{--<span class="text-success"><i class='bx bx-data' ></i></span> 
                {{ $main_directory_directories_count + $main_directory_files_count + $main_directory_links_count }} Attachments 
                <span class="px-2">|</span><span class="text-primary"><i class='bx bx-file-blank' ></i></span>
                {{$main_directory_files_count}} <span class="px-2">|</span> 
                <span class="text-warning"><i class='bx bx-folder' ></i></span> 
                {{ $main_directory_directories_count }} 
                <span class="px-2">|</span>
                <span class="text-secondary"><i class='bx bx-link-alt' ></i></span> 
                {{ $main_directory_links_count }} <span class="px-2">|</span>--}}
                <span class="text-primary"><i class='bx bx-data' ></i></span> {{$main_directory_size}} MB Used / 100MB
            </div>
        </div>
    </div>
    <div class="files-body">
        <!-- wire:loading -->
        <!-- <div class="spinner-border text-primary " role="status" >
            <span class="sr-only"></span>
        </div> -->

        <!-- Loader -->
        <div class="loader_cus loader_column w-100" wire:loading.delay>
            <div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar" style="width: 25%"></div>
            </div>
        </div> 

        <!-- Add Files & Folders -->
        <div class="row mt-3">
            <div class="col-md-4">
                <div class="files-folder files-folder-add_new files-folder-primary" data-bs-toggle="modal" data-bs-target="#add-new-file">
                    <span class="files-folder-icon"><i class='bx bx-file-blank' ></i></span>
                    <div class="files-folder-title ms-1">Add File</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="files-folder files-folder-add_new files-folder-warning" data-bs-toggle="modal" data-bs-target="#add-new-directory">
                    <span class="files-folder-icon"><i class='bx bx-folder' ></i></span>
                    <div class="files-folder-title ms-1">Add Folder</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="files-folder files-folder-add_new files-folder-secondary" data-bs-toggle="modal" data-bs-target="#add-new-link">
                    <span class="files-folder-icon"><i class='bx bx-link-alt' ></i></span>
                    <div class="files-folder-title ms-1">Add Link</div>
                </div>
            </div>
        </div>

        <nav aria-label="breadcrumb" class="mt-5">
            <ul class="breadcrumb mb-0">
                @foreach($path_array as $p )
                    @php
                        $path_for_folder_open = '';
                        foreach($path_array as $path_key => $path_value){
                            if($path_key <= $loop->index){
                                $path_for_folder_open .= $path_value.'/';
                            }
                        }

                    @endphp
                    <li class="breadcrumb-item  @if($loop->last) active @endif " @if(!$loop->first) wire:click="openFolder('{{$path_for_folder_open}}')" @endif><a>{{ $p }}</a></li>
                @endforeach
            </ul>
        </nav>

        <!-- File & Folder Wrap -->
        <div class="files-items-wrap my-3">
            <div class="column-head column-head-light d-flex flex-wrap align-items-center">
                <div>
                    <h5 class="title-sm mb-2">Directory Attachments</h5>
                    <div><i class='bx bx-data text-primary' ></i> {{ $directories_count + $files_count + $links_count }} Attachments <span class="px-2">|</span> {{$used_storage_size_in_mb}} MB Used / 100MB</div>
                </div>
                <form class="search-box search-box-float-style ms-auto" >
                    <span class="search-box-float-icon"><i class="bx bx-search"></i></span>
                    <input id="search" type="text" class="form-control" placeholder="Search">
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <!-- Filters -->
                <div class="btn-list mb-3">
                    <a href="javascipt:" class="btn-border btn-border-success filter-files" data-filter="all"><i class='bx bx-data'></i> All {{ $directories_count + $files_count + $links_count }}</a>
                    <a href="javascipt:" class="btn-border btn-border-primary filter-files" data-filter="files"><i class='bx bx-file-blank' ></i> Files {{ $files_count  }}</a>
                    <a href="javascipt:" class="btn-border btn-border-warning filter-files" data-filter="directory"><i class='bx bx-folder me-1' ></i> Folders {{ $directories_count }}</a>
                    <a href="javascipt:" class="btn-border btn-border-secondary filter-files" data-filter="links"><i class='bx bx-link-alt' ></i> Links {{ $links_count }}</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="btn-list align-items-center justify-content-end gap-10">
                    <a wire:click="downloadSelected" class="btn btn-sm btn-border-success 
                    @if(empty($selected_files)) disabled @endif
                    " data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Download Here">
                        <span><i class='bx bx-download' ></i> Download</span>
                    </a>
                    <a wire:click="deleteSelected" class="btn btn-sm btn-border-danger @if(empty($selected_files) && empty($selected_directories) && empty($selected_links)) disabled @endif "><span><i class='bx bx-trash' ></i></span> Delete</a>
                </div>
            </div>
        </div>

        <div class="files-list">
            {{-- Folders --}}
            @foreach($directories as $directory_name => $directory_data)
                <div class="files-folder files-item select_directory @if(in_array($directory_data['directory_path'], $selected_directories)) selected @endif" data-directory="{{$directory_data['directory_path']}}" wire:dblclick="openFolder('{{$directory_data['directory_path']}}')">
                    <span class="files-folder-icon"><i class='bx bx-folder'></i></span>
                    <div class="files-folder-title files-item-content-title">{{ $directory_name }}</div>
                    <div class="text-light"><span class="text-primary"><i class='bx bx-folder' ></i></span> {{ $directory_data['directories_count'] }} <span class="px-2">|</span> <span class="text-secondary"><i class='bx bx-file-blank' ></i></span> {{ $directory_data['files_count'] }}</div>
                </div>
            @endforeach
            {{-- Files --}}
            @foreach($files as $file_name => $file_data)
                <div class="files-item select_file open-file @if(in_array($file_data['file_path'], $selected_files)) selected @endif" data-file="{{ $file_data['file_path'] }}">
                    <div class="files-size">{{ $file_data['size'] }} KB</div>
                    
                    <div class="files-item-icon">
                        @if($this->createThumbnailFromFileName($file_name)) 
                            <span><img src="{{ $this->createThumbnailFromFileName($file_name) }}" alt=""></span>
                        @else
                            <span><i class='bx bx-file'></i></span>
                        @endif
                    </div>
                    <div class="files-item-content">
                        <div class="files-item-content-title mb-2">{{ Str::limit($file_name, 15) }}</div>
                    </div>
                    {{-- <div class="text-sm">{{ $file_data['last_modified'] }}</div> --}}
                </div>
            @endforeach
            {{-- Links --}}
            @foreach($links as $l)
                <div class="files-item select_link @if(in_array($l->id, $selected_links)) selected @endif" data-link="{{ $l->id }}">
                    <div class="files-item-icon">
                        <span><i class='bx bx-link-alt'></i></span>
                    </div>
                    <div class="files-item-content">
                        <a href="{{ $l->link }}" target="_blank" class="files-item-content-title">
                            @if($l->link_alias)
                                {{ $l->link_alias }}                            
                            @else
                                {{ Str::limit($l->link, 20) }}
                            @endif
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        @php
            $selected_count = count($selected_files) + count($selected_directories) + count($selected_links);
        @endphp
        @if($selected_count > 0)
            <div class="mt-3">Selected Count: {{ $selected_count }}</div>
        @endif
    </div>

    <!-- Add New File modal -->
    <div wire:ignore class="modal fade" id="add-new-file" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center justify-content-between gap-20">
                    <h3 class="modal-title"><span class="btn-icon btn-icon-primary me-1"><i class='bx bx-file'></i></span> Add New File</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit="addNewFile" enctype="multipart/form-data">
                        <div class="modal-form-body">
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <label for="">Add File</label>
                                </div>
                                <div class="col-md-8 mb-4">
                                    <div class="form-file_upload form-file_upload-logo">
                                        <input class="add-file-input" type="file" id="formFile" wire:model="new_file" >
                                        <div class="form-file_upload-box">
                                            <div class="form-file_upload-box-icon"><i class='bx bx-image'></i></div>
                                            <div class="form-file_upload-box-text">Add File</div>
                                        </div>
                                        <div class="form-file_upload-valText">Allowed *.jpeg, *.jpg, *.png, *.gif max size of 50 Mb</div>
                                        @error('new_file') <span class="text-danger">{{ $message }}</span> @enderror
                                        <div class="add-file-input-responce"></div>
                                    </div>
                                    <div class="mt-4 d-none upload-progress">
                                        <div class="progress w-100" role="progressbar" aria-label="Project Progress" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar progress-success" ><span class="progress-bar-text">0%</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <label for="">File Name</label>
                                </div>
                                <div class="col-md-8 mb-4">
                                    <input wire:model="new_file_name" type="text" class="form-style" placeholder="File Name Here...">
                                </div>
                            </div>
                        </div>

                        
                        
                        <div class="modal-form-btm">
                            <div class="row">
                                
                                <div class="col-md-6 ms-auto text-end mt-4">
                                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="new_file">
                                        <span>
                                            <span wire:loading wire:target="new_file"> 
                                                <i class='bx bx-loader-alt bx-spin'></i>
                                            </span>
                                            Save File 
                                        </span>
                                    </button>
                                    
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add New Folder -->
    <div wire:ignore class="modal fade" id="add-new-directory" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center justify-content-between gap-20">
                    <h3 class="modal-title">
                        <span class="btn-icon btn-icon-primary me-1"><i class='bx bx-file'></i></span> 
                        Add New Folder
                    </h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit="addNewDirectory" enctype="multipart/form-data">
                        <div class="modal-form-body">
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <label for="">Folder Name</label>
                                </div>
                                <div class="col-md-8 mb-4">
                                    <input wire:model="new_directory_name" type="text" class="form-style" placeholder="Folder Name Here...">
                                </div>
                            </div>
                        </div>
                        <div class="modal-form-btm">
                            <div class="row">
                                <div class="col-md-6 ms-auto text-end">
                                    <button type="submit" class="btn btn-primary">Create Folder</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add New Link -->
    <div wire:ignore class="modal fade" id="add-new-link" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center justify-content-between gap-20">
                    <h3 class="modal-title">
                        <span class="btn-icon btn-icon-primary me-1"><i class='bx bx-link'></i></span> 
                        Save Link
                    </h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit="saveLink" enctype="multipart/form-data">
                        <div class="modal-form-body">
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <label for="">Link</label>
                                </div>
                                <div class="col-md-8 mb-4">
                                    <input wire:model="link_name" type="text" class="form-style" placeholder="Paste Link Here...">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <label for="">Link Alias</label>
                                </div>
                                <div class="col-md-8 mb-4">
                                    <input wire:model="link_alias" type="text" class="form-style" placeholder="Link Alias Name Here...">
                                </div>
                            </div>
                        </div>
                        <div class="modal-form-btm">
                            <div class="row">
                                <div class="col-md-6 ms-auto text-end">
                                    <button type="submit" class="btn btn-primary">Save Link</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
   document.addEventListener('fileAdded', event => {
        $('#add-new-file').modal('hide');
        $(".add-file-input-responce").html('');
    })

    document.addEventListener('directoryAdded', event => {
        $('#add-new-directory').modal('hide');
    })

    document.addEventListener('linkAdded', event => {
        $('#add-new-link').modal('hide');
    })

    $(document).ready(function(){

        // search functionality for files folders and links

        $("#search").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".files-item").filter(function() {
                // highlight the matched text with yellow color
                $(this).find(".files-item-content-title").each(function(){
                    let title = $(this).text();
                    let title_lower = title.toLowerCase();
                    let title_lower_index = title_lower.indexOf(value);
                    if(title_lower_index > -1){
                        let title_lower_length = value.length;
                        let title_lower_substring = title.substring(title_lower_index, title_lower_index + title_lower_length);
                        let title_lower_replaced = title.replace(title_lower_substring, '<span class="text-secondary">'+title_lower_substring+'</span>');
                        $(this).html(title_lower_replaced);
                    }
                });

                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);

            });
        });

        // filter-files

        $(document).on("click", ".filter-files", function(){
            $(".filter-files").removeClass("active");

            $(this).addClass("active");

            let filter = $(this).data("filter");
            if(filter == 'all'){
                $(".select_file").fadeIn();
                $(".select_directory").fadeIn();
                $(".select_link").fadeIn();
                return;
            }
            if(filter == 'files'){
                $(".select_file").show();
                $(".select_directory").hide();
                $(".select_link").hide();
                return;
            }

            if(filter == 'directory'){
                $(".select_file").hide();
                $(".select_directory").show();
                $(".select_link").hide();
                return;
            }

            if(filter == 'links'){
                $(".select_file").hide();
                $(".select_directory").hide();
                $(".select_link").show();
                return;
            }
        });

        // when double click on .open-file class file should open in new tab

        $(document).on("dblclick", ".open-file", function(){
            let file_path = $(this).data("file");
            file_path = "{{ url('storage') }}/"+file_path;
            window.open(file_path, '_blank');
        });

        setInterval(() => {
            let progress = $(".progress-bar").width();
            if(progress >= 100){
                $(".progress").width(0);
            }
            $(".progress").width(progress + 100);

        }, 100);

        $(document).on("click", ".select_file", function(event){
            let file_path = $(this).data("file");
            console.log(file_path);
            if(event.ctrlKey){
                $(this).toggleClass("selected");
                @this.selectFile(file_path,'multipe');
                return;
            }
            if($(this).hasClass("selected")){
                $(this).removeClass("selected");
                @this.selectFile(file_path,'');
                return;
            }

            @this.selectFile(file_path,'single');
            $(".select_file").removeClass("selected");
            $(this).addClass("selected");

        });

        let clickedOnce = false;

        $(document).on("click", ".select_directory", function(event){
            let directory_path = $(this).data("directory");
            if (event.ctrlKey) {
                $(this).toggleClass("selected");
                @this.selectDirectory(directory_path, 'multipe');
                return;
            }

            if($(this).hasClass("selected")){
                $(this).removeClass("selected");
                @this.selectDirectory(directory_path, '');
                return;
            }

            @this.selectDirectory(directory_path, 'single');
            $(".select_directory").removeClass("selected");
            $(this).addClass("selected");
        });

        $(document).on("dblclick", ".select_directory", function(event){
            // If double-clicked, return false to prevent further action
            console.log('double clicked');
            event.preventDefault();
            return false;
        });

        $(".add-file-input").change(function(){
            let file_name = $(this).val().split('\\').pop();
            // if file is image then show preview else show file name

            if(file_name.split('.').pop() == 'jpg' || file_name.split('.').pop() == 'jpeg' || file_name.split('.').pop() == 'png' || file_name.split('.').pop() == 'gif'){
                $(".add-file-input-responce").html('<img src="'+URL.createObjectURL(this.files[0])+'" alt="">');
                return;
            }

            $(".add-file-input-responce").html(file_name);
        });


        $(document).on("click", ".select_link", function(event){
            let link_path = $(this).data("link");
            if(event.ctrlKey){
                $(this).toggleClass("selected");
                @this.selectLink(link_path,'multipe');
                return;
            }
            if($(this).hasClass("selected")){
                $(this).removeClass("selected");
                @this.selectLink(link_path,'');
                return;
            }

            @this.selectLink(link_path,'single');
            $(".select_link").removeClass("selected");
            $(this).addClass("selected");
        });
    });
</script>
@endscript
