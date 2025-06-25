<?php

namespace App\Livewire\Developers;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApiTokens extends Component
{
    public $tokens = [];
    public $tokenName;
    public $tokenAbilities = [];

    public function render()
    {
        return view('livewire.developers.api-tokens');
    }

    public function mount(){
        $this->tokens = User::find(auth()->id())->tokens;
    }

    public function createToken(){
        $this->validate([
            'tokenName' => 'required',
            'tokenAbilities' => 'required',
        ]);


        $user = User::find(auth()->id());
        $file_uuid = (string) Str::uuid();
        $token = $user->createToken($this->tokenName, $this->tokenAbilities)->plainTextToken;

        // downlaod the token

        $fileName = $file_uuid . '.txt';
        $fileContents = 'Here is your token: ' . $token;
        Storage::put('tokens/' . $fileName, $fileContents);

        $this->tokens = User::find(auth()->id())->tokens;
        
        $this->resetForm();
        $this->dispatch('success', 'Token created successfully');

        return response()->download(storage_path('app/public/tokens/' . $fileName));

    }

    public function resetForm(){
        $this->tokenName = '';
        $this->tokenAbilities = [];
    }

    public function deleteToken($tokenId){
        $user = User::find(auth()->id());
        $user->tokens()->where('id', $tokenId)->delete();
        $this->tokens = User::find(auth()->id())->tokens;
        $this->dispatch('success', 'Token deleted successfully');
    }
}
