<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 1. Wajib Soft Delete
use Spatie\Activitylog\Traits\LogsActivity; // 2. Wajib Activity Log
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Major extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'core_majors';
    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('master-data')
            ->setDescriptionForEvent(fn(string $eventName) => "Jurusan telah di-{$eventName}");
    }

    public function scopeRestricted(Builder $query)
    {
        if (Auth::check() && Auth::user()->is_operator && Auth::user()->major_id) {
            return $query->where('id', Auth::user()->major_id);
        }
        return $query;
    }
}
