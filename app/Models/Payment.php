<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_id',
        'payment_method',
        'invoice_number',
        'amount',
        'status',
        'paid_at',
        'callback_data',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];
}
