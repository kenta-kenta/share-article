<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tweet;
use Carbon\Carbon;

class DeleteOldTweets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tweets:delete-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete tweets older than 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cutoffDate = Carbon::now()->subDay();
        Tweet::where('created_at', '<', $cutoffDate)->delete();

        $this->info('Old tweets deleted successfully.');
    }
}
