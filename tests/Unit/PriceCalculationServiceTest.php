<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSpecification;
use App\Models\PromoCode;
use App\Services\PriceCalculationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PriceCalculationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $this->seed(\Database\Seeders\SettingsSeeder::class);
        $this->seed(\Database\Seeders\CategorySeeder::class);
        $this->seed(\Database\Seeders\ProductSeeder::class);
        $this->seed(\Database\Seeders\ProductSpecificationSeeder::class);
    }

    public function test_calculate_price_without_specifications(): void
    {
        $product = Product::where('slug', 'cetak-dokumen-a4')->first();
        $service = new PriceCalculationService();

        $breakdown = $service->calculatePrice($product, [], 10);

        $this->assertEquals(500.00, $breakdown['base_price']);
        $this->assertEquals(5000.00, $breakdown['subtotal']);
        $this->assertEquals(5000.00, $breakdown['final_price']);
    }

    public function test_calculate_price_with_specifications(): void
    {
        $product = Product::where('slug', 'cetak-dokumen-a4')->first();
        $service = new PriceCalculationService();

        $breakdown = $service->calculatePrice($product, [
            'warna' => 'Berwarna'
        ], 10);

        $this->assertEquals(1000.00, $breakdown['unit_price']); // 500 base + 500 berwarna modifier
        $this->assertEquals(10000.00, $breakdown['subtotal']);
        $this->assertEquals(10000.00, $breakdown['final_price']);
    }
}
