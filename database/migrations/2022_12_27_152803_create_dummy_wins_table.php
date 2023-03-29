<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDummyWinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dummy_wins', function (Blueprint $table) {
            $table->id();
            $table->integer('from_number')->nullable();
            $table->integer('to_number')->nullable();
            $table->integer('from_amount')->nullable();
            $table->integer('to_amount')->nullable();
            $table->string('text')->nullable();
            $table->boolean('is_active')->default(0);
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
        Schema::dropIfExists('dummy_wins');
    }
}
