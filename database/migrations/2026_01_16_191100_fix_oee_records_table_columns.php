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
            // Add missing columns that the model expects
            $table->date('date')->after('shift_id');
            $table->integer('operating_time')->after('planned_production_time');
            $table->integer('downtime_problems')->after('operating_time');
            $table->integer('downtime_maintenance')->after('downtime_problems');
            $table->integer('total_downtime')->after('downtime_maintenance');
            $table->integer('total_output')->after('total_downtime');
            $table->integer('good_output')->after('total_output');
            $table->integer('defect_count')->after('good_output');
            $table->decimal('ideal_cycle_time', 8, 2)->after('defect_count');
            $table->decimal('availability_rate', 8, 4)->after('ideal_cycle_time');
            $table->decimal('performance_rate', 8, 4)->after('availability_rate');
            $table->decimal('quality_rate', 8, 4)->after('performance_rate');
            $table->decimal('oee_score', 8, 4)->after('quality_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oee_records', function (Blueprint $table) {
            // Drop the new columns
            $table->dropColumn(['date', 'operating_time', 'downtime_problems', 'downtime_maintenance', 'total_downtime', 'total_output', 'good_output', 'defect_count', 'ideal_cycle_time', 'availability_rate', 'performance_rate', 'quality_rate', 'oee_score']);
        });
    }
};
