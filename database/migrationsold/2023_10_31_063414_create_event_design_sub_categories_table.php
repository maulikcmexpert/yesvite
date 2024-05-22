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
        Schema::create('event_design_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("event_design_category_id")->nullable();
            $table->foreign('event_design_category_id')->references('id')->on('event_design_categories')->onDelete('cascade');
            $table->string("subcategory_name")->nullable();
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
        Schema::dropIfExists('event_design_sub_categories');
    }
};
