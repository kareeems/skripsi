<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instalment extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'subtotal',
        'total',
        'due_date',
        'paid_at',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
    
    // Relasi banyak ke banyak dengan pembayaran
    public function payments()
    {
        return $this->belongsToMany(Payment::class, 'payment_instalment')
                    ->withPivot('amount')  // Menyimpan jumlah yang dibayar untuk instalmen ini
                    ->withTimestamps();
    }
}
