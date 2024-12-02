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
        Schema::table('contact_sync', function (Blueprint $table) {
            //
            $table->string('firstName')->nullable()->after('name');
            $table->string('lastName')->nullable()->after('firstName');
            $table->enum('prefer_by', ['email', 'phone'])->default('phone')->after('photo');
            $table->enum('visible', ['0','1', '2', '3'])->default('0')->comment('1 = Only guests from event, 2 = No One 3 = Custom')->after('prefer_by');
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
