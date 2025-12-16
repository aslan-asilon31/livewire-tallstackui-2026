<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');

            $table->date('sale_date');
            $table->decimal('total', 15, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->string('status')->default('completed');
            $table->string('created_by', 255)->nullable()->index();
            $table->string('updated_by', 255)->nullable()->index();
            $table->boolean('is_activated')->default(true);
            $table->integer('queued_at')->nullable();
            $table->timestamps();
        });


        Schema::create('sale_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sale_id');
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');

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

        Schema::create('promotions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->decimal('discount_value', 10, 2);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('created_by', 255)->nullable()->index();
            $table->string('updated_by', 255)->nullable()->index();
            $table->boolean('is_activated')->default(true);
            $table->integer('queued_at')->nullable();

            $table->timestamps();
        });

        Schema::create('customer_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');

            $table->uuid('sale_id');
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');

            $table->decimal('amount', 15, 2);
            $table->string('type'); // payment, refund
            $table->string('created_by', 255)->nullable()->index();
            $table->string('updated_by', 255)->nullable()->index();
            $table->boolean('is_activated')->default(true);
            $table->integer('queued_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('promotions');
        Schema::dropIfExists('customer_transactions');
    }
};
