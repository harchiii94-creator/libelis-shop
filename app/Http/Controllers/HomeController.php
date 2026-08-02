<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $bestSellers = Product::where('is_best_seller', true)
            ->orderByDesc('created_at')
            ->limit(4)
            ->get();

        $newArrivals = Product::where('is_new_arrival', true)
            ->orderByDesc('created_at')
            ->limit(4)
            ->get();

        $categories = Category::pluck('name', 'slug');

        $featuredProducts = Product::orderByDesc('created_at')
            ->limit(8)
            ->get();

        return view('home', compact('bestSellers', 'newArrivals', 'categories', 'featuredProducts'));
    }
}
