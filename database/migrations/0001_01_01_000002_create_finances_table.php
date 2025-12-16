<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('finances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type'); // income, expense
            $table->string('category')->nullable(); // kas/bank, gaji, dll
            $table->decimal('amount', 15, 2);
            $table->date('date');
            $table->string('description')->nullable();
            $table->string('created_by', 255)->nullable()->index();
            $table->string('updated_by', 255)->nullable()->index();
            $table->boolean('is_activated')->default(true);
            $table->integer('queued_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finances');
    }
};
