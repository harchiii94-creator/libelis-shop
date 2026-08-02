<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * Daftar semua pesanan
     */
    public function index(Request $request)
    {
        $query = Order::with('user', 'items.product');

        // Filter berdasarkan status pembayaran
        if ($request->has('payment_status') && $request->payment_status !== '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter berdasarkan status pesanan
        if ($request->has('order_status') && $request->order_status !== '') {
            $query->where('order_status', $request->order_status);
        }

        // Search berdasarkan nama atau nomor pesanan
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('buyer_name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhere('buyer_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest()->paginate(15);

        return view('admin.orders.index', [
            'orders' => $orders,
            'paymentStatuses' => [
                'pending' => 'Menunggu Pembayaran',
                'paid' => 'Lunas',
                'failed' => 'Pembayaran Gagal',
            ],
            'orderStatuses' => [
                'pending_payment' => 'Menunggu Pembayaran',
                'confirmed' => 'Dikonfirmasi',
                'processing' => 'Diproses',
                'shipped' => 'Dikirim',
                'delivered' => 'Diterima',
                'cancelled' => 'Dibatalkan',
            ],
        ]);
    }

    /**
     * Detail pesanan
     */
    public function show(Order $order)
    {
        $order->load('user', 'items.product');

        return view('admin.orders.show', ['order' => $order]);
    }

    /**
     * Edit pesanan - update status dan data pengiriman
     */
    public function edit(Order $order)
    {
        return view('admin.orders.edit', [
            'order' => $order,
            'paymentStatuses' => [
                'pending' => 'Menunggu Pembayaran',
                'paid' => 'Lunas',
                'failed' => 'Pembayaran Gagal',
            ],
            'orderStatuses' => [
                'pending_payment' => 'Menunggu Pembayaran',
                'confirmed' => 'Dikonfirmasi',
                'processing' => 'Diproses',
                'shipped' => 'Dikirim',
                'delivered' => 'Diterima',
                'cancelled' => 'Dibatalkan',
            ],
        ]);
    }

    /**
     * Update status pesanan dan pembayaran
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => 'sometimes|in:pending,paid,failed',
            'order_status' => 'sometimes|in:pending_payment,confirmed,processing,shipped,delivered,cancelled',
            'courier_name' => 'sometimes|string|max:255|nullable',
            'courier_service' => 'sometimes|string|max:255|nullable',
            'courier_tracking_number' => 'sometimes|string|max:255|nullable',
        ]);

        // Jika order status di-update ke status yang memerlukan pembayaran lunas, pastikan payment_status adalah 'paid'
        if (isset($validated['order_status']) && $validated['order_status'] !== 'pending_payment' && $validated['order_status'] !== 'cancelled') {
            if ($order->payment_status !== 'paid') {
                return back()->with('error', 'Pembayaran harus dikonfirmasi terlebih dahulu sebelum mengubah status pesanan.');
            }
        }

        $order->update($validated);

        $action = [];
        if (isset($validated['payment_status'])) {
            $action[] = "Status pembayaran diubah menjadi " . config('app.payment_status_labels.' . $validated['payment_status'], $validated['payment_status']);
        }
        if (isset($validated['order_status'])) {
            $statusLabel = match ($validated['order_status']) {
                'pending_payment' => 'Menunggu Pembayaran',
                'confirmed' => 'Dikonfirmasi',
                'processing' => 'Diproses',
                'shipped' => 'Dikirim',
                'delivered' => 'Diterima',
                'cancelled' => 'Dibatalkan',
                default => $validated['order_status'],
            };
            $action[] = "Status pesanan diubah menjadi {$statusLabel}";
        }
        if (isset($validated['courier_tracking_number'])) {
            $action[] = "Data pengiriman diperbarui";
        }

        $message = implode(', ', $action);

        return redirect()->route('admin.orders.show', $order)
                       ->with('success', $message . ' berhasil!');
    }

    /**
     * Konfirmasi pembayaran (update payment_status ke 'paid')
     */
    public function confirmPayment(Order $order)
    {
        if ($order->payment_status === 'paid') {
            return back()->with('warning', 'Pembayaran sudah dikonfirmasi sebelumnya.');
        }

        $order->update(['payment_status' => 'paid']);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }
}
