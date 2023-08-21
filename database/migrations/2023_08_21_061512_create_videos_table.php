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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('title');
            $table->string('disc');
            $table->string('video_path');
            $table->string('image_path');
            $table->string('hours')->nullable();
            $table->string('seconds')->nullable();
            $table->string('minutes')->nullable();
            $table->string('quality')->nullable();
            $table->boolean('processed');
            $table->boolean('longitudianl');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};