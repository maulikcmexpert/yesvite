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
        Schema::table('user_report_to_posts', function (Blueprint $table) {
            $table->string('report_type')->nullable()->after('event_post_id');
            $table->string('report_description')->nullable()->after('report_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_report_to_posts', function (Blueprint $table) {
            //
        });
    }
};
