<?php

namespace App\View\Components;

use Illuminate\View\Component;

class BackButton extends Component
{
    public string $href;
    public string $label;

    public function __construct(
        string $href = '',
        string $label = 'Volver'
    ) {
        $this->href  = $href ?: url()->previous();
        $this->label = $label;
    }

    public function render()
    {
        return view('components.back-button');
    }
}