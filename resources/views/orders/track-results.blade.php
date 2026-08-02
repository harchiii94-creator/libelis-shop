@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-2">Hasil Pencarian Pesanan</h1>
        <p class="text-gray-600 mb-8">{{ $orders->total() }} pesanan ditemukan</p>

        @if($orders->count() > 0)
            <!-- List Pesanan -->
            <div class="space-y-4 mb-8">
                @foreach($orders as $order)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <!-- Kiri: Info Pesanan -->
                            <div>
                                <p class="text-sm text-gray-600">Nomor Pesanan</p>
                                <p class="text-2xl font-bold text-blue-600 mb-4">{{ $order->invoice_number }}</p>

                                <div class="space-y-2">
                                    <div>
                                        <p class="text-sm text-gray-600">Pemesan</p>
                                        <p class="font-semibold">{{ $order->buyer_name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">WhatsApp</p>
                                        <p class="font-semibold">{{ $order->buyer_phone }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Tanggal Pesan</p>
                                        <p class="font-semibold">{{ $order->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Kanan: Status & Total -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600">Status Pesanan</p>
                                    <div class="mt-1">
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                                            @if($order->order_status === 'delivered') bg-green-100 text-green-800
                                            @elseif($order->order_status === 'shipped') bg-blue-100 text-blue-800
                                            @elseif($order->order_status === 'processing') bg-yellow-100 text-yellow-800
                                            @elseif($order->order_status === 'confirmed') bg-purple-100 text-purple-800
                                            @else bg-gray-100 text-gray-800
                                            @endif
                                        ">
                                            {{ $order->order_status_label }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <p class="text-sm text-gray-600">Status Pembayaran</p>
                                    <div class="mt-1">
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                                            @if($order->payment_status === 'paid') bg-green-100 text-green-800
                                            @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif
                                        ">
                                            {{ $order->payment_status_label }}
                                        </span>
                                    </div>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600">Total Belanja</p>
                                    <p class="text-2xl font-bold text-blue-600 mt-1">{{ $order->formatted_total }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Produk Ringkas -->
                        <div class="py-4 border-t border-b mb-4">
                            <p class="text-sm text-gray-600 mb-2">{{ $order->items->count() }} Produk</p>
                            <div class="space-y-1">
                                @foreach($order->items->take(3) as $item)
                                    <p class="text-sm">
                                        {{ $item->quantity }}x <strong>{{ $item->product->name }}</strong>
                                    </p>
                                @endforeach
                                @if($order->items->count() > 3)
                                    <p class="text-sm text-gray-600">+{{ $order->items->count() - 3 }} produk lainnya</p>
                                @endif
                            </div>
                        </div>

                        <!-- Tombol -->
                        <div class="flex gap-3">
                            <a 
                                href="{{ route('order.detail', $order) }}" 
                                class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition"
                            >
                                Lihat Detail Lengkap
                            </a>
                            @if($order->payment_status === 'pending')
                                <a 
                                    href="{{ route('order.confirm-whatsapp', $order) }}" 
                                    class="flex-1 text-center bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition"
                                >
                                    Konfirmasi via WA
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @else
            <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-12 text-center">
                <p class="text-gray-600 mb-4">Tidak ada pesanan yang sesuai dengan pencarian Anda</p>
                <a 
                    href="{{ route('order.track') }}" 
                    class="inline-block text-blue-600 hover:text-blue-800 font-semibold"
                >
                    Kembali ke Pencarian
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
