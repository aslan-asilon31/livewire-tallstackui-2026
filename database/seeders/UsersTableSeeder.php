<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            ['name' => 'Satoshi Tanaka', 'email' => 'satoshi.tanaka@example.com'],
            ['name' => 'Yuki Nakamura', 'email' => 'yuki.nakamura@example.com'],
            ['name' => 'Haruto Suzuki', 'email' => 'haruto.suzuki@example.com'],
            ['name' => 'Sakura Yamamoto', 'email' => 'sakura.yamamoto@example.com'],
            ['name' => 'Riku Kobayashi', 'email' => 'riku.kobayashi@example.com'],
            ['name' => 'Aiko Matsumoto', 'email' => 'aiko.matsumoto@example.com'],
            ['name' => 'Daiki Shimizu', 'email' => 'daiki.shimizu@example.com'],
            ['name' => 'Hina Fujimoto', 'email' => 'hina.fujimoto@example.com'],
            ['name' => 'Kaito Watanabe', 'email' => 'kaito.watanabe@example.com'],
            ['name' => 'Mei Ito', 'email' => 'mei.ito@example.com'],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'), // password default
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
