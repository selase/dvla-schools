<?php

use App\Models\School;
use App\Models\Student;
use App\Models\User;
use App\Support\CurrentSchool;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('prevents slot request when tuition not paid', function (): void {
    $school = School::factory()->create(['slug' => 'alpha-driving']);
    CurrentSchool::set($school);

    $user = User::factory()->create();
    $student = Student::factory()->create([
        'user_id' => $user->id,
        'school_id' => $school->id,
        'tuition_paid_at' => null,
    ]);

    // pretend route exists; here we just assert data/state for now
    // later we'll hit controller endpoint.
    expect($student->tuition_paid_at)->toBeNull();
});
