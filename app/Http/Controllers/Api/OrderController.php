<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Order::with(['items.product', 'payment', 'verifiedBy'])
            ->orderBy('created_at', 'desc');

        if ($user && $user->role !== 'admin') {
            $query->where(function ($q) {
                $q->where('status', '!=', \App\Enums\OrderStatus::SELESAI)
                  ->orWhere('payment_status', '!=', \App\Enums\PaymentStatus::LUNAS);
            });
        }

        // Get all orders with items, products, and payment
        return response()->json($query->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'payment_method' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.notes' => 'nullable|string',
        ]);

        $order = new Order();
        $order->order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
        $order->customer_name = $validated['customer_name'];
        $order->customer_email = $validated['customer_email'];
        $order->customer_phone = $validated['customer_phone'];
        $order->status = \App\Enums\OrderStatus::MENUNGGU_ANTRIAN;
        $order->payment_method = \App\Enums\PaymentMethod::tryFrom($validated['payment_method']) ?? \App\Enums\PaymentMethod::TUNAI;
        $order->payment_status = \App\Enums\PaymentStatus::BELUM_BAYAR;
        
        $totalPrice = 0;
        foreach ($validated['items'] as $item) {
            $totalPrice += $item['quantity'] * $item['price'];
        }
        
        $order->total_price = $totalPrice;
        $order->discount_amount = 0; // Simple for now
        $order->final_price = $totalPrice;
        $order->save();

        // Save items
        foreach ($validated['items'] as $itemData) {
            $order->items()->create([
                'product_id' => $itemData['product_id'],
                'quantity' => $itemData['quantity'],
                'price' => $itemData['price'],
                'subtotal' => $itemData['quantity'] * $itemData['price'],
                'notes' => $itemData['notes'] ?? null,
            ]);
        }

        return response()->json($order->load('items'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return response()->json($order->load(['items.product', 'payment', 'verifiedBy']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'sometimes|string',
            'payment_status' => 'sometimes|string',
            'internal_notes' => 'nullable|string',
        ]);

        if (isset($validated['status'])) {
            $order->status = \App\Enums\OrderStatus::tryFrom($validated['status']) ?? $order->status;
        }
        if (isset($validated['payment_status'])) {
            $order->payment_status = \App\Enums\PaymentStatus::tryFrom($validated['payment_status']) ?? $order->payment_status;
        }
        if (isset($validated['internal_notes'])) {
            $order->internal_notes = $validated['internal_notes'];
        }

        try {
            $order->save();
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
        
        return response()->json($order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(null, 204);
    }
}
