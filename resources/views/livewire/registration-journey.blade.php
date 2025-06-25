{{-- <div>
   <h1>Welcome</h1>
</div> --}}

<div class="signin-content">
   <div class="container-fluid">
       <div class="row align-items-center">
           <div class="col-md-6 text-center px-0">
               <div class="signin-content-left bg-md-secondary">
                   <img class="img-fluid d-none d-md-block" src="./assets/images/logo-verticle-white.png" alt="Kaykewalk White Logo" />
                   <img class="img-fluid d-md-none" src="./assets/images/logo.png" alt="Kaykewalk Logo" />
               </div>
           </div>
           <div class="col-md-6">
               <div class="signin-content-right text-center text-md-start space-sec">
                   <div class="signin-content-right-top">
                       <h6 class="title">Registration Journey</h6>
                   </div>
                   <div class="signin-content-right-btm mt-4">
                       @if($step == 1)
                       <form wire:submit="registerstepone" method="POST">
                           <div class="mb-3" >
                               <div class="form-field">
                                   <input type="number" wire:model="companysize" class="form-control" placeholder="Enter Company Size" required />
                                   @error('companysize') <span class="text-danger">{{ $message }}</span>@enderror
                               </div>
                           </div>
                           @if(session()->has('error'))
                               <div class="col text-center">
                                   <div class="text-danger">
                                       {{ session('error') }}
                                   </div>
                               </div>
                           @endif
                           <div class="col-12 mb-4">
                               <button class="w-100 btn btn-primary" type="submit">Next</button>
                           </div>
                           <div class="col-12 text-center">
                               <a wire:navigate href="{{ route('login') }}" class="text-link">Skip for now</a>
                           </div>
                       </form>

                       @endif
                       
                       @if($step == 2)
                       <form wire:submit="registersteptwo" method="POST">
                           <div class="mb-3" >
                               <div class="form-field">   
                                <div class="form-field-icon">
                                    <i class='bx bx-envelope'></i>
                                </div>                   
                                <input type="email" wire:model="memberemail" class="form-control" placeholder="Enter Member Email" required />
                                   @error('memberemail') <span class="text-danger">{{ $message }}</span>@enderror
                               </div>
                           </div>
                           @if(session()->has('error'))
                               <div class="col text-center">
                                   <div class="text-danger">
                                       {{ session('error') }}
                                   </div>
                               </div>
                           @endif
                           <div class="mt-2">
                               <div class="col">
                                   <button class="w-100 btn btn-primary" type="submit">Submit</button>
                               </div>
                               <div class="col-12 text-center">
                                <a wire:navigate href="{{ route('login') }}" class="text-link">Skip for now</a>
                            </div>
                           </div>
                       </form>
                       @endif

                       {{-- @if($step == 3)
                       <form wire:submit="dashboard" method="POST">
                        <div class="mb-3" >
                            <div class="form-field">
                                <div class="form-field-icon">
                                    <i class='bx bx-envelope'></i>
                                </div>
                                <input type="text" wire:model="question3" class="form-control" placeholder="Enter question 3" required />
                                @error('question3') <span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        @if(session()->has('error'))
                            <div class="col text-center">
                                <div class="text-danger">
                                    {{ session('error') }}
                                </div>
                            </div>
                        @endif
                        <div class="mt-2">
                            <div class="col">
                                <button class="w-100 btn btn-primary" type="submit">Submit</button>
                            </div>
                            <div class="col-12 text-center">
                             <a wire:navigate href="{{ route('login') }}" class="text-link">Skip for now</a>
                         </div>
                        </div>
                        </form>
                       @endif --}}

                       @if($step == 3)
    <form wire:submit.prevent="dashboard" method="POST" enctype="multipart/form-data">
        
        <div class="mb-3">
            <div class="form-field">
                <div class="form-field-icon">
                    <i class='bx bx-upload'></i>
                </div>
                {{-- <input type="file" wire:model="logo" class="form-control" /> --}}
                <input class="form-control" type="file" wire:model="logo" accept="image/jpeg, image/jpg, image/png, image/gif">
                @error('logo') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        {{-- @if(session()->has('error'))
            <div class="col text-center">
                <div class="text-danger">
                    {{ session('error') }}
                </div>
            </div>
        @endif --}}
        <div class="mt-2">
            <div class="col">
                <button class="w-100 btn btn-primary" type="submit">Submit</button>
            </div>
        </div>
    </form>
@endif
                   </div>
               </div>
           </div>
       </div>
   </div>
</div>