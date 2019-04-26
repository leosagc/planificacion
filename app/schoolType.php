<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class schoolType extends Model
{
    protected $table = 'school_type';

    public function schools()
    {
        return $this->hasMany(School::class);
    }

    /**
     * Scope a query to only include the school type given
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeSchoolType($query, $value)
    {
        $query->where('name', $value);
    }
}
