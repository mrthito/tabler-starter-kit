<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
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
            'web' => view('layouts.app', compact('title')),
            'admin' => view('admin.layouts.app', compact('title')),
        };
    }
}
