@extends('layouts.admin')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-start mb-8">
        <div>
            <h1 class="text-3xl font-bold">Detail Pesanan</h1>
            <p class="text-gray-600">{{ $order->invoice_number }}</p>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-600">Status Pesanan</p>
            <p class="text-2xl font-bold text-blue-600">{{ $order->order_status_label }}</p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <p class="text-green-800">✓ {{ session('success') }}</p>
        </div>
    @endif

    @if(session('warning'))
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <p class="text-yellow-800">⚠ {{ session('warning') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Informasi Pembeli -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Informasi Pembeli</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-gray-600 text-sm">Nama</p>
                    <p class="font-semibold">{{ $order->buyer_name }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">WhatsApp</p>
                    <p class="font-semibold">
                        <a href="https://wa.me/{{ $order->buyer_phone }}" target="_blank" class="text-green-600 hover:text-green-800">
                            {{ $order->buyer_phone }} ↗
                        </a>
                    </p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Email</p>
                    <p class="font-semibold">{{ $order->buyer_email }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Kota</p>
                    <p class="font-semibold">{{ $order->city }}</p>
                </div>
                <div class="pt-3 border-t">
                    <p class="text-gray-600 text-sm">Alamat Lengkap</p>
                    <p class="text-sm">{{ $order->address }}</p>
                </div>
                <div class="pt-3 border-t">
                    <p class="text-gray-600 text-sm">Tanggal Pesanan</p>
                    <p class="font-semibold">{{ $order->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Informasi Pembayaran -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Informasi Pembayaran</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-gray-600 text-sm">Metode Pembayaran</p>
                    <p class="font-semibold">{{ $order->payment_method_label }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Status Pembayaran</p>
                    <p class="text-lg font-semibold">
                        <span class="px-3 py-1 rounded-full {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : ($order->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $order->payment_status_label }}
                        </span>
                    </p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Total Belanja</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $order->formatted_total }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Batas Pembayaran</p>
                    <p class="font-semibold">{{ $order->payment_due_date->format('d M Y H:i') }}</p>
                </div>

                @if($order->payment_method === 'transfer')
                    <div class="pt-3 border-t">
                        <p class="text-gray-600 text-sm mb-2">Rekening Transfer</p>
                        <div class="bg-gray-50 p-2 rounded text-sm">
                            <p class="font-semibold">{{ $order->bank_name }}</p>
                            <p class="font-mono">{{ $order->bank_account_number }}</p>
                            <p class="text-gray-600">{{ $order->bank_account_holder }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Aksi Cepat</h2>
            <div class="space-y-3">
                <a 
                    href="{{ route('admin.orders.edit', $order) }}" 
                    class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg text-center transition"
                >
                    Edit Pesanan
                </a>

                @if($order->payment_status === 'pending')
                    <form action="{{ route('admin.orders.confirm-payment', $order) }}" method="POST">
                        @csrf
                        <button 
                            type="submit" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition"
                        >
                            ✓ Konfirmasi Pembayaran
                        </button>
                    </form>
                @else
                    <button 
                        type="button" 
                        disabled
                        class="w-full bg-gray-300 text-gray-600 font-semibold py-2 rounded-lg cursor-not-allowed"
                    >
                        ✓ Pembayaran Sudah Dikonfirmasi
                    </button>
                @endif

                <a 
                    href="https://wa.me/{{ $order->buyer_phone }}" 
                    target="_blank"
                    class="block w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 rounded-lg text-center transition"
                >
                    Hubungi via WhatsApp
                </a>

                <a 
                    href="{{ route('admin.orders.index') }}" 
                    class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 rounded-lg text-center transition"
                >
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Detail Produk -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Detail Produk</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="text-left py-2 px-4">Produk</th>
                        <th class="text-center py-2 px-4">Qty</th>
                        <th class="text-right py-2 px-4">Harga</th>
                        <th class="text-right py-2 px-4">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr class="border-b">
                            <td class="py-3 px-4">
                                <p class="font-semibold">{{ $item->product->name }}</p>
                                <p class="text-sm text-gray-600">SKU: {{ $item->product->id }}</p>
                            </td>
                            <td class="text-center py-3 px-4">{{ $item->quantity }}</td>
                            <td class="text-right py-3 px-4">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="text-right py-3 px-4 font-semibold">Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-4 border-t-2 border-gray-300 flex justify-end">
            <div class="w-64">
                <div class="flex justify-between py-2 text-lg font-bold bg-blue-50 p-3 rounded">
                    <span>Total</span>
                    <span class="text-blue-600">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Pengiriman -->
    @if($order->courier_tracking_number || $order->order_status !== 'pending_payment')
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Data Pengiriman</h2>
            
            @if($order->courier_tracking_number)
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">Nama Kurir</p>
                        <p class="font-semibold">{{ $order->courier_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Layanan Kurir</p>
                        <p class="font-semibold">{{ $order->courier_service }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Nomor Resi</p>
                        <p class="font-mono font-semibold">{{ $order->courier_tracking_number }}</p>
                    </div>
                </div>
            @else
                <p class="text-gray-600">Data pengiriman belum diisi</p>
            @endif
        </div>
    @endif
</div>
@endsection
