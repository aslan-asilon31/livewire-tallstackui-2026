<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('procurements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('supplier_id');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');

            $table->date('order_date');
            $table->string('status')->default('pending'); // pending, received, canceled
            $table->decimal('total', 15, 2);
            $table->string('created_by', 255)->nullable()->index();
            $table->string('updated_by', 255)->nullable()->index();
            $table->boolean('is_activated')->default(true);
            $table->integer('queued_at')->nullable();
            $table->timestamps();
        });

        Schema::create('procurement_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('procurement_id');
            $table->foreign('procurement_id')->references('id')->on('procurements')->onDelete('cascade');

            $table->uuid('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->integer('quantity');
            $table->decimal('price', 15, 2);
            $table->string('created_by', 255)->nullable()->index();
            $table->string('updated_by', 255)->nullable()->index();
            $table->boolean('is_activated')->default(true);
            $table->integer('queued_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procurements');
        Schema::dropIfExists('procurement_items');
    }
};
