<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectMultipleOptionsWithCounter extends Component
{
    public $name;

    public $placeholder;

    public $hasSearch;

    public $toggleCountText;

    public function __construct($name, $placeholder, $hasSearch, $toggleCountText)
    {
        $this->name = $name;
        $this->placeholder = $placeholder;
        $this->hasSearch = $hasSearch;
        $this->toggleCountText = $toggleCountText;
    }

    public function render(): View|Closure|string
    {
        return view('components.forms.select-multiple-options-with-counter');
    }
}
