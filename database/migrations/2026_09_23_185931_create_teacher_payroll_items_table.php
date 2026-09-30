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
        Schema::create('teacher_payroll_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained('teacher_payrolls')->restrictOnDelete();
            $table->foreignId('teacher_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('students_count');
            $table->decimal('fee_amount', 12, 2);
            $table->decimal('support_amount', 12, 2);
            $table->decimal('gross_amount', 12, 2);
            $table->decimal('center_percentage', 5, 2);
            $table->decimal('center_amount', 12, 2);
            $table->decimal('teacher_amount', 12, 2);
            $table->timestamps();

            $table->unique(['payroll_id', 'teacher_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_payroll_items');
    }
};
