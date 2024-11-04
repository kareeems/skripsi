<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Item::create([
            'name' => 'Uang Makan',
            'amount' => 2000000,
            'type' => 'pondok',
        ]);

        Item::create([
            'name' => 'Asrama',
            'amount' => 1000000,
            'type' => 'pondok',
        ]);

        Item::create([
            'name' => 'Biaya Sekolah',
            'amount' => 5000000,
            'type' => 'sekolah',
        ]);
    }
}
