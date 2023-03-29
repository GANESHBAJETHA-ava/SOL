<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('match_id')->unsigned();
            $table->string('name')->length(200);
            $table->string('slug')->length(200);
            $table->integer('mrk_id')->length(6);
            $table->string('result')->length(100)->nullable();
            $table->boolean('is_bet_block')->default(0);
            $table->boolean('is_active')->default(0);
            $table->decimal('odds', 10, 2)->default(0);
            $table->foreign('match_id')
                  ->references('id')->on('matches')
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
        Schema::dropIfExists('markets');
    }
}
