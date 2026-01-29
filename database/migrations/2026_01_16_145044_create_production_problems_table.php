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
        Schema::create('production_problems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_id')->constrained()->onDelete('cascade');
            $table->string('problem_type');
            $table->text('notes')->nullable();
            $table->string('image_path')->nullable();
            $table->string('cloudinary_url')->nullable();
            $table->string('cloudinary_id')->nullable();
            $table->string('status')->default('waiting');
            $table->timestamp('reported_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->integer('duration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_problems');
    }
};
