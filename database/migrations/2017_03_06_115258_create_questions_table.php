<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('eventId');
            $table->string('question', 2500);
            $table->string('options', 2000)->nullable();
            $table->string('image', 1000)->nullable();
            $table->string('html', 1000)->nullable();
            $table->integer('type')->nullable();
            $table->integer('level')->nullable();
            $table->foreign('eventId')->references('id')->on('events')->onDelete('cascade');
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
        Schema::dropIfExists('questions');
    }
}
