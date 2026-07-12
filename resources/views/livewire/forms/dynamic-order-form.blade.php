<div class="max-w-4xl mx-auto space-y-8">
    @if(session('error'))
        <div class="bg-red-50 text-red-500 p-4 rounded-xl border border-red-100 flex items-start gap-3">
            <x-heroicon-o-exclamation-triangle class="w-5 h-5 flex-shrink-0 mt-0.5" />
            <div>
                <h4 class="font-bold">Gagal memproses pesanan</h4>
                <p class="text-sm mt-1">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Form Section -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 md:p-8 shadow-sm dark:shadow-slate-900/50 border border-gray-100 dark:border-slate-700 space-y-6 transition-colors">
        <h3 class="text-xl font-bold text-slate-800 dark:text-white border-b border-gray-100 dark:border-slate-700 pb-4">Detail Pemesanan</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($schema as $field)
                <div class="{{ in_array($field['type'], ['textarea', 'file', 'header']) ? 'col-span-1 md:col-span-2' : '' }}">
                    @if($field['type'] === 'header')
                        <div class="mt-4 mb-2">
                            <h2 class="text-xl font-bold text-slate-800 dark:text-white">{{ $field['label'] }}</h2>
                            @if(!empty($field['description']))
                                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">{{ $field['description'] }}</p>
                            @endif
                        </div>
                    @else
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ $field['label'] }} @if($field['required']) <span class="text-red-500">*</span> @endif
                        </label>
                    @endif

                    @if($field['type'] === 'text')
                        <input type="text" wire:model.live="formData.{{ $field['name'] }}" 
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 dark:focus:ring-cyan-900/50 outline-none transition-all bg-gray-50/50 dark:bg-slate-700/50 dark:text-white" />
                    
                    @elseif($field['type'] === 'textarea')
                        <textarea wire:model.live="formData.{{ $field['name'] }}" rows="3"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 dark:focus:ring-cyan-900/50 outline-none transition-all bg-gray-50/50 dark:bg-slate-700/50 dark:text-white"></textarea>
                    
                    @elseif($field['type'] === 'number')
                        <input type="number" wire:model.live="formData.{{ $field['name'] }}" min="{{ $field['min'] ?? 1 }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 dark:focus:ring-cyan-900/50 outline-none transition-all bg-gray-50/50 dark:bg-slate-700/50 dark:text-white" />
                    
                    @elseif($field['type'] === 'select')
                        <select wire:model.live="formData.{{ $field['name'] }}"
                            class="w-full max-w-full truncate px-4 py-2.5 pr-10 rounded-xl border border-gray-200 dark:border-slate-600 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 dark:focus:ring-cyan-900/50 outline-none transition-all bg-gray-50/50 dark:bg-slate-700/50 dark:text-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236b7280%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E')] bg-[length:1em_1em] bg-[right_1rem_center] bg-no-repeat">
                            @foreach($field['options'] ?? [] as $opt)
                                <option value="{{ $opt['value'] }}">
                                    {{ $opt['label'] }} 
                                    @if(($opt['price_modifier'] ?? 0) > 0)
                                        (+Rp{{ number_format($opt['price_modifier'], 0, ',', '.') }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    
                    @elseif($field['type'] === 'radio')
                        <div class="space-y-3">
                            @foreach($field['options'] ?? [] as $opt)
                                <label class="flex items-center cursor-pointer group">
                                    <input type="radio" wire:model.live="formData.{{ $field['name'] }}" value="{{ $opt['value'] }}" class="peer sr-only" name="{{ $field['name'] }}">
                                    <div class="w-5 h-5 rounded-full border-2 border-gray-300 dark:border-slate-500 peer-checked:border-cyan-500 peer-checked:bg-cyan-500 flex items-center justify-center transition-all mr-3 relative">
                                        <div class="w-2 h-2 rounded-full bg-white opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                    </div>
                                    <span class="text-slate-700 dark:text-slate-300 font-medium group-hover:text-cyan-700 dark:group-hover:text-cyan-400 transition-colors">{{ $opt['label'] }}</span>
                                    @if(($opt['price_modifier'] ?? 0) > 0)
                                        <span class="ml-2 text-xs font-bold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 px-2 py-0.5 rounded-md">+Rp{{ number_format($opt['price_modifier'], 0, ',', '.') }}</span>
                                    @endif
                                </label>
                            @endforeach
                        </div>

                    @elseif($field['type'] === 'checkbox')
                        <div class="space-y-3">
                            @foreach($field['options'] ?? [] as $opt)
                                <label class="flex items-center cursor-pointer group">
                                    <input type="checkbox" wire:model.live="formData.{{ $field['name'] }}" value="{{ $opt['value'] }}" class="peer sr-only">
                                    <div class="w-5 h-5 rounded border-2 border-gray-300 dark:border-slate-500 peer-checked:border-cyan-500 peer-checked:bg-cyan-500 flex items-center justify-center transition-all mr-3">
                                        <svg class="w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <span class="text-slate-700 dark:text-slate-300 font-medium group-hover:text-cyan-700 dark:group-hover:text-cyan-400 transition-colors">{{ $opt['label'] }}</span>
                                    @if(($opt['price_modifier'] ?? 0) > 0)
                                        <span class="ml-2 text-xs font-bold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 px-2 py-0.5 rounded-md">+Rp{{ number_format($opt['price_modifier'], 0, ',', '.') }}</span>
                                    @endif
                                </label>
                            @endforeach
                        </div>

                    @elseif($field['type'] === 'date')
                        <input type="date" wire:model.live="formData.{{ $field['name'] }}" 
                            class="w-full md:w-1/2 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 dark:focus:ring-cyan-900/50 outline-none transition-all bg-gray-50/50 dark:bg-slate-700/50 dark:text-white" />
                            
                    @elseif($field['type'] === 'color')
                        <div class="flex items-center gap-3">
                            <input type="color" wire:model.live="formData.{{ $field['name'] }}" 
                                class="w-14 h-14 rounded-xl border-0 bg-transparent p-0 cursor-pointer overflow-hidden" />
                            <span class="text-sm font-medium text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-600 font-mono">{{ $formData[$field['name']] ?? '#000000' }}</span>
                        </div>
                    
                    @elseif($field['type'] === 'file')
                        <livewire:components.file-upload-handler 
                            :acceptedTypes="$field['accepted_types'] ?? '*'"
                            :maxFiles="5"
                            wire:key="file-upload-{{ $field['name'] }}"
                        />
                        
                        @if(count($uploadedFiles) > 0)
                            <div class="mt-4 space-y-3">
                                @foreach($uploadedFiles as $index => $file)
                                    <div class="flex items-center justify-between p-4 bg-white dark:bg-slate-700 border border-gray-100 dark:border-slate-600 shadow-sm rounded-2xl">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 text-blue-500 dark:text-blue-400 rounded-xl flex items-center justify-center shrink-0">
                                                <x-heroicon-o-document class="w-6 h-6" />
                                            </div>
                                            <div class="overflow-hidden">
                                                <p class="font-bold text-slate-700 dark:text-slate-200 text-sm truncate w-48 md:w-64">{{ $file['original_name'] }}</p>
                                                <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">{{ number_format($file['size'] / 1024, 2) }} KB</p>
                                            </div>
                                        </div>
                                        <button type="button" wire:click="handleFileRemoved({{ $index }})" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                            <x-heroicon-o-trash class="w-5 h-5" />
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif

                    @error('formData.' . $field['name'])
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </div>
        
        @error('uploadedFiles')
            <p class="text-red-500 text-sm mt-2 font-medium">{{ $message }}</p>
        @enderror
    </div>

    <!-- Informasi Pemesan & Pembayaran -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 md:p-8 shadow-sm dark:shadow-slate-900/50 border border-gray-100 dark:border-slate-700 space-y-6 transition-colors">
        <h3 class="text-xl font-bold text-slate-800 dark:text-white border-b border-gray-100 dark:border-slate-700 pb-4">Informasi Pemesan</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" wire:model.defer="customerName" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 dark:focus:ring-cyan-900/50 outline-none transition-all bg-gray-50/50 dark:bg-slate-700/50 dark:text-white" placeholder="Masukkan nama Anda">
                @error('customerName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">No. WhatsApp <span class="text-red-500">*</span></label>
                <input type="text" wire:model.defer="customerPhone" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 dark:focus:ring-cyan-900/50 outline-none transition-all bg-gray-50/50 dark:bg-slate-700/50 dark:text-white" placeholder="Contoh: 08123456789">
                @error('customerPhone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Catatan Tambahan (Opsional)</label>
                <textarea wire:model.defer="notes" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 dark:focus:ring-cyan-900/50 outline-none transition-all bg-gray-50/50 dark:bg-slate-700/50 dark:text-white" placeholder="Catatan untuk penjual..."></textarea>
            </div>
        </div>
        
        <h3 class="text-xl font-bold text-slate-800 dark:text-white border-b border-gray-100 dark:border-slate-700 pb-4 pt-4 mt-8">Metode Pembayaran</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <label class="relative cursor-pointer group">
                <input type="radio" wire:model="selectedPaymentMethod" value="qris" class="peer sr-only" name="payment_method">
                <div class="p-4 rounded-xl border-2 border-gray-100 dark:border-slate-700 hover:border-cyan-200 dark:hover:border-cyan-700 peer-checked:border-cyan-500 peer-checked:bg-cyan-50 dark:peer-checked:bg-cyan-900/20 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white dark:bg-slate-700 rounded-lg flex items-center justify-center border border-gray-100 dark:border-slate-600 shadow-sm shrink-0">
                            <svg class="w-6 h-6 text-cyan-600 dark:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                        </div>
                        <div>
                            @php
                                $qrisFee = (float) (\App\Models\Setting::where('key', 'payment_qris_fee')->value('value') ?? 0);
                            @endphp
                            <p class="font-bold text-slate-800 dark:text-white">QRIS / E-Wallet</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                Bayar instan via Gopay, OVO, Dana, dll
                                @if($qrisFee > 0)
                                    <span class="text-cyan-600 font-semibold block mt-0.5">(+ Biaya Admin Rp{{ number_format($qrisFee, 0, ',', '.') }})</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </label>
            
            <label class="relative cursor-pointer group">
                <input type="radio" wire:model="selectedPaymentMethod" value="tunai" class="peer sr-only" name="payment_method">
                <div class="p-4 rounded-xl border-2 border-gray-100 dark:border-slate-700 hover:border-cyan-200 dark:hover:border-cyan-700 peer-checked:border-cyan-500 peer-checked:bg-cyan-50 dark:peer-checked:bg-cyan-900/20 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white dark:bg-slate-700 rounded-lg flex items-center justify-center border border-gray-100 dark:border-slate-600 shadow-sm shrink-0">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 dark:text-white">Bayar di Tempat</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Bayar saat pesanan diambil di toko</p>
                        </div>
                    </div>
                </div>
            </label>
        </div>
        @error('selectedPaymentMethod') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Ringkasan & Checkout -->
    <div class="bg-slate-900 rounded-3xl p-6 md:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-blue-500/20 to-cyan-400/0 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <p class="text-blue-200 font-medium mb-1">Total Pembayaran</p>
                <div class="text-3xl md:text-4xl font-bold">
                    Rp{{ number_format($calculatedPrice, 0, ',', '.') }}
                </div>
            </div>
            
            <button wire:click="submitOrder" wire:loading.attr="disabled" class="w-full md:w-auto bg-gradient-to-r from-cyan-400 to-blue-500 hover:from-cyan-300 hover:to-blue-400 text-white px-8 py-4 rounded-xl font-bold text-lg shadow-lg shadow-blue-500/30 transition-all hover:scale-105 active:scale-95 disabled:opacity-70 flex items-center justify-center gap-2">
                <span wire:loading.remove wire:target="submitOrder">Buat Pesanan</span>
                <span wire:loading wire:target="submitOrder">Memproses...</span>
                <x-heroicon-o-arrow-right wire:loading.remove wire:target="submitOrder" class="w-5 h-5" />
            </button>
        </div>
    </div>
</div>
