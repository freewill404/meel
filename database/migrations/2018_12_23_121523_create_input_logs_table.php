<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInputLogsTable extends Migration
{
    public function up()
    {
        Schema::create('input_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('written_input');
            $table->string('prepared_written_input', 1000);
            $table->boolean('usable');
            $table->boolean('recurring');
            $table->string('source');
            $table->string('session_id');
            $table->dateTime('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('input_logs');
    }
}
