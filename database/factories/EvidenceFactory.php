<?php

namespace Database\Factories;

use App\Models\School;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Evidence>
 */
class EvidenceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'entity_type' => 'student',
            'entity_id' => Student::factory(),
            'type' => 'photo',
            'path' => 'evidence/' . Str::uuid() . '.jpg',
            'meta' => null,
        ];
    }

    /** Attach to a specific entity model and align school if present */
    public function forEntity(object $model): static
    {
        $type = mb_strtolower(class_basename($model));

        return $this->state(function () use ($model, $type) {
            $schoolId = $model->school_id ?? null;

            return [
                'entity_type' => $type,
                'entity_id' => $model->getKey(),
                'school_id' => $schoolId,
            ];
        });
    }

    public function forSchool(School $school): static
    {
        return $this->state(fn() => [
            'school_id' => $school->id,
        ]);
    }
}
