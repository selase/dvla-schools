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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->index();
            $table->string('phone')->nullable();
            $table->string('ghana_card_no')->nullable();
            $table->string('passport_no')->nullable();
            $table->string('status')->default('submitted'); // submitted, accepted, rejected
            $table->text('notes')->nullable();   // internal reviewer notes
            $table->timestamps();

            $table->unique(['school_id','email']); // 1 application per school per email
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
