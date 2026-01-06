<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Bill extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'du_bills';
    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'amount' => 'decimal:2',
    ];

    public function major()
    {
        return $this->belongsTo(Major::class, 'target_major_id');
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class, 'target_school_year_id');
    }

    public function requirements()
    {
        return $this->hasMany(BillRequirement::class, 'du_bill_id');
    }

    public function transactions()
    {
        return $this->hasMany(DuTransaction::class, 'du_bill_id');
    }

    public function scopeRestricted(Builder $query)
    {
        if (Auth::check() && Auth::user()->is_operator && Auth::user()->major_id) {
            return $query->where(function($q) {
                $q->where('target_major_id', Auth::user()->major_id)
                  ->orWhereNull('target_major_id');
            });
        }
        return $query;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('finance')
            ->setDescriptionForEvent(fn(string $eventName) => "Tagihan {$this->title} telah di-{$eventName}");
    }
}
