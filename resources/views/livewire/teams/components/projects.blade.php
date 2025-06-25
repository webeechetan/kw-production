<div class="container">
   <!-- Dashboard Header -->
   <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
         <li class="breadcrumb-item">
            <a wire:navigate href="{{ route('dashboard') }}">
            <i class='bx bx-line-chart'></i>{{ ucfirst(Auth::user()->organization->name) }}</a>
         </li>
         <li class="breadcrumb-item">
            <a wire:navigate href="{{ route('team.index') }}">All Team</a>
         </li>
         <li class="breadcrumb-item active" aria-current="page">Tech Team</li>
      </ol>
   </nav>
   <livewire:teams.components.teams-tab :team="$team" @saved="$refresh"/>
   <!--- Dashboard Body --->
   
   <!-- Projects -->
   @php
      $projects = $team->projects;
      $projects_for_count = $team->projects;

      if($filter != 'all'){
         if($filter == 'overdue'){
            $projects = $projects->where('due_date','<',now());
         }else{
            $projects = $projects->where('status',$filter);
         }
      }

      if($start_date && $end_date){
         $projects = $projects->whereBetween('due_date',[$start_date,$end_date]);
      }

      if($filterByClient){
         $projects = $projects->where('client_id',$filterByClient);
      }
      
      if($filterByUser){
        
         $projects = \App\Models\User::find($filterByUser)->projects;
         if($filterByClient){
            $projects = $projects->where('client_id',$filterByClient);
         }
      }

      if($project_start_date != null && $project_due_date != null){
            $projects = $projects->where('due_date', '>=', $project_start_date)->where('due_date', '<=', $project_due_date);
        }

   @endphp
   
   <div class="project-tabs mb-2">
      <div class="row">
         <div class="col-sm-7">
            <div class="dashboard_filters d-flex flex-wrap gap-4 align-items-center mb-4">
               <a class="@if($filter == 'all') active @endif" wire:click="$set('filter','all')">All <span class="btn-batch">{{ $projects_for_count->count() }}</span></a>
               <a class="@if($filter == 'active') active @endif" wire:click="$set('filter','active')">Active <span class="btn-batch">{{ $projects_for_count->where('status','active')->count() }}</span></a>
               <a class="@if($filter == 'overdue') active @endif" wire:click="$set('filter','overdue')">Overdue <span class="btn-batch">{{ $projects_for_count->where('due_date','<',now())->count() }}</span></a>
               <a class="@if($filter == 'completed') active @endif" wire:click="$set('filter','completed')">Completed <span class="btn-batch">{{ $projects_for_count->where('status','completed')->count() }}</span></a>
               <a class="@if($filter == 'archived') active @endif" wire:click="$set('filter','archived')">Archive <span class="btn-batch">{{ $projects_for_count->where('deleted_at','NOT NULL')->count() }}</span></a>
           </div>
         </div>

         {{-- ///sdfsdfgsdfg/ --}}
         <div class="col-md-5 text-end">
            <div class="cus_dropdown" wire:ignore.self>
                <div class="cus_dropdown-icon btn-border btn-border-secondary"><i class="bx bx-filter-alt"></i> Filter</div>
                <div class="cus_dropdown-body cus_dropdown-body-widh_l">
                    <div class="cus_dropdown-body-wrap">
                        <div class="filterSort">
                            <h5 class="filterSort-header mt-4"><i class="bx bx-calendar-alt text-primary"></i> Filter By Date</h5>
                            <div class="row align-items-center mt-2">
                                <div class="col mb-4 mb-md-0" wire:ignore>
                                    <a href="javascript:;" class="btn w-100 btn-sm btn-border-secondary start_date ">
                                        <i class="bx bx-calendar-alt"></i> Start Date
                                    </a>
                                </div>
                                <div class="col-auto text-center font-500 mb-4 mb-md-0 px-0">
                                    To
                                </div>
                                <div class="col" wire:ignore>
                                    <a href="javascript:;" class="btn w-100 btn-sm btn-border-danger due_date ">
                                        <i class="bx bx-calendar-alt"></i> Due Date
                                    </a>
                                </div>
                            </div> 
                            <h5 class="filterSort-header mt-4"><i class="bx bx-briefcase text-primary"></i> Filter By Clients</h5>
                            <select class="dashboard_filters-select mt-2 w-100" wire:model.live="filterByClient" id="">
                                <option value="all" disabled >Select Client</option>
                                @foreach($clients as $client)
                                    <option value="{{$client->id}}">{{$client->name}}</option>
                                @endforeach
                            </select>
                            <h5 class="filterSort-header mt-4"><i class="bx bx-user text-primary"></i> Filter By User</h5>
                            <select class="dashboard_filters-select mt-2 w-100" wire:model.live="filterByUser" name="" id="">
                                <option value="all" disabled >Select User</option>
                                @foreach($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>


      @if($this->doesAnyFilterApplied())
            <x-filters-query-params 
                :sort="$sort"
                :status="$filter" 
                :byUser="$filterByUser" 
                :byClient="$filterByClient"
                :startDate="$project_start_date" 
                :dueDate="$project_due_date" 
                :users="$users" 
                :clients="$clients"
                :projects="$projects"
                :clearFilters="route('team.projects',$team->id)"
            />
        @endif


      <div class="tab-content" id="myTabContent">
         <div class="tab-pane active show" id="project-all-tab-pane" role="tabpanel" aria-labelledby="project-all-tab" tabindex="0">
            <div class="row">

            @if($projects->isNotEmpty())
               @foreach($projects as $project)
                  <div class="col-lg-3">
                     <div class="project-list">
                        <div class="project project-align_left w-100">
                           <div class="project-icon"><i class="bx bx-layer"></i></div>
                           <div class="project-content">
                              <a href="{{ route('project.profile',$project->id) }}" class="project-title">{{$project->name}}</a>
                              <div class="project-selected-date">Due on <span>
                                 @if($project->due_date)
                                    {{  \Carbon\Carbon::parse($project->due_date)->diffForHumans()}}
                                 @else
                                    No Due Date
                                 @endif
                              </span></div>
                           </div>
                        </div>
                     </div>
                  </div>
               @endforeach
            @else
            <div class="col-md-12 text-center">               
               <img src="{{ asset('assets/images/'.'invite_signup_img.png') }}" width="150" alt="">
               <h5 class="text text-light mt-3">No Project found </h5>
            </div>
            @endif
            </div>
         </div>
      </div>
   </div>
   <livewire:components.add-team @saved="$refresh" />
</div>

@script
<script>
   $(document).ready(function(){
      flatpickr('.start_date', {
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr, instance) {
                    $(".start_date").html(dateStr);
                    @this.set('project_start_date', dateStr);
                },
            });


            flatpickr('.due_date', {
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr, instance) {
                    $(".due_date").html(dateStr);
                    @this.set('project_due_date', dateStr);
                },
            });
   });
</script>
@endscript