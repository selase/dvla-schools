<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('slot_batches', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained();
            $table->foreignId('requested_by')->constrained('users');
            $table->string('status')->default('draft');
            $table->unsignedInteger('count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slot_batches');
    }
};
