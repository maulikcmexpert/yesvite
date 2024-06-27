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
        Schema::table('user_report_to_posts', function (Blueprint $table) {
            $table->unsignedBigInteger('post_media_id')->nullable();
            $table->foreign('post_media_id')->references('id')->on('event_post_images')->onDelete('cascade');
            $table->enum('specific_report', ['0', '1'])->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_report_to_posts', function (Blueprint $table) {
            //
        });
    }
};
