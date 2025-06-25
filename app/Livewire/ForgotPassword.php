<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Organization;
use App\Notifications\OTPNotification;
use Illuminate\Support\Facades\Hash;
use App\Models\Scopes\OrganizationScope;

class ForgotPassword extends Component
{
    public $email;
    public $otp;
    public $password;
    public $password_confirmation;
    public $step = 1;
    public $enterdOTP;
    public $type;

    public function render()
    {
        return view('livewire.forgot-password')->layout('auth.forgot-password');
    }

    public function sendOTP(){

        $this->validate([
            'email' => 'required|email',
        ]);

        $user = User::withoutGlobalScope(OrganizationScope::class)->where('email',$this->email)->first();

        if($user){
            $this->type = 'user';
            $this->otp = rand(100000,999999);
            $user->notify(new OTPNotification($this->otp));
            session()->flash('otp_sent','OTP sent to your email');
            $this->step = 2;
            session()->flash('success','OTP sent to your email');
        }else{
            $organization = Organization::where('email',$this->email)->first();
            if($organization){
                $this->type = 'organization';
                $this->otp = rand(100000,999999);
                $organization->notify(new OTPNotification($this->otp));
                session()->flash('otp_sent','OTP sent to your email');
                $this->step = 2;
                session()->flash('success','OTP sent to your email');
            }else{
                session()->flash('error','Email not found');
            }
        }
    }

    public function verifyOTP(){
        $this->validate([
            'enterdOTP' => 'required',
        ]);

        if($this->enterdOTP == $this->otp){
            $this->step = 3;
        }else{
            session()->flash('error','OTP not matched');
        }
    }

    public function mount(){
        if(Auth::check()){
            return redirect()->route('dashboard');
        }
    }

    public function changePassword(){
        $this->validate([
            'password' => 'required|min:6|confirmed'
        ]);

        if($this->type == 'user'){
            $this->changeUserPassword();
        }else{
            $this->changeOrganizationPassword();
        }
    }

    public function changeUserPassword(){
        $user = User::withoutGlobalScope(OrganizationScope::class)->where('email',$this->email)->first();
        $user->password = Hash::make($this->password);
        $user->save();
        session()->flash('success','Password changed successfully');
        return redirect()->route('login');
    }

    public function changeOrganizationPassword(){
        $organization = Organization::where('email',$this->email)->first();
        $organization->password = Hash::make($this->password);
        $organization->save();
        session()->flash('success','Password changed successfully');
        return redirect()->route('login');
    }
}
