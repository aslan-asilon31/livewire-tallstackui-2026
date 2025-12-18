<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ja_JP');

        // Ambil KPI "Produktivitas" (pastikan KpiSeeder dijalankan dulu)
        $kpi = DB::table('kpis')->where('name', 'Produktivitas')->first();
        if (!$kpi) {
            throw new \RuntimeException('KPI "Produktivitas" belum ada. Jalankan KpiSeeder dulu.');
        }

        $factors = DB::table('kpi_factors')
            ->where('kpi_id', $kpi->id)
            ->get();

        if ($factors->isEmpty()) {
            throw new \RuntimeException('KPI factors belum ada. Jalankan KpiSeeder dulu.');
        }

        $names = [
            '山田 太郎 (Yamada Taro)',
            '佐藤 花子 (Sato Hanako)',
            '鈴木 一郎 (Suzuki Ichiro)',
            '高橋 美咲 (Takahashi Misaki)',
            '田中 翔 (Tanaka Sho)',
            '伊藤 彩 (Ito Aya)',
            '渡辺 健 (Watanabe Ken)',
            '中村 優 (Nakamura Yu)',
            '小林 美咲 (Kobayashi Misaki)',
            '加藤 涼 (Kato Ryo)',
            '吉田 真央 (Yoshida Mao)',
            '山本 陽菜 (Yamamoto Hina)',
            '石井 大輔 (Ishii Daisuke)',
            '松本 優奈 (Matsumoto Yuna)',
            '井上 拓海 (Inoue Takumi)',
            '木村 莉子 (Kimura Riko)',
            '林 蓮 (Hayashi Ren)',
            '斎藤 結衣 (Saito Yui)',
            '清水 大和 (Shimizu Yamato)',
            '森 咲良 (Mori Sakura)',
        ];

        // 16 bulan: Sep–Des 2024 + Jan–Des 2025
        $periodMonths = $this->buildMonths('2024-09-01', '2025-12-01');

        foreach ($names as $index => $name) {
            DB::transaction(function () use ($faker, $index, $name, $kpi, $factors, $periodMonths) {

                // Buat created_at user tersebar di rentang 2024-09..2025-12 juga
                $userCreatedAt = $this->randomDateTimeInMonth($faker, $periodMonths[array_rand($periodMonths)]);
                $userUpdatedAt = (clone $userCreatedAt)->addDays($faker->numberBetween(0, 20));

                // 1) users
                $userId = DB::table('users')->insertGetId([
                    'name' => $name,
                    'email' => 'user' . ($index + 1) . '@example.com',
                    'email_verified_at' => $userCreatedAt->copy()->addMinutes($faker->numberBetween(1, 600)),
                    'queue_number' => $index + 1,
                    'password' => Hash::make('password'),
                    'is_activated' => rand(0, 1),
                    'remember_token' => Str::random(10),
                    'created_at' => $userCreatedAt,
                    'updated_at' => $userUpdatedAt,
                ]);

                // 2) user_details (ikut tanggal user biar konsisten)
                DB::table('user_details')->insert([
                    'user_id' => $userId,
                    'phone' => $faker->phoneNumber(),
                    'address' => $faker->address(),
                    'birth_date' => $faker->date('Y-m-d', '2000-01-01'),
                    'gender' => $faker->randomElement(['male', 'female']),
                    'marital_status' => $faker->randomElement(['single', 'married']),
                    'created_at' => $userCreatedAt,
                    'updated_at' => $userUpdatedAt,
                ]);

                // 3) KPI per periode (16 bulan) + faktor scores + final_score
                foreach ($periodMonths as $monthStart) {
                    $year = (int) $monthStart->format('Y');
                    $month = (int) $monthStart->format('n');

                    $periodCreatedAt = $this->randomDateTimeInMonth($faker, $monthStart);
                    $periodUpdatedAt = (clone $periodCreatedAt)->addDays($faker->numberBetween(0, 10));

                    // insert user_kpi_periods dulu
                    $periodId = DB::table('user_kpi_periods')->insertGetId([
                        'user_id' => $userId,
                        'kpi_id' => $kpi->id,
                        'year' => $year,
                        'month' => $month,
                        'quarter' => null,
                        'final_score' => 0,
                        'created_at' => $periodCreatedAt,
                        'updated_at' => $periodUpdatedAt,
                    ]);

                    $weightedSum = 0.0;
                    $weightTotal = 0.0;

                    foreach ($factors as $factor) {
                        $score = $faker->randomFloat(2, 50, 100);

                        $factorCreatedAt = (clone $periodCreatedAt)->addMinutes($faker->numberBetween(1, 600));
                        $factorUpdatedAt = (clone $factorCreatedAt)->addMinutes($faker->numberBetween(0, 180));

                        DB::table('user_kpi_factor_scores')->insert([
                            'user_kpi_period_id' => $periodId,
                            'kpi_factor_id' => $factor->id,
                            'score' => $score,
                            'note' => null,
                            'created_at' => $factorCreatedAt,
                            'updated_at' => $factorUpdatedAt,
                        ]);

                        $weightedSum += ($score * (float) $factor->weight);
                        $weightTotal += (float) $factor->weight;
                    }

                    $finalScore = $weightTotal > 0 ? round($weightedSum / $weightTotal, 2) : 0;

                    DB::table('user_kpi_periods')
                        ->where('id', $periodId)
                        ->update([
                            'final_score' => $finalScore,
                            'updated_at' => $periodUpdatedAt,
                        ]);
                }
            });
        }
    }

    /**
     * Build list of month start Carbon instances (inclusive start..end).
     */
    private function buildMonths(string $startYmd, string $endYmd): array
    {
        $start = Carbon::parse($startYmd)->startOfMonth();
        $end = Carbon::parse($endYmd)->startOfMonth();

        $months = [];
        $cursor = $start->copy();

        while ($cursor->lte($end)) {
            $months[] = $cursor->copy();
            $cursor->addMonthNoOverflow();
        }

        return $months;
    }

    /**
     * Random datetime inside a given month (startOfMonth provided).
     */
    private function randomDateTimeInMonth($faker, Carbon $monthStart): Carbon
    {
        $start = $monthStart->copy()->startOfMonth();
        $end = $monthStart->copy()->endOfMonth();

        // random timestamp between start..end
        $randTs = $faker->numberBetween($start->timestamp, $end->timestamp);

        return Carbon::createFromTimestamp($randTs);
    }
}
