<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Process checkout form dan buat order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'buyer_name' => 'required|string|max:255',
            'buyer_phone' => 'required|string|max:20',
            'buyer_email' => 'required|email|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string',
            'payment_method' => 'required|in:cod,transfer',
            'selected_products' => 'required|array|min:1',
            'selected_products.*' => 'integer|exists:products,id',
        ]);

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
        $total = 0;
        $serviceFee = 2000;

        // Validasi stok
        foreach ($products as $product) {
            $quantity = $selectedCart[$product->id] ?? 0;
            if ($quantity > $product->stock) {
                return back()->with('error', "Stok {$product->name} tidak mencukupi.");
            }
            $total += $product->price * $quantity;
        }

        $total += $serviceFee;

        // Tentukan bank untuk transfer
        $bankData = null;
        if ($validated['payment_method'] === 'transfer') {
            // Ambil data bank dari config atau database
            // Untuk demo, hardcode BCA dan Mandiri
            $banks = [
                'bca' => [
                    'name' => 'BCA',
                    'account_number' => '1234567890',
                    'account_holder' => 'PT Libelis Shop',
                ],
                'mandiri' => [
                    'name' => 'Mandiri',
                    'account_number' => '9876543210',
                    'account_holder' => 'PT Libelis Shop',
                ],
            ];
            // Ambil bank secara random atau dari preference
            $bankData = collect($banks)->random();
        }

        // Buat Order
        $paymentDueDate = Carbon::now()->addDays(1); // Batas pembayaran 1 hari

        $order = Order::create([
            'user_id' => auth()->id(),
            'buyer_name' => $validated['buyer_name'],
            'buyer_phone' => $validated['buyer_phone'],
            'buyer_email' => $validated['buyer_email'],
            'city' => $validated['city'],
            'address' => $validated['address'],
            'total_price' => $total,
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'pending',
            'payment_due_date' => $paymentDueDate,
            'bank_name' => $bankData['name'] ?? null,
            'bank_account_number' => $bankData['account_number'] ?? null,
            'bank_account_holder' => $bankData['account_holder'] ?? null,
            'order_status' => 'pending_payment',
        ]);

        // Simpan detail pesanan dan update stok
        foreach ($products as $product) {
            $quantity = $selectedCart[$product->id];
            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
            
            $product->decrement('stock', $quantity);
        }

        // Hapus hanya produk yang dipesan dari cart session
        foreach ($selectedCart as $productId => $quantity) {
            unset($cart[$productId]);
        }
        session(['cart' => $cart]);

        // Jika Midtrans diaktifkan di config, buat transaksi Snap dan redirect
        if (config('midtrans.enabled') && $validated['payment_method'] === 'transfer') {
            try {
                $midtrans = app(\App\Services\MidtransService::class);

                $params = [
                    'transaction_details' => [
                        'order_id' => 'order-' . $order->id,
                        'gross_amount' => (int) $order->total_price,
                    ],
                    'customer_details' => [
                        'first_name' => $order->buyer_name,
                        'email' => $order->buyer_email,
                        'phone' => $order->buyer_phone,
                    ],
                    // Tell Midtrans which payment channels to show (configurable)
                    'enabled_payments' => config('midtrans.enabled_payments'),
                ];

                $response = $midtrans->createTransaction($params);

                // Midtrans Snap returns redirect_url for full-page redirect
                if (!empty($response->redirect_url)) {
                    return redirect()->away($response->redirect_url);
                }

                // fallback: go to order success page
            } catch (\Exception $e) {
                // Log but allow flow to continue to success page
                logger()->error('Midtrans create transaction failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('order.success', $order);
    }

    /**
     * Halaman pesanan berhasil dibuat
     */
    public function success(Order $order)
    {
        // Pastikan user hanya bisa lihat order mereka sendiri
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $midtransData = null;
        if (config('midtrans.enabled')) {
            try {
                \Midtrans\Config::$serverKey = config('midtrans.server_key');
                \Midtrans\Config::$isProduction = (bool) config('midtrans.is_production');

                // coba ambil status transaksi Midtrans dengan order id prefiks 'order-'
                $orderId = 'order-' . $order->id;
                $status = \Midtrans\Transaction::status($orderId);

                if (!empty($status)) {
                    $midtransData = $status;
                }
            } catch (\Exception $e) {
                logger()->warning('Midtrans status fetch failed: ' . $e->getMessage());
            }
        }

        return view('checkout.success', ['order' => $order, 'midtrans' => $midtransData]);
    }
}
