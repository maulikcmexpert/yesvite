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
        Schema::create('event_post_poll_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_post_poll_id')->nullable();
            $table->foreign('event_post_poll_id')->references('id')->on('event_post_polls')->onDelete('cascade');
            $table->string('option')->nullable();
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
        Schema::dropIfExists('event_post_poll_options');
    }
};
