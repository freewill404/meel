<?php

use App\Support\Enums\UserRole;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->boolean('email_confirmed')->default(false);
            $table->string('role')->default(UserRole::USER);
            $table->string('timezone')->default('Europe/Amsterdam');
            $table->unsignedInteger('emails_left')->default(100);
            $table->unsignedInteger('max_feeds')->default(5);

            $table->unsignedInteger('email_schedules_created')->default(0);
            $table->unsignedInteger('scheduled_emails_sent')->default(0);
            $table->unsignedInteger('scheduled_emails_not_sent')->default(0);

            $table->unsignedInteger('feeds_created')->default(0);
            $table->unsignedInteger('feed_emails_sent')->default(0);
            $table->unsignedInteger('feed_emails_not_sent')->default(0);

            $table->string('password');
            $table->string('email_confirm_token')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_resets');
    }
}
