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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id')->nullable();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->unsignedBigInteger('post_id')->nullable();
            $table->foreign('post_id')->references('id')->on('event_posts')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('notification_type')->nullable(); //'invite', 'upload_post', 'like', 'comment', 'reply', 'poll', 'rsvp'
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('notification_message')->nullable();
            $table->enum('rsvp_status', ['0', '1'])->nullable();
            $table->integer('kids')->default(0);
            $table->integer('adults')->default(0);
            $table->enum('read', ['0', '1'])->default('0');
            $table->unsignedBigInteger('comment_id')->nullable();
            $table->foreign('comment_id')->references('id')->on('event_post_comments')->onDelete('cascade');
            $table->string('rsvp_attempt')->nullable();
            $table->unsignedBigInteger('user_potluck_item_id')->nullable();
            $table->foreign('user_potluck_item_id')->references('id')->on('user_potluck_items')->onDelete('cascade');
            $table->string('user_potluck_item_count')->default("0");
            $table->string('from_addr')->nullable();
            $table->string('to_addr')->nullable();
            $table->string('from_time')->nullable();
            $table->string('to_time')->nullable();
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
        Schema::dropIfExists('notifications');
    }
};
