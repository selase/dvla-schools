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
        Schema::create('regulatory_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_user_id')->constrained('users');
            $table->string('scope');   // slot, student, payment
            $table->unsignedBigInteger('record_id');
            $table->string('action');  // approve, reject, flag
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['scope', 'record_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regulatory_actions');
    }
};
