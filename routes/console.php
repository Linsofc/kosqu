<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

try {
    if (Schema::hasTable('tb_settings')) {
        Schedule::command('reminder:send-daily')->everyMinute()->withoutOverlapping();
    } else {
        Schedule::command('reminder:send-daily')->everyMinute()->withoutOverlapping();
    }
} catch (\Exception $e) {
    Schedule::command('reminder:send-daily')->everyMinute()->withoutOverlapping();
}
