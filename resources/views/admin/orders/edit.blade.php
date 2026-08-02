@extends('layouts.admin')

@section('content')
<div class="p-8">
    <h1 class="text-3xl font-bold mb-2">Edit Pesanan</h1>
    <p class="text-gray-600 mb-8">{{ $order->invoice_number }}</p>

    <!-- Alert Messages -->
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <p class="text-red-800 font-semibold mb-2">Terjadi kesalahan:</p>
            <ul class="text-red-700 text-sm list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <p class="text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Utama -->
        <div class="lg:col-span-2">
            <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Status Pembayaran -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Status Pembayaran</h2>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status Pembayaran
                            </label>
                            <select 
                                name="payment_status" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="">-- Pilih Status --</option>
                                @foreach($paymentStatuses as $value => $label)
                                    <option 
                                        value="{{ $value }}" 
                                        {{ old('payment_status', $order->payment_status) === $value ? 'selected' : '' }}
                                    >
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded text-sm">
                            <p class="text-blue-800">
                                <strong>Status Saat Ini:</strong> {{ $order->payment_status_label }}
                            </p>
                            <p class="text-blue-700 text-xs mt-2">
                                Ubah status ke "Lunas" ketika pembayaran dari pelanggan sudah dikonfirmasi.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Status Pesanan -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Status Pesanan</h2>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status Pesanan <span class="text-red-500">*</span>
                            </label>
                            <select 
                                name="order_status" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="">-- Pilih Status --</option>
                                @foreach($orderStatuses as $value => $label)
                                    <option 
                                        value="{{ $value }}" 
                                        {{ old('order_status', $order->order_status) === $value ? 'selected' : '' }}
                                    >
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded text-sm">
                            <p class="text-yellow-800">
                                <strong>Status Saat Ini:</strong> {{ $order->order_status_label }}
                            </p>
                            <p class="text-yellow-700 text-xs mt-2">
                                Status pesanan harus diupdate secara berurutan: 
                                Menunggu Pembayaran → Dikonfirmasi → Diproses → Dikirim → Diterima
                            </p>
                        </div>

                        <div class="text-sm text-gray-600 space-y-1">
                            <p><strong>Alur Status:</strong></p>
                            <ul class="list-disc list-inside">
                                <li>Menunggu Pembayaran: Pesanan baru, menunggu pembayaran</li>
                                <li>Dikonfirmasi: Pembayaran sudah diterima, siap diproses</li>
                                <li>Diproses: Pesanan sedang disiapkan untuk dikirim</li>
                                <li>Dikirim: Pesanan sudah dikirim kepada kurir</li>
                                <li>Diterima: Pesanan sudah sampai ke pelanggan</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Data Pengiriman -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Data Pengiriman</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="courier_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Kurir
                            </label>
                            <input 
                                type="text" 
                                id="courier_name" 
                                name="courier_name" 
                                value="{{ old('courier_name', $order->courier_name) }}"
                                placeholder="Contoh: JNE, Go-Jek, Grab, dll"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            >
                            @error('courier_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="courier_service" class="block text-sm font-medium text-gray-700 mb-2">
                                Layanan Kurir
                            </label>
                            <input 
                                type="text" 
                                id="courier_service" 
                                name="courier_service" 
                                value="{{ old('courier_service', $order->courier_service) }}"
                                placeholder="Contoh: Regular, Express, Same Day"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            >
                            @error('courier_service')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="courier_tracking_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Resi
                            </label>
                            <input 
                                type="text" 
                                id="courier_tracking_number" 
                                name="courier_tracking_number" 
                                value="{{ old('courier_tracking_number', $order->courier_tracking_number) }}"
                                placeholder="Nomor resi pengiriman"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            >
                            @error('courier_tracking_number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-gray-50 border-l-4 border-gray-500 p-4 rounded text-sm">
                            <p class="text-gray-800">
                                📦 Isi data pengiriman ketika pesanan sudah dikirim kepada kurir.
                                Pelanggan akan menerima notifikasi dengan informasi tracking.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Button Submit -->
                <div class="flex gap-3">
                    <button 
                        type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition"
                    >
                        Simpan Perubahan
                    </button>
                    <a 
                        href="{{ route('admin.orders.show', $order) }}" 
                        class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 rounded-lg transition"
                    >
                        Batal
                    </a>
                </div>
            </form>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Detail Pesanan -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Detail Pesanan</h3>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-gray-600 text-sm">Nomor Pesanan</p>
                        <p class="font-bold text-blue-600">{{ $order->invoice_number }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Pemesan</p>
                        <p class="font-semibold">{{ $order->buyer_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Total Pesanan</p>
                        <p class="font-bold text-2xl text-blue-600">{{ $order->formatted_total }}</p>
                    </div>
                    <div class="pt-3 border-t">
                        <p class="text-gray-600 text-sm">Tanggal Pesanan</p>
                        <p class="font-semibold">{{ $order->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Status Saat Ini -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Status Saat Ini</h3>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-gray-600 text-sm">Status Pesanan</p>
                        <p class="text-lg font-bold">
                            <span class="px-3 py-1 rounded-full text-sm
                                @if($order->order_status === 'delivered') bg-green-100 text-green-800
                                @elseif($order->order_status === 'shipped') bg-blue-100 text-blue-800
                                @elseif($order->order_status === 'processing') bg-yellow-100 text-yellow-800
                                @elseif($order->order_status === 'confirmed') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800
                                @endif
                            ">
                                {{ $order->order_status_label }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Status Pembayaran</p>
                        <p class="text-lg font-bold">
                            <span class="px-3 py-1 rounded-full text-sm
                                @if($order->payment_status === 'paid') bg-green-100 text-green-800
                                @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif
                            ">
                                {{ $order->payment_status_label }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tombol Lainnya -->
            <div class="bg-white rounded-lg shadow-md p-6 space-y-3">
                <a 
                    href="{{ route('admin.orders.show', $order) }}" 
                    class="block w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 rounded-lg transition"
                >
                    Lihat Detail
                </a>
                <a 
                    href="https://wa.me/{{ $order->buyer_phone }}" 
                    target="_blank"
                    class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition"
                >
                    Hubungi via WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
