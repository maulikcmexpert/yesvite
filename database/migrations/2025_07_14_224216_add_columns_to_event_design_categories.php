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
        Schema::table('event_design_categories', function (Blueprint $table) {
            $table->enum('display_ad', ['0', '1'])->default('0')->after('category_name')->comment('1 => show ad, 0 => hide ad');
              });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_design_categories', function (Blueprint $table) {
            $table->enum('display_ad', ['0', '1'])->default('0')->after('category_name')->comment('1 => show ad, 0 => hide ad');
        });
    }
};
