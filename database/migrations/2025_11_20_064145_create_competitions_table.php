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
        // let this table be as attendnace records for competition.
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('center_id');
            $table->bigInteger('stage_id');
            $table->foreignId('committee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->bigInteger('questionset_id')->unsigned()->nullable();
            $table->enum('student_status', ['present', 'with_committee', 'withdraw', 'finish_competition'])->default('present');
            $table->decimal('grand_total',4,1)->default(0);
            $table->integer('judge_count')->default(0);
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
