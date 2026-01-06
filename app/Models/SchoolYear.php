<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SchoolYear extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'core_school_years';
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('master-data')
            ->setDescriptionForEvent(fn(string $eventName) => "Tahun Ajaran telah di-{$eventName}");
    }
}
