<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{School, Application, User, Course, Enrollment, Student};

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['permission.teams' => true, 'permission.team_foreign_key' => 'school_id']);
    // Seed minimal roles
    \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'student']);
});

it('submits an application and admin accepts it creating student and enrollments', function () {
    $school = School::factory()->create(['slug' => 'alpha-driving', 'name'=>'Alpha Driving']);
    // baseline courses
    $c1 = Course::factory()->create(['dvla_required'=>true]);
    $c2 = Course::factory()->create(['dvla_required'=>true]);
    \DB::table('school_course')->insert([
        ['school_id'=>$school->id,'course_id'=>$c1->id],
        ['school_id'=>$school->id,'course_id'=>$c2->id],
    ]);

    // Submit application (public)
    $resp = $this->post(route('school.apply.store', $school->slug), [
        'first_name' => 'Ama', 'last_name' => 'Mensah', 'email' => 'ama@example.com'
    ]);
    $resp->assertRedirect(route('school.apply.thanks', $school->slug));

    $app = Application::first();
    expect($app)->not()->toBeNull();
    expect($app->status)->toBe('submitted');

    // Act as owner/admin (skip detailed auth here)
    // Accept via controller route
    $this->actingAs(User::factory()->create(['password' => Hash::make('password')]));
    session(['active_school_id' => $school->id]);

    $accept = $this->post(route('school.applications.accept', [$school->slug, $app->id]));
    $accept->assertOk();

    $app->refresh();
    expect($app->status)->toBe('accepted');

    $student = Student::where('school_id',$school->id)->first();
    expect($student)->not()->toBeNull();

    $enrollCount = Enrollment::where('student_id',$student->id)->count();
    expect($enrollCount)->toBe(2);
});
