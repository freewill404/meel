<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('schedule_histories', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('schedule_id');
            $table->dateTime('sent_at');

            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('schedule_histories');
    }
}
