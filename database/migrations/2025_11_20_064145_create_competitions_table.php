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
        // let this table be as attendnace records for competition and finalization.
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('center_id');
            $table->bigInteger('stage_id');
            $table->foreignId('committee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('position')->default(1);
            $table->enum('level',['memorize','memorize_with_tafseer'])->default('memorize');
            $table->bigInteger('questionset_id')->unsigned()->nullable();
            $table->enum('student_status', ['present', 'with_committee', 'withdraw', 'waiting_finalization', 'finish_competition'])->default('present');
            $table->decimal('final_score', 6, 2)->nullable()->index();
            $table->decimal('memorization_score', 6, 2)->nullable()->index();
            $table->decimal('tafseer_score', 6, 2)->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('committee_students');
    }
};
