<?php

namespace Database\Factories;

use App\Models\School;
use App\Models\Service;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'student_id' => Student::factory(),
            'service_id' => Service::factory(),
            'driver' => $this->faker->randomElement(['paystack', 'mtn']),
            'amount' => $this->faker->numberBetween(1000, 500000),
            'currency' => 'GHS',
            'status' => 'initiated',
            'tx_ref' => 'TX-' . Str::uuid(),
            'provider_ref' => null,
            'paid_at' => null,
            'payload' => null,
        ];
    }

    public function forSchool(School $school): static
    {
        return $this->state(fn() => [
            'school_id' => $school->id,
            'student_id' => Student::factory()->forSchool($school),
            'service_id' => Service::factory()->forSchool($school),
        ]);
    }

    public function withStudent(Student $student): static
    {
        return $this->state(fn(array $attrs) => [
            'student_id' => $student->id,
            'school_id' => $student->school_id,
            // keep existing service if already aligned, else null to regenerate in tests
        ]);
    }

    public function withService(Service $service): static
    {
        return $this->state(fn() => [
            'service_id' => $service->id,
            // align to service's school if present
            'school_id' => $service->school_id ?? null,
        ]);
    }
}
