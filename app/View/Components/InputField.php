<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InputField extends Component
{
    public $label;
    public $name;
    public $type;
    public $value;
    public $placeholder;

    public function __construct($label, $name, $type = 'text', $value = '', $placeholder = '')
    {
        $this->label = $label;
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
        $this->placeholder = $placeholder;
    }

    public function render()
    {
        return view('components.input-field');
    }

}
