<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\User;
use App\Support\CurrentSchool;
use DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class InitialSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Roles
        $roles = ['superadmin', 'regulator', 'school_owner', 'school_admin', 'instructor', 'student'];
        foreach ($roles as $r) {
            Role::firstOrCreate(['name' => $r]);
        }

        // Demo school
        $school = School::firstOrCreate(
            ['slug' => 'alpha-driving'],
            ['name' => 'Alpha Driving School'],
        );

        // Set current team/school context for role assignments
        app(PermissionRegistrar::class)->setPermissionsTeamId($school->id);
        CurrentSchool::set($school);

        // Demo courses
        $courses = [
            ['code' => 'T101', 'title' => 'Road Signs & Rules', 'type' => 'theory'],
            ['code' => 'P201', 'title' => 'Basic Vehicle Control', 'type' => 'practical'],
        ];
        foreach ($courses as $c) {
            $course = \App\Models\Course::firstOrCreate(['code' => $c['code']], $c);
            DB::table('school_course')->updateOrInsert(['school_id' => $school->id, 'course_id' => $course->id], []);
        }

        // Services (tuition baseline)
        \App\Models\Service::firstOrCreate([
            'school_id' => $school->id,
            'code' => 'TUITION',
        ], [
            'name' => 'Tuition Fee',
            'baseline_fee' => 150000, // GHS 1,500.00 (pesewas)
        ]);

        // Superadmin user
        $superadmin = User::firstOrCreate([
            'email' => 'hiselase@gmail.com',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('password'),
        ]);

        // Assign superadmin within current team (school) context
        $superadmin->assignRole('superadmin');

        // Demo owner user and role within this school context
        $owner = User::firstOrCreate([
            'email' => 'owner@alpha-driving.test',
        ], [
            'name' => 'Alpha Owner',
            'password' => Hash::make('password'),
        ]);

        $owner->assignRole('school_owner');

    }
}
