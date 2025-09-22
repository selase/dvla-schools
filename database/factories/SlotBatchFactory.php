<?php

namespace Database\Factories;

use App\Models\School;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\SlotBatch>
 */
class SlotBatchFactory extends Factory
{
    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'requested_by' => User::factory(),
            'status' => 'draft',
            'count' => 0,
        ];
    }

    public function forSchool(School $school): static
    {
        return $this->state(fn() => [
            'school_id' => $school->id,
        ]);
    }

    public function requestedBy(User $user): static
    {
        return $this->state(fn() => [
            'requested_by' => $user->id,
        ]);
    }
}
