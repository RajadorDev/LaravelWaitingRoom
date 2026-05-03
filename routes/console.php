<?php

use App\Jobs\RemoveOfflineUsers;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule as FacadesSchedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

FacadesSchedule::job(new RemoveOfflineUsers)->everyFiveSeconds();
