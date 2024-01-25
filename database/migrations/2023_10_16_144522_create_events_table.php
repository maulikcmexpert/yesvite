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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_type_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('event_name')->nullable();
            $table->string('hosted_by')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('rsvp_by_date')->nullable();
            $table->string('rsvp_start_time')->nullable();
            $table->string('rsvp_start_timezone')->nullable();
            $table->enum('rsvp_end_time_set', ['0', '1'])->default('0');
            $table->string('rsvp_end_time')->nullable();
            $table->string('rsvp_end_timezone')->nullable();
            $table->string('event_location_name')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->text('address_1')->nullable();
            $table->text('address_2')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('city')->nullable();
            $table->text('message_to_guests')->nullable();
            $table->string('greeting_card_id')->nullable();
            $table->string('gift_registry_id')->nullable();
            $table->timestamps();

            $table->foreign('event_type_id')->references('id')->on('event_types')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
};
