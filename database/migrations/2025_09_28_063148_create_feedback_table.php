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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('checker_id');
            $table->foreign('checker_id')->references('id')->on('users');

            $table->text('message');

            $table->enum('status', ['Pending', 'Accepted', 'Declined'])->default('Pending');
            $table->text('admin_response')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
