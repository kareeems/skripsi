<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

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

        $faker = Faker::create();

        for ($i = 1; $i <= 50; $i++) {
            Item::create([
                'name' => $faker->words(3, true),
                'amount' => $faker->numberBetween(1, 50) * 100000,
                'type' => $faker->randomElement(['pondok', 'sekolah']),
            ]);
        }
    }
}
