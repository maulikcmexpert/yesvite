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
        Schema::create('user_event_stories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_story_id')->nullable();
            $table->foreign('event_story_id')->references('id')->on('event_user_stories')->onDelete('cascade');
            $table->string('story')->nullable();
            $table->string('duration')->nullable();
            $table->enum('type', ['image', 'video'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_event_stories');
    }
};
