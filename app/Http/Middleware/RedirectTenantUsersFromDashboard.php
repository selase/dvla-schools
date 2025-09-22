<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\MembershipService;
use Symfony\Component\HttpFoundation\Response;

class RedirectTenantUsersFromDashboard
{
    public function __construct(private MembershipService $memberships) {}
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user && ! $user->hasRole('superadmin')) {
            $schools = $this->memberships->schoolsFor($user);
            if ($schools->count() === 1) {
                $school = $schools->first();
                $this->memberships->setActiveSchoolId($school->id);
                return redirect()->route('school.home', $school->slug);
            }
            if ($schools->count() > 1) {
                return redirect()->route('choose-school.show');
            }
        }
        return $next($request);
    }
}
