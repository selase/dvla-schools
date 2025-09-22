<?php

namespace Database\Factories;

use App\Models\School;
use App\Models\SlotBatch;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Slot>
 */
class SlotFactory extends Factory
{
    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'student_id' => Student::factory(),
            'batch_id' => null,
            'status' => 'draft',
            'approved_by' => null,
            'approved_at' => null,
            'rejection_reason' => null,
            'token' => Str::uuid(),
            'meta' => null,
        ];
    }

    public function forSchool(School $school): static
    {
        return $this->state(fn() => [
            'school_id' => $school->id,
            'student_id' => Student::factory()->forSchool($school),
        ]);
    }

    public function withStudent(Student $student): static
    {
        return $this->state(fn() => [
            'student_id' => $student->id,
            'school_id' => $student->school_id,
        ]);
    }

    public function inBatch(?SlotBatch $batch): static
    {
        return $this->state(fn() => [
            'batch_id' => $batch?->id,
        ]);
    }

    public function approved(User $approver): static
    {
        return $this->state(fn() => [
            'status' => 'approved',
            'approved_by' => $approver->id,
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);
    }

    public function rejected(string $reason = 'Invalid'): static
    {
        return $this->state(fn() => [
            'status' => 'rejected',
            'approved_by' => null,
            'approved_at' => null,
            'rejection_reason' => $reason,
        ]);
    }
}
