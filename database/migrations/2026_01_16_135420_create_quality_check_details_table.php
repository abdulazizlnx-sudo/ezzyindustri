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
        Schema::create('quality_check_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quality_check_id')->constrained()->onDelete('cascade');
            $table->string('parameter');
            $table->decimal('standard_value', 10, 6);
            $table->decimal('measured_value', 10, 6);
            $table->decimal('tolerance_min', 10, 6);
            $table->decimal('tolerance_max', 10, 6);
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_check_details');
    }
};
