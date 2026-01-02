<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DuTransaction extends Model
{
    protected $table = 'du_transactions';

    protected $guarded = ['id'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
