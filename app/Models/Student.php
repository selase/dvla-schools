<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSchool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use BelongsToSchool;
    use HasFactory;

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'user_id',
        'school_id',
        'ghana_card_no',
        'passport_no',
        'status',
        'tuition_paid_at',
        'learner_license_no',
        'learner_license_verified_at',
    ];
}
