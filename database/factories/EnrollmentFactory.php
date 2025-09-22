<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\School;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Enrollment>
 */
class EnrollmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'student_id' => Student::factory(),
            'course_id' => Course::factory(),
            'progress_pct' => 0,
            'cum_score' => 0,
            'completed_at' => null,
        ];
    }

    /** Ensure enrollment is for the given school and align student */
    public function forSchool(School $school): static
    {
        return $this->state(fn() => [
            'school_id' => $school->id,
            'student_id' => Student::factory()->forSchool($school),
        ]);
    }

    /** Attach a specific student and align school */
    public function withStudent(Student $student): static
    {
        return $this->state(fn() => [
            'student_id' => $student->id,
            'school_id' => $student->school_id,
        ]);
    }

    /** Attach a specific course */
    public function withCourse(Course $course): static
    {
        return $this->state(fn() => [
            'course_id' => $course->id,
        ]);
    }

    /** Mark as completed */
    public function completed(): static
    {
        return $this->state(fn() => [
            'progress_pct' => 100,
            'completed_at' => now(),
        ]);
    }
}
