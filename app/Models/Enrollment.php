<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSchool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use BelongsToSchool;
    use HasFactory;

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'school_id',
        'student_id',
        'course_id',
        'progress_pct',
        'cum_score',
        'completed_at',
    ];
}
