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

            $table->time('start_time');
            $table->time('end_time');

            $table->string('day', 50);    // e.g. Monday

            // Foreign key: Instructor
            $table->unsignedBigInteger('instructor_id');
            $table->foreign('instructor_id')->references('id')->on('instructors')->onDelete('cascade');

            // Foreign key: Assigned Checker (User)
            $table->unsignedBigInteger('room_id');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');

            $table->timestamps();

            // Prevent exact duplicates
            $table->unique([
                'subject_code',
                'block',
                'day',
                'start_time',
                'end_time',
                'room_id',
                'instructor_id'
            ], 'unique_schedule');
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
