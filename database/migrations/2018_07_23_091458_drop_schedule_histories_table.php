<?php

use App\Models\Schedule;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropScheduleHistoriesTable extends Migration
{
    public function up()
    {
        Schedule::each(function (Schedule $schedule) {
            $history = DB::table('schedule_histories')->where('schedule_id', $schedule->id)->orderByDesc('sent_at')->first();

            $count = DB::table('schedule_histories')->where('schedule_id', $schedule->id)->count();

            $schedule->update([
                'times_sent'   => $count,
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
