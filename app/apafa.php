<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class apafa extends Model
{
    protected $table = 'apafa';

    protected $fillable = ['period', 'folder', 'binder', 'school_id', 'number'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
