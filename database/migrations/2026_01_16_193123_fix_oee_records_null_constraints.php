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
        Schema::table('oee_records', function (Blueprint $table) {
            // Make columns nullable that should allow 0 values
            $table->integer('downtime_problems')->nullable()->change();
            $table->integer('downtime_maintenance')->nullable()->change();
            $table->integer('total_downtime')->nullable()->change();
            $table->integer('total_output')->nullable()->change();
            $table->integer('good_output')->nullable()->change();
            $table->integer('defect_count')->nullable()->change();
            $table->decimal('ideal_cycle_time', 8, 2)->nullable()->change();
            $table->decimal('availability_rate', 8, 4)->nullable()->change();
            $table->decimal('performance_rate', 8, 4)->nullable()->change();
            $table->decimal('quality_rate', 8, 4)->nullable()->change();
            $table->decimal('oee_score', 8, 4)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oee_records', function (Blueprint $table) {
            // Revert columns back to NOT NULL
            $table->integer('downtime_problems')->nullable(false)->change();
            $table->integer('downtime_maintenance')->nullable(false)->change();
            $table->integer('total_downtime')->nullable(false)->change();
            $table->integer('total_output')->nullable(false)->change();
            $table->integer('good_output')->nullable(false)->change();
            $table->integer('defect_count')->nullable(false)->change();
            $table->decimal('ideal_cycle_time', 8, 2)->nullable(false)->change();
            $table->decimal('availability_rate', 8, 4)->nullable(false)->change();
            $table->decimal('performance_rate', 8, 4)->nullable(false)->change();
            $table->decimal('quality_rate', 8, 4)->nullable(false)->change();
            $table->decimal('oee_score', 8, 4)->nullable(false)->change();
        });
    }
};
