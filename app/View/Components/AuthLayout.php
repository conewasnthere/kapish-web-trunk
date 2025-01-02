<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AuthLayout extends Component
{
    /**
    /**
     * Create a new component instance.
     *
     * @param string $title The page title.
     * @param string $width The page width.
     * @return void
     */
    public function __construct(
        public string $title,
        public string $width = 'auth-form',
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('layouts.auth');
    }
}
