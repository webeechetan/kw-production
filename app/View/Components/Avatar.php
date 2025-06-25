<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Avatar extends Component
{
    public $user;
    public $class; 
    /**
     * Create a new component instance.
     */
    public function __construct($user, $class = null)
    {
        $this->user = $user;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.avatar');
    }
}
