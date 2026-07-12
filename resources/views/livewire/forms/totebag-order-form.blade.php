<div class="space-y-8">
    
    <div class="grid md:grid-cols-2 gap-8">
        
        <!-- Ukuran Tote Bag -->
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-4">
            <h3 class="text-lg font-bold text-slate-800">Ukuran Tote Bag</h3>
            <div class="relative">
                <select 
                    wire:model="selectedSize"
                    class="w-full border-2 border-gray-100 rounded-2xl p-4 bg-gray-50/50 focus:border-cyan-500 focus:bg-white transition outline-none appearance-none font-medium text-slate-800">
                    <option value="30x40 cm">Standar (30 x 40 cm)</option>
                    <option value="35x45 cm">Besar (35 x 45 cm)</option>
                </select>
            </div>
        </div>

        <!-- Warna Tote Bag -->
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-4">
            <h3 class="text-lg font-bold text-slate-800">Warna Tote Bag</h3>
            <div class="relative">
                <select 
                    wire:model="selectedColor"
                    class="w-full border-2 border-gray-100 rounded-2xl p-4 bg-gray-50/50 focus:border-cyan-500 focus:bg-white transition outline-none appearance-none font-medium text-slate-800">
                    <option value="Natural">Natural / Broken White</option>
                    <option value="Hitam">Hitam</option>
                </select>
            </div>
        </div>

    </div>

    <!-- Bahan Tote Bag -->
    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-4">
        <h3 class="text-lg font-bold text-slate-800">Bahan Tote Bag</h3>
        <div class="grid md:grid-cols-2 gap-4">
            <label class="flex flex-col p-4 border-2 border-gray-100 rounded-2xl cursor-pointer hover:border-gray-200 transition"
                   :class="$wire.selectedMaterial === 'Kanvas' && 'border-cyan-500 bg-cyan-50/10'">
                <div class="flex items-center">
                    <input 
                        type="radio" 
                        wire:model="selectedMaterial"
                        value="Kanvas"
                        class="text-cyan-500 focus:ring-cyan-500 border-gray-300">
                    <span class="ml-3 font-bold text-slate-800">Kanvas Premium</span>
                </div>
                <span class="text-xs text-gray-500 mt-2 ml-7">Lebih tebal, kaku, dan awet. Tampilan lebih premium.</span>
            </label>
            <label class="flex flex-col p-4 border-2 border-gray-100 rounded-2xl cursor-pointer hover:border-gray-200 transition"
                   :class="$wire.selectedMaterial === 'Blacu' && 'border-cyan-500 bg-cyan-50/10'">
                <div class="flex items-center">
                    <input 
                        type="radio" 
                        wire:model="selectedMaterial"
                        value="Blacu"
                        class="text-cyan-500 focus:ring-cyan-500 border-gray-300">
                    <span class="ml-3 font-bold text-slate-800">Kain Blacu</span>
                </div>
                <span class="text-xs text-gray-500 mt-2 ml-7">Lebih tipis dan fleksibel. Ekonomis untuk souvenir/merchandise.</span>
            </label>
        </div>
    </div>

    <!-- Upload Desain -->
    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-4">
        <h3 class="text-lg font-bold text-slate-800">Unggah Desain Sablon</h3>
        <p class="text-xs text-gray-500">Unggah desain dalam format PNG transparan atau vektor (PDF/SVG).</p>
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
            <p class="text-xs text-gray-400 leading-relaxed mt-2">Dapatkan diskon untuk pembelian grosir.</p>
        </div>

        <div class="space-y-4">
            <h3 class="text-lg font-bold text-slate-800">Catatan Khusus</h3>
            <textarea 
                wire:model="notes"
                placeholder="Contoh: Sablon satu sisi saja, atau sablon dua sisi."
                class="w-full border-2 border-gray-100 rounded-2xl p-4 focus:border-cyan-500 outline-none h-28 text-sm resize-none"></textarea>
        </div>

    </div>

    <!-- Sticky Bottom Price Calculator Display -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-3xl shadow-xl p-6 md:p-8 text-white sticky bottom-6 z-45 flex flex-col sm:flex-row items-center justify-between gap-6">
        
        <div class="space-y-1 w-full sm:w-auto">
            <div class="flex items-center justify-between sm:justify-start gap-4">
                <span class="text-blue-100 text-xs uppercase font-semibold tracking-wider">Harga per Pcs:</span>
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
