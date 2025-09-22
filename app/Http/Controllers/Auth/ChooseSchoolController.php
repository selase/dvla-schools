<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

class ChooseSchoolController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        // Find team memberships (team_id column when teams=true)
        $teamIds = DB::table('model_has_roles')
            ->where('model_type', \App\Models\User::class)
            ->where('model_id', $user->id)
            ->pluck('school_id')
            ->filter()
            ->unique()
            ->values();

        $schools = School::whereIn('id', $teamIds)->get();

        // If only one school, auto-select it
        if ($schools->count() === 1) {
            return $this->set($request->merge(['school_id' => $schools->first()->id]));
        }

        return view('auth.choose-school', ['schools' => $schools]);
    }

    public function set(Request $request)
    {
        $request->validate(['school_id' => 'required|exists:schools,id']);
        $school = School::findOrFail($request->integer('school_id'));

        // Persist active school for this session
        session(['active_school_id' => $school->id]);

        // Tell Spatie the active team for this request (subsequent requests use ResolveSchool/session)
        app(PermissionRegistrar::class)->setPermissionsTeamId($school->id);

        return redirect()->route('school.home', $school->slug);
    }

    // Superadmin “view as” helpers (optional)
    public function adminSwitch(Request $request)
    {
        $this->authorize('viewAny', School::class); // or a simple role check
        $request->validate(['school_id' => 'required|exists:schools,id']);
        session(['admin_context_school_id' => (int) $request->school_id]);
        return back();
    }

    public function adminClear()
    {
        session()->forget('admin_context_school_id');
        return back();
    }
}
