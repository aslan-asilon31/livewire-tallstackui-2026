<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class FileSeeder extends Seeder
{
    public function run(): void
    {
        $documents = [
            ['name' => '雇用契約書 (Koyou Keiyakusho) - Employment Contract', 'file_type' => 'pdf'],
            ['name' => '就業規則 (Shuugyou Kisoku) - Work Rules', 'file_type' => 'pdf'],
            ['name' => '給与明細 (Kyuuyo Meisai) - Payslip', 'file_type' => 'pdf'],
            ['name' => '人事評価 (Jinji Hyouka) - Performance Review', 'file_type' => 'pdf'],
            ['name' => '健康保険証 (Kenkou Hokenshou) - Health Insurance Card', 'file_type' => 'pdf'],
        ];

        $userIds = User::pluck('id')->toArray();

        // Seed documents
        foreach ($documents as $index => $doc) {
            $randomUserId = $userIds[array_rand($userIds)];

            DB::table('files')->insert([
                'name' => $doc['name'],
                'type' => 'document',
                'fileable_id' => $randomUserId,
                'fileable_type' => User::class,
                'file_type' => $doc['file_type'],
                'is_activated' => 1,
                'queue_number' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Seed avatars (1-5 png)
        foreach ($userIds as $userId) {
            $randomAvatar = rand(1, 5) . '.png';
            DB::table('files')->insert([
                'name' => 'Avatar ' . $randomAvatar,
                'type' => 'avatar',
                'fileable_id' => $userId,
                'fileable_type' => User::class,
                'file_type' => 'png',
                'is_activated' => 1,
                'queue_number' => 0,
                'created_at' => now(),
                'updated_at' => now(),
                'file_url' => 'user/avatar/' . $randomAvatar, // simpan path relatif dari public
            ]);
        }
    }
}
