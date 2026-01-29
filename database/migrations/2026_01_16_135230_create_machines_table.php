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
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('type');
            $table->text('description')->nullable();
            $table->string('location');
            $table->string('status')->default('active');
            $table->decimal('oee_target', 5, 2)->default(85.00);
            $table->boolean('alert_enabled')->default(false);
            $table->string('alert_email')->nullable();
            $table->string('alert_phone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machines');
    }
};
