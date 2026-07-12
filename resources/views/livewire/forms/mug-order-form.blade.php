<div class="space-y-8">
    
    <!-- Jenis Mug -->
    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-4">
        <h3 class="text-lg font-bold text-slate-800">Jenis Mug Custom</h3>
        <div class="grid md:grid-cols-3 gap-4">
            
            <button 
                type="button"
                wire:click="$set('selectedType', 'Standar Putih')"
                class="p-5 border-2 rounded-2xl text-left transition duration-300 relative group cursor-pointer"
                :class="$wire.selectedType === 'Standar Putih' ? 'border-cyan-500 bg-cyan-50/10' : 'border-gray-100 hover:border-gray-200'">
                <div class="flex items-center justify-between">
                    <span class="block font-bold text-slate-800">Standar Putih</span>
                    <div class="w-5 h-5 rounded-full border flex items-center justify-center"
                         :class="$wire.selectedType === 'Standar Putih' ? 'border-cyan-500 bg-cyan-500 text-white' : 'border-gray-300'">
                        <x-heroicon-o-check x-show="$wire.selectedType === 'Standar Putih'" class="w-3.5 h-3.5" />
                    </div>
                </div>
                <span class="text-xs text-gray-400 mt-2 block leading-relaxed">Mug keramik putih standar. Kualitas cetak cerah dan awet.</span>
            </button>

            <button 
                type="button"
                wire:click="$set('selectedType', 'Warna Dalam')"
                class="p-5 border-2 rounded-2xl text-left transition duration-300 relative group cursor-pointer"
                :class="$wire.selectedType === 'Warna Dalam' ? 'border-cyan-500 bg-cyan-50/10' : 'border-gray-100 hover:border-gray-200'">
                <div class="flex items-center justify-between">
                    <span class="block font-bold text-slate-800">Warna Dalam</span>
                    <div class="w-5 h-5 rounded-full border flex items-center justify-center"
                         :class="$wire.selectedType === 'Warna Dalam' ? 'border-cyan-500 bg-cyan-500 text-white' : 'border-gray-300'">
                        <x-heroicon-o-check x-show="$wire.selectedType === 'Warna Dalam'" class="w-3.5 h-3.5" />
                    </div>
                </div>
                <span class="text-xs text-gray-400 mt-2 block leading-relaxed">Mug dengan bagian dalam berwarna. Tersedia berbagai warna.</span>
            </button>

            <button 
                type="button"
                wire:click="$set('selectedType', 'Bunglon (Magic)')"
                class="p-5 border-2 rounded-2xl text-left transition duration-300 relative group cursor-pointer"
                :class="$wire.selectedType === 'Bunglon (Magic)' ? 'border-cyan-500 bg-cyan-50/10' : 'border-gray-100 hover:border-gray-200'">
                <div class="flex items-center justify-between">
                    <span class="block font-bold text-slate-800">Bunglon (Magic)</span>
                    <div class="w-5 h-5 rounded-full border flex items-center justify-center"
                         :class="$wire.selectedType === 'Bunglon (Magic)' ? 'border-cyan-500 bg-cyan-500 text-white' : 'border-gray-300'">
                        <x-heroicon-o-check x-show="$wire.selectedType === 'Bunglon (Magic)'" class="w-3.5 h-3.5" />
                    </div>
                </div>
                <span class="text-xs text-gray-400 mt-2 block leading-relaxed">Gambar muncul saat mug diisi air panas.</span>
            </button>

        </div>
    </div>

    <!-- Upload Desain -->
    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-4">
        <h3 class="text-lg font-bold text-slate-800">Unggah Desain</h3>
        <p class="text-xs text-gray-500">Unggah desain atau gambar yang ingin dicetak di mug. (Format: JPG, PNG, PDF)</p>
        <livewire:components.file-upload-handler />
        @error('uploadedFiles') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
    </div>

    <!-- Kuantitas & Catatan -->
    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 grid md:grid-cols-2 gap-8">
        
        <div class="space-y-4">
            <h3 class="text-lg font-bold text-slate-800">Catatan Khusus</h3>
            <textarea 
                wire:model="notes"
                placeholder="Deskripsikan posisi gambar, tambahan teks, atau warna dalam (jika pilih Warna Dalam)."
                class="w-full border-2 border-gray-100 rounded-2xl p-4 focus:border-cyan-500 outline-none h-28 text-sm resize-none"></textarea>
        </div>

        <div class="space-y-4 flex flex-col justify-between">
            <h3 class="text-lg font-bold text-slate-800">Jumlah Pesanan</h3>
            <div class="flex items-center gap-4">
                <button 
                    type="button"
                    wire:click="decrementQuantity"
                    class="w-12 h-12 border-2 border-gray-200 rounded-xl flex items-center justify-center font-bold text-lg hover:border-cyan-500 hover:text-cyan-500 transition cursor-pointer">-</button>
                <input 
                    type="number" 
                    wire:model="quantity"
                    class="w-20 text-center border-2 border-gray-150 rounded-xl p-2.5 font-bold text-lg outline-none">
                <button 
                    type="button"
                    wire:click="incrementQuantity"
                    class="w-12 h-12 border-2 border-gray-200 rounded-xl flex items-center justify-center font-bold text-lg hover:border-cyan-500 hover:text-cyan-500 transition cursor-pointer">+</button>
            </div>
            <p class="text-xs text-gray-400 leading-relaxed mt-2">Pesan lebih banyak untuk mendapatkan harga khusus grosir.</p>
        </div>

    </div>

    <!-- Sticky Bottom Price Calculator Display -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-3xl shadow-xl p-6 md:p-8 text-white sticky bottom-6 z-45 flex flex-col sm:flex-row items-center justify-between gap-6">
        
        <div class="space-y-1 w-full sm:w-auto">
            <div class="flex items-center justify-between sm:justify-start gap-4">
                <span class="text-blue-100 text-xs uppercase font-semibold tracking-wider">Harga Satuan:</span>
                <span class="font-bold text-sm">Rp{{ number_format($priceBreakdown['unit_price'] ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="flex items-baseline justify-between sm:justify-start gap-4 mt-2">
                <span class="text-blue-100 text-sm">Total Estimasi:</span>
                <span class="text-3xl font-extrabold">
                    Rp{{ number_format($calculatedPrice, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <button 
            type="button"
            wire:click="proceedToCheckout"
            class="bg-white text-blue-700 font-bold px-8 py-4 rounded-2xl hover:bg-blue-50 transition w-full sm:w-auto shadow-md text-center cursor-pointer">
            Lanjut ke Pembayaran
        </button>

    </div>

</div>
