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
        Schema::create('instructors', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('instructor_id', 20)->unique();
            $table->string('course');
            $table->string('email')->unique();
            $table->string('phone', 15)->unique();
            $table->longText('photo')->nullable(); // store as Base64 or blob (depends on how you send it from React Native)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructors');
    }
};
