<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createTransaction(Order $order): string
    {
        // Build items array
        $items = [];
        foreach ($order->items as $item) {
            $items[] = [
                'id' => $item->id,
                'price' => (int) $item->unit_price,
                'quantity' => $item->quantity,
                'name' => mb_substr($item->product_name, 0, 50),
            ];
        }

        // Add discount as a negative item if any
        if ($order->discount_amount > 0) {
            $items[] = [
                'id' => 'DISCOUNT',
                'price' => -(int) $order->discount_amount,
                'quantity' => 1,
                'name' => 'Promo/Discount',
            ];
        }

        $transactionDetails = [
            'order_id' => $order->order_number,
            'gross_amount' => (int) $order->final_price,
        ];

        $customerDetails = [
            'first_name' => $order->customer_name,
            'email' => $order->customer_email,
            'phone' => $order->customer_phone,
        ];

        $transactionParams = [
            'transaction_details' => $transactionDetails,
            'item_details' => $items,
            'customer_details' => $customerDetails,
        ];

        try {
            // Get Snap Token
            $snapToken = Snap::getSnapToken($transactionParams);
            return $snapToken;
        } catch (\Exception $e) {
            // Log error
            \Log::error('Midtrans Transaction Error: ' . $e->getMessage());
            throw $e;
        }
    }
}
