<?php

namespace Database\Factories;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'school_id' => $this->faker->boolean(80) ? School::factory() : null,
            'name' => $this->faker->words(3, true),
            'code' => Str::upper($this->faker->unique()->bothify('SVC###')),
            'baseline_fee' => $this->faker->numberBetween(10000, 200000),
            'active' => true,
            'meta' => null,
        ];
    }

    public function forSchool(?School $school): static
    {
        return $this->state(fn() => [
            'school_id' => $school?->id,
        ]);
    }
}
