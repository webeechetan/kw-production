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
                        <h2 class="title">Reset your password</h2>
                    </div>
                    <div class="signin-content-right-btm mt-4">
                        @if($step == 1)
                        <form wire:submit="sendOTP" method="POST">
                            <div class="mb-3" >
                                <div class="form-field">
                                    <div class="form-field-icon">
                                        <i class='bx bx-envelope'></i>
                                    </div>
                                    <input type="email" wire:model="email" class="form-control" placeholder="Enter Email" required />
                                    @error('email') <span class="text-danger">{{ $message }}</span>@enderror
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
                                <button class="w-100 btn btn-primary" type="submit">Send OTP</button>
                            </div>
                            <div class="col-12 text-center">
                                <a wire:navigate href="{{ route('login') }}" class="text-link">Back to Login</a>
                            </div>
                        </form>

                        @endif
                        
                        @if($step == 2)
                        <form wire:submit="verifyOTP" method="POST">
                            <div class="mb-3" >
                                <div class="form-field">
                                    <div class="form-field-icon">
                                        <i class='bx bx-envelope'></i>
                                    </div>
                                    <input type="text" wire:model="enterdOTP" class="form-control" placeholder="Enter OTP" required />
                                    @error('enterdOTP') <span class="text-danger">{{ $message }}</span>@enderror
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
                                    <button class="w-100 btn btn-primary" type="submit">Verify OTP</button>
                                </div>
                            </div>
                        </form>
                        @endif

                        @if($step == 3)
                        <form wire:submit="changePassword" method="POST">
                            <div class="mb-3" >
                                <div class="form-field">
                                    <div class="form-field-icon">
                                        <i class='bx bx-lock-open'></i>
                                    </div>
                                    <input type="text" wire:model="password" class="form-control" placeholder="Enter Password" required />
                                    @error('password') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-field">
                                    <div class="form-field-icon">
                                        <i class='bx bx-lock-open'></i>
                                    </div>
                                    <input type="text" wire:model="password_confirmation" class="form-control" placeholder="Confirm Password" required />
                                    @error('password_confirmation') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            @if(session()->has('success'))
                                <div class="col text-center">
                                    <div class="text-success">
                                        {{ session('success') }}
                                    </div>
                                </div>
                            @endif
                            <div class="mt-2">
                                <div class="col">
                                    <button class="w-100 btn btn-primary" type="submit">Change Password</button>
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