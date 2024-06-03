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
        Schema::create('version_settings', function (Blueprint $table) {
            $table->id();
            $table->string('android_version')->nullable();
            $table->enum('android_in_force', ['0', '1'])->default('0');
            $table->string('ios_version')->nullable();
            $table->enum('ios_in_force', ['0', '1'])->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('version_settings');
    }
};
