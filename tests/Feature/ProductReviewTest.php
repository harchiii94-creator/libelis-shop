<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_review_for_delivered_order_item(): void
    {
        $user = User::factory()->create();
        $product = Product::create([
            'name' => 'Kopi Arabica',
            'category' => 'Minuman',
            'description' => 'Kopi premium',
            'image_url' => '/images/test.jpg',
            'price' => 50000,
            'stock' => 10,
            'is_best_seller' => false,
            'is_new_arrival' => false,
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'buyer_name' => 'Budi',
            'buyer_phone' => '081234567890',
            'buyer_email' => 'budi@example.com',
            'city' => 'Jakarta',
            'address' => 'Jl. Contoh No. 1',
            'total_price' => 50000,
            'payment_method' => 'cod',
            'payment_status' => 'paid',
            'payment_due_date' => now()->addDay(),
            'order_status' => 'delivered',
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 50000,
        ]);

        $response = $this->actingAs($user)->post(route('reviews.store'), [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'Produk sangat bagus',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'order_id' => $order->id,
            'rating' => 5,
            'comment' => 'Produk sangat bagus',
        ]);
    }
}
