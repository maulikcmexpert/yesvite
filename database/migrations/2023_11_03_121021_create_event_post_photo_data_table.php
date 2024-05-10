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
        Schema::create('event_post_photo_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_post_photo_id')->nullable();
            $table->foreign('event_post_photo_id')->references('id')->on('event_post_photos')->onDelete('cascade');
            $table->string('post_media')->nullable();
            $table->enum('type', ['image', 'video', 'record'])->nullable();
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
        Schema::dropIfExists('event_post_photo_data');
    }
};
