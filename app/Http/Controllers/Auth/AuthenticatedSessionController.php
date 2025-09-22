<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Services\MembershipService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     return redirect()->intended(route('dashboard', absolute: false));
    // }
    public function store(LoginRequest $request, MembershipService $memberships): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // 1) If there is an intended URL (user tried to visit a protected page), go there.
        if (session()->has('url.intended')) {
            return redirect()->intended();
        }

        $user = $request->user();

        // 2) Superadmin: default to dashboard (global space for cross-tenant tasks)
        if ($user->hasRole('superadmin')) {
            return redirect()->route('dashboard');
        }

        // 3) Non-superadmin: check school memberships
        $schools = $memberships->schoolsFor($user);

        if ($schools->count() === 1) {
            // single membership → go straight to tenant home
            $school = $schools->first();
            $memberships->setActiveSchoolId($school->id);

            return redirect()->route('school.home', $school->slug);
        }

        // 4) Multiple memberships → choose school
        if ($schools->count() > 1) {
            return redirect()->route('choose-school.show');
        }

        // 5) No memberships (e.g., fresh user): keep them on dashboard or onboard
        return redirect()->route('dashboard');
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
