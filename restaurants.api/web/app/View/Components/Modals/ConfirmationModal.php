<?php

namespace App\View\Components\Modals;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ConfirmationModal extends Component
{
    public $id;

    public $title;

    public $subtitle;

    public $confirmButtonType;

    public function __construct($id, $title, $subtitle, $confirmButtonType)
    {
        $this->id = $id;
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->confirmButtonType = $confirmButtonType;
    }

    public function render(): View|Closure|string
    {
        return view('components.modals.confirmation-modal');
    }
}
