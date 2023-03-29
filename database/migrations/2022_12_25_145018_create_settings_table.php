<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('first_reward')->length(8, 2)->nullable();
            $table->decimal('level_1_reward')->length(8, 2)->nullable();
            $table->decimal('level_2_reward')->length(8, 2)->nullable();
            $table->decimal('level_3_reward')->length(8, 2)->nullable();
            $table->decimal('order_fee')->length(8, 2)->nullable();
            $table->decimal('withdrawal_fee')->length(8, 2)->nullable();
            $table->decimal('jackpot_odds')->length(8, 2)->nullable();
            $table->decimal('reward_threshold')->length(8, 2)->nullable();
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
        Schema::dropIfExists('settings');
    }
}
