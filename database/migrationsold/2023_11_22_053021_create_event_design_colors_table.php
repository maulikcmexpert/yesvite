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
        Schema::create('event_design_colors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("event_design_id")->nullable();
            $table->foreign('event_design_id')->references('id')->on('event_designs')->onDelete('cascade');
            $table->string('event_design_color')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_design_colors');
    }
};
