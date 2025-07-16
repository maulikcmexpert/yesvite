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
        Schema::table('events', function (Blueprint $table) {
            $table->unsignedBigInteger('template_id')->default(null);
            $table->foreign('template_id')->references('id')->on('event_design_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('template_id')->default(null);
            $table->foreign('template_id')->references('id')->on('text_data')->onDelete('cascade');
        });
    }
};
