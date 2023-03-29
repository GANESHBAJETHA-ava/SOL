<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('bank_id')->unsigned();
            $table->decimal('amount', 20, 2)->nullable();
            $table->decimal('fee', 20, 2)->nullable();
            $table->string('txn_id')->nullable();
            $table->string('type')->nullable();
            $table->boolean('status')->default(0);
            $table->boolean('is_rejected')->default(0);
            $table->timestamps();
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
            $table->index(['user_id']);
            $table->foreign('bank_id')
                  ->references('id')->on('banks')
                  ->onDelete('cascade');
            $table->index(['bank_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withdrawals');
    }
}
