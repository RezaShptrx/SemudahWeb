<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Product::with(['category', 'specifications'])->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->has('form_schema') && is_string($request->form_schema)) {
            $request->merge(['form_schema' => json_decode($request->form_schema, true)]);
        }
        if ($request->has('specifications') && is_string($request->specifications)) {
            $request->merge(['specifications' => json_decode($request->specifications, true)]);
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'is_active' => 'boolean',
            'requires_file_upload' => 'boolean',
            'specifications' => 'nullable|array',
            'specifications.*.spec_name' => 'required|string|max:255',
            'specifications.*.spec_value' => 'required|string|max:255',
            'specifications.*.price_modifier' => 'nullable|numeric',
            'form_schema' => 'nullable|array',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($validated);

        if (!empty($validated['specifications'])) {
            $specs = [];
            foreach ($validated['specifications'] as $index => $spec) {
                $specs[] = new \App\Models\ProductSpecification([
                    'spec_name' => $spec['spec_name'],
                    'spec_value' => $spec['spec_value'],
                    'price_modifier' => $spec['price_modifier'] ?? 0,
                    'display_order' => $index,
                    'is_active' => true,
                ]);
            }
            $product->specifications()->saveMany($specs);
        }

        return response()->json($product->load('specifications'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json($product->load(['category', 'specifications']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        if ($request->has('form_schema') && is_string($request->form_schema)) {
            $request->merge(['form_schema' => json_decode($request->form_schema, true)]);
        }
        if ($request->has('specifications') && is_string($request->specifications)) {
            $request->merge(['specifications' => json_decode($request->specifications, true)]);
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug,' . $product->id . '|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'is_active' => 'boolean',
            'requires_file_upload' => 'boolean',
            'specifications' => 'nullable|array',
            'specifications.*.spec_name' => 'required|string|max:255',
            'specifications.*.spec_value' => 'required|string|max:255',
            'specifications.*.price_modifier' => 'nullable|numeric',
            'form_schema' => 'nullable|array',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        if (isset($validated['specifications'])) {
            $product->specifications()->delete(); // Recreate them
            $specs = [];
            foreach ($validated['specifications'] as $index => $spec) {
                $specs[] = new \App\Models\ProductSpecification([
                    'spec_name' => $spec['spec_name'],
                    'spec_value' => $spec['spec_value'],
                    'price_modifier' => $spec['price_modifier'] ?? 0,
                    'display_order' => $index,
                    'is_active' => true,
                ]);
            }
            $product->specifications()->saveMany($specs);
        }

        return response()->json($product->load('specifications'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
