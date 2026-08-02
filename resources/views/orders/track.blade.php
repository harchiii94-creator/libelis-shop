@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-2">Lacak Pesanan</h1>
        <p class="text-gray-600 mb-8">Cari pesanan Anda menggunakan nomor pesanan atau nomor WhatsApp</p>

        <!-- Alert Messages -->
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-8">
                <p class="text-red-800 font-semibold mb-2">Tidak ada pesanan yang ditemukan</p>
                <p class="text-red-700 text-sm">{{ $errors->first() }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-8">
                <p class="text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Search Form -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <form action="{{ route('order.search') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-4">Pilih Metode Pencarian</label>
                    
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input 
                                type="radio" 
                                id="search_invoice" 
                                name="search_type" 
                                value="invoice"
                                {{ old('search_type') === 'invoice' || !old('search_type') ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600"
                            >
                            <label for="search_invoice" class="ml-3 cursor-pointer text-gray-700">
                                Cari berdasarkan Nomor Pesanan (Invoice)
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input 
                                type="radio" 
                                id="search_phone" 
                                name="search_type" 
                                value="phone"
                                {{ old('search_type') === 'phone' ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600"
                            >
                            <label for="search_phone" class="ml-3 cursor-pointer text-gray-700">
                                Cari berdasarkan Nomor WhatsApp
                            </label>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="search_value" class="block text-sm font-medium text-gray-700 mb-2">
                        Masukkan Data Pencarian <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="search_value" 
                        name="search_value" 
                        value="{{ old('search_value') }}"
                        placeholder="Contoh: INV-000001-20260615 atau 62812345678"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    >
                    <p class="text-gray-600 text-sm mt-2">
                        Format nomor invoice: <code class="bg-gray-100 px-2 py-1 rounded">INV-XXXXXX-YYYYMMDD</code>
                    </p>
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition duration-200"
                >
                    Cari Pesanan
                </button>
            </form>
        </div>

        <!-- Info Bantuan -->
        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-6">
            <p class="text-blue-900 font-semibold mb-3">💡 Tidak menemukan pesanan?</p>
            <ul class="text-blue-800 space-y-2 list-disc list-inside">
                <li>Pastikan Anda sudah login dengan akun yang digunakan saat checkout</li>
                <li>Periksa kembali nomor pesanan yang Anda masukkan</li>
                <li>Hubungi admin melalui WhatsApp jika masih ada masalah</li>
            </ul>
        </div>

        <!-- Pesanan User (jika sudah login) -->
        @auth
            <div class="mt-12">
                <h2 class="text-2xl font-bold mb-4">Pesanan Anda</h2>
                
                @if(auth()->user()->orders->count() > 0)
                    <div class="space-y-3">
                        @foreach(auth()->user()->orders()->latest()->limit(5)->get() as $order)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="font-bold text-gray-800">{{ $order->invoice_number }}</p>
                                        <p class="text-sm text-gray-600">{{ $order->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                    <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded">
                                        {{ $order->order_status_label }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center pt-3 border-t">
                                    <div>
                                        <p class="text-gray-700">{{ $order->buyer_name }} • {{ $order->city }}</p>
                                        <p class="text-gray-600 text-sm">{{ $order->formatted_total }}</p>
                                    </div>
                                    <a 
                                        href="{{ route('order.detail', $order) }}" 
                                        class="text-blue-600 hover:text-blue-800 font-semibold"
                                    >
                                        Lihat Detail →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <a 
                        href="{{ route('order.history') }}" 
                        class="block text-center mt-4 text-blue-600 hover:text-blue-800 font-semibold"
                    >
                        Lihat Riwayat Pesanan
                    </a>
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
                        <p class="text-gray-600">Anda belum memiliki pesanan</p>
                        <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                            Mulai Belanja
                        </a>
                    </div>
                @endif
            </div>
        @else
            <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                <p class="text-yellow-800 mb-3">Untuk melihat riwayat pesanan Anda, silakan login terlebih dahulu</p>
                <a 
                    href="{{ route('login') }}" 
                    class="inline-block bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-6 rounded-lg transition"
                >
                    Login
                </a>
            </div>
        @endauth
    </div>
</div>
@endsection
