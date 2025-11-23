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

            // 1. Title is required and indexed for searching/uniqueness (e.g., within a parent)
            $table->string('title');

            // 2. Branch: Use ENUM or a defined set of string values. Nullable if it's a child element.
            // Example branches: 'tafsir_hifz', 'hifz_only'
            $table->enum('branch', ['تفسير وحفظ', 'فقط حفظ'])->nullable();

            // 3. Max Score: Use integer/smallInteger, nullable for parent headers.
            $table->smallInteger('max_score')->nullable();

            // 4. Parent ID: Correct self-referencing foreign key with proper constraints.
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('evaluation_elements') // References the same table
                ->cascadeOnDelete(); // If a header is deleted, its children are deleted

            // 5. Order: Use an index for efficient sorting.
            $table->smallInteger('order')->default(0)->index();

            // Optional: Add unique constraint to ensure 'title' is unique within the same parent
            $table->unique(['title', 'parent_id']);

            $table->timestamps();
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
