<div class="w-full space-y-4">
    <!-- Drag & Drop Container -->
    <div x-data="{ isDragOver: false }"
         @dragover.prevent="isDragOver = true"
         @dragleave.prevent="isDragOver = false"
         @drop.prevent="isDragOver = false; $wire.uploadMultiple('files', $event.dataTransfer.files)"
         @click="$refs.fileInput.click()"
         :class="isDragOver ? 'border-cyan-500 bg-cyan-50 shadow-[0_10px_25px_rgba(14,165,233,0.15)] -translate-y-1' : 'border-gray-300 bg-gray-50/50 hover:bg-gray-100 hover:border-gray-400'"
         class="relative w-full rounded-2xl border-2 border-dashed flex flex-col items-center justify-center p-8 transition-all duration-300 cursor-pointer group overflow-hidden">
         
        <!-- Animated Background Decoration -->
        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-blue-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
         
        <div class="w-16 h-16 rounded-full bg-white shadow-sm flex items-center justify-center mb-4 text-cyan-500 group-hover:scale-110 transition-transform duration-300 z-10">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
            </svg>
        </div>
        
        <h3 class="text-lg font-bold text-slate-800 z-10">Klik atau Drag & Drop File</h3>
        <p class="text-sm font-medium text-slate-500 mt-2 text-center max-w-sm z-10">
            Pilih file dokumen atau desain Anda untuk diunggah ke sistem.
        </p>
        
        <div class="flex items-center justify-center gap-2 mt-4 z-10 flex-wrap">
            @foreach(explode(',', $acceptedTypes ?? '.pdf,.docx,.pptx,.jpg,.png') as $type)
                <span class="px-2.5 py-1 rounded-lg bg-white border border-gray-200 text-xs font-bold text-slate-600 shadow-sm uppercase tracking-wider">
                    {{ str_replace('.', '', $type) }}
                </span>
            @endforeach
        </div>
    </div>

    <!-- Hidden Input outside to avoid click bubbling loops -->
    <input 
        x-ref="fileInput"
        type="file"
        wire:model="files"
        multiple
        accept="{{ $acceptedTypes ?? '.pdf,.docx,.pptx,.jpg,.jpeg,.png' }}"
        class="hidden">

    <!-- Upload Progress Indicator -->
    <div wire:loading wire:target="files" class="w-full">
        <div class="bg-cyan-50 rounded-xl p-4 flex items-center justify-center gap-3 border border-cyan-100">
            <svg class="animate-spin h-5 w-5 text-cyan-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-cyan-700 font-semibold text-sm">Sedang mengunggah berkas... mohon tunggu.</span>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mt-2">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="text-red-700 font-bold text-sm mb-1">Gagal mengunggah berkas</p>
                    <ul class="text-red-600 text-xs list-disc pl-4 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
</div>
