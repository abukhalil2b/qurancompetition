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
        Schema::create('judge_evaluations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_question_selection_id')->constrained()->cascadeOnDelete();

            $table->foreignId('evaluation_element_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('judge_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('achieved_point', 4, 1)->default(0);
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('judge_evaluations');
    }
};
