<?php

namespace App\Livewire\Developers;

use Livewire\Component;
use App\Models\Webhook;
use Illuminate\Support\Str;

class Dashbaord extends Component
{
    public function render()
    {
        return view('livewire.developers.dashbaord');
    }

}
