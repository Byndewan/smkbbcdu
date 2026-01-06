<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillRequirement extends Model
{
    protected $table = 'du_bill_requirements';
    protected $guarded = ['id'];

    public function bill()
    {
        return $this->belongsTo(Bill::class, 'du_bill_id');
    }
}
