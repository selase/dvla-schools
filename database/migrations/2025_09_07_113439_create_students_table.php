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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('school_id')->constrained();
            $table->string('ghana_card_no')->nullable();
            $table->string('passport_no')->nullable();
            $table->string('status')->default('registered');
            $table->timestamp('tuition_paid_at')->nullable();
            $table->string('learner_license_no')->nullable();
            $table->timestamp('learner_license_verified_at')->nullable();
            $table->timestamps();

            $table->index(['school_id', 'status']);
            $table->index('learner_license_no');
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
