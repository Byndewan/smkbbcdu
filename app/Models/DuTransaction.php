<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DuTransaction extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'du_transactions';
    protected $guarded = ['id'];

    protected $casts = [
        'payment_date' => 'datetime',
        'payment_expiry_time' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function bill()
    {
        return $this->belongsTo(Bill::class, 'du_bill_id');
    }

    public function files()
    {
        return $this->hasMany(DuTransactionFile::class, 'du_transaction_id');
    }

    public function scopeRestricted(Builder $query)
    {
        if (Auth::check() && Auth::user()->is_operator && Auth::user()->major_id) {
            return $query->whereHas('student', function ($q) {
                $q->where('major_id', Auth::user()->major_id);
            });
        }
        return $query;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('transaction')
            ->setDescriptionForEvent(fn(string $eventName) => "Transaksi #{$this->trx_code} telah di-{$eventName}");
    }
}
