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
        Schema::create('event_design_category', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('event_design', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_design_category_id')->index();
            $table->string('template_name');
            $table->string('image');
            $table->timestamps();

            $table->foreign('event_design_category_id')->references('id')->on('event_design_category');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_design_category');
        Schema::dropIfExists('event_design');
    }
};
