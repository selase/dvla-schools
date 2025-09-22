<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Slot;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\RegulatoryAction>
 */
class RegulatoryActionFactory extends Factory
{
    public function definition(): array
    {
        $scope = $this->faker->randomElement(['slot', 'student', 'payment']);

        return [
            'actor_user_id' => User::factory(),
            'scope' => $scope,
            'record_id' => match ($scope) {
                'slot' => Slot::factory(),
                'student' => Student::factory(),
                'payment' => Payment::factory(),
                default => 1,
            },
            'action' => $this->faker->randomElement(['approve', 'reject', 'flag']),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }

    public function withActor(User $user): static
    {
        return $this->state(fn() => [
            'actor_user_id' => $user->id,
        ]);
    }

    /** Set scope and model id based on instance */
    public function forModel(object $model): static
    {
        $map = [
            'slot' => Slot::class,
            'student' => Student::class,
            'payment' => Payment::class,
        ];
        $scope = collect($map)->first(fn($cls, $key) => $model instanceof $cls, 'slot');

        return $this->state(fn() => [
            'scope' => array_search($scope, $map, true),
            'record_id' => $model->getKey(),
        ]);
    }
}
