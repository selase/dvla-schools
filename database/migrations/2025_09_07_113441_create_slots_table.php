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
        Schema::create('slot_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained();
            $table->foreignId('requested_by')->constrained('users');
            $table->string('status')->default('draft');
            $table->unsignedInteger('count')->default(0);
            $table->timestamps();
        });

        Schema::create('slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained();
            $table->foreignId('student_id')->constrained();
            $table->foreignId('batch_id')->nullable()->constrained('slot_batches');
            $table->string('status')->default('draft'); // requested, verifying, approved, rejected, used
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->string('rejection_reason')->nullable();
            $table->uuid('token')->unique()->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['school_id', 'status']);
            $table->index('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slots');
        Schema::dropIfExists('slot_batches');
    }
};
