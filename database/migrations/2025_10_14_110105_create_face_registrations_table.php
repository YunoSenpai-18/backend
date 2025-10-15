<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('face_registrations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('instructor_id'); // Link to instructor
            $table->string('face_id')->unique();          // From frontend
            $table->string('name');                       // Instructor name
            $table->json('signature');                    // Array of numbers (64 length)
            $table->string('facial_image')->nullable();   // Stored image path
            $table->timestamp('registered_at')->nullable();
            $table->timestamps();

            $table->foreign('instructor_id')
                  ->references('id')
                  ->on('instructors')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('face_registrations');
    }
};