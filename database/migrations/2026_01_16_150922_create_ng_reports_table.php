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
        Schema::create('ng_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_id')->nullable()->constrained()->onDelete('set null');
            $table->date('date');
            $table->string('operator_name');
            $table->string('employee_id')->nullable();
            $table->string('machine_name');
            $table->string('shift');
            $table->string('batch_number')->nullable();
            $table->string('product_name');
            $table->string('product_code');
            $table->integer('total_production');
            $table->integer('total_ng');
            $table->decimal('ng_percentage', 5, 2);
            $table->string('ng_type');
            $table->string('ng_type_other')->nullable();
            $table->text('what')->nullable();
            $table->text('why')->nullable();
            $table->text('where')->nullable();
            $table->text('when')->nullable();
            $table->text('who')->nullable();
            $table->text('how')->nullable();
            $table->text('countermeasure')->nullable();
            $table->text('preventive_action')->nullable();
            $table->string('pic')->nullable();
            $table->string('verified_by')->nullable();
            $table->string('status')->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ng_reports');
    }
};
