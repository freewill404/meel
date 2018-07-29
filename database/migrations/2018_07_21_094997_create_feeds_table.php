<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedsTable extends Migration
{
    public function up()
    {
        Schema::create('feeds', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id');
            $table->string('when')->nullable();
            $table->string('url');
            $table->boolean('group_new_entries')->default(true);
            $table->dateTime('next_poll_at');
            $table->dateTime('last_polled_at');
            $table->string('last_poll_error')->nullable();
            $table->unsignedInteger('emails_sent')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('feeds');
    }
}
