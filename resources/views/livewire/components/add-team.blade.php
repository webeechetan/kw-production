<div wire:ignore class="modal fade" id="add-team-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center justify-content-between gap-20">
                <h3 class="modal-title"><span class="btn-icon btn-icon-primary me-1"><i class='bx bx-user' ></i></span> <span class="modal-text">Add Team</span></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form wire:submit="addTeam" method="POST" enctype="multipart/form-data">
                    <div class="modal-form-body">
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label for="">Team Name<sup class="text-primary">*</sup></label>
                            </div>
                            <div class="col-md-8 mb-4">
                                <input wire:model="name" type="text" class="form-style" placeholder="Team Name Here..." required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label for="">Upload Logo</label>
                            </div>
                            <div class="col-md-8 mb-4">
                                <x-image-input model="image" />
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-4 mb-lg-0">
                                <label for="">Assign Manager</label>
                            </div>
                            <div class="col-md-8">
                                <select wire:model="team_manager" id="" class="form-style team_manager">
                                    <option value="">Select Manager</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>                            
                    </div>
                    <div class="modal-form-btm">
                        <div class="row">
                            <div class="col-md-6 ms-auto text-end">
                                <button type="submit" class="btn btn-primary modal-btn">Add Team</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        document.addEventListener('team-added', event => {
            $("#add-team-modal").modal('hide');
            $('.image_upload_input').val('');
            $('.image-preview-section').addClass('d-none');
            $('.remove-image-sesction').addClass('d-none');
        });

        document.addEventListener('team-updated', event => {
            $("#add-team-modal").modal('hide');
            $('.image_upload_input').val('');
            $('.image-preview-section').addClass('d-none');
            $('.remove-image-sesction').addClass('d-none');
        });

       

        document.addEventListener('editTeamEvent', event => {
            $("#add-team-modal").modal('show');
            $(".modal-btn").html('Update Team');
            $(".modal-text").html('Edit Team');
            if (event.detail[0].image) {
                $(".image-preview-section").removeClass('d-none');
                $(".remove-image-sesction").removeClass('d-none');
                $('.image-preview-section').html('<img src="{{ asset('storage') }}/'+event.detail[0].image+'" alt="project image" class="img-fluid">');
            }
        });

    </script>
@endscript