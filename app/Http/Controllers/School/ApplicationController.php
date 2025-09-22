<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApplicationRequest;
use App\Models\Application;
use App\Models\School;

class ApplicationController extends Controller
{
    // Public page (no auth) but route carries {school:slug}, ResolveSchool sets context
    public function create(School $school)
    {
        return view('school.apply', compact('school'));
    }

    public function store(School $school, StoreApplicationRequest $request)
    {
        Application::create([
            'school_id'     => $school->id,
            'first_name'    => $request->string('first_name'),
            'last_name'     => $request->string('last_name'),
            'email'         => $request->string('email'),
            'phone'         => $request->string('phone'),
            'ghana_card_no' => $request->string('ghana_card_no'),
            'passport_no'   => $request->string('passport_no'),
            'status'        => 'submitted',
        ]);

        return redirect()
            ->route('school.apply.thanks', $school->slug)
            ->with('status', 'Application submitted. We will contact you soon.');
    }

    public function thanks(School $school)
    {
        return view('school.apply-thanks', compact('school'));
    }
}
