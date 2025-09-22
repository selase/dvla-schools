<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SchoolsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\ChooseSchoolController;
use App\Http\Controllers\School\ApplicationController;
use App\Http\Controllers\School\ApplicationReviewController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'redirect.tenant.dashboard'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/choose-school', [ChooseSchoolController::class, 'show'])->name('choose-school.show');
    Route::post('/choose-school', [ChooseSchoolController::class, 'set'])->name('choose-school.set');

    // Tenant home (works when you have a slug):
    Route::get('/s/{school:slug}', function (\App\Models\School $school) {
        return view('school.home', compact('school'));
    })->middleware(['auth','ensure.school','school'])->name('school.home');

    // Optional: superadmin “view as” helpers
    Route::post('/admin/context/switch', [ChooseSchoolController::class, 'adminSwitch'])->name('admin.context.switch');
    Route::post('/admin/context/clear', [ChooseSchoolController::class, 'adminClear'])->name('admin.context.clear');
});


// Public application form (no auth), school context via slug
Route::get('/s/{school:slug}/apply', [ApplicationController::class, 'create'])
    ->middleware(['school'])
    ->name('school.apply.create');

Route::post('/s/{school:slug}/apply', [ApplicationController::class, 'store'])
    ->middleware(['school'])
    ->name('school.apply.store');

Route::get('/s/{school:slug}/apply/thanks', [ApplicationController::class, 'thanks'])
    ->middleware(['school'])
    ->name('school.apply.thanks');

// Admin review (auth + ensure.school + school)
Route::middleware(['auth','ensure.school','school'])->group(function () {
    Route::get('/s/{school:slug}/applications', [ApplicationReviewController::class, 'index'])
        ->name('school.applications.index');

    Route::post('/s/{school:slug}/applications/{application}/accept', [ApplicationReviewController::class, 'accept'])
        ->name('school.applications.accept');

    Route::post('/s/{school:slug}/applications/{application}/reject', [ApplicationReviewController::class, 'reject'])
        ->name('school.applications.reject');
});


// Route::prefix('admin')
//     ->middleware(['auth','role:superadmin'])
//     ->group(function () {
//         Route::get('/', DashboardController::class)->name('admin.dashboard');

//         // You can add global listings here; examples shown later
//         Route::get('/schools', [SchoolsController::class, 'index'])
//             ->name('admin.schools.index');
//         Route::get('/users', [\App\Http\Controllers\Admin\UsersController::class, 'index'])
//             ->name('admin.users.index');
//     });

    

require __DIR__.'/auth.php';
