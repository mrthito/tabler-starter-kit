<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Widgets\ActiveUsersWidget;
use App\Widgets\TotalUsersWidget;
use App\Widgets\WelcomeBack;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $widgets = [
            WelcomeBack::class,
            TotalUsersWidget::class,
            ActiveUsersWidget::class,
        ];

        return view('admin.dashboard', compact('widgets'));
    }
}
