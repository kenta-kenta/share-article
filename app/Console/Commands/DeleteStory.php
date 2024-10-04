<?php

namespace App\Console\Commands;

use App\Models\Tweet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeleteStory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-story';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('hello');
        dd();
    }
}
