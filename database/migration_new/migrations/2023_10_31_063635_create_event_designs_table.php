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
        Schema::create('event_designs', function (Blueprint $table) {
            $table->id();
            $table->string("design_template_name")->nullable();
            $table->unsignedBigInteger("event_design_category_id")->nullable();
            $table->foreign('event_design_category_id')->references('id')->on('event_design_categories')->onDelete('cascade');
            $table->unsignedBigInteger("event_design_subcategory_id")->nullable();
            $table->foreign('event_design_subcategory_id')->references('id')->on('event_design_sub_categories')->onDelete('cascade');
            $table->unsignedBigInteger("event_design_style_id")->nullable();
            $table->foreign('event_design_style_id')->references('id')->on('event_design_styles')->onDelete('cascade');
            $table->string("image")->nullable();
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
        Schema::dropIfExists('event_designs');
    }
};
