<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DuTransactionFile extends Model
{
    protected $table = 'du_transaction_files';
    protected $guarded = ['id'];

    public function transaction()
    {
        return $this->belongsTo(DuTransaction::class, 'du_transaction_id');
    }

    public function requirement()
    {
        return $this->belongsTo(BillRequirement::class, 'du_bill_requirement_id');
    }
}
