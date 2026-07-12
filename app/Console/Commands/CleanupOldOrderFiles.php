<?php

namespace App\Console\Commands;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CleanupOldOrderFiles extends Command
{
    protected $signature = 'app:cleanup-old-order-files';

    protected $description = 'Cleanup old order files for completed or taken orders based on retention days setting';

    public function handle()
    {
        $retentionDays = (int) Setting::where('key', 'file_retention_days')->value('value') ?: 7;
        $autoDelete = Setting::where('key', 'auto_delete_files')->value('value') === '1';

        if (!$autoDelete) {
            $this->info('Auto delete is disabled in settings.');
            return;
        }

        $thresholdDate = Carbon::now()->subDays($retentionDays);

        $orders = Order::where('status', OrderStatus::SELESAI)
            ->where('updated_at', '<', $thresholdDate)
            ->get();

        $count = 0;
        foreach ($orders as $order) {
            if ($order->hasMedia('order_files')) {
                $order->clearMediaCollection('order_files');
                $count++;
            }
        }

        $this->info("Cleaned up files for {$count} old orders.");
    }
}
