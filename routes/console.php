<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());

    
})->purpose('Display an inspiring quote');

// Schedule::command('app:notify-incomplete-weekly-quota')->weeklyOn(1,'00:05');
// Schedule::command('app:store-suggestions')->weeklyOn(5,'10:00');
Schedule::command('app:notify-incomplete-weekly-quota')->everyMinute();
Schedule::command('app:store-suggestions')->everyMinute();