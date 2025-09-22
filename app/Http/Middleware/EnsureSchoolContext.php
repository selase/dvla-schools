<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSchoolContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            // If user has memberships and no active school, redirect to chooser
            $hasMemberships = \DB::table('model_has_roles')
                ->where('model_type', \App\Models\User::class)
                ->where('model_id', $request->user()->id)
                ->whereNotNull('school_id')
                ->exists();

            if ($hasMemberships && ! session('active_school_id') && ! $request->routeIs('choose-school.*')) {
                return redirect()->route('choose-school.show');
            }
        }
        return $next($request);
    }
}
