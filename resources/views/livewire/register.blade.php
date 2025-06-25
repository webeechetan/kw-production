<div>
    <div class="header-signup d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-md-center">
                
                <div class="col-sm-6 text-md-end">
                    @if(!in_array($step, [2, 3]))
                    <a wire:navigate href="/login">
                        <button class="btn btn-primary-border btn-smt">Sign In</button>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div> 
    @if($step == 1)
    <div class="signup-content">
        <div class="container">
            <div class="row">
                <div class="col text-center mx-auto">
                    <img class="signup-welcome-img" src="{{ asset('') }}assets/images/signup_welcome.png" alt="Sign Up Welcome" />
                    <div class="title-wrap">
                        <h2 class="title text-primary mt-3">Welcome to Kaykewalk</h2>
                        <p>Let’s get started with a few simple steps</p>
                    </div>
                    <div class="signup-form">
                        <form method="POST" wire:submit="register">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3 form-field" controlId="signupEmail">
                                        <div class="form-field-icon"><i class='bx bx-user'></i></div>
                                        <input type="text" wire:model="user_name" class="form-control" placeholder="e.g., John Doe"   />
                                        @error('user_name') <span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 form-field" controlId="signupEmail">
                                        <div class="form-field-icon"><i class='bx bx-envelope'></i></div>
                                        <input type="email" wire:model="email" class="form-control" placeholder="e.g., john.doe@yourcompany.com"   />
                                        @error('email') <span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-field mb-3" controlId="signupName">
                                        <div class="form-field-icon"><i class='bx bx-user'></i></div>
                                        <input type="text" wire:model="name" class="form-control" placeholder="Organization Name"   />
                                        @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-field mb-3" controlId="signupName">
                                        <div class="form-field-icon"><i class='bx bx-home'></i></div>
                                        {{-- industry type --}}
                                        <select class="form-control" wire:model="industry_type">
                                            <option value="" disabled selected>Industry Type</option>
                                            <option value="inforamtion-technology">Information Technology</option>
                                            <option value="agency">Digital Media Agency</option>
                                        </select>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3 form-field" controlId="signupPassword">
                                        <div class="form-field-icon"><i class='bx bx-lock-open'></i></div>
                                        <input type="password" wire:model="password" class="form-control" placeholder="Password 8+ Characters"    />
                                        @error('password') <span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 form-field" controlId="signupEmail">
                                        <div class="form-field-icon"><i class='bx bx-lock-open'></i></div>
                                        <input type="password" wire:model="confirm_password" class="form-control" placeholder="Confirm Password"   />
                                        @error('confirm_password') <span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                
                            </div>
                            <div class="my-3">
                                <button class="w-100 btn btn-primary btn-smt" type="submit">Sign Up</button>
                            </div>
                            {{-- <p>Or sign up with:</p>
                            <a href="#" class="signin-google-btn w-100">
                                <svg viewBox="0 0 48 48">
                                    <clipPath id="g">
                                        <path d="M44.5 20H24v8.5h11.8C34.7 33.9 30.1 37 24 37c-7.2 0-13-5.8-13-13s5.8-13 13-13c3.1 0 5.9 1.1 8.1 2.9l6.4-6.4C34.6 4.1 29.6 2 24 2 11.8 2 2 11.8 2 24s9.8 22 22 22c11 0 21-8 21-22 0-1.3-.2-2.7-.5-4z"/>
                                    </clipPath>
                                    <g class="colors" clip-path="url(#g)">
                                        <path fill="#FBBC05" d="M0 37V11l17 13z"/>
                                        <path fill="#EA4335" d="M0 11l17 13 7-6.1L48 14V0H0z"/>
                                        <path fill="#34A853" d="M0 37l30-23 7.9 1L48 0v48H0z"/>
                                        <path fill="#4285F4" d="M48 48L17 24l-4-3 35-10z"/>
                                    </g>
                                </svg>
                                <span>google</span>
                            </a> --}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($step == 2)
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
                            <h6 class="title">Upload Logo</h6>
                        </div>
                        <div class="signin-content-right-btm mt-4">
                            <form wire:submit="registerStepOne" method="POST" enctype="multipart/form-data">
                                <div class="mb-3" >
                                    <div class="form-field">
                                        <input type="file" wire:model="image" class="form-control" />
                                        @error('image') <span class="text-danger">{{ $message }}</span>@enderror
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
                                    <button class="w-100 btn btn-primary" type="submit">Done</button>
                                </div>
                                <div class="col-12 text-center">
                                    <a wire:navigate href="{{ env('APP_URL') }}/{{session('org_name')}}/dashboard" class="text-link">Skip for now</a>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
    @endif
</div>
