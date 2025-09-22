<?php

namespace App\Http\Controllers;

class AdminSwitcher extends Controller
{
    public function switch(int $schoolId)
    {
        session(['admin_context_school_id' => $schoolId]);
        return back();
    }

    public function clear()
    {
        session()->forget('admin_context_school_id');
        return back();
    }

}
