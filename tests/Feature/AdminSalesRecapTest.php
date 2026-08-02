<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSalesRecapTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_shows_sales_recap_statistics_and_export_links(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        Order::create([
            'user_id' => $admin->id,
            'buyer_name' => 'Admin Buyer',
            'buyer_phone' => '081111111111',
            'buyer_email' => 'buyer@example.com',
            'city' => 'Jakarta',
            'address' => 'Jl. Admin',
            'total_price' => 120000,
            'payment_method' => 'cod',
            'payment_status' => 'paid',
            'payment_due_date' => now()->addDay(),
            'order_status' => 'delivered',
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Rekap Penjualan');
        $response->assertSee('Total Penjualan Hari Ini');
        $response->assertSee('Total Penjualan Bulan Ini');
        $response->assertSee('Total Pendapatan');
        $response->assertSee('Total Transaksi');
        $response->assertSee(route('admin.sales.export.pdf'));
        $response->assertSee(route('admin.sales.export.excel'));
    }
}
