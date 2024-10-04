<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * アプリケーションのコマンド実行スケジュール定義
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('app:delete-story')->everyMinute();
    }
}
