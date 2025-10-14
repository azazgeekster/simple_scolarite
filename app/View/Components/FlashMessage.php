<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FlashMessage extends Component
{
    /**
     * Create a new component instance.
     */
    public $type;
    public $message;

    public function __construct($type = 'status', $message = null)
    {
        $this->type = $type;
        $this->message = $message ?? session($type);
    }

    public function render()
    {
        return view('components.flash-message');
    }
}
