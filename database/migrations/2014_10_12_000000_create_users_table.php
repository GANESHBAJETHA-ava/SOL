<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('phone')->length(20)->unique();            
            $table->double('balance')->length(10, 2)->default(0);
            $table->double('loss_reward_balance')->length(10, 2)->default(0);
            $table->double('commission')->length(10, 2)->default(0);
            $table->double('interest')->length(10, 2)->default(0);
            $table->string('email')->length(40)->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->length(200);
            $table->string('password_text')->length(200);            
            $table->boolean('is_admin')->default(0);
            $table->boolean('is_blocked')->default(0);
            $table->string('r_code')->length(8)->nullable();
            $table->bigInteger('referred_id')->nullable();
            $table->double('contribution')->length(10, 2)->default(0);
            $table->double('level_1_first_reward')->length(8, 2)->default(0);
            $table->double('level_2_first_reward')->length(8, 2)->default(0);
            $table->double('level_3_first_reward')->length(8, 2)->default(0);
            $table->double('level_1_water_reward')->length(8, 2)->default(0);
            $table->double('level_2_water_reward')->length(8, 2)->default(0);
            $table->double('level_3_water_reward')->length(8, 2)->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
