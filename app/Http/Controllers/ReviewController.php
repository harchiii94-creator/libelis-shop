<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
        ]);

        $order = Order::where('id', $request->input('order_id'))
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($order->order_status !== 'delivered') {
            return back()->with('error', 'Ulasan hanya bisa diberikan setelah pesanan Anda diterima.');
        }

        $productIds = $request->input('product_id', []);
        if (! is_array($productIds)) {
            $productIds = [$productIds];
        }

        $ratings = $request->input('rating', []);
        $comments = $request->input('comment', []);

        if (empty($productIds)) {
            return back()->with('error', 'Pilih produk yang ingin Anda beri ulasan.');
        }

        foreach ($productIds as $key => $productId) {
            $product = Product::findOrFail($productId);

            $hasPurchasedProduct = $order->items()->where('product_id', $product->id)->exists();
            if (! $hasPurchasedProduct) {
                continue;
            }

            $rating = is_array($ratings) ? (int) ($ratings[$key] ?? 5) : (int) $ratings;
            $comment = is_array($comments) ? ($comments[$key] ?? null) : $comments;

            $existingReview = Review::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->where('order_id', $order->id)
                ->first();

            if ($existingReview) {
                $existingReview->update([
                    'rating' => $rating,
                    'comment' => $comment,
                ]);
                continue;
            }

            Review::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'order_id' => $order->id,
                'rating' => $rating,
                'comment' => $comment,
            ]);
        }

        return back()->with('success', 'Ulasan Anda berhasil dikirim.');
    }
}
