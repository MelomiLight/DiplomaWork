<?php


// Initial setup
use App\Services\ScheduleService;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;

Schedule::call(function (ScheduleService $scheduleService) {
    Log::info('Running initializeUsers');
    $scheduleService->initializeUsers();
})->cron('* * * * *'); // This can be any time for the initial setup

// Daily reset
Schedule::call(function (ScheduleService $scheduleService) {
    Log::info('Running updateDailyChallenges');
    $scheduleService->updateDailyChallenges();
})->cron('0 0 * * *'); // Daily reset at midnight

// Weekly reset
Schedule::call(function (ScheduleService $scheduleService) {
    Log::info('Running updateWeeklyChallenges');
    $scheduleService->updateWeeklyChallenges();
})->cron('0 0 * * 1'); // Weekly reset on Monday at midnight

// Monthly reset
Schedule::call(function (ScheduleService $scheduleService) {
    Log::info('Running updateMonthlyChallenges');
    $scheduleService->updateMonthlyChallenges();
})->cron('0 0 1 * *'); // Monthly reset on the 1st day at midnight
