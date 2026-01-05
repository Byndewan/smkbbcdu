<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DuTransactionFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'du_transaction_files';

    protected $guarded = ['id'];

}
