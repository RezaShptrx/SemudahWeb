<x-layouts.app>
    <x-slot:title>
        Pemesanan Berhasil - SEMUDAH
    </x-slot:title>

    <div class="max-w-3xl mx-auto px-6 py-12 space-y-8">
        
        <!-- Status Card -->
        <div class="bg-white rounded-3xl p-8 text-center border border-gray-100 shadow-sm space-y-6">
            <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto shadow-inner">
                <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            
            <div class="space-y-2">
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Terima Kasih, Pesanan Diterima!</h1>
                <p class="text-gray-400 text-sm md:text-base max-w-md mx-auto">Pesanan Anda telah masuk ke dalam antrean sistem. Segera selesaikan pembayaran untuk memproses pesanan.</p>
            </div>

            <div class="bg-blue-50/50 border border-blue-100/50 rounded-2xl p-4 inline-block">
                <span class="text-xs text-gray-400 block uppercase tracking-wider font-semibold">Nomor Pelacakan Pesanan</span>
                <span class="text-xl font-mono font-bold text-blue-800 tracking-wide select-all">{{ $order->order_number }}</span>
            </div>
        </div>

        <!-- Payment Instructions -->
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-6">
            <h3 class="text-xl font-bold text-slate-800 pb-4 border-b">Instruksi Pembayaran</h3>
            
            @if(isset($snapToken))
                <div class="text-center space-y-4">
                    <p class="text-gray-500 mb-4">Klik tombol di bawah ini untuk melakukan pembayaran via Midtrans (Mendukung QRIS, GoPay, Transfer Bank, dll).</p>
                    <button id="pay-button" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-700 shadow-md transition">
                        Bayar Sekarang
                    </button>
                </div>
                
                @push('scripts')
                <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
                <script type="text/javascript">
                    document.getElementById('pay-button').onclick = function(){
                        snap.pay('{{ $snapToken }}', {
                            onSuccess: function(result){
                                window.location.href = "{{ route('order.tracking') }}?q={{ $order->order_number }}";
                            },
                            onPending: function(result){
                                window.location.href = "{{ route('order.tracking') }}?q={{ $order->order_number }}";
                            },
                            onError: function(result){
                                alert("Pembayaran gagal!");
                            },
                            onClose: function(){
                                alert('Anda menutup popup sebelum menyelesaikan pembayaran');
                            }
                        });
                    };
                </script>
                @endpush
            @elseif($order->payment_method->value === 'qris')
                <div class="flex flex-col md:flex-row items-center gap-8">
                    <!-- QRIS Placeholder Image -->
                    <div class="w-48 h-48 bg-gray-50 border-2 border-gray-150 rounded-2xl flex items-center justify-center p-4 shadow-sm relative group overflow-hidden">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https://semudah.local/pay/{{ $order->order_number }}" 
                             alt="QRIS Code" 
                             class="w-full h-full object-contain">
                    </div>
                    <div class="space-y-3 flex-1">
                        <span class="bg-cyan-50 text-cyan-600 text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider">QRIS OTOMATIS</span>
                        <h4 class="font-bold text-slate-800 text-lg">Pindai QR Kode di samping</h4>
                        <ol class="text-xs text-gray-400 space-y-1.5 list-decimal pl-4 leading-relaxed">
                            <li>Buka aplikasi pembayaran digital pilihan Anda (Gopay, OVO, ShopeePay, M-Banking, dll).</li>
                            <li>Pilih opsi <strong>Scan / Bayar</strong>.</li>
                            <li>Arahkan kamera ke QR Kode tersebut.</li>
                            <li>Masukkan nominal sebesar <strong class="text-blue-600">Rp{{ number_format($order->final_price, 0, ',', '.') }}</strong>.</li>
                            <li>Setelah pembayaran sukses, sistem akan memperbarui status pelacakan secara otomatis.</li>
                        </ol>
                    </div>
                </div>
            @else
                <div class="space-y-3">
                    <span class="bg-amber-50 text-amber-600 text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider">BAYAR TUNAI DI KASIR</span>
                    <h4 class="font-bold text-slate-800 text-lg">Bayar Langsung ke Outlet Kami</h4>
                    <p class="text-xs text-gray-400 leading-relaxed">
                        Silakan datangi petugas kasir SEMUDAH di SMKN 12 Jakarta dengan menyebutkan nomor pesanan pelacakan Anda <strong class="text-slate-800">{{ $order->order_number }}</strong> dan serahkan uang tunai sebesar <strong class="text-blue-600">Rp{{ number_format($order->final_price, 0, ',', '.') }}</strong>.
                    </p>
                </div>
            @endif
        </div>

        <!-- Order Summary Receipt -->
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-6">
            <h3 class="text-xl font-bold text-slate-800 pb-4 border-b">Detail Rincian Biaya</h3>
            
            <div class="space-y-4">
                @foreach($order->items as $item)
                <div class="flex justify-between items-start text-sm">
                    <div>
                        <p class="font-bold text-slate-850">{{ $item->product_name }}</p>
                        <p class="text-xs text-gray-400 mt-1">Kuantitas: {{ $item->quantity }}x</p>
                    </div>
                    <span class="font-bold text-slate-800">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>

            <div class="border-t pt-5 space-y-2.5 text-sm">
                <div class="flex justify-between text-gray-400">
                    <span>Subtotal:</span>
                    <span>Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
                @if($order->discount_amount > 0)
                <div class="flex justify-between text-emerald-500 font-semibold">
                    <span>Kupon Potongan:</span>
                    <span>-Rp{{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between text-base font-extrabold text-slate-900 border-t pt-3 mt-2">
                    <span>Total Pembayaran:</span>
                    <span class="text-blue-600">Rp{{ number_format($order->final_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="flex flex-col sm:flex-row gap-4 justify-between items-center pt-4">
            <a href="{{ route('order.tracking') }}?q={{ $order->order_number }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-3.5 rounded-2xl transition w-full sm:w-auto text-center cursor-pointer shadow-md shadow-blue-100" 
               id="btn-track-success-page">
                Lacak Status Pesanan
            </a>
            
            <a href="https://wa.me/6281234567890?text=Halo%20SEMUDAH,%20saya%20sudah%20memesan%20dengan%20nomor%20pesanan%20{{ $order->order_number }}.%20Mohon%20verifikasi%20pembayaran%20saya." 
               target="_blank" 
               class="border-2 border-emerald-500 text-emerald-500 hover:bg-emerald-50 font-bold px-8 py-3 rounded-2xl transition w-full sm:w-auto text-center cursor-pointer"
               id="btn-whatsapp-confirm">
                Konfirmasi via WhatsApp
            </a>
        </div>

    </div>
</x-layouts.app>
