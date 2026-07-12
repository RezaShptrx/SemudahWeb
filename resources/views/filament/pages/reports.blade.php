<x-filament-panels::page>
    <div style="display: flex; gap: 8px; margin-bottom: 24px; flex-wrap: wrap;">
        @foreach(['daily' => 'Harian', 'weekly' => 'Mingguan', 'monthly' => 'Bulanan', 'yearly' => 'Tahunan'] as $key => $label)
            <button
                wire:click="$set('period', '{{ $key }}')"
                style="padding: 8px 16px; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s; {{ $period === $key ? 'background-color: #06b6d4; color: white; border: 1px solid #06b6d4;' : 'background-color: transparent; color: inherit; border: 1px solid currentColor; opacity: 0.6;' }}"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>

    @php $stats = $this->getOrderStats(); @endphp
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <x-filament::section>
            <div style="display: flex; flex-direction: column; gap: 4px;">
                <span style="font-size: 12px; text-transform: uppercase; font-weight: 600; opacity: 0.7;">Total Pesanan</span>
                <span style="font-size: 28px; font-weight: bold;">{{ number_format($stats['total']) }}</span>
            </div>
        </x-filament::section>
        
        <x-filament::section>
            <div style="display: flex; flex-direction: column; gap: 4px;">
                <span style="font-size: 12px; text-transform: uppercase; font-weight: 600; opacity: 0.7;">Pesanan Selesai</span>
                <span style="font-size: 28px; font-weight: bold; color: #10b981;">{{ number_format($stats['completed']) }}</span>
            </div>
        </x-filament::section>

        <x-filament::section>
            <div style="display: flex; flex-direction: column; gap: 4px;">
                <span style="font-size: 12px; text-transform: uppercase; font-weight: 600; opacity: 0.7;">Pesanan Dibatalkan</span>
                <span style="font-size: 28px; font-weight: bold; color: #ef4444;">{{ number_format($stats['cancelled']) }}</span>
            </div>
        </x-filament::section>

        <x-filament::section>
            <div style="display: flex; flex-direction: column; gap: 4px;">
                <span style="font-size: 12px; text-transform: uppercase; font-weight: 600; opacity: 0.7;">Total Pendapatan</span>
                <span style="font-size: 28px; font-weight: bold; color: #3b82f6;">Rp{{ number_format($stats['revenue'], 0, ',', '.') }}</span>
            </div>
        </x-filament::section>
    </div>

    @php $revenue = $this->getRevenueData(); @endphp
    <div style="margin-bottom: 24px;">
        <x-filament::section>
            <x-slot name="heading">Grafik Pendapatan</x-slot>
            @if(count($revenue['labels']) > 0)
                <div style="height: 250px; display: flex; align-items: flex-end; justify-content: space-evenly; gap: 16px; margin-top: 16px;">
                    @php $max = max($revenue['values'] ?: [1]); @endphp
                    @foreach($revenue['values'] as $i => $val)
                        <div style="flex: 1; max-width: 60px; display: flex; flex-direction: column; align-items: center; gap: 4px;">
                            <span style="font-size: 11px; opacity: 0.7;">Rp{{ number_format($val / 1000, 0) }}k</span>
                            <div style="width: 100%; background-color: #06b6d4; border-radius: 4px 4px 0 0; height: {{ $max > 0 ? ($val / $max * 200) : 0 }}px; transition: height 0.3s ease;"></div>
                            <span style="font-size: 10px; opacity: 0.7; text-align: center; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 100%;" title="{{ $revenue['labels'][$i] }}">{{ $revenue['labels'][$i] }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="opacity: 0.6; font-size: 14px; margin-top: 16px;">Belum ada data pendapatan.</p>
            @endif
        </x-filament::section>
    </div>

    @php $products = $this->getTopProducts(); @endphp
    <x-filament::section>
        <x-slot name="heading">Produk Terlaris</x-slot>
        @if(count($products) > 0)
            <div style="overflow-x: auto; margin-top: 16px;">
                <table style="width: 100%; font-size: 14px; text-align: left; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(156, 163, 175, 0.2); opacity: 0.8;">
                            <th style="padding: 12px 8px;">#</th>
                            <th style="padding: 12px 8px;">Produk</th>
                            <th style="padding: 12px 8px; text-align: right;">Qty Terjual</th>
                            <th style="padding: 12px 8px; text-align: right;">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $i => $p)
                            <tr style="border-bottom: 1px solid rgba(156, 163, 175, 0.1);">
                                <td style="padding: 12px 8px; opacity: 0.6;">{{ $i + 1 }}</td>
                                <td style="padding: 12px 8px; font-weight: 500;">{{ $p['product_name'] }}</td>
                                <td style="padding: 12px 8px; text-align: right; opacity: 0.8;">{{ number_format($p['total_qty']) }}</td>
                                <td style="padding: 12px 8px; text-align: right; font-weight: 600;">Rp{{ number_format($p['total_revenue'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p style="opacity: 0.6; font-size: 14px; margin-top: 16px;">Belum ada data produk.</p>
        @endif
    </x-filament::section>
</x-filament-panels::page>
