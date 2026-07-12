<?php

namespace App\Console\Commands;

use App\Jobs\DeleteExpiredFiles as DeleteExpiredFilesJob;
use Illuminate\Console\Command;

class DeleteExpiredFiles extends Command
{
    protected $signature = 'app:delete-expired-files';
    
    protected $description = 'Delete files from completed orders older than configured days';

    public function handle(): int
    {
        $this->info('Starting file cleanup...');

        dispatch(new DeleteExpiredFilesJob());

        $this->info('File cleanup job dispatched successfully.');

        return Command::SUCCESS;
    }
}
