<div class="grid lg:grid-cols-3 gap-8">
    
    <!-- Left: Order Summary & Promo Code (2 cols) -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- Order Items -->
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100">
            <h3 class="text-xl font-bold text-slate-800 mb-6">Detail Pesanan</h3>
            
            <div class="space-y-4">
                @foreach($orderData['items'] ?? [] as $item)
                <div class="bg-gray-50/50 border border-gray-100 rounded-2xl p-5 flex justify-between items-start gap-4">
                    <div class="space-y-2">
                        <h4 class="font-bold text-slate-800 text-lg">{{ $item['product_name'] ?? '' }}</h4>
                        <div class="flex flex-wrap gap-x-4 gap-y-1.5 text-xs text-gray-500">
                            @foreach(($item['specifications'] ?? []) as $key => $value)
                                <span><strong class="text-slate-600 font-semibold">{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</span>
                            @endforeach
                        </div>
                        <p class="text-sm text-gray-400">Jumlah: {{ $item['quantity'] ?? 1 }}x</p>
                    </div>
                    <span class="font-bold text-slate-800 text-lg">Rp{{ number_format($item['subtotal'] ?? 0, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Promo Code -->
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-4">
            <h3 class="text-xl font-bold text-slate-800">Kode Promo / Kupon</h3>
            
            @if($appliedPromo)
            <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5 flex items-center justify-between">
                <div class="space-y-1">
                    <span class="font-bold text-emerald-800 text-base">Kupon Terpasang: {{ $appliedPromo->code }}</span>
                    <p class="text-xs text-emerald-600">Anda menghemat Rp{{ number_format($appliedPromo->discount_value, 0, ',', '.') }} dari kupon ini!</p>
                </div>
                <button 
                    type="button"
                    wire:click="removePromo"
                    class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2.5 rounded-xl transition cursor-pointer flex items-center gap-1">
                    <x-heroicon-o-x-mark class="w-4 h-4" /> Hapus
                </button>
            </div>
            @else
            <div class="flex gap-3">
                <input 
                    type="text"
                    wire:model="promoCode"
                    placeholder="Masukkan kode promo (e.g. DISKON50)"
                    class="flex-1 border-2 border-gray-100 rounded-2xl p-4 bg-gray-50/50 focus:border-cyan-500 focus:bg-white outline-none font-medium text-slate-800 text-sm">
                <button 
                    type="button"
                    wire:click="applyPromo"
                    class="px-6 py-4 bg-cyan-500 hover:bg-cyan-600 text-white font-bold rounded-2xl transition shadow-md shadow-cyan-100 cursor-pointer">
                    Pasang
                </button>
            </div>
            @endif

            @if(session()->has('promo_error'))
                <span class="text-xs text-red-500 mt-1 block">{{ session('promo_error') }}</span>
            @endif
            @if(session()->has('promo_success'))
                <span class="text-xs text-emerald-600 mt-1 block font-semibold">{{ session('promo_success') }}</span>
            @endif
        </div>

    </div>

    <!-- Right: Customer Info & Checkout (1 col) -->
    <div class="space-y-8">
        
        <!-- Customer Info Form -->
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-5">
            <h3 class="text-xl font-bold text-slate-800 mb-2">Informasi Pembeli</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input 
                        type="text"
                        wire:model="customerName"
                        placeholder="Masukkan nama lengkap Anda"
                        class="w-full border-2 border-gray-100 rounded-2xl p-3.5 bg-gray-50/50 focus:border-cyan-500 focus:bg-white outline-none text-sm font-medium text-slate-800">
                    @error('customerName') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Nomor WhatsApp</label>
                    <input 
                        type="text"
                        wire:model="customerPhone"
                        placeholder="Contoh: 08123456789"
                        class="w-full border-2 border-gray-100 rounded-2xl p-3.5 bg-gray-50/50 focus:border-cyan-500 focus:bg-white outline-none text-sm font-medium text-slate-800">
                    @error('customerPhone') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Alamat Email</label>
                    <input 
                        type="email"
                        wire:model="customerEmail"
                        placeholder="Contoh: pembeli@email.com"
                        class="w-full border-2 border-gray-100 rounded-2xl p-3.5 bg-gray-50/50 focus:border-cyan-500 focus:bg-white outline-none text-sm font-medium text-slate-800">
                    @error('customerEmail') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Payment Method -->
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-4">
            <h3 class="text-xl font-bold text-slate-800">Metode Pembayaran</h3>
            
            <div class="grid grid-cols-2 gap-3">
                <label class="flex items-center justify-between p-4 border-2 border-gray-100 rounded-2xl cursor-pointer hover:border-gray-200 transition"
                       :class="$wire.paymentMethod === 'qris' && 'border-cyan-500 bg-cyan-50/10'">
                    <div class="flex items-center">
                        <input 
                            type="radio" 
                            wire:model="paymentMethod"
                            value="qris"
                            class="text-cyan-500 focus:ring-cyan-500 border-gray-300">
                        <span class="ml-2.5 font-bold text-slate-800 text-sm">QRIS</span>
                    </div>
                </label>
                
                <label class="flex items-center justify-between p-4 border-2 border-gray-100 rounded-2xl cursor-pointer hover:border-gray-200 transition"
                       :class="$wire.paymentMethod === 'tunai' && 'border-cyan-500 bg-cyan-50/10'">
                    <div class="flex items-center">
                        <input 
                            type="radio" 
                            wire:model="paymentMethod"
                            value="tunai"
                            class="text-cyan-500 focus:ring-cyan-500 border-gray-300">
                        <span class="ml-2.5 font-bold text-slate-800 text-sm">Tunai</span>
                    </div>
                </label>
            </div>
            @error('paymentMethod') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Checkout Card -->
        <div class="bg-slate-900 text-white rounded-3xl p-6 md:p-8 shadow-lg space-y-6">
            <h3 class="text-lg font-bold">Ringkasan Biaya</h3>
            
            <div class="space-y-3 text-sm border-b border-slate-800 pb-5">
                <div class="flex justify-between text-gray-400">
                    <span>Subtotal:</span>
                    <span>Rp{{ number_format($orderData['subtotal'] ?? 0, 0, ',', '.') }}</span>
                </div>
                
                @if($appliedPromo)
                <div class="flex justify-between text-emerald-400">
                    <span>Potongan Kupon:</span>
                    <span>-Rp{{ number_format($appliedPromo->discount_value, 0, ',', '.') }}</span>
                </div>
                @endif
            </div>

            <div class="flex justify-between items-baseline">
                <span class="text-sm font-semibold">Total Pembayaran:</span>
                <span class="text-2xl font-extrabold text-cyan-400">
                    Rp{{ number_format($totalPrice, 0, ',', '.') }}
                </span>
            </div>

            <button 
                type="button"
                wire:click="submitOrder"
                class="w-full bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-4 rounded-2xl transition shadow-lg shadow-cyan-900/10 text-center cursor-pointer"
                id="btn-submit-order">
                Konfirmasi & Kirim Pesanan
            </button>
        </div>

    </div>

</div>
