<div class="container">
    <!-- Dashboard Header -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a wire:navigate href="{{ route('client.index') }}"><i class='bx bx-line-chart'></i>{{ ucfirst(Auth::user()->organization->name) }}</a></li>
            <li class="breadcrumb-item"><a wire:navigate href="{{ route('client.index') }}">All Clients</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $client->name }}</li>
        </ol>
    </nav>
    
    <livewire:clients.components.client-tabs :client="$client" @saved="$refresh" />

    <!-- Dashboard Body -->
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="column-box states_style-progress">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="states_style-icon"><i class='bx bx-layer' ></i></div>                        
                    </div>
                    <div class="col">
                        <div class="row align-items-center g-2">
                            <div class="col-auto">
                                <h5 class="title-md mb-0">{{ $client->projects->count() }}</h5>
                            </div>
                            <div class="col-auto">
                                <span class="font-400 text-grey">|</span>
                            </div>
                            <div class="col-auto">
                                @if($client->projects->count() > 0)
                                    <div class="states_style-text">{{ pluralOrSingular($client->projects->count(),'Project') }}</div>
                                @else
                                    <div class="states_style-text text-light">No Projects Assigned</div>
                                @endif  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="column-box states_style-active">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="states_style-icon"><i class='bx bx-sitemap' ></i></div>                        
                    </div>
                    <div class="col">
                        <div class="row align-items-center g-2">
                            <div class="col-auto">
                                <h5 class="title-md mb-0">{{ $client_teams->count() }}</h5>
                            </div>
                            <div class="col-auto">
                                <span class="font-400 text-grey">|</span>
                            </div>
                            <div class="col-auto">
                                @if($client_teams->count() > 0)
                                    <div class="states_style-text"> {{ pluralOrSingular($client_teams->count(),'Team')}}  Assigned </div>
                                @else
                                    <div class="states_style-text text-light">No Teams Assigned</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="column-box states_style-success">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="states_style-icon"><i class='bx bx-user-plus' ></i></div>                        
                    </div>
                    <div class="col">
                        <div class="row align-items-center g-2">
                            <div class="col-auto">
                                <h5 class="title-md mb-0">{{ $client_users->count() }}</h5>
                            </div>
                            <div class="col-auto">
                                <span class="font-400 text-grey">|</span>
                            </div>
                            <div class="col-auto">
                                @if($client_users->count() > 0)
                                    <div class="states_style-text">{{ pluralOrSingular($client_users->count(),'Member')}} Assigned</div>
                                @else
                                    <div class="states_style-text text-light">No Members Assigned</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="column-box font-500">
                <div class="row align-items-center">
                    <div class="col"><span><i class='bx bx-layer text-secondary' ></i></span> Created By</div>
                    <div class="col text-secondary">@if($client->createdBy) {{ $client->createdBy->name }} @else Auto Generated @endif</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="column-box font-500">
                <div class="row align-items-center">
                    <div class="col"><span><i class='bx bx-calendar text-success'></i></span> Onboard Date</div> 
                    <div class="col text-success"> {{ $client->onboard_date ? (\Carbon\Carbon::parse($client->onboard_date)->format('d M Y')) : 'Not Added' }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="column-box font-500">
                <div class="row align-items-center">
                    <div class="col"><span><i class='bx bx-user text-primary'></i></span> Point Of Contact</div>
                    <div class="col text-primary">{{ $client->point_of_contact ? $client->point_of_contact : 'Not Added' }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="column-box mb-4">
        <div class="row">
            <div class="col-sm-auto pe-5">
                <h5 class="column-title mb-0">Teams</h5>
            </div>
            <div class="col">
                <!-- Teams -->
                <div class="team-list row">
                    @forelse($client_teams as $team)
                        <div class="col-auto mt-3">
                            <div class="team team-style_2 editTeam">
                                <div class="team-style_2-head_wrap">
                                    <div class="team-avtar">
                                        <span>
                                            @if($team->image)
                                                <img src="{{ env('APP_URL') }}/storage/{{ $team->image }}" alt="">
                                            @else
                                                <a href="#" class="avatarGroup-avatar">
                                                    <span class="avatar avatar-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ $team->name }}">{{ $team->initials }}</span>
                                                </a>
                                            @endif

                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="team-style_2-title">{{$team->name}}</h4>
                                        <div class="avatarGroup avatarGroup-overlap">
                                            @php
                                                $c_u = $client_users->pluck('id');
                                                $t_u = $team->users->pluck('id'); 
                                                $c_u = $c_u->intersect($t_u);
                                                $plus_more_users = 0;
                                                $loop_index = 1;
                                            @endphp
                                            @foreach($team->users as $user)
                                                @if($client_users->contains($user->id))
                                                    @if($loop_index > 2)
                                                        @php
                                                            $plus_more_users++;
                                                        @endphp
                                                        @continue;
                                                    @endif
                                                    @php
                                                        $loop_index++;
                                                    @endphp

                                                    <a href="#" class="avatarGroup-avatar">
                                                        <x-avatar :user="$user" />
                                                    </a>
                                                @endif
                                            @endforeach
                                            {{-- <a href="#" class="avatarGroup-avatar">
                                                <span class="avatar avatar-sm avatar-more"> +{{ $plus_more_users }}</span>
                                            </a> --}}
                                            @if($plus_more_users > 0)
                                                    <a href="#" class="avatarGroup-avatar">
                                                        <span class="avatar avatar-sm avatar-more"> +{{ $plus_more_users }}</span>
                                                    </a>
                                                @else
                                                    <span class="avatar avatar-sm avatar-more"></span>
                                                @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-light">No Teams Assigned</div>
                    @endforelse
                    
                </div>
            </div>
        </div>
    </div>
    <div class="column-box" wire:ignore>
        <div class="column-title mb-3">Description <a href="javascript:;" class="btn-link"><i class='bx bx-pencil edit-des' ></i></a></div>
        <hr>
        <div class="user-profile-bio">
            @if($client->description)
                {!! $client->description !!}
            @else
                Not Added
            @endif
        </div>
        <button class="btn btn-primary mt-2 update-des-btn d-none" wire:click="updateDescription">Update</button>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('client-added', event => {
        $(".user-profile-bio").html(event.detail[0].description);
    });
    $('.edit-des').click(function(){
        $(".update-des-btn").removeClass('d-none');
        $('.user-profile-bio').summernote({
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onChange: function(contents, $editable) {
                    @this.set('description', contents);
                }
            }
        });
    });
    $('.update-des-btn').click(function(){
        $('.user-profile-bio').summernote('destroy');
        $(".update-des-btn").addClass('d-none');
    });
</script>
@endpush
