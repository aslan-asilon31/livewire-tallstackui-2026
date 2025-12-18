<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KpiSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // 1) KPI master
            $kpiId = DB::table('kpis')->insertGetId([
                'name' => 'Produktivitas',
                'description' => 'KPI umum untuk mengukur performa karyawan berdasarkan kualitas, kuantitas, ketepatan waktu, efisiensi, kepuasan pelanggan, dan growth.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2) KPI factors (bobot total 100)
            $factors = [
                [
                    'kpi_id' => $kpiId,
                    'name' => 'Kualitas',
                    'code' => 'QUALITY',
                    'weight' => 25.00,
                    'definition' => 'Seberapa baik tugas diselesaikan (akurasi, minim revisi, sesuai SOP).',
                ],
                [
                    'kpi_id' => $kpiId,
                    'name' => 'Kuantitas',
                    'code' => 'QUANTITY',
                    'weight' => 25.00,
                    'definition' => 'Jumlah output kerja yang diselesaikan (volume pekerjaan).',
                ],
                [
                    'kpi_id' => $kpiId,
                    'name' => 'Ketepatan Waktu',
                    'code' => 'TIMELINESS',
                    'weight' => 20.00,
                    'definition' => 'Kecepatan respons dan penyelesaian pekerjaan (SLA, lead time).',
                ],
                [
                    'kpi_id' => $kpiId,
                    'name' => 'Efektivitas & Efisiensi',
                    'code' => 'EFFICIENCY',
                    'weight' => 15.00,
                    'definition' => 'Pemakaian resource yang optimal untuk hasil terbaik (biaya/waktu/tenaga).',
                ],
                [
                    'kpi_id' => $kpiId,
                    'name' => 'Kepuasan Pelanggan',
                    'code' => 'CUSTOMER_SATISFACTION',
                    'weight' => 10.00,
                    'definition' => 'Tingkat kepuasan pelanggan terhadap layanan/produk.',
                ],
                [
                    'kpi_id' => $kpiId,
                    'name' => 'Pengembangan & Pembelajaran',
                    'code' => 'LEARNING_GROWTH',
                    'weight' => 5.00,
                    'definition' => 'Peningkatan kompetensi dan kontribusi improvement.',
                ],
            ];

            foreach ($factors as $f) {
                DB::table('kpi_factors')->insert([
                    ...$f,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
