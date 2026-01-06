<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class Student extends Authenticatable
{
    use HasRoles, LogsActivity, Notifiable, SoftDeletes;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'nipd',
                'name',
                'email',
                'phone',
                'current_class_id',
                'is_active',
                'is_graduated',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => "Data Siswa ini telah di-{$eventName}");
    }
}
