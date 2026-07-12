<div class="space-y-8">
    
    <div class="grid md:grid-cols-2 gap-8">
        
        <!-- Ukuran Kaos -->
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-4">
            <h3 class="text-lg font-bold text-slate-800">Ukuran Kaos</h3>
            <div class="relative">
                <select 
                    wire:model="selectedSize"
                    class="w-full border-2 border-gray-100 rounded-2xl p-4 bg-gray-50/50 focus:border-cyan-500 focus:bg-white transition outline-none appearance-none font-medium text-slate-800">
                    <option value="S">S (Small)</option>
                    <option value="M">M (Medium)</option>
                    <option value="L">L (Large)</option>
                    <option value="XL">XL (Extra Large)</option>
                    <option value="XXL">XXL (Double XL) +Rp10.000</option>
                </select>
            </div>
            <p class="text-xs text-gray-400 mt-2">Pastikan ukuran sesuai dengan chart ukuran kami.</p>
        </div>

        <!-- Warna Kaos -->
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-4">
            <h3 class="text-lg font-bold text-slate-800">Warna Kaos</h3>
            <div class="relative">
                <select 
                    wire:model="selectedColor"
                    class="w-full border-2 border-gray-100 rounded-2xl p-4 bg-gray-50/50 focus:border-cyan-500 focus:bg-white transition outline-none appearance-none font-medium text-slate-800">
                    <option value="Hitam">Hitam</option>
                    <option value="Putih">Putih</option>
                    <option value="Navy">Navy (Biru Dongker)</option>
                    <option value="Merah">Merah Maroon</option>
                    <option value="Abu-abu">Abu-abu Misty</option>
                </select>
            </div>
        </div>

    </div>

    <!-- Bahan Kaos -->
    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-4">
        <h3 class="text-lg font-bold text-slate-800">Bahan Kaos</h3>
        <div class="grid md:grid-cols-2 gap-4">
            <label class="flex flex-col p-4 border-2 border-gray-100 rounded-2xl cursor-pointer hover:border-gray-200 transition"
                   :class="$wire.selectedMaterial === 'Cotton Combed 30s' && 'border-cyan-500 bg-cyan-50/10'">
                <div class="flex items-center">
                    <input 
                        type="radio" 
                        wire:model="selectedMaterial"
                        value="Cotton Combed 30s"
                        class="text-cyan-500 focus:ring-cyan-500 border-gray-300">
                    <span class="ml-3 font-bold text-slate-800">Cotton Combed 30s</span>
                </div>
                <span class="text-xs text-gray-500 mt-2 ml-7">Bahan standar distro. Nyaman, adem, dan tidak terlalu tebal.</span>
            </label>
            <label class="flex flex-col p-4 border-2 border-gray-100 rounded-2xl cursor-pointer hover:border-gray-200 transition"
                   :class="$wire.selectedMaterial === 'Cotton Combed 24s' && 'border-cyan-500 bg-cyan-50/10'">
                <div class="flex items-center">
                    <input 
                        type="radio" 
                        wire:model="selectedMaterial"
                        value="Cotton Combed 24s"
                        class="text-cyan-500 focus:ring-cyan-500 border-gray-300">
                    <span class="ml-3 font-bold text-slate-800">Cotton Combed 24s</span>
                </div>
                <span class="text-xs text-gray-500 mt-2 ml-7">Lebih tebal dari 30s. Sangat awet dan menyerap keringat dengan baik.</span>
            </label>
        </div>
    </div>

    <!-- Upload Desain -->
    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-4">
        <h3 class="text-lg font-bold text-slate-800">Unggah Desain Sablon</h3>
        <p class="text-xs text-gray-500">Unggah desain dalam format PNG transparan atau vektor (PDF/SVG) untuk hasil sablon terbaik.</p>
        <livewire:components.file-upload-handler />
        @error('uploadedFiles') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
    </div>

    <!-- Kuantitas & Catatan -->
    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 grid md:grid-cols-2 gap-8">
        
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
            <p class="text-xs text-gray-400 leading-relaxed mt-2">Dapatkan diskon otomatis untuk pemesanan di atas 12 pcs (1 lusin).</p>
        </div>

        <div class="space-y-4">
            <h3 class="text-lg font-bold text-slate-800">Catatan Sablon</h3>
            <textarea 
                wire:model="notes"
                placeholder="Contoh: Sablon di bagian dada kiri ukuran logo, dan punggung full A4."
                class="w-full border-2 border-gray-100 rounded-2xl p-4 focus:border-cyan-500 outline-none h-28 text-sm resize-none"></textarea>
        </div>

    </div>

    <!-- Sticky Bottom Price Calculator Display -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-3xl shadow-xl p-6 md:p-8 text-white sticky bottom-6 z-45 flex flex-col sm:flex-row items-center justify-between gap-6">
        
        <div class="space-y-1 w-full sm:w-auto">
            <div class="flex items-center justify-between sm:justify-start gap-4">
                <span class="text-blue-100 text-xs uppercase font-semibold tracking-wider">Harga per Kaos:</span>
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
