<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->nullable(); // misal document, image, pdf
            $table->string('file_url')->nullable(); // misal document, image, pdf
            $table->morphs('fileable'); // akan membuat fileable_id + fileable_type
            $table->string('file_type')->nullable(); // ekstensi atau tipe file
            $table->integer('is_activated')->default(1);
            $table->integer('queue_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
