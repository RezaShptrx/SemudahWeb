<x-layouts.app>
    <x-slot:title>
        Lacak Pesanan - SEMUDAH
    </x-slot:title>

    <div class="max-w-4xl mx-auto px-6 py-12 space-y-8">
        
        <!-- Tracking Form Card -->
        <div class="bg-white dark:bg-slate-800/80 backdrop-blur-xl rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 dark:border-slate-700/50 space-y-6 transition-colors">
            <div class="space-y-2">
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Lacak Pesanan Anda</h1>
                <p class="text-gray-400 dark:text-slate-400 text-sm">Masukkan nomor pelacakan pesanan atau nomor telepon Anda untuk melihat progres pengerjaan.</p>
            </div>

            <form action="{{ route('order.tracking') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <input 
                    type="text" 
                    name="q" 
                    value="{{ request('q') }}"
                    placeholder="Contoh: SMDH-20260620-0001 atau 081234..."
                    class="flex-1 border-2 border-gray-100 dark:border-slate-700 rounded-2xl p-4 bg-gray-50/50 dark:bg-slate-900/50 focus:border-cyan-500 dark:focus:border-cyan-400 focus:bg-white dark:focus:bg-slate-800 outline-none font-medium text-slate-800 dark:text-slate-200 text-sm transition-all shadow-inner"
                    id="input-tracking-query"
                    required
                >
                <button 
                    type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 dark:bg-cyan-600 dark:hover:bg-cyan-500 text-white font-bold px-8 py-4 rounded-2xl transition shadow-md shadow-blue-100 dark:shadow-cyan-900/50 cursor-pointer text-center sm:w-auto w-full"
                    id="btn-search-tracking"
                >
                    Cari Pesanan
                </button>
            </form>

            @if(session()->has('error'))
            <div class="bg-red-50 dark:bg-red-900/30 border border-red-100 dark:border-red-800/50 text-red-700 dark:text-red-400 p-4 rounded-2xl text-sm font-medium transition-colors">
                {{ session('error') }}
            </div>
            @endif
        </div>

        @if($order)
            <!-- Order Details and Timeline -->
            <div class="bg-white dark:bg-slate-800/80 backdrop-blur-xl rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 dark:border-slate-700/50 space-y-8 transition-colors">
                
                <!-- Summary Meta -->
                <div class="flex flex-col md:flex-row justify-between gap-6 pb-6 border-b border-gray-100 dark:border-slate-700/50">
                    <div class="space-y-1.5">
                        <span class="text-xs text-gray-400 dark:text-slate-500 uppercase font-semibold">Nomor Pelacakan</span>
                        <h2 class="text-xl font-mono font-bold text-slate-800 dark:text-slate-100">{{ $order->order_number }}</h2>
                        <p class="text-xs text-gray-400 dark:text-slate-400">Dibuat pada: {{ $order->created_at->format('d M Y, H:i') }} WIB</p>
                    </div>

                    <div class="space-y-1 md:text-right">
                        <span class="text-xs text-gray-400 dark:text-slate-500 uppercase font-semibold block">Status Saat Ini</span>
                        @php
                            $badgeClass = 'bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200/50 dark:border-amber-800/50';
                            $statusLabel = 'Menunggu Pembayaran';
                            
                            if ($order->status->value === 'antri') {
                                $badgeClass = 'bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200/50 dark:border-amber-800/50';
                                $statusLabel = 'Antri / Menunggu';
                            } elseif ($order->status->value === 'diproses') {
                                $badgeClass = 'bg-cyan-50 text-cyan-600 dark:bg-cyan-900/30 dark:text-cyan-400 border border-cyan-200/50 dark:border-cyan-800/50';
                                $statusLabel = 'Sedang Diproses';
                            } elseif ($order->status->value === 'selesai') {
                                $badgeClass = 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-800/50';
                                $statusLabel = 'Selesai';
                            } elseif ($order->status->value === 'dibatalkan') {
                                $badgeClass = 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400 border border-red-200/50 dark:border-red-800/50';
                                $statusLabel = 'Dibatalkan';
                            }
                        @endphp
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider shadow-sm transition-colors {{ $badgeClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>

                <!-- Timeline Progress Graphics -->
                <div>
                    <h3 class="font-bold text-slate-850 dark:text-slate-200 text-base mb-6">Lini Masa Produksi</h3>
                    
                    @php
                        // Mapping stages
                        $stages = [
                            ['key' => 'antri', 'label' => 'Antri', 'desc' => 'Pesanan terdaftar, menunggu pembayaran / verifikasi.'],
                            ['key' => 'diproses', 'label' => 'Diproses', 'desc' => 'Desain sedang diperiksa dan pesanan sedang diproduksi.'],
                            ['key' => 'selesai', 'label' => 'Selesai', 'desc' => 'Pesanan telah selesai dan siap dikirim/diambil.'],
                        ];

                        $activeStageIndex = 0;
                        if ($order->status->value === 'diproses') {
                            $activeStageIndex = 1;
                        } elseif ($order->status->value === 'selesai') {
                            $activeStageIndex = 2;
                        }
                        if ($order->status->value === 'dibatalkan') {
                            $activeStageIndex = -1;
                        }
                    @endphp

                    @if($activeStageIndex === -1)
                        <div class="bg-red-50 dark:bg-red-900/30 border border-red-100 dark:border-red-800/50 text-red-700 dark:text-red-400 p-4 rounded-2xl text-sm font-medium transition-colors">
                            Pesanan ini telah dibatalkan. Hubungi admin untuk detail informasi lebih lanjut.
                        </div>
                    @else
                        <div class="relative pl-8 space-y-8 before:absolute before:left-3 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-150 dark:before:bg-slate-700">
                            @foreach($stages as $index => $stage)
                                @php
                                    $isPassed = $index <= $activeStageIndex;
                                    $isCurrent = $index === $activeStageIndex;
                                @endphp
                                <div class="relative group">
                                    <!-- Bullet Indicator -->
                                    <div class="absolute -left-8 top-1 w-6.5 h-6.5 rounded-full border-2 flex items-center justify-center transition-all duration-300 z-10
                                         {{ $isPassed ? 'bg-blue-600 dark:bg-cyan-500 border-blue-600 dark:border-cyan-500 text-white shadow-sm shadow-blue-500/30 dark:shadow-cyan-500/30' : 'bg-white dark:bg-slate-800 border-gray-300 dark:border-slate-600 text-gray-300 dark:text-slate-600' }}">
                                        @if($isPassed)
                                            <x-heroicon-o-check class="w-3.5 h-3.5" />
                                        @else
                                            <span class="text-[10px] font-bold">{{ $index + 1 }}</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Info -->
                                    <div class="space-y-1 pl-2">
                                        <h4 class="font-bold text-sm md:text-base transition-colors duration-300
                                            {{ $isCurrent ? 'text-blue-600 dark:text-cyan-400' : ($isPassed ? 'text-slate-800 dark:text-slate-200' : 'text-slate-400 dark:text-slate-500') }}">
                                            {{ $stage['label'] }}
                                        </h4>
                                        <p class="text-xs text-gray-400 dark:text-slate-400/80 leading-relaxed">{{ $stage['desc'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Info summary customer & items -->
                <div class="border-t border-gray-100 dark:border-slate-700/50 pt-6 grid md:grid-cols-2 gap-8 text-sm">
                    <div class="space-y-3">
                        <h4 class="font-bold text-slate-850 dark:text-slate-200">Informasi Pelanggan</h4>
                        <div class="space-y-1.5 text-xs text-gray-500 dark:text-slate-400">
                            <p><strong class="dark:text-slate-300">Nama:</strong> {{ $order->customer_name }}</p>
                            <p><strong class="dark:text-slate-300">Telepon:</strong> {{ $order->customer_phone }}</p>
                            <p><strong class="dark:text-slate-300">Email:</strong> {{ $order->customer_email }}</p>
                            <p><strong class="dark:text-slate-300">Metode Bayar:</strong> <span class="text-slate-700 dark:text-slate-200">{{ strtoupper($order->payment_method->value) }}</span> ({{ $order->payment_status->value === 'sudah_dibayar' ? 'LUNAS' : 'BELUM LUNAS' }})</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <h4 class="font-bold text-slate-850 dark:text-slate-200">Item Pesanan</h4>
                        <div class="space-y-2 text-xs text-gray-500 dark:text-slate-400">
                            @foreach($order->items as $item)
                                <div class="flex justify-between border-b border-gray-50 dark:border-slate-700/30 pb-2">
                                    <span>{{ $item->product_name }} <span class="text-gray-400 dark:text-slate-500 ml-1">({{ $item->quantity }}x)</span></span>
                                    <span class="font-bold text-slate-700 dark:text-slate-200">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                            <div class="flex justify-between pt-1">
                                <span>Total Tagihan</span>
                                <span class="font-black text-blue-600 dark:text-cyan-400 text-sm">Rp{{ number_format($order->final_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @elseif(request()->has('q'))
            <!-- Not Found State Placeholder (Handled by Flash error inside tracking card) -->
        @else
            <!-- Initial Info Help Card -->
            <div class="bg-blue-50/20 dark:bg-cyan-900/10 border border-blue-100/30 dark:border-cyan-800/30 rounded-3xl p-8 text-center space-y-4 transition-colors">
                <div class="w-16 h-16 bg-blue-50 dark:bg-cyan-900/30 text-blue-500 dark:text-cyan-400 rounded-2xl flex items-center justify-center mx-auto shadow-inner shadow-blue-100/50 dark:shadow-cyan-900/20 rotate-3 transition-transform hover:rotate-6">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="font-bold text-lg text-slate-800 dark:text-slate-200">Menunggu Input Pelacakan</h3>
                <p class="text-xs text-gray-400 dark:text-slate-400 max-w-sm mx-auto leading-relaxed">Gunakan kolom pencarian di atas untuk memantau detail pengerjaan pesanan cetak atau produk merchandise Anda.</p>
            </div>
        @endif

    </div>
</x-layouts.app>
