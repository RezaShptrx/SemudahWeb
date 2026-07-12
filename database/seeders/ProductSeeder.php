<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'category_slug' => 'jasa-print',
                'name' => 'Cetak Dokumen A4',
                'description' => 'Cetak dokumen ukuran A4 berkualitas.',
                'base_price' => 500,
                'unit' => 'lembar',
                'requires_file_upload' => true,
            ],
            [
                'category_slug' => 'jasa-print',
                'name' => 'Cetak Dokumen F4',
                'description' => 'Cetak dokumen ukuran F4/Folio berkualitas.',
                'base_price' => 600,
                'unit' => 'lembar',
                'requires_file_upload' => true,
            ],
            [
                'category_slug' => 'jasa-fotocopy',
                'name' => 'Fotocopy A4',
                'description' => 'Fotocopy dokumen cepat ukuran A4.',
                'base_price' => 250,
                'unit' => 'lembar',
                'requires_file_upload' => false,
            ],
            [
                'category_slug' => 'jasa-fotocopy',
                'name' => 'Fotocopy F4',
                'description' => 'Fotocopy dokumen cepat ukuran F4/Folio.',
                'base_price' => 300,
                'unit' => 'lembar',
                'requires_file_upload' => false,
            ],
            [
                'category_slug' => 'custom-mug',
                'name' => 'Mug Putih Standar',
                'description' => 'Mug keramik putih standar tahan panas dengan cetak foto atau desain custom.',
                'base_price' => 15000,
                'unit' => 'pcs',
                'requires_file_upload' => true,
            ],
            [
                'category_slug' => 'custom-mug',
                'name' => 'Mug Warna Custom',
                'description' => 'Mug keramik dengan warna bagian dalam yang bervariasi dengan desain custom.',
                'base_price' => 25000,
                'unit' => 'pcs',
                'requires_file_upload' => true,
            ],
            [
                'category_slug' => 'custom-kaos',
                'name' => 'Kaos Cotton Combed 30s',
                'description' => 'Kaos custom bahan 100% cotton combed 30s premium yang sejuk dan nyaman dipakai.',
                'base_price' => 60000,
                'unit' => 'pcs',
                'requires_file_upload' => true,
            ],
            [
                'category_slug' => 'custom-tote-bag',
                'name' => 'Tote Bag Kanvas',
                'description' => 'Tote bag bahan kanvas tebal dan kuat dengan cetak sablon digital custom.',
                'base_price' => 35000,
                'unit' => 'pcs',
                'requires_file_upload' => true,
            ],
            [
                'category_slug' => 'custom-emoney',
                'name' => 'Kartu E-Money Custom',
                'description' => 'Kartu E-Money (Mandiri, BCA, BNI, BRI) dengan cetak custom desain pada 1 atau 2 sisi.',
                'base_price' => 45000,
                'unit' => 'pcs',
                'requires_file_upload' => true,
            ],
            [
                'category_slug' => 'custom-gantungan-kunci',
                'name' => 'Gantungan Kunci Akrilik',
                'description' => 'Gantungan kunci akrilik transparan dengan cetak dua sisi desain bebas.',
                'base_price' => 5000,
                'unit' => 'pcs',
                'requires_file_upload' => true,
            ],
        ];

        foreach ($products as $prod) {
            $category = Category::where('slug', $prod['category_slug'])->first();
            if ($category) {
                Product::firstOrCreate(
                    ['slug' => Str::slug($prod['name'])],
                    [
                        'category_id' => $category->id,
                        'name' => $prod['name'],
                        'description' => $prod['description'],
                        'base_price' => $prod['base_price'],
                        'unit' => $prod['unit'],
                        'requires_file_upload' => $prod['requires_file_upload'],
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
