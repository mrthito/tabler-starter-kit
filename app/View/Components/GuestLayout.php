<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    public function __construct(
        public string $page,
        public string $layout = 'web',
    ) {}

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $title = $this->page ? $this->page . ' - ' . config('app.name') : config('app.name');
        return match ($this->layout) {
            'web' => view('layouts.guest', compact('title')),
            'admin' => view('admins.layouts.guest', compact('title')),
        };
    }
}
