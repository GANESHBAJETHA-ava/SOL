<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('game_id')->unsigned();
            $table->bigInteger('period_id')->unsigned();
            $table->string('selection')->length(30);
            $table->string('contract_money')->length(10)->nullable();
            $table->string('contract_count')->length(10)->nullable();
            $table->string('delivery')->length(10)->nullable();
            $table->string('fee')->length(10)->nullable();
            $table->double('pl')->length(10, 2)->nullable();
            $table->boolean('is_cleared')->default(0);
            $table->index(['user_id', 'game_id', 'period_id']);
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
        Schema::dropIfExists('orders');
    }
}
