<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteStatsTable extends Migration
{
    public function up()
    {
        Schema::create('site_stats', function (Blueprint $table) {
            $table->increments('id');

            $table->date('date')->unique();
            $table->unsignedInteger('users_registered')->default(0);
            $table->unsignedInteger('schedules_created')->default(0);
            $table->unsignedInteger('emails_sent')->default(0);
            $table->unsignedInteger('emails_not_sent')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('site_stats');
    }
}
