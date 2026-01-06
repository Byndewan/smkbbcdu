<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DuBill extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'du_bills';

    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'title',
                'amount',
                'start_date',
                'end_date',
                'is_active',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => "Tagihan ini telah di-{$eventName}");
    }

    public function bill()
    {
        return $this->belongsTo(DuBill::class, 'du_bill_id');
    }
}
