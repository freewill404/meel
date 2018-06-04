<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailScheduleHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('email_schedule_histories', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('email_schedule_id');
            $table->dateTime('sent_at');

            $table->foreign('email_schedule_id')->references('id')->on('email_schedules')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_schedule_histories');
    }
}
