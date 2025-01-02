<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class AppLayout extends Component
{
    /**
     * The page title.
     *
     * @var string
     */
    public string $title;

    /**
     * Whether to include the footer and navigation bar.
     *
     * @var bool
     */
    public bool $fluff;

    /**
     * Additional view context.
     */
    public array $data;

    /**
     * Create the component instance.
     *
     * @param string $title The page title.
     * @param string|bool $fluff Whether to include the footer and navigation bar.
     * @param array $data Additional view context.
     * @return void
     */
    public function __construct(string $title = '', string|bool $fluff = true, array $data = [])
    {
        $title = trim($title);

        if (empty($title))
        {
            $title = config('app.name');
        }
        else
        {
            $title .= ' - ' . config('app.name');
        }

        $this->title = $title;
        $this->fluff = $fluff === 'on' || $fluff === true;
        $this->data = $data;
    }

    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $context = [
            'baseURL' => url('/')
        ];

        // Avoid generated routes
        if (Route::has(Route::currentRouteName()))
        {
            $context['route'] = Route::currentRouteName();
        }

        if (Auth::check())
        {
            /** @var \App\Models\User */
            $user = Auth::user();

            $context['session'] = [
                'id' => $user->id,
                'heartbeat' => ($user->hasVerifiedEmail() || !config('app.email_verification')) && !$user->isBanned()
            ];
        }

        if (!empty($this->data))
        {
            $context['data'] = $this->data;
        }

        return view('layouts.app', compact('context'));
    }
}
