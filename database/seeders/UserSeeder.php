<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id' => 1,
                'name' => 'Admin',
                'username' => 'admin',
                'password' => bcrypt('1'),
                'role' => 'admin',
            ],[
                'id' => 2,
                'name' => 'Viki',
                'username' => 'viki',
                'password' => bcrypt('1'),
                'role' => 'user',
            ],[
                'id' => 3,
                'name' => 'Agib',
                'username' => 'agib',
                'password' => bcrypt('1'),
                'role' => 'user',
            ]
        ];

        foreach ($data as $key => $value) {
            User::create($value);
        }
    }
}
