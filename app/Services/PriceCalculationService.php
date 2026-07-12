<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductSpecification;
use App\Models\PromoCode;

class PriceCalculationService
{
    public function calculatePrice(
        Product $product,
        array $specifications,
        int $quantity,
        ?PromoCode $promoCode = null
    ): array {
        // 1. Base price dari product
        $basePrice = $product->base_price;
        
        // 2. Add modifiers dari specifications
        foreach ($specifications as $specName => $specValue) {
            $cacheKey = "spec_modifier_{$product->id}_{$specName}_{$specValue}";
            $modifier = \Illuminate\Support\Facades\Cache::remember($cacheKey, 3600, function () use ($product, $specName, $specValue) {
                return ProductSpecification::where([
                    'product_id' => $product->id,
                    'spec_name' => $specName,
                    'spec_value' => $specValue
                ])->first()?->price_modifier ?? 0;
            });
            
            $basePrice += $modifier;
        }
        
        // 3. Kalkulasi berdasarkan quantity
        $subtotal = $basePrice * $quantity;
        
        // 4. Apply promo code jika ada
        $discount = $this->calculateDiscount($subtotal, $promoCode);
        
        // 5. Return breakdown
        return [
            'base_price' => $product->base_price,
            'unit_price' => $basePrice,
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'final_price' => max(0.00, $subtotal - $discount),
            'details' => [
                'product_name' => $product->name,
                'quantity' => $quantity,
                'specifications' => $specifications,
            ]
        ];
    }

    private function calculateDiscount(float $amount, ?PromoCode $promo = null): float
    {
        if (!$promo || !$promo->is_active) {
            return 0;
        }

        if ($promo->valid_from > now() || $promo->valid_until < now()) {
            return 0;
        }

        if ($promo->max_usage !== null && $promo->used_count >= $promo->max_usage) {
            return 0;
        }

        if ($amount < $promo->min_order_amount) {
            return 0;
        }
        
        if ($promo->discount_type->value === 'percentage') {
            return ($amount * $promo->discount_value) / 100;
        }
        
        return $promo->discount_value;
    }
}
