<div class="container">
   <!-- Dashboard Header -->
   <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
         <li class="breadcrumb-item">
            <a wire:navigate href="{{ route('team.index') }}">
            <i class='bx bx-line-chart'></i>{{ ucfirst(Auth::user()->organization->name) }}</a>
         </li>
         <li class="breadcrumb-item">
            <a wire:navigate href="{{ route('team.index') }}">All Team</a>
         </li>
         <li class="breadcrumb-item active" aria-current="page">{{$team->name}}
         </li>
      </ol>
   </nav>
   <livewire:teams.components.teams-tab :team="$team" @saved="$refresh"/>
   <!--- Dashboard Body --->
   <div class="row mb-4">
      <div class="col-lg-4">
         <div class="column-box states_style-progress h-100">
            <div class="row align-items-center">
               <div class="col-auto">
                  <div class="states_style-icon">
                     <i class='bx bx-layer'></i>
                  </div>
               </div>
               <div class="col">
                  <div class="row align-items-center g-2">
                     <div class="col-auto">
                        {{-- <h5 class="title-md mb-0">{{$team->projects->count()}}</h5> --}}
                        <h5 class="title-md mb-0">{{$team->clients->count()}}</h5>
                     </div>
                     <div class="col-auto">
                        <span class="font-400 text-grey">|</span>
                     </div>
                     <div class="col-auto">
                        {{-- <div class="states_style-text">{{$team->projects->count() > 1 ? 'Clients' : 'Client'}}</div> --}}
                        <div class="states_style-text">{{$team->clients->count() > 1 ? 'Clients' : 'Client'}}</div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-lg-4">
         <div class="column-box states_style-active h-100">
            <div class="row align-items-center">
               <div class="col-auto">
                  <div class="states_style-icon">
                     <i class='bx bx-layer'></i>
                  </div>
               </div>
               <div class="col">
                  <div class="row align-items-center g-2">
                     <div class="col-auto">
                        <h5 class="title-md mb-0"> {{ $team->projects->count() }} </h5>
                     </div>
                     <div class="col-auto">
                        <span class="font-400 text-grey">|</span>
                     </div>
                     <div class="col-auto">
                        <div class="states_style-text">  {{ $team->projects->count() >1 ? 'Projects' : 'Project' }}</div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-lg-4">
         <div class="column-box states_style-success h-100">
            <div class="row align-items-center">
               <div class="col-auto">
                  <div class="states_style-icon">
                     <i class='bx bx-layer'></i>
                  </div>
               </div>
               <div class="col">
                  <div class="row align-items-center g-2">
                     <div class="col-auto">
                        <h5 class="title-md mb-0">{{ $team->users->count() }}</h5>
                     </div> 
                     <div class="col-auto">
                        <span class="font-400 text-grey">|</span>
                     </div>
                     <div class="col-auto">
                        <div class="states_style-text"> {{ $team->users->count() >1 ? 'Members' : 'Member' }}</div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="column-box mb-4">
      <div class="row align-items-center">
         <div class="col-auto w-150 pe-5">
            <h5 class="column-title mb-0">Clients</h5>
         </div>
         <div class="col">
            <!-- Teams -->
            <div class="btn-list">
               @foreach($team->clients as $client)
               <a class="btn btn-border btn-border-rounded d-flex align-items-center" href="{{route('client.profile',$client->id)}}" wire:navigate="">
                  <span class="avatar avatar-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{$client->name}}">{{$client->initials}}</span>
                  {{$client->name}}
               </a>
               @endforeach
            </div>
         </div>
      </div>
   </div>
   <div class="column-box mb-4">
      <div class="row align-items-center">
         <div class="col-auto w-150 pe-5">
            <h5 class="column-title mb-0">Members</h5>
         </div>
         <div class="col"> 
            <!-- Teams -->
            <div class="avatarGroup">
               <div class="btn-list">
                  @foreach($team->users as  $user)
                     <a class="btn btn-border btn-border-rounded d-flex align-items-center" href="{{route('user.profile',$user->id)}}" wire:navigate="">
                        @if(!$user->image)
                           <span class="avatar avatar-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{$user->name}}">{{$user->initials}}</span>
                        @else
                           <img src="{{asset('storage/'.$user->image)}}" class="avatar avatar-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{$user->name}}" alt="{{$user->name}}">
                        @endif
                        {{$user->name}}
                     </a>
                  @endforeach
               </div>
            </div>
         </div>
      </div>
   </div>
   <livewire:components.add-team @saved="$refresh" />
</div>