<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkingTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('working_times', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('timeable_id')->unsigned();
            $table->string('timeable_type');
            $table->string('day_code', 20);
            $table->boolean('status')->default(true);
            $table->boolean('is_full_day')->default(true);
//            $table->json('custom_times')->nullable();
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
        Schema::dropIfExists('working_times');
    }
}
