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

            $table->unsignedInteger('email_schedules_created')->default(0);
            $table->unsignedInteger('scheduled_emails_sent')->default(0);
            $table->unsignedInteger('scheduled_emails_not_sent')->default(0);

            $table->unsignedInteger('feeds_created')->default(0);
            $table->unsignedInteger('feed_polls')->default(0);
            $table->unsignedInteger('feed_emails_sent')->default(0);
            $table->unsignedInteger('feed_emails_not_sent')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('site_stats');
    }
}
