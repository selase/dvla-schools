<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ApplicationReviewController extends Controller
{
    // Show list (HTMX or full)
    public function index(School $school, Request $request)
    {
        $apps = Application::where('school_id', $school->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        if ($request->headers->get('HX-Request')) {
            return view('school.partials.applications-table', compact('apps','school'));
        }

        return view('school.applications', compact('apps','school'));
    }

    // Accept -> create user + student + assign student role (school_id)
    public function accept(School $school, Application $application)
    {
        abort_if($application->school_id !== $school->id, 404);

        // Create or fetch user by email
        $user = User::firstOrCreate(
            ['email' => $application->email],
            [
                'name' => $application->first_name.' '.$application->last_name,
                // Temporary password; in real life email a set-password link
                'password' => Hash::make(str()->random(16)),
            ]
        );

        // Create the student under this school
        $student = Student::firstOrCreate([
            'user_id'   => $user->id,
            'school_id' => $school->id,
        ], [
            'ghana_card_no' => $application->ghana_card_no,
            'passport_no'   => $application->passport_no,
            'status'        => 'registered',
        ]);

        // Assign school-scoped role
        $user->assignRole('student', $school->id); // NOTE: school_id not team_id

        // Enroll into DVLA-required baseline courses for this school
        $courseIds = Course::query()
            ->join('school_course','courses.id','=','school_course.course_id')
            ->where('school_course.school_id', $school->id)
            ->where('courses.dvla_required', true)
            ->pluck('courses.id');

        foreach ($courseIds as $cid) {
            Enrollment::firstOrCreate([
                'school_id'  => $school->id,
                'student_id' => $student->id,
                'course_id'  => $cid,
            ]);
        }

        $application->update(['status' => 'accepted']);

        // Return updated row (HTMX swap)
        return view('school.partials.application-row', [
            'app'   => $application->fresh(),
            'school'=> $school,
        ]);
    }

    public function reject(School $school, Application $application, Request $request)
    {
        abort_if($application->school_id !== $school->id, 404);

        $application->update([
            'status' => 'rejected',
            'notes'  => $request->string('notes'),
        ]);

        return view('school.partials.application-row', [
            'app'   => $application->fresh(),
            'school'=> $school,
        ]);
    }
}
