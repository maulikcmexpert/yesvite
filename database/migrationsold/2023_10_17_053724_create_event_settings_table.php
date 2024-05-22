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
        Schema::create('event_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id')->default('0');
            $table->enum('allow_for_1_more', ['0', '1'])->default('0');
            $table->integer('allow_limit');
            $table->enum('adult_only_party', ['0', '1'])->default('0');
            $table->enum('rsvp_by_date_status', ['0', '1'])->default('0');
            $table->enum('thank_you_cards', ['0', '1'])->default('0');
            $table->enum('add_co_host', ['0', '1'])->default('0');
            $table->enum('gift_registry', ['0', '1'])->default('0');
            $table->enum('events_schedule', ['0', '1'])->default('0');
            $table->enum('event_wall', ['0', '1'])->default('0');
            $table->enum('guest_list_visible_to_guests', ['0', '1'])->default('0');
            $table->enum('podluck', ['0', '1'])->default('0');
            $table->enum('rsvp_updates', ['0', '1'])->default('0');
            $table->enum('event_updates', ['0', '1'])->default('0');
            $table->enum('send_event_dater_reminders', ['0', '1'])->default('0');
            $table->enum('request_event_photos_from_guests', ['0', '1'])->default('0');
            $table->timestamps();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_settings');
    }
};
