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
            'first_name' => 'Kareem',
            'last_name' => 'Ahmad',
            'email' => 'karem@student.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);

        User::create([
            'first_name' => 'Guru',
            'last_name' => 'Budi',
            'email' => 'budi@teacher.com',
            'password' => bcrypt('password'),
            'role' => 'teacher',
        ]);

        User::create([
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
    }
}
