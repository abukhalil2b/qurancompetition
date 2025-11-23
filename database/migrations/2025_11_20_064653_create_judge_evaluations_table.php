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

            // Original relation - remains cascade (deleting an attempt deletes the evaluations)
            $table->foreignId('student_question_attempt_id')->constrained()->cascadeOnDelete();

            // MODIFIED: Use restrictOnDelete() to prevent deleting the EvaluationElement
            // if it has associated JudgeEvaluations.
            $table->foreignId('evaluation_element_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('judge_id')->constrained('users')->cascadeOnDelete();
            $table->integer('achieved_point')->default(0);
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
