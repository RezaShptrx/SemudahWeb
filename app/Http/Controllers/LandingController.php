<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->orderBy('display_order')->get();
        // Removed products from here to optimize loading if not needed, or keep for a featured section. Let's keep it but pass it. Actually, we'll pass it to catalog.
        return view('landing.index', compact('categories'));
    }

    public function catalog(Request $request)
    {
        $categories = Category::where('is_active', true)->orderBy('display_order')->get();
        $query = Product::where('is_active', true)->with('category');

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $products = $query->get();

        return view('landing.catalog', compact('categories', 'products'));
    }
}
