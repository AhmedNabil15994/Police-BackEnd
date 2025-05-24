<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkingTimeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('working_time_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('working_time_id')->unsigned();
            $table->time('time_from');
            $table->time('time_to');
            $table->foreign('working_time_id')->references('id')->on('working_times')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('working_time_details');
    }
}
