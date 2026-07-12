<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DeleteExpiredFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $autoDelete = \App\Models\Setting::where('key', 'auto_delete_files')->value('value');
        if ($autoDelete === '0') {
            return;
        }

        $daysToKeep = (int) \App\Models\Setting::where('key', 'file_retention_days')->value('value') ?: 7;

        // Find completed orders older than specified days
        $expiredOrders = Order::where('status', \App\Enums\OrderStatus::SELESAI)
            ->where('updated_at', '<=', now()->subDays($daysToKeep))
            ->get();

        foreach ($expiredOrders as $order) {
            $order->media->each(function (Media $media) {
                $media->delete();
            });

            activity('file-cleanup')
                ->performedOn($order)
                ->log("Files deleted for order {$order->order_number}");
        }

        \Log::info("Deleted files for " . $expiredOrders->count() . " orders");
    }
}
