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
        Schema::create('event_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id')->nullable();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('post_message')->nullable();
            $table->string('post_recording')->nullable();
            $table->enum('post_privacy', ['1', '2', '3', '4'])->default('1');
            $table->enum('post_type', ['0', '1', '2', '3'])->default('0');
            $table->enum('commenting_on_off', ['0', '1'])->default('0');
            $table->enum('is_in_photo_moudle', ['0', '1'])->default('0')->comment('0 = is not in photo module,1 = is in photo module');
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
        Schema::dropIfExists('event_posts');
    }
};
