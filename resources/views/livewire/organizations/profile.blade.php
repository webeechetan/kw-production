<div class="container">
    <!-- Dashboard Header -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard') }}"><i class='bx bx-line-chart'></i>{{ Auth::user()->organization ? Auth::user()->organization->name : 'No organization' }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ucfirst(Auth::user()->organization->name)}}</li>
        </ol>
    </nav>
    <div class="row ">
        <div class="col-lg-4">
            <div class="column-box">
                <div class="user-profile">
                    <img class="org-image" src="{{ env('APP_URL') }}/storage/{{ Auth::user()->organization->image }}" alt="User Image">
                    <input class="org-image-input d-none" type="file" wire:model="image" accept="image/*" >
                    <h3 class="main-body-header-title mb-2">{{ucfirst(Auth::user()->organization->name)}}</h3>
                    <div wire:loading >
                        Updating Image...
                    </div>
                </div>
                <hr>
                <div class="row align-items-center mb-2">
                    <div class="col"><span><i class='bx bx-calendar-alt text-success' ></i></span> Joining Date</div>
                    <div class="col">{{Auth::user()->organization->created_at->format('d M-y')}}</div>
                </div>        
            </div>
        </div>
        <div class="col-lg-8">
            <div class="column-box mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="column-title mb-0"><i class="bx bx-line-chart text-primary"></i> Overview</h3>
                    </div>
                    <div class="col">
                        <div class="main-body-header-right">
                           
                        </div>
                    </div>
                </div>                    
            </div>

        </div>
    </div>
  </div>

  @assets
    <script>
        $(document).ready(function(){
            $('.org-image').click(function(){
                $('.org-image-input').click();
            });
        });
    </script>
@endassets

