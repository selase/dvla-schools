<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('instructors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('primary_school_id')->nullable()->constrained('schools');
            $table->boolean('is_locum')->default(false);
            $table->timestamps();
        });

        Schema::create('instructor_school', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained();
            $table->foreignId('school_id')->constrained();
            $table->unique(['instructor_id', 'school_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructors');
        Schema::dropIfExists('instructor_school');
    }
};
