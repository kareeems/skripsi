<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Contoh data transaksi
        $transactions = [
            [
                'user_id' => 5,
                'subtotal' => 1000.00,
                'total' => 1200.00,
                'status' => 'paid',
                'instalment' => 1,
            ]
        ];

        // Menambahkan data ke tabel transactions
        foreach ($transactions as $transaction) {
            Transaction::create($transaction);
        }
    }
}
