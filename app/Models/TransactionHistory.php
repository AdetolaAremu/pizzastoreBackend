<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
    use HasFactory;

    protected $casts = [
        'raw' => 'array'
    ];

    protected $fillable = [
        'user_id',
        'order_id',
        'transaction_reference',
        'transaction_type',
        'transaction_status',
        'email',
        'raw',
        'reason_for_transaction'
    ];
}
