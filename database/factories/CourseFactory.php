<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->bothify('C####'),
            'title' => $this->faker->sentence(3),
            'type' => $this->faker->randomElement(['theory', 'practical']),
            'dvla_required' => true,
            'meta' => null,
        ];
    }

    /** Make it a theory course */
    public function theory(): static
    {
        return $this->state(fn() => ['type' => 'theory']);
    }

    /** Make it a practical course */
    public function practical(): static
    {
        return $this->state(fn() => ['type' => 'practical']);
    }
}
