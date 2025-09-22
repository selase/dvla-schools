<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        // High-level, cross-tenant KPIs
        // keep it simple for now
        return view('admin.dashboard');
    }
}
