<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periods', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('game_id')->unsigned();
            $table->string('period')->length(100);
            $table->string('price_money')->nullable();
            $table->string('result')->nullable();
            $table->boolean('is_cleared')->default(0);
            $table->boolean('is_manual')->default(0);
            $table->timestamps();
            $table->index(['game_id', 'is_cleared']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('periods');
    }
}
