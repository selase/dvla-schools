<?php

namespace Database\Factories;

use App\Models\School;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Instructor>
 */
class InstructorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'primary_school_id' => $this->faker->boolean(70) ? School::factory() : null,
            'is_locum' => $this->faker->boolean(10),
        ];
    }

    /** Attach a specific user */
    public function withUser(User $user): static
    {
        return $this->state(fn() => [
            'user_id' => $user->id,
        ]);
    }

    /** Set the primary school */
    public function forPrimarySchool(School $school): static
    {
        return $this->state(fn() => [
            'primary_school_id' => $school->id,
        ]);
    }
}
