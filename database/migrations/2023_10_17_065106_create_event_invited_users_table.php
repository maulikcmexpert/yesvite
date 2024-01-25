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
        Schema::create('event_invited_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id')->nullable();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('rsvp_status', ['0', '1'])->nullable();
            $table->integer('adults')->default(0);
            $table->integer('kids')->default(0);
            $table->text('message_to_host')->nullable();
            $table->string('message_by_video')->nullable();
            $table->enum('read', ['0', '1'])->default(0);
            $table->enum('rsvp_d', ['0', '1'])->default(0);
            $table->date('event_view_date')->nullable();
            $table->enum('invitation_sent', ['0', '1'])->default(0);
            $table->enum('is_co_host', ['0', '1'])->default(0);
            $table->enum('prefer_by', ['email', 'phone'])->default(0);
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
        Schema::dropIfExists('event_invited_users');
    }
};
