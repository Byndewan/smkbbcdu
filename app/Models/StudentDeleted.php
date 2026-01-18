<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentDeleted extends Model
{
    protected $table = 'core_students_deleted';
    protected $guarded = ['id'];
    public $timestamps = false;

    protected $casts = [
        'dob' => 'date',
        'deleted_at' => 'datetime',
    ];
}
