<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(PromoCode::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:promo_codes,code|max:50',
            'discount_type' => 'required|string|in:percentage,fixed',
            'discount_amount' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
            'max_uses' => 'nullable|integer|min:1',
        ]);

        $promoCode = PromoCode::create($validated);
        return response()->json($promoCode, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(PromoCode $promoCode)
    {
        return response()->json($promoCode);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PromoCode $promoCode)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:promo_codes,code,' . $promoCode->id . '|max:50',
            'discount_type' => 'required|string|in:percentage,fixed',
            'discount_amount' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
            'max_uses' => 'nullable|integer|min:1',
        ]);

        $promoCode->update($validated);
        return response()->json($promoCode);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PromoCode $promoCode)
    {
        $promoCode->delete();
        return response()->json(null, 204);
    }
}
