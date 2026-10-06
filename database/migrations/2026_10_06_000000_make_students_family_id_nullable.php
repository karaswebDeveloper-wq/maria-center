<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['family_id']);
            $table->foreignId('family_id')->nullable()->change();
            $table->foreign('family_id')->references('id')->on('families')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['family_id']);
            $table->foreignId('family_id')->nullable(false)->change();
            $table->foreign('family_id')->references('id')->on('families')->restrictOnDelete();
        });
    }
};
