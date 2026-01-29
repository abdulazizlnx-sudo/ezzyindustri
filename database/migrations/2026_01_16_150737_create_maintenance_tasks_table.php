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
        Schema::create('maintenance_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_name');
            $table->text('description')->nullable();
            $table->string('maintenance_type');
            $table->string('frequency');
            $table->foreignId('machine_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('requires_photo')->default(false);
            $table->string('standard_value')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('shift_ids')->nullable();
            $table->time('preferred_time')->nullable();
            $table->json('schedule_config')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_tasks');
    }
};
