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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();

            $table->string('subject_code', 50);
            $table->string('subject', 255);
            $table->string('block', 50);
            $table->string('time', 50);   // can change later to time/datetime
            $table->string('day', 50);    // e.g. "Monday"
            $table->string('room', 5);

            // Foreign key: Instructor
            $table->unsignedBigInteger('instructor_id');
            $table->foreign('instructor_id')->references('id')->on('instructors')->onDelete('cascade');

            // Foreign key: Assigned Checker (User)
            $table->unsignedBigInteger('assigned_checker_id');
            $table->foreign('assigned_checker_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
