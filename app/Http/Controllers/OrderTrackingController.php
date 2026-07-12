<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function index(Request $request)
    {
        $order = null;
        if ($request->has('q')) {
            $query = trim($request->get('q'));
            $order = Order::where('order_number', $query)
                ->orWhere('customer_phone', $query)
                ->with(['items', 'payment'])
                ->first();
                
            if (!$order) {
                session()->flash('error', 'Pesanan tidak ditemukan. Periksa kembali nomor pesanan Anda.');
            }
        }
        return view('order.tracking', compact('order'));
    }
}
