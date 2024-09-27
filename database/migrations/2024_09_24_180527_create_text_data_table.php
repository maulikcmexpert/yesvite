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
            $table->unsignedBigInteger('event_design_category_id')->nullable();
            $table->foreign('event_design_category_id')->references('id')->on('event_design_categories')->onDelete('cascade');
            $table->unsignedBigInteger('event_design_sub_category_id')->nullable();
            $table->foreign('event_design_sub_category_id')->references('id')->on('event_design_sub_categories')->onDelete('cascade');
            $table->json('static_information')->nullable();
            $table->string('image')->nullable();
            $table->string('filled_image')->nullable();
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
