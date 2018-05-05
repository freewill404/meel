<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailSchedulesTable extends Migration
{
    public function up()
    {
        Schema::create('email_schedules', function (Blueprint $table) {
            $table->increments('id');

            $table->string('what');
            $table->string('when');
            $table->dateTime('previous_occurrence')->nullable();
            $table->dateTime('next_occurrence')->nullable();
            $table->unsignedInteger('times_sent')->default(0);
            $table->boolean('disabled')->default(false);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_schedules');
    }
}
