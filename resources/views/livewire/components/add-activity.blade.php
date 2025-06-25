<div>
    <div wire:ignore class="modal fade" id="add-activity-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center justify-content-between gap-20">
                    <h3 class="modal-title"><span class="btn-icon btn-icon-primary me-1"><i class='bx bx-user'></i></span> <span class="activity-form-text">Add Activity</span></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit="addActivity" method="POST" enctype="multipart/form-data">
                        <div class="modal-form-body">
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <label for="">Activity Name<sup class="text-primary">*</sup></label>
                                </div>
                                <div class="col-md-8 mb-4">
                                    <input wire:model="name" type="text" class="form-style" placeholder="Activity Name" required>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-4">
                                    <label for="">Upload Logo</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-file_upload form-file_upload-logo">
                                        <input class="image_upload_input" type="file" id="formFile" wire:model="activity_image" accept="image/jpeg, image/jpg, image/png, image/gif">
                                        <div class="form-file_upload-box">
                                            <div class="form-file_upload-box-icon"><i class='bx bx-image'></i></div>
                                            <div class="form-file_upload-box-text">Upload Image</div>
                                        </div>
                                        <div class="form-file_upload-valText">Allowed *.jpeg, *.jpg, *.png, *.gif max size of 3 Mb</div>
                                    </div>
                                    <div class="mt-4 d-none upload-progress">
                                        <div class="progress w-100" role="progressbar" aria-label="Project Progress" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar progress-success" ><span class="progress-bar-text">0%</span></div>
                                        </div>
                                    </div>

                                    <div class="image-preview-section mt-3 d-none">

                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row align-items-center">
                                <div class="col mb-4 mb-md-0">
                                    <a href="javascript:;" class="btn w-100 btn-sm btn-border-secondary activity_start_date "  ><i class="bx bx-calendar-alt"></i> Start Date</a>
                                </div>
                                <div class="col-auto text-center font-500 mb-4 mb-md-0">To</div>
                                <div class="col">
                                    <a href="javascript:;" class="btn w-100 btn-sm btn-border-danger activity_end_date "  ><i class="bx bx-calendar-alt"></i> End Date</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-12">
                                    <label for="" class="mb-2">Add Description</label>
                                    <textarea type="text" wire:model="description" class="form-style" placeholder="Add Description" rows="2" cols="30"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-form-btm">
                            <div class="row">
                                <div class="col-md-6 ms-auto text-end">
                                    <button wire:loading.attr="disabled" wire:target="activity_image" type="submit" class="btn btn-primary activity-form-btn">
                                        <span wire:loading wire:target="activity_image"> 
                                            <i class='bx bx-loader-alt bx-spin'></i>
                                        </span>
                                        Add Activity
                                    </button>
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
        $(document).ready(function() {


            flatpickr('.activity_start_date', {
                enableTime: true,
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr, instance) {
                    $(".activity_start_date").html(dateStr);
                    @this.set('start_date', dateStr);
                },
            });

            flatpickr('.activity_end_date', {
                enableTime: true,
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr, instance) {
                    $(".activity_end_date").html(dateStr);
                    @this.set('end_date', dateStr);
                },
            });

            document.addEventListener('activity-added', event => {
                $('#add-activity-modal').modal('hide');
                $('.activity-form-text').html('Add Client');
                $('.activity-form-btn').html('Add Client');
                $('.old-image').addClass('d-none');
                $('.old-image-src').attr('src', '');
                $('.image-preview-section').addClass('d-none');
                $('.image-preview-section').html('');
                $('.activity_end_date').html('End Date');
                $('.activity_start_date').html('Start Date');
            })

            document.addEventListener('edit-activity', event => {

                $('#add-activity-modal').modal('show');
                if(event.detail[0].start_date){
                    $('.activity_start_date').html(event.detail[0].start_date);
                }else{
                    $('.activity_start_date').html('Start Date');
                }
                if(event.detail[0].due_date){
                    $('.activity_end_date').html(event.detail[0].due_date);
                }else{
                    $('.activity_end_date').html('End Date');
                }

                $('.activity_start_date').flatpickr({
                    enableTime: true,
                    dateFormat: "Y-m-d",
                    defaultDate: event.detail[0].start_date,
                    onChange: function(selectedDates, dateStr, instance) {
                        $(".activity_start_date").html(dateStr);
                        @this.set('start_date', dateStr);
                    },
                });

                $('.activity_end_date').flatpickr({
                    enableTime: true,
                    dateFormat: "Y-m-d",
                    defaultDate: event.detail[0].due_date,
                    onChange: function(selectedDates, dateStr, instance) {
                        $(".activity_end_date").html(dateStr);
                        @this.set('end_date', dateStr);
                    },
                });

                if (event.detail[0].image) {
                    $(".image-preview-section").removeClass('d-none');
                    $('.image-preview-section').html('<img src="{{ asset('storage') }}/'+event.detail[0].image+'" alt="project image" class="img-fluid">');
                }

                $('.activity-form-text').html('Edit Activity');
                $('.activity-form-btn').html('Update');

            })

        });
    </script>
@endscript