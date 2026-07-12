<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PromoCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderService
{
    public function createOrder(
        string $customerName,
        string $customerEmail,
        string $customerPhone,
        array $items,
        float $totalPrice,
        string $paymentMethod = 'qris',
        ?string $notes = null,
        ?PromoCode $promoCode = null
    ): Order {
        return DB::transaction(function () use ($customerName, $customerEmail, $customerPhone, $items, $totalPrice, $paymentMethod, $notes, $promoCode) {
            $orderNumber = $this->generateOrderNumber();
            
            $discountAmount = 0.00;
            if ($promoCode) {
                $subtotal = 0.00;
                foreach ($items as $item) {
                    $subtotal += $item['subtotal'];
                }
                
                if ($promoCode->discount_type->value === 'percentage') {
                    $discountAmount = ($subtotal * $promoCode->discount_value) / 100;
                } else {
                    $discountAmount = $promoCode->discount_value;
                }
                
                $promoCode->increment('used_count');
            }

            $finalPrice = max(0.00, $totalPrice - $discountAmount);

            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_name' => $customerName,
                'customer_email' => $customerEmail,
                'customer_phone' => $customerPhone,
                'status' => 'menunggu_antrian',
                'total_price' => $totalPrice,
                'discount_amount' => $discountAmount,
                'final_price' => $finalPrice,
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending',
                'notes' => $notes,
            ]);

            foreach ($items as $item) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'specifications' => $item['specifications'],
                    'subtotal' => $item['subtotal'],
                ]);

                if (isset($item['files']) && is_array($item['files'])) {
                    foreach ($item['files'] as $fileData) {
                        if (isset($fileData['path']) && Storage::disk('local')->exists($fileData['path'])) {
                            $order->addMedia(Storage::disk('local')->path($fileData['path']))
                                ->usingFileName($fileData['original_name'])
                                ->toMediaCollection('order_files');
                        }
                    }
                }
            }

            // Send WhatsApp Notification
            app(\App\Services\WhatsAppService::class)->sendMessage(
                $order->customer_phone, 
                "Halo {$order->customer_name},\n\nTerima kasih telah memesan di SEMUDAH.\nNomor Pesanan: *{$order->order_number}*\nTotal Tagihan: *Rp" . number_format($order->final_price, 0, ',', '.') . "*\n\nLacak pesanan Anda di sini: " . route('order.tracking', ['q' => $order->order_number]) . "\n\nSegera lakukan pembayaran agar pesanan Anda dapat diproses."
            );

            return $order;
        });
    }

    private function generateOrderNumber(): string
    {
        $prefix = config('semudah.order.number_prefix', 'SMDH');
        $date = date('Ymd');
        
        $latestOrder = Order::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = 1;
        if ($latestOrder) {
            $parts = explode('-', $latestOrder->order_number);
            if (count($parts) === 3) {
                $sequence = ((int) $parts[2]) + 1;
            }
        }

        return sprintf('%s-%s-%04d', $prefix, $date, $sequence);
    }
}
