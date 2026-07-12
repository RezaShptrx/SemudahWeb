<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return redirect()->route('landing.index') . '#katalog';
    }

    public function show(string $service)
    {
        $product = Product::where('slug', $service)->where('is_active', true)->firstOrFail();
        return view('order.show', compact('product'));
    }

    public function success(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        $snapToken = null;

        if ($order->payment_status === \App\Enums\PaymentStatus::PENDING && $order->payment_method !== \App\Enums\PaymentMethod::TUNAI) {
            try {
                $midtransService = app(\App\Services\MidtransService::class);
                $snapToken = $midtransService->createTransaction($order);
            } catch (\Exception $e) {
                // Jangan crash halaman sukses jika Midtrans error (misal: config belum diatur)
                \Log::error("Failed to generate Midtrans snap token for order {$orderNumber}: " . $e->getMessage());
            }
        }

        return view('order.success', compact('order', 'snapToken'));
    }

    public function invoice(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        return view('order.invoice', compact('order'));
    }
}
