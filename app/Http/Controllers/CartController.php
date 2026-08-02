<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get();

        $items = $products->map(function ($product) use ($cart) {
            return (object) [
                'product' => $product,
                'quantity' => $cart[$product->id] ?? 0,
            ];
        });

        $total = $items->reduce(fn ($sum, $item) => $sum + ($item->product->price * $item->quantity), 0);

        return view('cart.index', [
            'items' => $items,
            'total' => $total,
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'sometimes|integer|min:1',
        ]);

        $cart = session('cart', []);
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        $cart[$productId] = max(1, ($cart[$productId] ?? 0) + $quantity);
        session(['cart' => $cart]);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = session('cart', []);
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = $quantity;
        }

        session(['cart' => $cart]);

        return redirect()->route('cart.index');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $cart = session('cart', []);
        unset($cart[$request->input('product_id')]);
        session(['cart' => $cart]);

        return redirect()->route('cart.index');
    }

    public function checkout(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        $selectedProductIds = collect($request->input('selected_products', []))
            ->map(fn ($id) => intval($id))
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($selectedProductIds)) {
            return redirect()->route('cart.index')->with('error', 'Pilih minimal satu produk untuk checkout.');
        }

        $selectedCart = array_intersect_key($cart, array_flip($selectedProductIds));
        if (empty($selectedCart)) {
            return redirect()->route('cart.index')->with('error', 'Produk checkout tidak ditemukan di keranjang.');
        }

        $products = Product::whereIn('id', array_keys($selectedCart))->get();
        $subtotal = 0;
        $serviceFee = 2000;

        foreach ($products as $product) {
            $quantity = $selectedCart[$product->id] ?? 0;
            if ($quantity <= 0) {
                continue;
            }
            $subtotal += $product->price * $quantity;
        }

        return view('checkout.index', [
            'products' => $products,
            'cart' => $selectedCart,
            'subtotal' => $subtotal,
            'serviceFee' => $serviceFee,
            'total' => $subtotal + $serviceFee,
        ]);
    }
}
