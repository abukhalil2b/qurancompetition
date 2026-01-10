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
        Schema::create('tafseer_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tafseer_question_id')->constrained()->cascadeOnDelete();
            $table->foreignId('judge_id')->constrained('users');
            $table->decimal('score', 5, 2); // 0–10
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['tafseer_question_id', 'judge_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tafseer_evaluations');
    }
};
