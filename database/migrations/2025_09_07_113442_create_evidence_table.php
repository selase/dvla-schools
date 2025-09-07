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
        Schema::create('evidences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained();
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id');
            $table->string('type');
            $table->string('path');
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['school_id', 'entity_type', 'entity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evidence');
    }
};
