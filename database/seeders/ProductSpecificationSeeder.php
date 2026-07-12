<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductSpecification;
use Illuminate\Database\Seeder;

class ProductSpecificationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Cetak Dokumen A4 & F4
        $printProducts = Product::whereIn('slug', ['cetak-dokumen-a4', 'cetak-dokumen-f4'])->get();
        foreach ($printProducts as $product) {
            $specs = [
                ['name' => 'warna', 'value' => 'Hitam Putih', 'modifier' => 0],
                ['name' => 'warna', 'value' => 'Berwarna', 'modifier' => 500],
                ['name' => 'kualitas', 'value' => 'Standar', 'modifier' => 0],
                ['name' => 'kualitas', 'value' => 'High', 'modifier' => 300],
                ['name' => 'bolak_balik', 'value' => 'Tidak', 'modifier' => 0],
                ['name' => 'bolak_balik', 'value' => 'Ya', 'modifier' => -100], // Diskon per lembar jika bolak balik
            ];

            foreach ($specs as $index => $spec) {
                ProductSpecification::firstOrCreate(
                    [
                        'product_id' => $product->id,
                        'spec_name' => $spec['name'],
                        'spec_value' => $spec['value'],
                    ],
                    [
                        'price_modifier' => $spec['modifier'],
                        'display_order' => $index,
                        'is_active' => true,
                    ]
                );
            }
        }

        // 2. Kaos Cotton Combed 30s
        $tshirt = Product::where('slug', 'kaos-cotton-combed-30s')->first();
        if ($tshirt) {
            $specs = [
                ['name' => 'ukuran', 'value' => 'S', 'modifier' => 0],
                ['name' => 'ukuran', 'value' => 'M', 'modifier' => 0],
                ['name' => 'ukuran', 'value' => 'L', 'modifier' => 0],
                ['name' => 'ukuran', 'value' => 'XL', 'modifier' => 5000],
                ['name' => 'ukuran', 'value' => 'XXL', 'modifier' => 10000],
                ['name' => 'warna', 'value' => 'Hitam', 'modifier' => 0],
                ['name' => 'warna', 'value' => 'Putih', 'modifier' => 0],
                ['name' => 'warna', 'value' => 'Navy', 'modifier' => 3000],
                ['name' => 'sablon', 'value' => 'Satu Sisi (Depan)', 'modifier' => 0],
                ['name' => 'sablon', 'value' => 'Dua Sisi (Depan & Belakang)', 'modifier' => 15000],
            ];

            foreach ($specs as $index => $spec) {
                ProductSpecification::firstOrCreate(
                    [
                        'product_id' => $tshirt->id,
                        'spec_name' => $spec['name'],
                        'spec_value' => $spec['value'],
                    ],
                    [
                        'price_modifier' => $spec['modifier'],
                        'display_order' => $index,
                        'is_active' => true,
                    ]
                );
            }
        }

        // 3. Tote Bag Kanvas
        $totebag = Product::where('slug', 'tote-bag-kanvas')->first();
        if ($totebag) {
            $specs = [
                ['name' => 'warna', 'value' => 'Putih / Broken White', 'modifier' => 0],
                ['name' => 'warna', 'value' => 'Hitam', 'modifier' => 2000],
                ['name' => 'perekat', 'value' => 'Tanpa Perekat', 'modifier' => 0],
                ['name' => 'perekat', 'value' => 'Resleting', 'modifier' => 4000],
                ['name' => 'perekat', 'value' => 'Velcro', 'modifier' => 2000],
            ];

            foreach ($specs as $index => $spec) {
                ProductSpecification::firstOrCreate(
                    [
                        'product_id' => $totebag->id,
                        'spec_name' => $spec['name'],
                        'spec_value' => $spec['value'],
                    ],
                    [
                        'price_modifier' => $spec['modifier'],
                        'display_order' => $index,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
