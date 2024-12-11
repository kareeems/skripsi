<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'subtotal',
        'total',
        'status',
        'instalment',
    ];

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Item melalui tabel pivot TransactionItem.
     */
    public function items()
    {
        return $this->belongsToMany(Item::class, 'transaction_items')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
    
    public function instalments()
    {
        return $this->hasMany(Instalment::class);
    }
}
