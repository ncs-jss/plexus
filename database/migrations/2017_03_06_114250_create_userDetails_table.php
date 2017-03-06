<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userDetails', function (Blueprint $table) {
            $table->increments('id');
            $table->string('admissionNo')->unique()->nullable();
            $table->string('college');
            $table->unsignedInteger('userId');
            $table->string('contact', 10);
            $table->string('details', 1000)->nullable();
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('userDetails');
    }
}
