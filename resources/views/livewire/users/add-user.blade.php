<div class="container">
    <div class="row">
        <div class="col-md-6 offset-3">
            <div class="card">
                <h5 class="card-header">Add User</h5>
                {{-- invite user with email --}}
                <div class="card-body">
                    <div class="row">
                        <form wire:submit.prevent="inviteUser" method="POST">
                            <div class="col-md-12">
                                <label for="email">Email</label>
                                <input type="text" wire:model="email" name="email" class="form-control" placeholder="Enter Email">
                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">Invite User</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <form wire:submit="store" method="POST" enctype="multipart/form-data">
                            <div class="col-md-12">
                                <label for="name">User Name</label>
                                <input type="text" wire:model="name" name="name" class="form-control" placeholder="Enter Client Name">
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-12 mt-3">
                                <label for="email">Email</label>
                                <input type="text" wire:model="email" name="email" class="form-control" placeholder="Enter Email">
                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-12 mt-3">
                                <label for="password">Password</label>
                                <input type="text" wire:model="password" name="password" class="form-control" placeholder="Enter Password">
                                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-12 mt-3">
                                <label for="profile">Profile</label>
                                <input type="file" wire:model="image" class="form-control">
                            </div>

                            <div class="col-md-12 mt-3" wire:ignore>
                                <label for="teams">Teams</label>
                                <select class="form-control teams" multiple>
                                    <option value="">Select Project</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">Add User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $('.teams').select2();

        $('.teams').on('change', function(e){
            var teams = $('.teams');
            var selected_teams = teams.val();
            @this.set('team_ids', selected_teams);
        });
    </script>
@endpush



