@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
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

        @if($order->order_status === 'cancelled')
            <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                Pesanan ini telah dibatalkan dan stok produk sudah dikembalikan.
            </div>
        @endif

        @if($order->isCancellable())
            <form action="{{ route('order.cancel', $order) }}" method="POST" class="mb-6" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                @csrf
                <button type="submit" class="rounded-full border border-rose-300 bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700 transition hover:bg-rose-100">
                    Batalkan Pesanan
                </button>
            </form>
        @endif

        <!-- Timeline Status -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-xl font-semibold mb-6">Status Pengiriman</h2>
            
            <div class="relative">
                @foreach($order->status_timeline as $index => $status)
                    <div class="flex">
                        <!-- Timeline dot -->
                        <div class="flex flex-col items-center mr-6">
                            <div class="flex items-center justify-center w-12 h-12 rounded-full {{ $status['completed'] ? 'bg-green-500' : 'bg-gray-300' }} mb-2">
                                @if($status['completed'])
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <div class="w-6 h-6 rounded-full border-2 border-gray-400"></div>
                                @endif
                            </div>
                            @if($index < count($order->status_timeline) - 1)
                                <div class="w-1 h-12 bg-gray-300"></div>
                            @endif
                        </div>

                        <!-- Timeline content -->
                        <div class="pb-8">
                            <p class="text-lg font-semibold {{ $status['completed'] ? 'text-green-600' : 'text-gray-600' }}">
                                {{ $status['label'] }}
                            </p>
                            @if($status['date'])
                                <p class="text-sm text-gray-500">
                                    {{ $status['date']->format('d M Y H:i') }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Data Pembeli -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-xl font-semibold mb-4">Data Pemesan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-600 text-sm">Nama Lengkap</p>
                    <p class="text-lg font-semibold">{{ $order->buyer_name }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Nomor WhatsApp</p>
                    <p class="text-lg font-semibold">{{ $order->buyer_phone }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Email</p>
                    <p class="text-lg font-semibold">{{ $order->buyer_email }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Kota Pengiriman</p>
                    <p class="text-lg font-semibold">{{ $order->city }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-gray-600 text-sm">Alamat Lengkap</p>
                    <p class="text-lg font-semibold">{{ $order->address }}</p>
                </div>
            </div>
        </div>

        <!-- Informasi Pembayaran -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-xl font-semibold mb-4">Informasi Pembayaran</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="text-gray-600 text-sm">Metode Pembayaran</p>
                    <p class="text-lg font-semibold">{{ $order->payment_method_label }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Status Pembayaran</p>
                    <p class="text-lg font-semibold">
                        <span class="px-3 py-1 rounded-full text-sm {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : ($order->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $order->payment_status_label }}
                        </span>
                    </p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Total Pesanan</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $order->formatted_total }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Batas Pembayaran</p>
                    <p class="text-lg font-semibold">
                        {{ $order->payment_due_date->format('d M Y H:i') }}
                        @if($order->isPaymentOverdue())
                            <span class="text-red-600 text-sm">(Terlewat)</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Instruksi Pembayaran -->
            @if($order->payment_status === 'pending')
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                    <p class="text-yellow-800 font-semibold mb-3">📋 Instruksi Pembayaran</p>
                    <div class="text-yellow-700 whitespace-pre-wrap text-sm">{{ $order->payment_instructions }}</div>
                </div>
            @elseif($order->payment_method === 'transfer')
                <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded">
                    <p class="text-purple-800 font-semibold mb-3">🏦 Detail Rekening Transfer</p>
                    <div class="bg-white p-3 rounded space-y-2">
                        <div>
                            <p class="text-gray-600 text-sm">Bank</p>
                            <p class="font-bold">{{ $order->bank_name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Nomor Rekening</p>
                            <p class="font-mono font-bold">{{ $order->bank_account_number }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Atas Nama</p>
                            <p class="font-bold">{{ $order->bank_account_holder }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Detail Produk -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-xl font-semibold mb-4">Detail Produk Pesanan</h2>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-300">
                            <th class="text-left py-2 px-2">Produk</th>
                            <th class="text-center py-2 px-2">Qty</th>
                            <th class="text-right py-2 px-2">Harga</th>
                            <th class="text-right py-2 px-2">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr class="border-b border-gray-200">
                                <td class="py-3 px-2">
                                    <p class="font-semibold">{{ $item->product->name }}</p>
                                </td>
                                <td class="text-center py-3 px-2">{{ $item->quantity }}</td>
                                <td class="text-right py-3 px-2">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="text-right py-3 px-2 font-semibold">Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 pt-4 border-t-2 border-gray-300">
                <div class="flex justify-end">
                    <div class="w-64">
                        <div class="flex justify-between py-2">
                            <span>Subtotal</span>
                            <span>{{ $order->formatted_subtotal }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span>Biaya Pelayanan</span>
                            <span>{{ $order->formatted_service_fee }}</span>
                        </div>
                        <div class="flex justify-between py-2 font-bold bg-blue-50 p-3 rounded">
                            <span>Total</span>
                            <span class="text-blue-600">{{ $order->formatted_total }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Pengiriman (jika ada) -->
        @if($order->courier_tracking_number)
            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <h2 class="text-xl font-semibold mb-4">Data Pengiriman</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-600 text-sm">Nama Kurir</p>
                        <p class="text-lg font-semibold">{{ $order->courier_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Layanan Kurir</p>
                        <p class="text-lg font-semibold">{{ $order->courier_service }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-gray-600 text-sm">Nomor Resi</p>
                        <p class="text-lg font-semibold font-mono">{{ $order->courier_tracking_number }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($order->order_status === 'delivered')
            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <h2 class="text-xl font-semibold mb-4">Berikan Ulasan</h2>
                <p class="text-sm text-gray-600 mb-4">Beri rating dan komentar untuk produk yang Anda terima.</p>

                <form action="{{ route('reviews.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    @foreach($order->items as $item)
                        <div class="rounded-2xl border border-slate-200 p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $item->product->name }}</p>
                                    <p class="text-sm text-slate-500">{{ $item->quantity }} item</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="hidden" name="product_id[{{ $item->product->id }}]" value="{{ $item->product->id }}">
                                    <select name="rating[{{ $item->product->id }}]" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                                        <option value="5">5 - Sangat Bagus</option>
                                        <option value="4">4 - Bagus</option>
                                        <option value="3">3 - Cukup</option>
                                        <option value="2">2 - Kurang</option>
                                        <option value="1">1 - Buruk</option>
                                    </select>
                                </div>
                            </div>
                            <textarea name="comment[{{ $item->product->id }}]" rows="3" class="mt-4 w-full rounded-xl border border-slate-300 px-4 py-3 text-sm" placeholder="Tulis komentar Anda tentang produk ini..."></textarea>
                        </div>
                    @endforeach

                    <button type="submit" class="rounded-full bg-[#2FA884] px-6 py-3 text-sm font-semibold text-white hover:bg-[#239272] transition">
                        Kirim Ulasan
                    </button>
                </form>
            </div>
        @endif

        <!-- Aksi -->
        <div class="flex gap-4 mb-8">
            @if($order->payment_status === 'pending')
                <a 
                    href="{{ route('order.confirm-whatsapp', $order) }}" 
                    class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg text-center transition duration-200"
                >
                    ✓ Konfirmasi via WhatsApp
                </a>
            @endif
            
            <a 
                href="{{ route('order.track') }}" 
                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 rounded-lg text-center transition duration-200"
            >
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection
