<div class="space-y-8">
    
    <div class="grid md:grid-cols-2 gap-8">
        
        <!-- Ukuran Kertas -->
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-4">
            <h3 class="text-lg font-bold text-slate-800">Ukuran Kertas</h3>
            <div class="relative">
                <select 
                    wire:model="selectedSize"
                    class="w-full border-2 border-gray-100 rounded-2xl p-4 bg-gray-50/50 focus:border-cyan-500 focus:bg-white transition outline-none appearance-none font-medium text-slate-800">
                    <option value="A4">A4 (21.0 x 29.7 cm)</option>
                    <option value="F4">F4 / Folio (21.5 x 33.0 cm)</option>
                    <option value="A3">A3 (29.7 x 42.0 cm)</option>
                </select>
            </div>
        </div>

        <!-- Tipe Cetak (Bolak Balik) -->
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-4">
            <h3 class="text-lg font-bold text-slate-800">Tipe Fotocopy</h3>
            <div class="grid grid-cols-2 gap-4">
                <label class="flex items-center p-4 border-2 border-gray-100 rounded-2xl cursor-pointer hover:border-gray-200 transition"
                       :class="$wire.selectedType === 'Tidak' && 'border-cyan-500 bg-cyan-50/10'">
                    <input 
                        type="radio" 
                        wire:model="selectedType"
                        value="Tidak"
                        class="text-cyan-500 focus:ring-cyan-500 border-gray-300">
                    <span class="ml-3 font-semibold text-slate-800 text-sm">Satu Sisi</span>
                </label>
                <label class="flex items-center p-4 border-2 border-gray-100 rounded-2xl cursor-pointer hover:border-gray-200 transition"
                       :class="$wire.selectedType === 'Ya' && 'border-cyan-500 bg-cyan-50/10'">
                    <input 
                        type="radio" 
                        wire:model="selectedType"
                        value="Ya"
                        class="text-cyan-500 focus:ring-cyan-500 border-gray-300">
                    <span class="ml-3 font-semibold text-slate-800 text-sm">Bolak-Balik</span>
                </label>
            </div>
        </div>

    </div>

    <!-- Upload Berkas (Opsional) -->
    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-4">
        <h3 class="text-lg font-bold text-slate-800">Unggah Dokumen (Opsional)</h3>
        <p class="text-xs text-gray-500">Jika Anda memiliki softcopy, silakan unggah. Jika hardcopy, serahkan kepada petugas kami di lokasi.</p>
        <livewire:components.file-upload-handler />
        @error('uploadedFiles') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
    </div>

    <!-- Kuantitas & Salinan -->
    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 grid md:grid-cols-2 gap-8">
        
        <div class="space-y-4 flex flex-col justify-between">
            <h3 class="text-lg font-bold text-slate-800">Jumlah Halaman Asli</h3>
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
            <p class="text-xs text-gray-400 leading-relaxed mt-2">Berapa banyak halaman fisik yang akan difotocopy.</p>
        </div>

        <div class="space-y-4 flex flex-col justify-between">
            <h3 class="text-lg font-bold text-slate-800">Jumlah Salinan (Copy) per Halaman</h3>
            <div class="flex items-center gap-4">
                <button 
                    type="button"
                    wire:click="decrementCopies"
                    class="w-12 h-12 border-2 border-gray-200 rounded-xl flex items-center justify-center font-bold text-lg hover:border-cyan-500 hover:text-cyan-500 transition cursor-pointer">-</button>
                <input 
                    type="number" 
                    wire:model="copies"
                    class="w-20 text-center border-2 border-gray-150 rounded-xl p-2.5 font-bold text-lg outline-none">
                <button 
                    type="button"
                    wire:click="incrementCopies"
                    class="w-12 h-12 border-2 border-gray-200 rounded-xl flex items-center justify-center font-bold text-lg hover:border-cyan-500 hover:text-cyan-500 transition cursor-pointer">+</button>
            </div>
            <p class="text-xs text-gray-400 leading-relaxed mt-2">Berapa rangkap yang dibutuhkan.</p>
        </div>

    </div>

    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-4">
        <h3 class="text-lg font-bold text-slate-800">Catatan Tambahan</h3>
        <textarea 
            wire:model="notes"
            placeholder="Contoh: Tolong di-staples ujung kiri atas, dll."
            class="w-full border-2 border-gray-100 rounded-2xl p-4 focus:border-cyan-500 outline-none h-28 text-sm resize-none"></textarea>
    </div>

    <!-- Sticky Bottom Price Calculator Display -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-3xl shadow-xl p-6 md:p-8 text-white sticky bottom-6 z-45 flex flex-col sm:flex-row items-center justify-between gap-6">
        
        <div class="space-y-1 w-full sm:w-auto">
            <div class="flex items-center justify-between sm:justify-start gap-4">
                <span class="text-blue-100 text-xs uppercase font-semibold tracking-wider">Harga per Lembar:</span>
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
