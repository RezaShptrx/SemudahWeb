<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $order->order_number }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body class="bg-gray-50 p-8 font-sans text-slate-800" onload="window.print()">
    <div class="max-w-3xl mx-auto bg-white p-10 shadow-sm border border-gray-100 rounded-xl">
        
        <!-- Header -->
        <div class="flex justify-between items-start border-b pb-6 mb-6">
            <div>
                <h1 class="text-3xl font-black text-blue-600 tracking-tighter uppercase">SEMUDAH</h1>
                <p class="text-gray-500 text-sm mt-1">SMKN 12 Jakarta</p>
            </div>
            <div class="text-right">
                <h2 class="text-2xl font-bold text-slate-800">INVOICE</h2>
                <p class="text-gray-500 mt-1 font-mono">{{ $order->order_number }}</p>
            </div>
        </div>

        <!-- Info -->
        <div class="flex justify-between mb-8">
            <div>
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Ditagihkan Kepada:</h3>
                <p class="font-bold text-lg">{{ $order->customer_name }}</p>
                <p class="text-gray-500">{{ $order->customer_phone }}</p>
                <p class="text-gray-500">{{ $order->customer_email }}</p>
            </div>
            <div class="text-right space-y-1">
                <p><span class="text-gray-400 font-semibold mr-2">Tanggal:</span> {{ $order->created_at->format('d M Y') }}</p>
                <p><span class="text-gray-400 font-semibold mr-2">Metode Bayar:</span> <span class="uppercase">{{ $order->payment_method->value }}</span></p>
                <p><span class="text-gray-400 font-semibold mr-2">Status Bayar:</span> 
                    <span class="{{ $order->payment_status->value === 'sudah_dibayar' ? 'text-emerald-600 font-bold' : 'text-red-600 font-bold' }}">
                        {{ strtoupper(str_replace('_', ' ', $order->payment_status->value)) }}
                    </span>
                </p>
            </div>
        </div>

        <!-- Items -->
        <table class="w-full text-left border-collapse mb-8">
            <thead>
                <tr class="border-b-2 border-gray-200 text-gray-500 text-sm uppercase">
                    <th class="py-3 font-semibold">Deskripsi Layanan / Produk</th>
                    <th class="py-3 font-semibold text-center">Qty</th>
                    <th class="py-3 font-semibold text-right">Harga Satuan</th>
                    <th class="py-3 font-semibold text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @foreach($order->items as $item)
                <tr class="border-b border-gray-100">
                    <td class="py-4 font-medium">{{ $item->product_name }}</td>
                    <td class="py-4 text-center">{{ $item->quantity }}</td>
                    <td class="py-4 text-right">Rp{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="py-4 text-right font-bold text-slate-800">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="flex justify-end">
            <div class="w-1/2 space-y-3 text-sm">
                <div class="flex justify-between text-gray-500">
                    <span>Subtotal</span>
                    <span>Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
                @if($order->discount_amount > 0)
                <div class="flex justify-between text-emerald-600">
                    <span>Diskon</span>
                    <span>- Rp{{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between text-lg font-bold text-slate-900 border-t pt-3">
                    <span>Total Keseluruhan</span>
                    <span class="text-blue-600">Rp{{ number_format($order->final_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="border-t border-gray-200 mt-12 pt-6 text-center text-gray-400 text-xs">
            <p>Terima kasih atas pesanan Anda. Untuk pertanyaan lebih lanjut, silakan hubungi admin kami.</p>
        </div>

    </div>
</body>
</html>
