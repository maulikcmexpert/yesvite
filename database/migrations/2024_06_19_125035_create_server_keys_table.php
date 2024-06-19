<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('server_keys', function (Blueprint $table) {
            $table->id();
            $table->string('firebase_key')->nullable();
            $table->string('google_client_id')->nullable();
            $table->string('google_secret_id')->nullable();
            $table->string('facebook_client_id')->nullable();
            $table->string('facebook_secret_id')->nullable();
            $table->string('apple_client_id')->nullable();
            $table->string('apple_secret_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('server_keys');
    }
};
