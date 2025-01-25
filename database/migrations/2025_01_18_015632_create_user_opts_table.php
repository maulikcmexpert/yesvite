<?php
// Create UserOpt model and migration
// Run the following commands in your Laravel project:
// php artisan make:model UserOpt -m

// Migration file: database/migrations/YYYY_MM_DD_create_user_opts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserOptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_opts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id')->nullable();
            $table->string('phone')->unique(); // Phone number of the user
            $table->boolean('opt_in_status')->default(false); // Opt-in status
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_opts');
    }
}
