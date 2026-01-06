<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes, LogsActivity;

    protected $guarded = ['id'];
    protected $guard_name = 'web';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_operator' => 'boolean',
    ];

    public function major()
    {
        return $this->belongsTo(Major::class);
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
            ->dontLogIfAttributesChangedOnly(['remember_token', 'password'])
            ->useLogName('system-user')
            ->setDescriptionForEvent(fn(string $eventName) => "User {$this->name} telah di-{$eventName}");
    }
}
