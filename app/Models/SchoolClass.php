<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SchoolClass extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'core_classes';
    protected $guarded = ['id'];

    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'current_class_id');
    }

    public function scopeRestricted(Builder $query)
    {
        if (Auth::check() && Auth::user()->is_operator && Auth::user()->major_id) {
            return $query->where('major_id', Auth::user()->major_id);
        }
        return $query;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('master-data')
            ->setDescriptionForEvent(fn(string $eventName) => "Kelas telah di-{$eventName}");
    }
}
