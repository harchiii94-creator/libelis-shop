@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Success Message -->
        <div class="bg-green-50 border-2 border-green-500 rounded-lg p-8 text-center mb-8">
            <div class="text-5xl mb-4">✓</div>
            <h1 class="text-3xl font-bold text-green-700 mb-2">Pesanan Berhasil Dibuat!</h1>
            <p class="text-gray-700">Terima kasih telah berbelanja di Libelis Shop</p>
        </div>

        <!-- Detail Pesanan -->
        <div class="bg-white rounded-lg shadow-lg p-8 space-y-6">
            <!-- Invoice & Status -->
            <div class="grid grid-cols-2 gap-4 pb-6 border-b">
                <div>
                    <p class="text-gray-600 text-sm">Nomor Pesanan</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $order->invoice_number }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Status Pesanan</p>
                    <p class="text-lg font-semibold text-yellow-600">{{ $order->order_status_label }}</p>
                </div>
            </div>

            <!-- Info Pembayaran -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Informasi Pembayaran</h2>
                <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-700">Total Belanja</span>
                        <span class="font-bold text-lg">{{ $order->formatted_total }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-700">Metode Pembayaran</span>
                        <span class="font-semibold">{{ $order->payment_method_label }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-700">Batas Pembayaran</span>
                        <span class="font-semibold">{{ $order->payment_due_date->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Instruksi Pembayaran -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Instruksi Pembayaran</h2>
                
                @if($order->payment_method === 'cod')
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                        <p class="text-blue-800 font-semibold mb-2">💵 Cash on Delivery (COD)</p>
                        <p class="text-blue-700">{{ $order->payment_instructions }}</p>
                        <p class="text-blue-600 text-sm mt-3">
                            Admin akan menghubungi Anda untuk mengkonfirmasi pesanan melalui WhatsApp.
                        </p>
                    </div>
                @else
                    <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded">
                        <p class="text-purple-800 font-semibold mb-3">🏦 Transfer Bank Manual</p>
                        <div class="bg-white p-3 rounded mb-3 space-y-2">
                            <div>
                                <p class="text-gray-600 text-sm">Bank</p>
                                <p class="font-bold text-lg">{{ $order->bank_name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Nomor Rekening</p>
                                <p class="font-mono font-bold text-lg">{{ $order->bank_account_number }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Atas Nama</p>
                                <p class="font-bold">{{ $order->bank_account_holder }}</p>
                            </div>
                        </div>
                        <p class="text-purple-700 text-sm">
                            Transfer ke rekening di atas sebelum batas waktu pembayaran. Setelah transfer, 
                            konfirmasi pembayaran melalui sistem admin agar pesanan dapat segera diproses.
                        </p>
                    </div>
                @endif
            </div>

            <!-- Data Pemesan -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Data Pemesan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg">
                    <div>
                        <p class="text-gray-600 text-sm">Nama</p>
                        <p class="font-semibold">{{ $order->buyer_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">WhatsApp</p>
                        <p class="font-semibold">{{ $order->buyer_phone }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Email</p>
                        <p class="font-semibold">{{ $order->buyer_email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Kota</p>
                        <p class="font-semibold">{{ $order->city }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-gray-600 text-sm">Alamat Lengkap</p>
                        <p class="font-semibold">{{ $order->address }}</p>
                    </div>
                </div>
            </div>

            <!-- Detail Produk -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Detail Produk</h2>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                            <div>
                                <p class="font-semibold">{{ $item->product->name }}</p>
                                <p class="text-gray-600 text-sm">{{ $item->quantity }}x @ Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <p class="font-bold">Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Aksi -->
            <div class="pt-6 border-t space-y-3">
                <a 
                    href="{{ route('order.detail', $order) }}" 
                    class="block w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg text-center transition duration-200"
                >
                    Lihat Detail Pesanan
                </a>
                <a 
                    href="{{ route('home') }}" 
                    class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 rounded-lg text-center transition duration-200"
                >
                    Lanjut Belanja
                </a>
                <a 
                    href="{{ route('home') }}" 
                    class="block w-full text-center text-gray-600 hover:text-gray-800 py-2"
                >
                    Lanjut Belanja
                </a>
            </div>
        </div>

        <!-- Info Penting -->
        <div class="mt-8 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
            <p class="text-yellow-800 font-semibold mb-2">⚠️ Informasi Penting</p>
            <ul class="text-yellow-700 text-sm space-y-1 list-disc list-inside">
                <li>Simpan nomor pesanan Anda: <strong>{{ $order->invoice_number }}</strong></li>
                <li>Pembayaran harus dilakukan sebelum: <strong>{{ $order->payment_due_date->format('d M Y H:i') }}</strong></li>
                <li>Konfirmasi pembayaran akan diproses melalui sistem admin setelah pembayaran diterima</li>
                <li>Jika ada pertanyaan, silakan hubungi admin melalui WhatsApp</li>
            </ul>
        </div>
    </div>
</div>
@endsection
