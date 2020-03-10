<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    const API_FIELDS = ['name', 'position_id', 'parent_id', 'salary', 'phone', 'email', 'employment_at', 'admin_created_id', 'admin_updated_id'];

    const FIELDS_CREATE = [
        'name' => '',
        'position_id' => null,
        'parent_id' => null,
        'salary' => null,
        'phone' => '',
        'email' => '',
        'employment_at' => '',
    ];

    const FILE_PATH = '/uploads/employees/';

    protected $guarded = [];

    /*
     * Mutator
     */

    public function getPhotoAttribute()
    {
        if($this->attributes['photo']){
            return self::FILE_PATH . $this->attributes['photo'];
        }

        return null;
    }

    /*
     * Relation
     */
    public function position()
    {
        return $this->hasOne(Position::class, 'id', 'position_id');
    }

    public function parent()
    {
        return $this->belongsTo(Employee::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(Employee::class, 'parent_id', 'id');
    }
}
