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
            $table->foreignId('committee_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('position')->default(1);
            $table->boolean('level')->default(1);
            $table->bigInteger('questionset_id')->unsigned()->nullable();
            $table->enum('student_status', ['registration', 'present', 'with_committee', 'withdraw', 'waiting_finalization', 'finish_competition'])->default('registration');
            // التسجيل في المسابقة -  تسجيل الحضور - مع اللجنة من أجل التقييم
            $table->timestamp('present_at')->nullable()->index();
            $table->decimal('final_score', 6, 2)->nullable()->index();
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
