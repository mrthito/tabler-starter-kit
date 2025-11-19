<?php

namespace App\Widgets;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WelcomeBack extends Widget
{
    public $user;
    public $message;

    protected $view = 'widgets.welcome-back';

    protected $width = 'col-md-6';

    public function __construct()
    {
        $user = Auth::user();
        $this->user = $user;
        $this->message = $user ? 'Hola, ' . $user->name . '!' : 'Welcome back!';
    }
}
