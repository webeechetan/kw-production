<div class="d-flex flex-wrap gap-2 align-items-center justify-content-end mb-2">
    <span class="pe-2"><i class='bx bx-filter-alt text-secondary'></i> Filter Results:</span>
        @if($sort != 'all')
            <span class="btn-batch">
                @if($sort == 'newest') Newest @endif
                @if($sort == 'oldest') Oldest @endif
                @if($sort == 'a_z') A to Z @endif
                @if($sort == 'z_a') Z to A @endif
                <a wire:click="$set('sort','all')" class="ms-1"><i class='bx bx-x'></i></a></span> <span class="text-grey">|</span>
        @endif

        @if($status != 'all')

            <span class="btn-batch">
                @if($status == 'active') Active @endif
                @if($status == 'pending') Assigned @endif
                @if($status == 'overdue') Overdue @endif
                @if($status == 'completed') Completed @endif
                @if($status == 'in_progress') In Progress @endif
                @if($status == 'in_review') In Review @endif
                @if($status == 'archived') Archived @endif
                <a wire:click="$set('status','all')" class="ms-1"><i class='bx bx-x'></i></a></span> <span class="text-grey">|</span>
        @endif 
    
        @if($byUser != 'all' && $byUser != null)
            <span class="btn-batch">{{ $users->find($byUser)->name }} <a wire:click="$set('byUser','all')" class="ms-1"><i class='bx bx-x'></i></a></span> <span class="text-grey">|</span>
        @endif

        @if($byClient != 'all' && $byClient != null)
            <span class="btn-batch">{{ $clients->find($byClient)->name }} <a wire:click="$set('byClient','all')" class="ms-1"><i class='bx bx-x'></i></a></span> <span class="text-grey">|</span>
        @endif

        @if($byTeam != 'all' && $byTeam != null)
            <span class="btn-batch">{{ $teams->find($byTeam)->name }} <a wire:click="$set('byTeam','all')" class="ms-1"><i class='bx bx-x'></i></a></span> <span class="text-grey">|</span>
        @endif

        @if($byProject != 'all' && $byProject != null)
            <span class="btn-batch">{{ $projects->find($byProject)?->name }} <a wire:click="$set('byProject','all')" class="ms-1"><i class='bx bx-x'></i></a></span> <span class="text-grey">|</span>
        @endif

        @if($startDate)
            <span class="btn-batch">{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} <a wire:click="$set('startDate','')" class="ms-1"><i class='bx bx-x'></i></a></span> <span class="text-grey">|</span>
        @endif

        @if($dueDate)
            <span class="btn-batch">{{ \Carbon\Carbon::parse($dueDate)->format('d M Y') }} <a wire:click="$set('dueDate','')" class="ms-1"><i class='bx bx-x'></i></a></span> <span class="text-grey">|</span>
        @endif
        
    <a wire:navigate href="{{$clearFilters}}" class="text-danger d-flex align-items-center">Reset <span class="ms-1 d-inline-flex"><i class='bx bx-refresh'></i></span></a>
</div>