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
        Schema::create('evaluation_elements', function (Blueprint $table) {
            $table->id();
            $table->string('title');// التجويد- الوقف والابتداء- التفسير
            $table->enum('level', ['حفظ', 'حفظ وتفسير'])->default('حفظ');
            $table->smallInteger('max_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_elements');
    }
};
