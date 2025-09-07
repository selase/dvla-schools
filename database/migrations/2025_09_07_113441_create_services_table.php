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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained();
            $table->string('name');
            $table->string('code');
            $table->unsignedInteger('baseline_fee'); // in minor units (e.g., pesewas)
            $table->boolean('active')->default(true);
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['school_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
