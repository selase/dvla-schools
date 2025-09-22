<?php

namespace Database\Factories;

use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Assessment>
 */
class AssessmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'enrollment_id' => Enrollment::factory(),
            'max_score' => 100,
            'score' => 0,
            'type' => 'quiz',
            'meta' => null,
        ];
    }

    /** Attach a specific enrollment */
    public function forEnrollment(Enrollment $enrollment): static
    {
        return $this->state(fn() => [
            'enrollment_id' => $enrollment->id,
        ]);
    }

    /** Set a given score and max */
    public function scored(int $score, int $max = 100): static
    {
        return $this->state(fn() => [
            'score' => $score,
            'max_score' => $max,
        ]);
    }
}
