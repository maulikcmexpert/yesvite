<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_event_poll_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_post_poll_id')->nullable();
            $table->foreign('event_post_poll_id')->references('id')->on('event_post_polls')->onDelete('cascade');
            $table->unsignedBigInteger('event_poll_option_id')->nullable();
            $table->foreign('event_poll_option_id')->references('id')->on('event_post_poll_options')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_event_poll_data');
    }
};
