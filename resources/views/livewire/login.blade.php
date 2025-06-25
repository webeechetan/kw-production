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
                        <h2 class="title mb-4">Sign In to your account</h2>
                        {{-- <a href="#" class="signin-google-btn w-100">
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
                        <p>or sign in with your E-mail</p>
                    </div>
                    <div class="signin-content-right-btm mt-4">
                        <form wire:submit="login" method="POST">
                            <div class="mb-3" controlId="signinEmail">
                                <div class="form-field">
                                    <div class="form-field-icon"><i class='bx bx-envelope'></i></div>
                                    <input type="email" wire:model="email" class="form-control" placeholder="Enter Email" required />
                                    @error('email') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="mb-3" controlId="signinPassword">
                                <div class="form-field">
                                    <div class="form-field-icon"><i class='bx bx-lock-open'></i></div>
                                    <input type="password" wire:model="password" class="form-control" placeholder="Enter Password" required />
                                    @error('password') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="row my-4">
                                <div class="col-12">
                                    <a wire:navigate href="{{ route('forgot.password') }}" class="text-link">Forgot Password?</a>
                                </div>
                            </div>
                            @if(session()->has('error'))
                                <div class="col mb-4">
                                    <div class="text-danger">
                                        {{ session('error') }}
                                    </div>
                                </div>
                            @endif
                            
                            @if(session()->has('success'))
                                <div class="col mb-4">
                                    <div class="text-success"> 
                                        {{ session('success') }}
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                <!-- <div class="col-md-6">
                                    <a class="w-100 btn btn-primary-border btn-smt wire:navigate" href="/register">Sign Up</a>
                                </div> -->
                                <div class="col-md-6">
                                    <button class="w-100 btn btn-primary btn-smt" type="submit">Sign In</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>