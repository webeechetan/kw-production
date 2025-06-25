<div>
    <div wire:ignore class="modal fade" id="add-user-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center justify-content-between gap-20">
                    <h3 class="modal-title"><span class="btn-icon btn-icon-primary me-1"><i class='bx bx-user' ></i></span> <span class="modal-title">Add User</span></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" class="invite-user-form">
                        <div class="modal-form-body">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="title-sm title-with_icon"><i class='bx bx-mail-send text-secondary' ></i> Invite User</h5>
                                </div>
                                <div class="col-lg mb-4 mb-lg-0">
                                    <div class="single-add">
                                        <div class="single-add-wrap">
                                            <input wire:model="email" type="text" class="form-control" placeholder="Email Here..."> 
                                        </div>
                                        <button  wire:click="inviteUser" type="submit" class="btn-border btn-border-secondary"><i class='bx bx-send' ></i> Send</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="divider-or"><span></span> OR <span></span></div>
                    <form wire:submit="addUser" method="POST" enctype="multipart/form-data">
                        <div class="modal-form-body">
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <label for="">User Name<sup class="text-primary">*</sup></label>
                                </div>
                                <div class="col-md-8 mb-4">
                                    <input wire:model="name" type="text" class="form-style" placeholder="User Name Here..." required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <label for="">Designation<sup class="text-primary">*</sup></label>
                                </div>
                                <div class="col-md-8 mb-4">
                                    <input wire:model="designation" required type="text" class="form-style" placeholder="Designation Here...">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <label for="">Email<sup class="text-primary">*</sup></label>
                                </div>
                                <div class="col-md-8 mb-4">
                                    <input wire:model="email"  type="email" class="form-style" placeholder="Email Here..." required>
                                </div>
                            </div>
                            <div class="row password-col">
                                <div class="col-md-4 mb-4">
                                    <label for="">Password<sup class="text-primary">*</sup></label>
                                </div>
                                <div class="col-md-8 mb-4">
                                    <input wire:model="password"  type="text" class="form-style password" placeholder="Password Here..." minlength="6" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <label for="">Team<sup class="text-primary">*</sup></label>
                                </div>
                                <div class="col-md-8 mb-4">
                                    <select class="form-style" wire:model="main_team_id" required>
                                        <option value="">Select Team</option>
                                        @foreach ($teams as $team)
                                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <label for="">Role<sup class="text-primary">*</sup></label>
                                </div>
                                <div class="col-md-8 mb-4">
                                    <select class="roles form-style" >
                                        <option value="">Select Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>                            
                        </div>
                        <div class="modal-form-btm">
                            <div class="row">
                                <div class="col-md-6 ms-auto text-end">
                                    <button type="submit" class="btn btn-primary form-btn">Add User</button>
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

            setTimeout(() => {
                initPlugins();
            }, 2000);

            $(".roles").on('change', function(){
                @this.set('role', $(this).val());
            });

            document.addEventListener('user-added', event => {
                $('#add-user-modal').modal('hide');
            })
        });

        function initPlugins(){
            $(".roles").select2({
                placeholder: "Select Role",
                allowClear: true
            });
        }

        document.addEventListener('edit-user', event => {
            $('#add-user-modal').modal('show');
            $(".modal-title").text("Edit User");
            $(".form-btn").text("Update User");
            $(".invite-user-form").hide();
            $(".divider-or").hide();
            $(".password").attr('required', false);
            $(".password-col").hide();
            $(".roles").val(@this.role).trigger('change');
        });

        document.addEventListener('user-updated', event => {
            $('#add-user-modal').modal('hide');
            location.reload();
        });
    </script>
@endscript
