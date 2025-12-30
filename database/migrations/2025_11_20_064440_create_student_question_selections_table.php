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
        Schema::create('student_question_selections', function (Blueprint $table) {
            $table->id();
             $table->bigInteger('center_id');
            $table->bigInteger('stage_id');
            $table->bigInteger('committee_id');
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->foreignId('questionset_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->enum('level', ['حفظ','حفظ وتفسير'])->default('حفظ');
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->decimal('total_element_evaluation', 6,2)->default(0)->comment('Total score from all judges for this question selection');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_question_selections');
    }
};
