<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'school_id','first_name','last_name','email','phone',
        'ghana_card_no','passport_no','status','notes',
    ];

    public function school(){ 
        return $this->belongsTo(School::class); 
    }
}
