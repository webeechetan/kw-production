<div class="container">
    <!-- Dashboard Header -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard') }}"><i class='bx bx-line-chart'></i>{{ Auth::user()->organization ? Auth::user()->organization->name : 'No organization' }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">All Roles</li>
        </ol>
    </nav>

    <div class="dashboard-head mb-4">
        <div class="row align-items-center">
            <div class="col d-flex align-items-center gap-3">
                <h3 class="main-body-header-title mb-0">All Roles</h3>
                <span class="text-light">|</span>
                @can('Create Role')
                    <a data-step="1" data-intro='Create roles' data-position='right' data-bs-toggle="modal" data-bs-target="#add-role-modal" href="javascript:void(0);" class="btn-border btn-border-sm btn-border-primary"><i class="bx bx-plus"></i> Create Role</a>
                @endcan
            </div>
            <div class="col">
                <div class="main-body-header-right">
                    <form class="search-box" wire:submit="search" action="" data-step="2" data-intro='Search Role' data-position='bottom'>
                        <input wire:model="query" type="text" class="form-control" placeholder="Search Role">
                        <button type="submit" class="search-box-icon"><i class='bx bx-search me-1'></i> Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        @if($roles->isNotEmpty())
            @foreach($roles as $role)
                <div class="col-md-3 mt-3">
                    <div class=" card_style card_style-roles h-100">
                        <a wire:navigate="" href="{{ route('role.profile',$role->id) }}" class="card_style-open"><i class='bx bx-chevron-right'></i></a>
                        <div class="card_style-roles-head">
                            <p class="text-muted mb-2">Total 
                                @php
                                    $users = \App\Models\User::role($role->name)->count()
                                @endphp
                                {{$users}}  {{$users > 1 ? 'users' : 'user'}}
                            </p>
                            <h4>
                                <a wire:navigate="" href="{{ route('role.profile',$role->id) }}">{{$role->name}}</a>
                            </h4>
                        </div>
                        <div class="card_style-roles-permission">
                            <div class="card_style-roles-title mt-2"><span><i class='bx bx-universal-access'></i></span> {{ $role->permissions->count() }}  {{ $role->permissions->count() >1 ? 'Permissions' : 'Permission'}}</div>
                            <div class="mt-2">
                                <a class="edit-role" wire:click="emitEditRoleEvent({{$role->id}})"> Edit Role</a>
                                </div>
                        </div>              
                    </div> 
                </div>
            @endforeach
        @else
            <div class="col-md-12">
                <h4 class="text text-danger">No Roles found 
                    @if($query) 
                    with {{$query}}
                @endif
                </h4>
            </div>
        @endif
    </div>
    <livewire:components.add-role  @saved="$refresh" />
</div>

@script
    <script>
        window.addEventListener('role-added', event => {
            toastr.success(event.detail.message, 'Success');
            location.reload();
        });

        window.addEventListener('edit-role', event => {
            console.log('edit');
            $('.role-form-text').text('Edit Role');
            $('.role-form-btn').text('Update Role');
            $('#add-role-modal').modal('show');
        });

        window.addEventListener('role-updated', event => {
            toastr.success(event.detail.message, 'Success');
            location.reload();
        });
    </script>
@endscript


@php
    $tour = session()->get('tour');
@endphp

{{-- @if($tour['role_tour']) --}}
@if(isset($tour) && $tour != 'null' && isset($tour['role_tour']))
    @assets
        <link href="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/minified/introjs.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/intro.min.js"></script>
    @endassets
@endif

{{-- @if($tour['role_tour']) --}}
@if(isset($tour) && $tour != 'null' && isset($tour['role_tour']))
    @script
            <script>
                introJs()
                .setOptions({
                showProgress: true,
                })
                .onbeforeexit(function () {
                    location.href = "{{ route('role.index') }}?tour=close-role-tour";
                })
                .start();
            </script>
    @endscript
@endif
