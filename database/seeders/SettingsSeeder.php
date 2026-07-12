<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'company_name',
                'value' => 'SEMUDAH',
                'type' => 'string',
                'description' => 'Nama Perusahaan/Usaha',
            ],
            [
                'key' => 'company_phone',
                'value' => '081234567890',
                'type' => 'string',
                'description' => 'Nomor Kontak Perusahaan',
            ],
            [
                'key' => 'company_address',
                'value' => 'Jl. Kebon Sirih No. 1, Jakarta Pusat',
                'type' => 'string',
                'description' => 'Alamat Perusahaan',
            ],
            [
                'key' => 'whatsapp_number',
                'value' => '6281234567890',
                'type' => 'string',
                'description' => 'Nomor WhatsApp untuk Notifikasi (Gunakan Kode Negara)',
            ],
            [
                'key' => 'file_auto_delete_days',
                'value' => '7',
                'type' => 'integer',
                'description' => 'Durasi berkas disimpan sebelum dihapus otomatis (dalam hari)',
            ],
            [
                'key' => 'midtrans_is_production',
                'value' => 'false',
                'type' => 'boolean',
                'description' => 'Apakah pembayaran Midtrans menggunakan mode produksi',
            ],
            [
                'key' => 'auto_delete_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Aktifkan auto-delete file',
            ]
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
