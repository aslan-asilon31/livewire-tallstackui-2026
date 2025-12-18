<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MASTER KPI (template)
        Schema::create('kpis', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // contoh: "Produktivitas"
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // FAKTOR PENENTU KPI (per KPI)
        Schema::create('kpi_factors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kpi_id')->constrained('kpis')->cascadeOnDelete();
            $table->string('name');        // contoh: "Quality"
            $table->string('code')->nullable(); // contoh: QUALITY, QUANTITY, TIMELINESS
            $table->decimal('weight', 5, 2)->default(0); // bobot persen, contoh 25.00
            $table->text('definition')->nullable();      // definisi faktor
            $table->timestamps();

            $table->unique(['kpi_id', 'name']);
        });

        // DETAIL USER (tanpa kpi_id di sini, karena KPI itu per periode penilaian)
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('marital_status')->nullable();
            $table->timestamps();
        });

        // PERIODE KPI PER USER (tempat final score disimpan)
        Schema::create('user_kpi_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kpi_id')->constrained('kpis')->cascadeOnDelete();

            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month')->nullable();   // kalau bulanan
            $table->unsignedTinyInteger('quarter')->nullable(); // kalau kuartal

            $table->decimal('final_score', 6, 2)->default(0); // skor akhir KPI
            $table->timestamps();

            $table->index(['user_id', 'kpi_id', 'year']);
        });

        // NILAI PER FAKTOR (ini yang jadi bahan hitung final score)
        Schema::create('user_kpi_factor_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_kpi_period_id')->constrained('user_kpi_periods')->cascadeOnDelete();
            $table->foreignId('kpi_factor_id')->constrained('kpi_factors')->cascadeOnDelete();

            $table->decimal('score', 6, 2)->default(0); // 0-100
            $table->text('note')->nullable();           // catatan penilai
            $table->timestamps();

            $table->unique(['user_kpi_period_id', 'kpi_factor_id']);
        });


        // Faktor penentu KPI :
        // kpi_factors: misal KPI “Produktivitas” punya faktor:
        // Quality (bobot 25%)
        // Quantity (bobot 25%)
        // Timeliness (bobot 20%)
        // Efficiency (bobot 15%)
        // Customer Satisfaction (bobot 10%)
        // Learning & Growth (bobot 5%)

        // Penilaian user per periode
        // user_kpi_periods: satu baris per user per periode (bulan/kuartal/tahun)
        // user_kpi_factor_scores: isi nilai faktor (0–100) per periode
        // user_kpi_periods.final_score: hasil hitung akhir KPI
    }

    public function down(): void
    {
        Schema::dropIfExists('user_kpi_factor_scores');
        Schema::dropIfExists('user_kpi_periods');
        Schema::dropIfExists('user_details');
        Schema::dropIfExists('kpi_factors');
        Schema::dropIfExists('kpis');
    }
};
