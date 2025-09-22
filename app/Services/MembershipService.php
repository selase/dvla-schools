<?php

namespace App\Services;

use App\Models\School;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MembershipService
{
    /**
     * Get team (school) IDs where the user has any team-scoped role.
     */
    public function teamIdsFor(User $user): Collection
    {
        return DB::table('model_has_roles')
            ->where('model_type', User::class)
            ->where('model_id', $user->id)
            ->whereNotNull('school_id') // teams=true => school_id stored here
            ->pluck('school_id')
            ->unique()
            ->values();
    }

    /**
     * Get School models for the user's team memberships.
     */
    public function schoolsFor(User $user): Collection
    {
        $ids = $this->teamIdsFor($user);
        return School::whereIn('id', $ids)->get();
    }

    /**
     * Persist the chosen active school in session.
     */
    public function setActiveSchoolId(?int $schoolId): void
    {
        if ($schoolId) {
            session(['active_school_id' => $schoolId]);
        } else {
            session()->forget('active_school_id');
        }
    }

    public function activeSchoolId(): ?int
    {
        return session('active_school_id');
    }
}
