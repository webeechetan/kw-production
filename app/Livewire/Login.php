<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Organization;
use App\Models\User;
use App\Models\Scopes\OrganizationScope;

class Login extends Component
{
    public $email;
    public $password;

    public function render()
    {
        return view('livewire.login')->layout('auth.login');
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        // dd(Hash::make('123456'));

        // $res = Auth::guard('orginizations')->attempt(['email' => $this->email, 'password' => $this->password]);

        // if(!$res){
        //     $user = User::withoutGlobalScope(OrganizationScope::class)->where('email',$this->email)->first();
        //     if($user){
        //         $res = Hash::check($this->password,$user->password);
        //         if($res){
        //             Auth::guard('web')->login($user);
        //             session()->put('guard','web');
        //             session()->put('org_id',User::withoutGlobalScope(OrganizationScope::class)->where('email',$this->email)->first()->org_id);
        //             session()->put('org_name',Organization::find(session('org_id'))->name);
        //             session()->put('user',User::withoutGlobalScope(OrganizationScope::class)->where('email',$this->email)->first()->id);
        //         }
        //     }
        // }else{
        //     session()->put('guard','orginizations');
        //     session()->put('org_id',Organization::where('email',$this->email)->first()->id);
        //     session()->put('org_name',Organization::where('email',$this->email)->first()->name);
        //     session()->put('user',User::where('email',$this->email)->first());
        // }
        $user = User::withoutGlobalScope(OrganizationScope::class)->where('email',$this->email)->first();
        // dd($user);
        if($user){
            $res = Hash::check($this->password,$user->password);
            if($res){
                Auth::login($user);
                session()->put('guard','web');
                session()->put('org_id',User::withoutGlobalScope(OrganizationScope::class)->where('email',$this->email)->first()->org_id);
                $org_name = Organization::find(session('org_id'))->name;
                $org_name = str_replace(' ','-',$org_name);
                session()->put('org_name',$org_name);
                session()->put('user',User::withoutGlobalScope(OrganizationScope::class)->where('email',$this->email)->first());
                return $this->redirect(session('org_name') .'/dashboard');
            }else{
                session()->flash('error','Invalid email or password');
            }
        }else{
            session()->flash('error','Invalid email or password');
        }

        // if($res){
        //     return $this->redirect(route('dashboard'),navigate: true);
        // }else{
        //     session()->flash('error','Invalid email or password');
        // }
    }
}
