<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'type',
    ];

    /**
     * Relasi ke Transaction melalui tabel pivot TransactionItem.
     */
    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_items')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

}
