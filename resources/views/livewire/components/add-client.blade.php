<div>
    <div wire:ignore class="modal fade" id="add-client-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center justify-content-between gap-20">
                    <h3 class="modal-title"><span class="btn-icon btn-icon-primary me-1"><i class='bx bx-user'></i></span> <span class="client-form-text">Add Client</span></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit="addClient" method="POST" enctype="multipart/form-data">
                        <div class="modal-form-body">
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <label for="">Company Name<sup class="text-primary">*</sup></label>
                                </div>
                                <div class="col-md-8 mb-4">
                                    <input wire:model="client_name" type="text" class="form-style" placeholder="Company Name" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <label for="">Brand Name</label>
                                </div>
                                <div class="col-md-8 mb-4">
                                    <input wire:model="brand_name" type="text" class="form-style brand_name" placeholder="Brand Name">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" wire:model="use_brand_name" value="1" id="use_brand_name">
                                        <label class="form-check-label" for="defaultCheck1">Use This as Title</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <label for="">Point Of Contact</label>
                                </div>
                                <div class="col-md-8 mb-4">
                                    <input wire:model="point_of_contact" type="text" class="form-style" placeholder="Point Of Contact">
                                </div>
                                <span class="text-danger">@error('point_of_contact') {{ $message }} @enderror</span>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <label for="">Onboard Date</label>
                                </div>
                                <div class="col-md-8 mb-4">
                                    <div class="btn-list btn-list-full-2 justify-content-between gap-10">
                                        <a class="btn btn-50 btn-sm btn-border-secondary client-onboard-date"><i class='bx bx-calendar-alt' ></i> Select Date</a>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">Upload Logo</label>
                                </div>
                                <div class="col-md-8">
                                    <x-image-input model="client_image" />
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-12">
                                    <label for="" class="mb-2">Add Description</label>
                                    <textarea type="text" wire:model="client_description" class="form-style" placeholder="Add Description" rows="2" cols="30"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-form-btm">
                            <div class="row">
                                <div class="col-md-6 ms-auto text-end">
                                    <button wire:loading.attr="disabled" wire:target="client_image" type="submit" class="btn btn-primary client-form-btn">
                                        <span wire:loading wire:target="client_image"> 
                                            <i class='bx bx-loader-alt bx-spin'></i>
                                        </span>
                                        Add Client
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

            $("#use_brand_name").on('change', function(){
                if($(this).is(':checked')){
                    $('.brand_name').attr('required', true);
                }else{
                    $('.brand_name').attr('required', false);
                }
            });

            flatpickr('.client-onboard-date', {
                enableTime: true,
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr, instance) {
                    $(".client-onboard-date").html(dateStr);
                    @this.set('client_onboard_date', dateStr);
                },
            });

            document.addEventListener('client-added', event => {
                $('#add-client-modal').modal('hide');
                $('.client-form-text').html('Add Client');
                $('.client-form-btn').html('Add Client');
                // $('.old-image').addClass('d-none');
                $('.image-preview-section').addClass('d-none');
                $('.remove-image-sesction').addClass('d-none');
                $('.old-image-src').attr('src', '');
                $('.client-onboard-date').html('Select Date'); 
            })

            document.addEventListener('edit-client', event => {
                $('#add-client-modal').modal('show');
                if(event.detail[0].onboard_date){
                    $('.client-onboard-date').html(event.detail[0].onboard_date);
                }else{
                    $('.client-onboard-date').html('Select Date');
                }
                $('.client-onboard-date').flatpickr({
                    enableTime: true,
                    dateFormat: "Y-m-d",
                    defaultDate: event.detail[0].onboard_date,
                    onChange: function(selectedDates, dateStr, instance) {
                        $(".client-onboard-date").html(dateStr);
                        @this.set('client_onboard_date', dateStr);
                    },
                });
                if(event.detail[0].use_brand_name == 1){
                    $('#use_brand_name').prop('checked', true);
                }else{
                    $('#use_brand_name').prop('checked', false);
                }

                if (event.detail[0].image) {
                    $(".image-preview-section").removeClass('d-none');
                    $('.image-preview-section').html('<img src="{{ asset('storage') }}/'+event.detail[0].image+'" alt="project image" class="img-fluid">');
                    $('.remove-image-sesction').removeClass('d-none');
                }

                $('.client-form-text').html('Edit Client');
                $('.client-form-btn').html('Update');
            })

        });
    </script>
@endscript