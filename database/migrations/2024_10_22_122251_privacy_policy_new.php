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
        Schema::create('privacy_policy', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->enum('type', ['0', '1'])->default('0')->comment('0 = privacy policy ,1 = terms & condition');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
