<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        Log::info('Midtrans Webhook Received: ', $payload);

        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        $transactionId = $payload['transaction_id'] ?? null;

        if (!$orderId || !$transactionStatus) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        $order = Order::where('order_number', $orderId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Midtrans logic
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $order->payment_status = 'pending';
            } else if ($fraudStatus == 'accept') {
                $order->payment_status = 'lunas';
            }
        } else if ($transactionStatus == 'settlement') {
            $order->payment_status = 'lunas';
        } else if ($transactionStatus == 'cancel' ||
          $transactionStatus == 'deny' ||
          $transactionStatus == 'expire') {
            $order->payment_status = 'gagal';
        } else if ($transactionStatus == 'pending') {
            $order->payment_status = 'pending';
        }

        if ($order->payment_status === 'lunas') {
            $order->status = 'diproses';
        }

        $order->save();

        // Save payment record if paid
        if ($order->payment_status === 'lunas') {
            Payment::updateOrCreate(
                ['transaction_id' => $transactionId],
                [
                    'order_id' => $order->id,
                    'amount' => $payload['gross_amount'],
                    'method' => $payload['payment_type'] ?? 'qris',
                    'status' => 'success',
                    'transaction_time' => $payload['transaction_time'] ?? now(),
                    'payment_date' => now(),
                    'midtrans_response' => json_encode($payload)
                ]
            );

            // Send WA Notification
            app(\App\Services\WhatsAppService::class)->sendMessage(
                $order->customer_phone,
                "Halo {$order->customer_name},\n\nPembayaran untuk pesanan *{$order->order_number}* telah berhasil dikonfirmasi.\nPesanan Anda sekarang akan segera diproses oleh tim kami.\n\nPantau terus status pesanan Anda di sini: " . route('order.tracking', ['q' => $order->order_number]) . "\n\nTerima kasih telah mempercayakan SEMUDAH!"
            );

            // trigger event PaymentCompleted
            event(new \App\Events\PaymentCompleted($order));
        }

        return response()->json(['message' => 'Webhook handled']);
    }
}
