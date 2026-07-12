<?php

namespace App\Notifications;

use App\Models\Order;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentCompleteNotification extends Notification
{
    use Queueable;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['custom_wa'];
    }

    public function send($notifiable, Notification $notification)
    {
        $waService = app(WhatsAppService::class);
        $message = "Halo {$this->order->customer_name},\n\n";
        $message .= "Pembayaran untuk pesanan *{$this->order->order_number}* telah berhasil dikonfirmasi.\n";
        $message .= "Pesanan Anda sekarang akan segera diproses oleh tim kami.\n\n";
        $message .= "Pantau terus status pesanan Anda di sini: " . route('order.tracking', ['q' => $this->order->order_number]) . "\n\n";
        $message .= "Terima kasih telah mempercayakan SEMUDAH!";

        $waService->sendMessage($this->order->customer_phone, $message);
    }
}
