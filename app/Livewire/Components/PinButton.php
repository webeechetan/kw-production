<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Pin;
use Illuminate\Support\Facades\Auth;

class PinButton extends Component
{
    public $pinned;
    public $pinnable_type;
    public $pinnable_id;
    public $user_id;


    public function render()
    {
        return view('livewire.components.pin-button');
    }

    public function mount($pinnable_type, $pinnable_id)
    {
        $this->pinnable_type = $pinnable_type;
        $this->pinnable_id = $pinnable_id;
        $this->user_id = Auth::user()->id;
        $this->pinned = Pin::where('pinnable_type', $this->pinnable_type)
            ->where('pinnable_id', $this->pinnable_id)
            ->where('user_id', $this->user_id)
            ->exists();
    }

    public function togglePin()
    {
        $pin = Pin::where('pinnable_type', $this->pinnable_type)
            ->where('pinnable_id', $this->pinnable_id)
            ->where('user_id', $this->user_id)
            ->first();

        if ($pin) {
            $pin->delete();
        } else {
            $pin = new Pin();
            $pin->org_id = session('org_id');
            $pin->pinnable_type = $this->pinnable_type;
            $pin->pinnable_id = $this->pinnable_id;
            $pin->user_id = $this->user_id;
            $pin->save();
        }

        $this->pinned = !$this->pinned;
        $msg = $this->pinned ? 'Pinned' : 'Unpinned';
        $this->dispatch('success', $msg);
        // refresh parent component

        // $this->
    }
}
