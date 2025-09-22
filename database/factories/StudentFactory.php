<?php

namespace Database\Factories;

use App\Models\School;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'school_id' => School::factory(),
            'ghana_card_no' => $this->faker->optional()->numerify('GHA-############'),
            'passport_no' => $this->faker->optional()->bothify('P########'),
            'status' => 'registered',
            'tuition_paid_at' => null,
            'learner_license_no' => $this->faker->optional()->bothify('L########'),
            'learner_license_verified_at' => null,
        ];
    }

    /** Ensure the student belongs to a given school */
    public function forSchool(School $school): static
    {
        return $this->state(fn() => [
            'school_id' => $school->id,
        ]);
    }

    /** Attach a specific user */
    public function withUser(User $user): static
    {
        return $this->state(fn() => [
            'user_id' => $user->id,
        ]);
    }
}
