<?php

use App\Jobs\SendMessage;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    while (true){
        SendMessage::dispatch();
    }
})->purpose('Display an inspiring quote')->hourly();

