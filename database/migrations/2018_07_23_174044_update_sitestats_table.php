<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSitestatsTable extends Migration
{
    public function up()
    {
        Schema::table('site_stats', function (Blueprint $table) {
            $table->renameColumn('schedules_created', 'email_schedules_created');
        });
        Schema::table('site_stats', function (Blueprint $table) {
            $table->renameColumn('emails_sent',       'scheduled_emails_sent');
        });
        Schema::table('site_stats', function (Blueprint $table) {
            $table->renameColumn('emails_not_sent',   'scheduled_emails_not_sent');
        });

        Schema::table('site_stats', function (Blueprint $table) {
            $table->unsignedInteger('feeds_created')->default(0);
            $table->unsignedInteger('feed_polls')->default(0);
            $table->unsignedInteger('feed_emails_sent')->default(0);
            $table->unsignedInteger('feed_emails_not_sent')->default(0);
        });





        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('schedules_created', 'email_schedules_created');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('emails_sent',       'scheduled_emails_sent');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('emails_not_sent',   'scheduled_emails_not_sent');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('feeds_created')->default(0);
            $table->unsignedInteger('feed_emails_sent')->default(0);
            $table->unsignedInteger('feed_emails_not_sent')->default(0);
        });
    }

    public function down()
    {
        //
    }
}
