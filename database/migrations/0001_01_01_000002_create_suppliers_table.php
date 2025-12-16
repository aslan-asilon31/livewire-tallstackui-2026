<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('created_by', 255)->nullable()->index();
            $table->string('updated_by', 255)->nullable()->index();
            $table->boolean('is_activated')->default(true);
            $table->integer('queued_at')->nullable();

            $table->timestamps();
        });

        Schema::create('supplier_purchases', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('supplier_id');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');

            $table->date('purchase_date');
            $table->decimal('total', 15, 2);
            $table->string('created_by', 255)->nullable()->index();
            $table->string('updated_by', 255)->nullable()->index();
            $table->boolean('is_activated')->default(true);
            $table->integer('queued_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('supplier_purchases');
    }
};
