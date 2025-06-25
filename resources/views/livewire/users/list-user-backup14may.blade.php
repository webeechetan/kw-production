<div class="container">
    <!-- Dashboard Header -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class='bx bx-line-chart'></i>{{ Auth::user()->organization ? Auth::user()->organization->name : 'No organization' }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">All Users</li>
        </ol>
    </nav>
    <div class="dashboard-head mb-4">
        <div class="row align-items-center">
            <div class="col d-flex align-items-center gap-3">
                <h3 class="main-body-header-title mb-0">All Users</h3>
                <span class="text-light">|</span>
                <a data-bs-toggle="modal" data-bs-target="#add-user-modal" href="javascript:void(0);" class="btn-border btn-border-sm btn-border-primary"><i class="bx bx-plus"></i> Add User</a>
                <!-- <a wire:navigate href="{{ route('user.add') }}" href="javascript:void(0);" class="btn-border btn-border-sm btn-border-primary"><i class="bx bx-plus"></i> Add User</a> -->
            </div>
            <div class="text-end col">
                <div class="main-body-header-right">
                    <form class="search-box" wire:submit="search" action="">
                        <input wire:model="query" type="text" class="form-control" placeholder="Search Users...">
                        <button type="submit" class="search-box-icon"><i class='bx bx-search me-1'></i> Search</button>
                    </form>
                    <div class="main-body-header-filters">
                        <div class="cus_dropdown">
                            <div class="cus_dropdown-icon btn-border btn-border-secondary"><i class='bx bx-filter-alt' ></i> Filter</div>
                            <div class="cus_dropdown-body cus_dropdown-body-widh_l">
                                <div class="cus_dropdown-body-wrap">
                                    <div class="filterSort">
                                        <h5 class="filterSort-header"><i class='bx bx-sort-down text-primary' ></i> Sort By</h5>
                                        <ul class="filterSort_btn_group list-none">
                                            <li class="filterSort_item">
                                                <a wire:navigate href="{{route('user.index',['sort'=>'newest', 'filter'=>$filter])}}" class="btn-batch @if($sort == 'newest') active @endif">Newest</a>
                                            </li>
                                            <li class="filterSort_item"><a href="#" class="btn-batch"><i class='bx bx-down-arrow-alt' ></i> A To Z</a></li>
                                            <li class="filterSort_item"><a href="#" class="btn-batch"><i class='bx bx-up-arrow-alt' ></i> Z To A</a></li>
                                        </ul>
                                        <hr>
                                        <h5 class="filterSort-header"><i class='bx bx-briefcase text-primary' ></i> Filter By Status</h5>
                                        <ul class="filterSort_btn_group list-none">
                                            <li class="filterSort_item"><a href="#" class="btn-batch">Active</a></li>
                                            <li class="filterSort_item"><a href="#" class="btn-batch">Archived</a></li>
                                        </ul>
                                        <hr>
                                        <h5 class="filterSort-header"><i class='bx bx-briefcase text-primary' ></i> Filter By Clients</h5>
                                        <select class="form-control"name="" id="">
                                            <option value="Rakesh">Rakesh</option>
                                            <option value="Rajiv">Rajiv</option>
                                        </select>
                                        <hr>
                                        <h5 class="filterSort-header"><i class='bx bx-objects-horizontal-left text-primary'></i> Filter By Projects</h5>
                                        <select class="form-control"name="" id="">
                                            <option value="Rakesh">Tech Team</option>
                                            <option value="Rajiv">Copy Team</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">        
        @foreach($users as $user)
            <div class="col-md-4 mb-4">
                <div class="card_style card_style-user h-100">
                    <a href="{{ route('user.profile',$user->id) }}" class="card_style-open"><i class='bx bx-chevron-right'></i></a>
                    <div class="card_style-user-head">
                        <div class="card_style-user-profile-img">
                            @if($user->image)
                                <img src="{{ env('APP_URL') }}/storage/{{ $user->image }}" alt="{{ $user->name }}">
                            @else
                                <span class="avatar avatar-{{$user->color}}">{{ $user->initials }}</span>
                            @endif
                        </div>
                        <div class="card_style-user-profile-content mt-2">
                            <h4><a wire:navigate href="{{ route('user.profile',$user->id) }}">{{ $user->name }}</a></h4>
                            <div class="d-flex align-items-center justify-content-center"><i class='bx bx-envelope me-1 text-secondary' ></i> {{ $user->email }}</div>
                            <div class="card_style-user-head-position mt-2"><i class='bx bx-user'></i> Web Developer | Tech Team</div>
                            @if($user->teams->count() > 0)
                                <div class="card_style-user-head-team"><span class="btn-batch btn-batch-warning">{{ $user->teams->count() }} Teams</span></div>
                            @endif
                        </div>
                    </div>
                    <div class="card_style-user-body mt-3">
                        <div class="card_style-options d-none">
                            <div class="card_style-options-head"><span><i class='bx bx-layer text-secondary' ></i></span> 30 Projects <span class="text-dark-grey ms-1">|</span> 5 Clients</div>
                            <div class="card_style-options-list d-flex">
                                <div class="text-primary">Active <span class="btn-batch-bg btn-batch-bg-primary">10 Projects</span></div>
                                <div class="text-success ms-4">Completed <span class="btn-batch-bg btn-batch-bg-success">5 Projects</span></div>
                            </div>
                        </div>
                        <div class="card_style-tasks text-center">
                            <div class="card_style-tasks-title"><span><i class='bx bx-objects-horizontal-left' ></i></span> {{ $user->tasks->count() }} Tasks</div>
                            <div class="card_style-tasks-list justify-content-center mt-2">
                                <div class="card_style-tasks-item card_style-tasks-item-pending"><span><i class='bx bx-objects-horizontal-center' ></i></span>
                                {{
                                    $user->tasks->where(function($query) {
                                        $query->where('status', 'pending')->orWhere('status', 'in_progress')->orWhere('status', 'in_review');
                                    })->count()
                                }} Active</div>
                                <div class="card_style-tasks-item card_style-tasks-item-overdue"><span><i class='bx bx-objects-horizontal-center' ></i></span>{{ $user->tasks->where('due_date', '<', now())->where('status','!=','completed')->count() }} Overdue</div>
                                <div class="card_style-tasks-item card_style-tasks-item-done"><span><i class='bx bx-objects-horizontal-center' ></i></span>{{ $user->tasks->where('status', 'completed')->count() }}  Completed</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach 

        <!-- Pagination -->
        {{ $users->links(data: ['scrollTo' => false]) }}
    </div>

    <!-- User Modal Component -->

    <livewire:components.add-user  @saved="$refresh" />
    
</div>
