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
        Schema::create('coin_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('event_id')->nullable();
            $table->unsignedBigInteger('user_subscription_id')->nullable();
            $table->enum('status',['0','1','2'])->default('0')->comment('0 = unused, 1 = used, 2 = expired');
            $table->enum('type',['credit','debit'])->default('credit');
            $table->bigInteger('coins')->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('current_balance')->nullable();
            $table->bigInteger('used_coins')->default(0);
            $table->string('endDate')->nullable();
            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('user_subscription_id')->references('id')->on('user_subscriptions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coin_transactions');
    }
};
