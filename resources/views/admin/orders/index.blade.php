@extends('layouts.admin')

@section('content')
<div class="p-8">
    <h1 class="text-3xl font-bold mb-2">Daftar Pesanan</h1>
    <p class="text-gray-600 mb-8">Kelola semua pesanan dari pelanggan</p>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <p class="text-green-800">✓ {{ session('success') }}</p>
        </div>
    @endif

    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari Pesanan</label>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="No. Pesanan, Nama, atau HP"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran</label>
                <select name="payment_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    @foreach($paymentStatuses as $value => $label)
                        <option value="{{ $value }}" {{ request('payment_status') === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status Pesanan</label>
                <select name="order_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    @foreach($orderStatuses as $value => $label)
                        <option value="{{ $value }}" {{ request('order_status') === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button 
                    type="submit" 
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition"
                >
                    Filter
                </button>
                <a 
                    href="{{ route('admin.orders.index') }}" 
                    class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 rounded-lg transition"
                >
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Tabel Pesanan -->
    @if($orders->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b-2 border-gray-300">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold">Nomor Pesanan</th>
                        <th class="text-left py-3 px-4 font-semibold">Pemesan</th>
                        <th class="text-left py-3 px-4 font-semibold">Tanggal</th>
                        <th class="text-right py-3 px-4 font-semibold">Total</th>
                        <th class="text-center py-3 px-4 font-semibold">Status Pesanan</th>
                        <th class="text-center py-3 px-4 font-semibold">Pembayaran</th>
                        <th class="text-center py-3 px-4 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="py-3 px-4">
                                <p class="font-bold text-blue-600">{{ $order->invoice_number }}</p>
                            </td>
                            <td class="py-3 px-4">
                                <p class="font-medium">{{ $order->buyer_name }}</p>
                                <p class="text-sm text-gray-600">{{ $order->buyer_phone }}</p>
                            </td>
                            <td class="py-3 px-4">
                                <p class="text-sm">{{ $order->created_at->format('d M Y') }}</p>
                                <p class="text-xs text-gray-600">{{ $order->created_at->format('H:i') }}</p>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <p class="font-bold text-lg">{{ $order->formatted_total }}</p>
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
                                <div class="flex gap-2 justify-center">
                                    <a 
                                        href="{{ route('admin.orders.show', $order) }}" 
                                        class="text-blue-600 hover:text-blue-800 font-semibold text-sm"
                                    >
                                        Lihat
                                    </a>
                                    <a 
                                        href="{{ route('admin.orders.edit', $order) }}" 
                                        class="text-green-600 hover:text-green-800 font-semibold text-sm"
                                    >
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @else
        <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-12 text-center">
            <p class="text-gray-600">Tidak ada pesanan</p>
        </div>
    @endif
</div>
@endsection
