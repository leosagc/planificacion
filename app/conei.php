<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class conei extends Model
{
    protected $table = 'conei';
    
    protected $fillable = ['period', 'folder', 'binder', 'school_id', 'number'];

    public function schools()
    {
        return $this->belongsTo(School::class);
    }
}
