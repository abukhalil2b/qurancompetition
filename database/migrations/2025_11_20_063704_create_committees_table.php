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
            
            $table->bigInteger('stage_id');

            $table->foreignId('center_id')->constrained()->cascadeOnDelete();

            $table->enum('gender', ['males','females'])->default('males');

            $table->boolean('active')->default(false);
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
