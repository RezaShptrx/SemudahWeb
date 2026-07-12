<?php

namespace App\Notifications;

use App\Models\Order;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification
{
    use Queueable;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['custom_wa']; // We could use mail as well
    }

    public function send($notifiable, Notification $notification)
    {
        // Custom channel implementation directly here for simplicity,
        // or properly register a Custom channel. Let's just call WhatsAppService directly.
        $waService = app(WhatsAppService::class);
        $message = "Halo {$this->order->customer_name},\n\n";
        $message .= "Terima kasih telah memesan di SEMUDAH.\n";
        $message .= "Nomor Pesanan: *{$this->order->order_number}*\n";
        $message .= "Total Tagihan: *Rp" . number_format($this->order->final_price, 0, ',', '.') . "*\n\n";
        $message .= "Lacak pesanan Anda di sini: " . route('order.tracking', ['q' => $this->order->order_number]) . "\n\n";
        $message .= "Segera lakukan pembayaran agar pesanan Anda dapat diproses.";

        $waService->sendMessage($this->order->customer_phone, $message);
    }
}
