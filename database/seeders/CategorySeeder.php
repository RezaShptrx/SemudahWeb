<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Jasa Print',
                'type' => 'jasa',
                'description' => 'Layanan cetak dokumen, tugas sekolah, dokumen kantor, brosur, dll.',
                'icon' => 'printer',
                'display_order' => 1,
            ],
            [
                'name' => 'Jasa Fotocopy',
                'type' => 'jasa',
                'description' => 'Layanan penggandaan dokumen cepat.',
                'icon' => 'document-duplicate',
                'display_order' => 2,
            ],
            [
                'name' => 'Custom Mug',
                'type' => 'produk',
                'description' => 'Mug custom berkualitas tinggi dengan foto atau desain sendiri.',
                'icon' => 'archive-box',
                'display_order' => 3,
            ],
            [
                'name' => 'Custom Kaos',
                'type' => 'produk',
                'description' => 'Cetak kaos sablon custom satuan hingga lusinan dengan bahan katun premium.',
                'icon' => 'tag',
                'display_order' => 4,
            ],
            [
                'name' => 'Custom Tote Bag',
                'type' => 'produk',
                'description' => 'Tote bag kanvas berkualitas dengan cetak desain custom.',
                'icon' => 'shopping-bag',
                'display_order' => 5,
            ],
            [
                'name' => 'Custom E-Money',
                'type' => 'produk',
                'description' => 'Cetak kartu E-Money custom dengan desain foto atau logo sesuai keinginan.',
                'icon' => 'credit-card',
                'display_order' => 6,
            ],
            [
                'name' => 'Custom Gantungan Kunci',
                'type' => 'produk',
                'description' => 'Gantungan kunci akrilik custom desain bebas untuk souvenir atau hadiah.',
                'icon' => 'key',
                'display_order' => 7,
            ]
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'type' => $cat['type'],
                    'description' => $cat['description'],
                    'icon' => $cat['icon'],
                    'display_order' => $cat['display_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
