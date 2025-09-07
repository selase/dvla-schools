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
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained();
            $table->foreignId('student_id')->nullable()->constrained();
            $table->foreignId('instructor_id')->nullable()->constrained();
            $table->foreignId('course_id')->nullable()->constrained();
            $table->string('photo_path');
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->timestamp('captured_at');
            $table->json('exif')->nullable();
            $table->enum('liveness_result', ['unknown', 'pass', 'fail'])->default('unknown');
            $table->unsignedInteger('distance_m_from_school')->default(0);
            $table->enum('verdict', ['valid', 'suspicious', 'invalid'])->default('valid');
            $table->timestamps();

            $table->index(['school_id', 'course_id', 'captured_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};
