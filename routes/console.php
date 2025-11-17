<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jalankan setiap menit untuk mencari session yang akan dimulai dalam 1 jam
Schedule::command('sessions:send-reminders')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground();
