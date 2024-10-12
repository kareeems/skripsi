<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { 
        User::create([
            'name' => 'Kareem',
            'email' => 'karem@student.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);

        User::create([
            'name' => 'Budi',
            'email' => 'budi@teacher.com',
            'password' => bcrypt('password'),
            'role' => 'teacher',
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
    }
}
