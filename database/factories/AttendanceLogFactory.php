<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\School;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\AttendanceLog>
 */
class AttendanceLogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'student_id' => $this->faker->boolean(80) ? Student::factory() : null,
            'instructor_id' => $this->faker->boolean(50) ? Instructor::factory() : null,
            'course_id' => $this->faker->boolean(60) ? Course::factory() : null,
            'photo_path' => 'photos/' . Str::uuid() . '.jpg',
            'lat' => $this->faker->optional()->latitude(),
            'lng' => $this->faker->optional()->longitude(),
            'captured_at' => now(),
            'exif' => null,
            'liveness_result' => $this->faker->randomElement(['unknown', 'pass', 'fail']),
            'distance_m_from_school' => $this->faker->numberBetween(0, 1000),
            'verdict' => $this->faker->randomElement(['valid', 'suspicious', 'invalid']),
        ];
    }

    public function forSchool(School $school): static
    {
        return $this->state(fn() => [
            'school_id' => $school->id,
        ]);
    }

    public function withStudent(Student $student): static
    {
        return $this->state(fn() => [
            'student_id' => $student->id,
            'school_id' => $student->school_id,
        ]);
    }

    public function withInstructor(Instructor $instructor): static
    {
        return $this->state(fn() => [
            'instructor_id' => $instructor->id,
        ]);
    }

    public function withCourse(Course $course): static
    {
        return $this->state(fn() => [
            'course_id' => $course->id,
        ]);
    }
}
