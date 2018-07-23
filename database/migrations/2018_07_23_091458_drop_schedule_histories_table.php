<?php

use App\Models\Schedule;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropScheduleHistoriesTable extends Migration
{
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->unsignedInteger('times_sent')->default(0);
            $table->dateTime('last_sent_at')->nullable();
        });

        Schedule::each(function (Schedule $schedule) {
            $history = $schedule->scheduleHistories->first();

            $schedule->update([
                'times_sent'   => $schedule->scheduleHistories->count(),
                'last_sent_at' => $history ? $history->sent_at : null,
            ]);
        });

        Schema::drop('schedule_histories');
    }

    public function down()
    {
        //
    }
}
