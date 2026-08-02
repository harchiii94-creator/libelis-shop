<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::pluck('name', 'slug');

        $query = Product::query();
        $activeCategory = $request->query('category');
        $search = $request->query('search');

        if ($activeCategory) {
            $categoryName = Category::where('slug', $activeCategory)->value('name') ?: $activeCategory;
            $query->where('category', $categoryName);
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $products = $query->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('products.index', compact('products', 'categories', 'activeCategory'));
    }

    public function show($id)
    {
        $product = Product::with(['reviews.user'])->findOrFail($id);

        $categories = Category::pluck('name', 'slug');

        return view('products.show', compact('product', 'categories'));
    }
}
