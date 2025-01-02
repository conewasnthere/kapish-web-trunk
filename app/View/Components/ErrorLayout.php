<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ErrorLayout extends Component
{
    /**
     * Create a new component instance.
     *
     * @param string $title The page title.
     * @param string $code The error code.
     * @param string $message The error message.
     * @param string $blob The blob image.
     * @return void
     */
    public function __construct(
        public string $title,
        public string $code,
        public string $message,
        public string $blob,
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('layouts.error');
    }
}
