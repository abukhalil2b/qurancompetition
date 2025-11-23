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
        // Questions Package we prefer name it as questionsets
        Schema::create('questionsets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('branch');
            $table->boolean('selected')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionsets');
    }
};
