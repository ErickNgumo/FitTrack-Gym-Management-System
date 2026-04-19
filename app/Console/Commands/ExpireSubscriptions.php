<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExpireSubscriptions extends Command
{
    protected $signature   = 'fittrack:expire-subscriptions';
    protected $description = 'Mark all past-end-date subscriptions as expired';

    public function handle(): int
    {
        $count = $service->expireOutdated();
        $this->info("Marked {$count} subscription(s) as expired.");
        return self::SUCCESS;
    }
}
