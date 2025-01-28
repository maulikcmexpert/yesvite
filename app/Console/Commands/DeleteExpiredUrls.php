<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Url;

class DeleteExpiredUrls extends Command
{
    protected $signature = 'urls:delete-expired';
    protected $description = 'Delete expired short URLs from the database';

    public function handle()
    {
        Url::where('expires_at', '<', now())->delete();
        $this->info('Expired URLs deleted successfully.');
    }
}
