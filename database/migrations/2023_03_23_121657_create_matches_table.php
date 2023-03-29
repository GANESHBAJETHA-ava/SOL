<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sport_id')->unsigned();
            $table->string('name')->length(100);
            $table->string('slug')->length(100);
            $table->string('team_one')->length(150);
            $table->string('team_two')->length(150);
            $table->timestamp('match_date');
            $table->boolean('is_bet_block')->default(0);
            $table->boolean('is_active')->default(0);
            $table->foreign('sport_id')
                  ->references('id')->on('sports')
                  ->onDelete('cascade');
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
        Schema::dropIfExists('matches');
    }
}
