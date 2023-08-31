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
        
        Schema::create('event', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('event_category_id')->index();
            $table->string('name');
            $table->string('profile');
            $table->date('start_date');
            $table->time('start_time', $precision = 0);
            $table->date('end_date');
            $table->time('end_time', $precision = 0);
            $table->text('description');
            $table->text('message');
            $table->text('location');
            $table->string('latitude');
            $table->string('longitude');            
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('event_category_id')->references('id')->on('event_category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event');
    }
};
