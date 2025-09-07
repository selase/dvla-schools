<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Models\School;
use App\Support\CurrentSchool;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\PermissionRegistrar;

class ResolveSchool
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('school')?->slug ?? $request->route('school_slug');

        $school = $slug ? School::where('slug', $slug)->first() : null;
        CurrentSchool::set($school);

        // Set default team for Spatie Permission
        app(PermissionRegistrar::class)->setPermissionsTeamId($school?->id);

        return $next($request);
    }
}
