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
        Schema::create('text_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_design_id')->nullable();
            $table->foreign('event_design_id')->references('id')->on('event_design_categories')->onDelete('cascade');
            $table->json('static_information');
            $table->string('image');
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('text_data');
    }
};
