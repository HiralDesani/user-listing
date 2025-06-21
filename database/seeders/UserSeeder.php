<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chunkSize = 1000;
        $data = [];

        for ($i = 1; $i <= 50000; $i++) {
            $data[] = [
                'name' => "User $i",
                'email' => "user$i@gmail.com",
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($i % $chunkSize == 0) {
                User::insert($data);
                $data = [];
            }
        }

        if (!empty($data)) {
            User::insert($data);
        }
    }

}
