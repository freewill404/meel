<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('free_emails_left', 'emails_left');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('paid_emails_left');
        });
    }

    public function down()
    {
        //
    }
}
