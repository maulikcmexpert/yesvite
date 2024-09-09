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
        Schema::create('user_report_chats', function (Blueprint $table) {
            $table->id()->primary();
            $table->unsignedBigInteger('reporter_user_id')->nullable();
            $table->foreign('reporter_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('to_be_reported_user_id')->nullable();
            $table->foreign('to_be_reported_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('conversation_id')->nullable();
            $table->string('report_type')->nullable();
            $table->string('report_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_report_chats');
    }
};
