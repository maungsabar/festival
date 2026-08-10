<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Contoh command
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Tampilkan kutipan inspiratif')->hourly();

// Laravel 11: scheduler juga bisa didefinisikan langsung di sini
// Schedule::command('inspire')->hourly();
