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
        Schema::create('oee_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_id')->constrained()->onDelete('cascade');
            $table->foreignId('shift_id')->constrained()->onDelete('cascade');
            $table->decimal('availability', 8, 4);
            $table->decimal('performance', 8, 4);
            $table->decimal('quality', 8, 4);
            $table->decimal('oee', 8, 4);
            $table->integer('planned_production_time');
            $table->integer('actual_production_time');
            $table->integer('downtime');
            $table->integer('total_produced');
            $table->integer('good_products');
            $table->integer('defective_products');
            $table->date('record_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oee_records');
    }
};
