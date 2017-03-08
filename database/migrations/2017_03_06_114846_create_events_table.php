<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('eventName');
            $table->string('eventDes', 1000);
            $table->timestamp('startTime')->nullable();
            $table->timestamp('endTime')->nullable();
            $table->integer('duration');
            $table->integer('totalQues');
            $table->unsignedInteger('societyId')->length(10);
            $table->tinyInteger('type');
            $table->boolean('approve')->default(0);
            $table->boolean('active')->default(0);
            $table->string('forum', 500)->nullable();
            $table->tinyInteger('winners')->default(0);
            $table->foreign('societyId')->references('id')->on('societies')->onDelete('cascade');
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
        Schema::dropIfExists('events');
    }
}
