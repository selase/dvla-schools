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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained();
            $table->foreignId('student_id')->constrained();
            $table->foreignId('service_id')->constrained();
            $table->string('driver'); // paystack, mtn, etc.
            $table->unsignedBigInteger('amount'); // minor units
            $table->string('currency', 10)->default('GHS');
            $table->string('status')->default('initiated');
            $table->string('tx_ref')->unique();
            $table->string('provider_ref')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index(['school_id', 'student_id', 'service_id']);
            $table->index(['status', 'paid_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
