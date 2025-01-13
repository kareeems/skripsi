<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference_id',
        'reference_type',
        'payment_method',
        'invoice_number',
        'amount',
        'status',
        'paid_at',
        'callback_data',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'callback_data' => 'array',
    ];

    // Relasi user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi polymorphic untuk reference_type dan reference_id
    public function reference()
    {
        return $this->morphTo();
    }

    // Relasi banyak ke banyak dengan instalmen menggunakan pivot table
    public function instalments()
    {
        return $this->belongsToMany(Instalment::class, 'payment_instalment')
                    ->withPivot('amount')  // Menyimpan jumlah yang dibayar untuk setiap instalmen
                    ->withTimestamps();
    }
}
