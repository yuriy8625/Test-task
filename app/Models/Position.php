<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $guarded = [];

    /*
     * Relation
     */
    public function employe()
    {
        return $this->belongsTo(Employee::class, 'position_id', 'id');
    }
}
