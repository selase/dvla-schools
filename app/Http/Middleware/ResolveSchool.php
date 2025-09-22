<?php

namespace App\Http\Middleware;

use App\Models\School;
use App\Support\CurrentSchool;
use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\Response;

class ResolveSchool
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     // Works with either {school:slug} (implicit binding) or a plain {school_slug} parameter
    //     $slug = $request->route('school')?->slug ?? $request->route('school_slug');

    //     $school = $slug ? School::where('slug', $slug)->first() : null;

    //     if ( ! $slug && auth()->check() && auth()->user()->hasRole('superadmin')) {
    //         if ($id = session('admin_context_school_id')) {
    //             $school = School::find($id);
    //             CurrentSchool::set($school);
    //             app(PermissionRegistrar::class)->setPermissionsTeamId($school?->id);
    //             return $next($request);
    //         }
    //     }

    //     // Set current school for the request lifecycle
    //     CurrentSchool::set($school);

    //     // VERY IMPORTANT: Tell Spatie Permission which "team" (school) is active
    //     app(PermissionRegistrar::class)->setPermissionsTeamId($school?->id);

    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next)
    {
        $slug = $request->route('school')?->slug ?? $request->route('school_slug');

        $school = null;

        // 1) If the route contains a {school:slug}, use it
        if ($slug) {
            $school = School::where('slug', $slug)->first();
        }

        // 2) Otherwise, if user picked a school earlier, use session active_school_id
        if (! $school && auth()->check() && session()->has('active_school_id')) {
            $school = School::find(session('active_school_id'));
        }

        // 3) Superadmin "view as" context (optional)
        if (! $school && auth()->check() && auth()->user()->hasRole('superadmin') && session()->has('admin_context_school_id')) {
            $school = School::find(session('admin_context_school_id'));
        }

        CurrentSchool::set($school);

        // Team context for Spatie Permission (null is fine for global/superadmin view)
        app(PermissionRegistrar::class)->setPermissionsTeamId($school?->id);

        return $next($request);
    }
}
