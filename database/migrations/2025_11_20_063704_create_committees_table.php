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
        // imagine as classroom where judge and student meet to start the competition
        Schema::create('committees', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            
            $table->foreignId('center_id')->constrained()->cascadeOnDelete();

            $table->enum('gender', ['males','females'])->default('males');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('committees');
    }
};
