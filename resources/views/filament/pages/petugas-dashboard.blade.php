<x-filament-panels::page>
    <style>
        .clock-container {
            text-align: center;
            padding: 1rem 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }
        .clock-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem 1rem;
            background-color: rgba(6, 182, 212, 0.1);
            color: rgb(6, 182, 212);
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 9999px;
            border: 1px solid rgba(6, 182, 212, 0.2);
        }
        .clock-card {
            padding: 1rem 2rem;
            background-color: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid rgb(229, 231, 235);
            display: inline-block;
            min-width: 240px;
        }
        .dark .clock-card {
            background-color: rgb(24, 24, 27);
            border-color: rgba(255, 255, 255, 0.1);
        }
        .clock-time {
            font-family: monospace;
            font-size: 2.25rem;
            font-weight: 800;
            color: rgb(6, 182, 212);
            letter-spacing: 0.1em;
        }
        .clock-date {
            font-size: 0.875rem;
            color: rgb(107, 114, 128);
            font-weight: 600;
            margin-top: 0.5rem;
        }
        .dark .clock-date {
            color: rgb(156, 163, 175);
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        @media (min-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
        .custom-table-container {
            overflow-x: auto;
            margin: -1.5rem;
            margin-top: 0;
        }
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        .custom-table th {
            padding: 0.75rem 1.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            color: rgb(107, 114, 128);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background-color: rgb(249, 250, 251);
            border-bottom: 1px solid rgb(229, 231, 235);
        }
        .dark .custom-table th {
            color: rgb(156, 163, 175);
            background-color: rgba(255, 255, 255, 0.03);
            border-bottom-color: rgba(255, 255, 255, 0.1);
        }
        .custom-table td {
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            border-bottom: 1px solid rgb(229, 231, 235);
            vertical-align: middle;
        }
        .dark .custom-table td {
            border-bottom-color: rgba(255, 255, 255, 0.1);
        }
        .custom-table tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.01);
        }
        .dark .custom-table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.02);
        }
        .field-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: rgb(17, 24, 39);
        }
        .dark .field-label {
            color: rgb(243, 244, 246);
        }
    </style>

    <div class="space-y-6">

        {{-- Header with Digital Clock --}}
        <div class="clock-container">
            <div class="clock-badge">
                <x-heroicon-o-clock class="animate-pulse" style="width: 16px; height: 16px;" />
                Sistem Absensi Penjaga SEMUDAH
            </div>

            {{-- Digital Clock Widget --}}
            <div class="clock-card">
                <div x-data="{
                    time: '00:00:00',
                    init() {
                        this.updateClock();
                        setInterval(() => this.updateClock(), 1000);
                    },
                    updateClock() {
                        const now = new Date();
                        this.time = String(now.getHours()).padStart(2, '0') + ':' +
                                    String(now.getMinutes()).padStart(2, '0') + ':' +
                                    String(now.getSeconds()).padStart(2, '0');
                    }
                }" class="clock-time" x-text="time"></div>
                <div class="clock-date">
                    {{ today()->translatedFormat('l, d F Y') }}
                </div>
            </div>
        </div>

        {{-- Check-In Form Card --}}
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-heroicon-m-clipboard-document-check class="text-cyan-600 dark:text-cyan-400" style="width: 20px; height: 20px;" />
                    <span class="text-gray-900 dark:text-white">Form Absensi Masuk</span>
                </div>
            </x-slot>

            <form wire:submit.prevent="checkIn" class="space-y-4">
                <div class="form-grid">
                    {{-- Name Field --}}
                    <div class="space-y-2">
                        <label class="field-label">Nama Lengkap</label>
                        <x-filament::input.wrapper>
                            <x-filament::input
                                type="text"
                                wire:model="name"
                                placeholder="Masukkan nama Anda..."
                                required
                            />
                        </x-filament::input.wrapper>
                        @error('name')
                            <p class="text-sm text-danger-600 dark:text-danger-400 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Class Field --}}
                    <div class="space-y-2">
                        <label class="field-label">Kelas</label>
                        <x-filament::input.wrapper>
                            <x-filament::input
                                type="text"
                                wire:model="class"
                                placeholder="Contoh: XII-RPL-1"
                                required
                            />
                        </x-filament::input.wrapper>
                        @error('class')
                            <p class="text-sm text-danger-600 dark:text-danger-400 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Auto Major Info --}}
                <div class="space-y-2">
                    <label class="field-label">Jurusan</label>
                    <x-filament::input.wrapper disabled>
                        <x-filament::input
                            type="text"
                            value="Rekayasa Perangkat Lunak (RPL)"
                            disabled
                            readonly
                            class="cursor-not-allowed"
                        />
                        <x-slot name="suffix">
                            <span class="px-2 py-1 bg-cyan-50 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400 text-xs font-bold rounded">
                                Otomatis
                            </span>
                        </x-slot>
                    </x-filament::input.wrapper>
                </div>

                {{-- Submit Button --}}
                <div class="pt-2">
                    <x-filament::button
                        type="submit"
                        size="md"
                        color="primary"
                        icon="heroicon-s-arrow-right-end-on-rectangle"
                        wire:loading.attr="disabled"
                        wire:target="checkIn"
                    >
                        Absen Masuk (Check In)
                    </x-filament::button>
                </div>
            </form>
        </x-filament::section>

        {{-- Today's Attendance Log Table --}}
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-heroicon-m-list-bullet class="text-cyan-600 dark:text-cyan-400" style="width: 20px; height: 20px;" />
                    <span class="text-gray-900 dark:text-white">Log Absensi Hari Ini</span>
                </div>
            </x-slot>
            <x-slot name="headerEnd">
                <x-filament::badge color="info" size="sm" class="font-bold">
                    {{ $this->todayAttendances->count() }} Terdaftar
                </x-filament::badge>
            </x-slot>

            <div class="custom-table-container">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th style="width: 25%">Nama</th>
                            <th style="width: 15%">Kelas</th>
                            <th style="width: 15%">Jurusan</th>
                            <th style="width: 15%">Jam Masuk</th>
                            <th style="width: 15%">Jam Keluar</th>
                            <th style="width: 15%; text-align: right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->todayAttendances as $attendance)
                            <tr wire:key="att-{{ $attendance->id }}">
                                <td style="font-weight: 600;">
                                    {{ $attendance->name }}
                                </td>
                                <td>
                                    {{ $attendance->class }}
                                </td>
                                <td>
                                    <x-filament::badge color="gray" size="sm" class="font-medium">
                                        {{ $attendance->major }}
                                    </x-filament::badge>
                                </td>
                                <td style="font-family: monospace; font-weight: 600; color: #10b981;">
                                    {{ $attendance->check_in ? $attendance->check_in->format('H:i:s') : '-' }}
                                </td>
                                <td style="font-family: monospace; font-weight: 600;">
                                    @if ($attendance->check_out)
                                        <span style="color: #ef4444;">{{ $attendance->check_out->format('H:i:s') }}</span>
                                    @else
                                        <span style="color: #9ca3af;">Belum Keluar</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    @if ($attendance->check_out)
                                        <x-filament::badge color="success" icon="heroicon-m-check-circle" size="sm" class="font-bold" style="display: inline-flex;">
                                            Selesai
                                        </x-filament::badge>
                                    @else
                                        <x-filament::button
                                            wire:click="checkOut({{ $attendance->id }})"
                                            wire:target="checkOut({{ $attendance->id }})"
                                            color="danger"
                                            size="sm"
                                            icon="heroicon-m-arrow-left-start-on-rectangle"
                                        >
                                            Check Out
                                        </x-filament::button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 3rem 0; text-align: center;">
                                    <div class="flex flex-col items-center justify-center space-y-3" style="display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                                        <x-heroicon-o-clipboard-document class="text-gray-300 dark:text-gray-600" style="width: 48px; height: 48px;" />
                                        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Belum ada absensi hari ini</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">Daftar kehadiran petugas hari ini akan muncul di sini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>

    </div>
</x-filament-panels::page>
