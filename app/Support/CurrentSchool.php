<?php

namespace App\Support;

use App\Models\School;

class CurrentSchool
{
    public static ?School $school = null;

    public static function set(?School $school): void
    {
        self::$school = $school;
    }

    public static function get(): ?School
    {
        return self::$school;
    }
}
