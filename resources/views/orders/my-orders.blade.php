@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-2">Riwayat Pesanan</h1>
    <p class="text-gray-600 mb-8">Lihat riwayat pesanan Anda dan cek status setiap pesanan.</p>

    @if($orders->count() > 0)
        <!-- Filter & Search (optional) -->
        <div class="bg-white rounded-lg shadow p-4 mb-6 flex gap-2">
            <input 
                type="text" 
                placeholder="Cari nomor pesanan..."
                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg"
                id="searchInput"
            >
        </div>

        <!-- Tabel Desktop -->
        <div class="hidden md:block bg-white rounded-lg shadow overflow-hidden mb-8">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold">Nomor Pesanan</th>
                        <th class="text-left py-3 px-4 font-semibold">Tanggal</th>
                        <th class="text-left py-3 px-4 font-semibold">Total</th>
                        <th class="text-center py-3 px-4 font-semibold">Status Pesanan</th>
                        <th class="text-center py-3 px-4 font-semibold">Status Pembayaran</th>
                        <th class="text-center py-3 px-4 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="py-3 px-4">
                                <p class="font-bold text-blue-600">{{ $order->invoice_number }}</p>
                            </td>
                            <td class="py-3 px-4">{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td class="py-3 px-4">
                                <p class="font-bold">{{ $order->formatted_total }}</p>
                            </td>
                            <td class="py-3 px-4 text-center">
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
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    @if($order->payment_status === 'paid') bg-green-100 text-green-800
                                    @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif
                                ">
                                    {{ $order->payment_status_label }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <a 
                                        href="{{ route('order.detail', $order) }}" 
                                        class="text-blue-600 hover:text-blue-800 font-semibold"
                                    >
                                        Lihat Detail
                                    </a>
                                    @if($order->isCancellable())
                                        <form action="{{ route('order.cancel', $order) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                            @csrf
                                            <button type="submit" class="text-sm font-semibold text-rose-600 hover:text-rose-800">
                                                Batalkan
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Kartu Mobile -->
        <div class="md:hidden space-y-4 mb-8">
            @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow p-4 border-l-4 
                    @if($order->order_status === 'delivered') border-green-500
                    @elseif($order->order_status === 'shipped') border-blue-500
                    @elseif($order->order_status === 'processing') border-yellow-500
                    @else border-gray-500
                    @endif
                ">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-bold text-blue-600">{{ $order->invoice_number }}</p>
                            <p class="text-sm text-gray-600">{{ $order->created_at->format('d M Y') }}</p>
                        </div>
                        <p class="font-bold text-lg">{{ $order->formatted_total }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-2 py-3 border-t border-b mb-3">
                        <div>
                            <p class="text-xs text-gray-600">Status Pesanan</p>
                            <p class="text-sm font-semibold">{{ $order->order_status_label }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Pembayaran</p>
                            <p class="text-sm font-semibold">{{ $order->payment_status_label }}</p>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <a 
                            href="{{ route('order.detail', $order) }}" 
                            class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition"
                        >
                            Lihat Detail
                        </a>
                        @if($order->isCancellable())
                            <form action="{{ route('order.cancel', $order) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                @csrf
                                <button type="submit" class="block w-full text-center rounded-lg border border-rose-300 bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700 transition hover:bg-rose-100">
                                    Batalkan Pesanan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @else
        <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-12 text-center">
            <p class="text-gray-600 mb-4">Anda belum memiliki pesanan</p>
            <a 
                href="{{ route('products.index') }}" 
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition"
            >
                Mulai Belanja
            </a>
        </div>
    @endif
</div>

<script>
    // Search filter untuk tabel
    document.getElementById('searchInput')?.addEventListener('keyup', function() {
        const filter = this.value.toUpperCase();
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent;
            row.style.display = text.toUpperCase().includes(filter) ? '' : 'none';
        });
    });
</script>
@endsection
