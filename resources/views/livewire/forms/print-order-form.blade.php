<div>
<div class="grid-trans">
    <!-- kiri -->
    <div>
        <!-- Card 1: Upload Dokumen -->
        <div class="card-trans">
            <h2 class="section-title-trans">Upload Dokumen</h2>
            <div class="min-h-[220px]">
                <livewire:components.file-upload-handler />
            </div>

            <!-- List of uploaded files with detected page counts -->
            @if(count($uploadedFiles) > 0)
            <div style="margin-top: 25px; display: flex; flex-direction: column; gap: 15px;">
                <p style="font-weight: 700; color: #1E293B; font-size: 16px;">{{ count($uploadedFiles) }} berkas terpilih:</p>
                @foreach($uploadedFiles as $index => $file)
                <div style="display: flex; justify-content: space-between; align-items: center; background: rgba(255, 255, 255, 0.6); padding: 18px; border-radius: 18px; border: 1px solid rgba(255, 255, 255, 0.8); box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div style="width: 45px; height: 45px; background: white; border-radius: 14px; display: flex; align-items: center; justify-content: center; color: #0ea5e9; border: 1px solid rgba(14, 165, 233, 0.2); box-shadow: 0 2px 8px rgba(14, 165, 233, 0.1);">
                            <svg style="width: 24px; height: 24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <p style="font-size: 15px; font-weight: 600; color: #1E293B; margin: 0;" title="{{ $file['original_name'] }}">{{ \Illuminate\Support\Str::limit($file['original_name'], 20) }}</p>
                            <p style="font-size: 13px; color: #64748B; margin: 2px 0 0 0;">
                                {{ round($file['size'] / 1024, 1) }} KB &bull; <strong style="color: #0ea5e9;">{{ $file['page_count'] ?? 1 }} Halaman</strong>
                            </p>
                        </div>
                    </div>
                    <button 
                        wire:click="handleFileRemoved({{ $index }})"
                        type="button"
                        style="background: transparent; border: none; color: #ef4444; cursor: pointer; padding: 10px; border-radius: 12px; display: flex; align-items: center; justify-content: center; transition: 0.2s;"
                        onmouseover="this.style.backgroundColor='rgba(239, 68, 68, 0.1)'"
                        onmouseout="this.style.backgroundColor='transparent'">
                        <svg style="width: 22px; height: 22px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
                @endforeach
            </div>
            @endif

            @error('uploadedFiles') 
                <span style="color: #ef4444; font-size: 14px; margin-top: 10px; display: block; font-weight: 500;">{{ $message }}</span> 
            @enderror
        </div>

        <!-- Card 2: Detail Print -->
        <div class="card-trans" style="margin-top: 30px;">
            <h2 class="section-title-trans">Detail Print</h2>

            <div class="row-trans">
                <div class="input-group-trans">
                    <label>Jenis Cetak</label>
                    <select wire:model.live="selectedColor" class="select-trans">
                        <option value="Hitam Putih">Hitam Putih</option>
                        <option value="Berwarna">Berwarna</option>
                    </select>
                </div>
                <div class="input-group-trans">
                    <label>Ukuran Kertas</label>
                    <select wire:model.live="selectedSize" class="select-trans">
                        <option value="A4">A4</option>
                        <option value="F4">F4</option>
                        <option value="A3">A3</option>
                    </select>
                </div>
            </div>

            <div class="row-trans">
                <div class="input-group-trans">
                    <label>Sisi Cetak</label>
                    <select wire:model.live="selectedType" class="select-trans">
                        <option value="Tidak">Satu Sisi</option>
                        <option value="Ya">Bolak Balik</option>
                    </select>
                </div>
                <div class="input-group-trans">
                    <label>Kualitas</label>
                    <select wire:model.live="selectedQuality" class="select-trans">
                        <option value="Draft">Draft</option>
                        <option value="Standar">Normal</option>
                        <option value="High">High Quality</option>
                    </select>
                </div>
            </div>

            <div class="row-trans">
                <div class="input-group-trans">
                    <label>Jumlah Copy</label>
                    <input type="number" wire:model.live="quantity" min="1" class="input-trans">
                </div>
                <div class="input-group-trans">
                    <label>Halaman</label>
                    <div class="flex flex-col sm:flex-row gap-2.5 sm:items-center">
                        <select wire:model.live="selectedPages" class="select-trans w-full sm:w-[35%]">
                            <option value="semua">Semua</option>
                            <option value="custom">Custom</option>
                        </select>
                        <input type="text" wire:model.live="customPageRange" placeholder="1-5 atau 1,3,7" class="input-trans w-full sm:w-[65%]" @if($selectedPages === 'semua') disabled style="opacity: 0.5; background: rgba(226, 232, 240, 0.5);" @endif>
                    </div>
                    @error('customPageRange') 
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block; font-weight: 500;">{{ $message }}</span> 
                    @enderror
                </div>
            </div>

            <div class="input-group-trans">
                <label>Catatan Tambahan</label>
                <textarea wire:model.live="notes" class="textarea-trans" placeholder="Mohon halaman terakhir jangan dicetak."></textarea>
            </div>
        </div>

        <!-- Card 3: Data Pemesan -->
        <div class="card-trans" style="margin-top: 30px;">
            <h2 class="section-title-trans">Data Pemesan</h2>

            <div class="row-trans">
                <div class="input-group-trans">
                    <label>Nama Lengkap</label>
                    <input type="text" wire:model.defer="customerName" placeholder="Masukkan nama Anda" class="input-trans">
                    @error('customerName') 
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block; font-weight: 500;">{{ $message }}</span> 
                    @enderror
                </div>
                <div class="input-group-trans">
                    <label>No. WhatsApp</label>
                    <input type="text" wire:model.defer="customerPhone" placeholder="08123456789" class="input-trans">
                    @error('customerPhone') 
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block; font-weight: 500;">{{ $message }}</span> 
                    @enderror
                </div>
            </div>

            <div class="input-group-trans" style="margin-top: 20px; margin-bottom: 0;">
                <label>Metode Pembayaran</label>
                <select wire:model.live="selectedPaymentMethod" class="select-trans">
                    <option value="qris">QRIS (Otomatis - Gopay, OVO, ShopeePay, Dana, dll.)</option>
                    <option value="tunai">Bayar di Toko (Tunai)</option>
                </select>
                @error('selectedPaymentMethod') 
                    <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block; font-weight: 500;">{{ $message }}</span> 
                @enderror
            </div>
        </div>
    </div>

    <!-- kanan -->
    <div>
        <div class="card-trans summary-trans">
            <h2 class="section-title-trans">Ringkasan Pesanan</h2>

            <div class="summary-item-trans">
                <span>Jumlah Halaman</span>
                <span class="value-trans">{{ $this->calculateTotalPages() }}</span>
            </div>
            <div class="summary-item-trans">
                <span>Jenis Print</span>
                <span class="value-trans">{{ $selectedColor }}</span>
            </div>
            <div class="summary-item-trans">
                <span>Jumlah Copy</span>
                <span class="value-trans">{{ $quantity }}</span>
            </div>

            <hr style="margin: 25px 0; border: none; border-top: 2px dashed rgba(148, 163, 184, 0.3);">

            <div class="summary-item-trans" style="align-items: center; margin-bottom: 25px;">
                <strong style="font-size: 18px; color: #1E293B;">Total</strong>
                <div class="total-trans">
                    Rp{{ number_format($calculatedPrice, 0, ',', '.') }}
                </div>
            </div>

            <button wire:click="submitOrder" class="button-trans" wire:loading.attr="disabled">
                <span wire:loading.remove>Buat Pesanan</span>
                <span wire:loading>Memproses...</span>
            </button>

            @if (session()->has('error'))
                <div style="margin-top: 15px; padding: 15px; border-radius: 10px; background-color: #fef2f2; color: #991b1b; font-size: 14px; border: 1px solid #fee2e2;">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>
</div>
</div>
