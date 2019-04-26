<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class district extends Model
{
    protected $table = 'district';

    public function schools()
    {
        return $this->hasMany(School::class);
    }
}
