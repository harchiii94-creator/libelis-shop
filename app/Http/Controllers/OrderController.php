<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Halaman lacak pesanan (search form)
     */
    public function track()
    {
        return view('orders.track');
    }

    /**
     * Search pesanan berdasarkan invoice atau nomor HP
     */
    public function search(Request $request)
    {
        $validated = $request->validate([
            'search_type' => 'required|in:invoice,phone',
            'search_value' => 'required|string|max:255',
        ]);

        $query = Order::query();

        if ($validated['search_type'] === 'invoice') {
            // Extract order ID dari invoice number (format: INV-XXXXXX-YYYYMMDD)
            $parts = explode('-', $validated['search_value']);
            if (count($parts) === 3 && is_numeric($parts[1])) {
                $orderId = (int) $parts[1];
                $query->where('id', $orderId);
            } else {
                return redirect()->route('order.track')->with('error', 'Format nomor pesanan tidak valid.');
            }
        } else {
            // Search by phone
            $query->where('buyer_phone', 'like', '%' . str_replace([' ', '-', '+62'], '', $validated['search_value']) . '%');
        }

        $orders = $query->latest()->paginate(10);

        if ($orders->isEmpty()) {
            return redirect()->route('order.track')->with('error', 'Pesanan tidak ditemukan.');
        }

        return view('orders.track-results', ['orders' => $orders, 'searchType' => $validated['search_type']]);
    }

    /**
     * Detail pesanan dengan timeline
     */
    public function show(Order $order)
    {
        $order->load(['items.product.reviews.user']);

        return view('orders.detail', ['order' => $order]);
    }

    /**
     * Daftar pesanan user
     */
    public function myOrders()
    {
        $orders = auth()->user()->orders()->latest()->paginate(10);
        return view('orders.my-orders', ['orders' => $orders]);
    }

    /**
     * Batalkan pesanan milik user yang sedang login
     */
    public function cancel(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->order_status === 'cancelled') {
            return back()->with('error', 'Pesanan ini sudah dibatalkan sebelumnya.');
        }

        if (!in_array($order->order_status, ['pending_payment', 'confirmed', 'processing'], true)) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan pada status saat ini.');
        }

        foreach ($order->items as $item) {
            if ($item->product_id) {
                Product::whereKey($item->product_id)->increment('stock', $item->quantity);
            }
        }

        $order->update([
            'order_status' => 'cancelled',
            'payment_status' => 'failed',
        ]);

        return redirect()->route('order.detail', $order)->with('success', 'Pesanan berhasil dibatalkan.');
    }

    /**
     * Generate link WhatsApp untuk konfirmasi pembayaran
     */
    public function confirmViaWhatsapp(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $message = "Halo, saya ingin mengkonfirmasi pembayaran pesanan saya.\n\n";
        $message .= "Nomor Pesanan: {$order->invoice_number}\n";
        $message .= "Total: {$order->formatted_total}\n";
        $message .= "Metode Pembayaran: {$order->payment_method_label}\n";

        if ($order->payment_method === 'transfer') {
            $message .= "\nSaya telah melakukan transfer ke rekening {$order->bank_name}.\n";
            $message .= "Nomor rekening: {$order->bank_account_number}\n";
        }

        $encodedMessage = urlencode($message);
        $adminPhone = env('ADMIN_WHATSAPP', '6281234567890');
        
        return redirect("https://wa.me/{$adminPhone}?text={$encodedMessage}");
    }
}
