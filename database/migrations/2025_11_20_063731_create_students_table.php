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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('gender', ['male','female'])->default('male');
            $table->string('phone', 8)->nullable()->unique();// local phone number = 8 digit
            $table->string('national_id', 11)->nullable()->unique();// max digit = 11
            $table->string('nationality');
            $table->date('dob');
            $table->string('age')->nullable();
            $table->string('state');
            $table->string('wilaya');
            $table->string('qarya')->nullable();
            $table->string('branch')->nullable();
            $table->date('registration_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
