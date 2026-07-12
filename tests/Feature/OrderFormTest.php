<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class OrderFormTest extends TestCase
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
        $this->seed(\Database\Seeders\UserSeeder::class);
    }

    public function test_print_order_form_renders_correctly(): void
    {
        $product = Product::where('slug', 'cetak-dokumen-a4')->first();

        $response = $this->get(route('order.show', $product->slug));
        
        $response->assertStatus(200);
        $response->assertSeeLivewire(\App\Livewire\Forms\PrintOrderForm::class);
    }

    public function test_print_order_form_calculates_price_initially(): void
    {
        $product = Product::where('slug', 'cetak-dokumen-a4')->first();

        Livewire::test(\App\Livewire\Forms\PrintOrderForm::class, ['product' => $product])
            ->assertSet('calculatedPrice', 500.00)
            ->set('selectedColor', 'Berwarna')
            ->assertSet('calculatedPrice', 1000.00);
    }

    public function test_order_tracking_page_loads_successfully(): void
    {
        $response = $this->get(route('order.tracking'));

        $response->assertStatus(200);
        $response->assertSee('Lacak Pesanan Anda');
    }

    public function test_order_success_page_loads_successfully(): void
    {
        $order = \App\Models\Order::factory()->create([
            'payment_method' => 'tunai',
        ]);

        $response = $this->get(route('order.success', $order->order_number));

        $response->assertStatus(200);
        $response->assertSee('Pemesanan Berhasil');
        $response->assertSee($order->order_number);
    }
}
