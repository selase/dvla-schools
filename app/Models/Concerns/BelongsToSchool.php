<?php

namespace App\Models\Concerns;

use App\Support\CurrentSchool;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToSchool
{
    protected static function bootBelongsToSchool(): void
    {
        static::creating(function ($model) {
            if (property_exists($model, 'fillable') && in_array('school_id', $model->getFillable(), true)) {
                $model->school_id ??= CurrentSchool::get()?->id;
            } elseif (isset($model->school_id)) {
                $model->school_id ??= CurrentSchool::get()?->id;
            }
        });

        static::addGlobalScope('school', function (Builder $builder) {
            if ($school = CurrentSchool::get()) {
                $builder->where($builder->getModel()->getTable() . '.school_id', $school->id);
            }
        });
    }
}
