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
        Schema::create('sop_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sop_id')->constrained()->onDelete('cascade');
            $table->integer('urutan');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('gambar_path')->nullable();
            $table->string('cloudinary_id')->nullable();
            $table->boolean('is_checkpoint')->default(false);
            $table->boolean('needs_standard')->default(false);
            $table->decimal('nilai_standar', 10, 2)->nullable();
            $table->decimal('toleransi_min', 10, 2)->nullable();
            $table->decimal('toleransi_max', 10, 2)->nullable();
            $table->string('measurement_type')->nullable();
            $table->string('measurement_unit')->nullable();
            $table->integer('interval_value')->nullable();
            $table->string('interval_unit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sop_steps');
    }
};
