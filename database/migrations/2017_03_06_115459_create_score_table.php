<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scores', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('userId');
            $table->unsignedInteger('eventId');
            $table->integer('score')->default(0);
            $table->integer('level')->default(0);
            $table->integer('counter')->default(0);
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('eventId')->references('id')->on('events')->onDelete('cascade');
             $table->timestamp('logged_on');
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
        Schema::dropIfExists('scores');
    }
}
