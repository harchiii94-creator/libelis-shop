<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderCancellationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_cancel_a_pending_order(): void
    {
        $user = User::factory()->create();

        $order = Order::create([
            'user_id' => $user->id,
            'buyer_name' => 'Budi',
            'buyer_phone' => '081234567890',
            'buyer_email' => 'budi@example.com',
            'city' => 'Bandung',
            'address' => 'Jl. Contoh No. 1',
            'total_price' => 50000,
            'payment_method' => 'transfer',
            'payment_status' => 'pending',
            'payment_due_date' => now()->addDay(),
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_holder' => 'PT Libelis',
            'order_status' => 'pending_payment',
        ]);

        $response = $this->actingAs($user)->post(route('order.cancel', $order));

        $response->assertRedirect(route('order.detail', $order));
        $response->assertSessionHas('success', 'Pesanan berhasil dibatalkan.');
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => 'cancelled',
            'payment_status' => 'failed',
        ]);
    }
}
