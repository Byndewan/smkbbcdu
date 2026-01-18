<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use LogsActivity, HasRoles, Notifiable;

    protected $table = 'core_students';
    protected $guarded = ['id'];
    protected $guard_name = 'student';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_profile_completed' => 'boolean',
    ];

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'current_class_id');
    }

    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id');
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    public function scopeRestricted(Builder $query)
    {
        if (Auth::guard('web')->check() && Auth::user()->is_operator && Auth::user()->major_id) {
            return $query->where('major_id', Auth::user()->major_id);
        }
        return $query;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['remember_token', 'password'])
            ->useLogName('master-data')
            ->setDescriptionForEvent(fn(string $eventName) => "Data Siswa {$this->name} telah di-{$eventName}");
    }

    protected static function booted()
    {
        static::created(function ($student) {
            self::createBackup($student, 'create');
        });
        static::updated(function ($student) {
            if ($student->wasChanged()) {
                self::createBackup($student, 'update');
            }
        });
    }

    protected static function createBackup($student, $action)
    {
        $userId = Auth::guard('web')->id() ?? 0;

        \App\Models\StudentBackup::create([
            'student_id'       => $student->id,
            'nipd'             => $student->nipd,
            'name'             => $student->name,
            'email'            => $student->email,
            'phone'            => $student->phone,
            'gender'           => $student->gender,
            'pob'              => $student->pob,
            'address'          => $student->address,
            'dob'              => $student->dob,
            'photo_path'       => $student->photo_path,
            'current_class_id' => $student->current_class_id,
            'prev_class_id'    => $student->prev_class_id,
            'major_id'         => $student->major_id,
            'school_year_id'   => $student->school_year_id,
            'is_active'        => $student->is_active,
            'trigger_action'   => $action,
            'backup_by'        => $userId,
        ]);
    }
}
