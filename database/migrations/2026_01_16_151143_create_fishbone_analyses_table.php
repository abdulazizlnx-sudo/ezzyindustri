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
        Schema::create('fishbone_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ng_report_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('quality_check_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');
            $table->text('problem_statement');
            $table->date('analysis_date');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('status')->default('draft');
            $table->string('cloudinary_url')->nullable();
            $table->string('cloudinary_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fishbone_analyses');
    }
};
