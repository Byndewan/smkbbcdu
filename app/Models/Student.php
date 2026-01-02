<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
// Import Model yang baru dibuat
use App\Models\Major;
use App\Models\CoreClass;

class Student extends Authenticatable
{
    use HasRoles, Notifiable;

    protected $guard = 'student';
    protected $table = 'core_students';
    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id', 'id');
    }

    public function currentClass()
    {
        return $this->belongsTo(CoreClass::class, 'current_class_id', 'id');
    }

    public function prevClass()
    {
        return $this->belongsTo(CoreClass::class, 'prev_class_id', 'id');
    }
}
