@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Formulir Checkout -->
        <div class="lg:col-span-2">
            <h1 class="text-3xl font-bold mb-8">Checkout</h1>

            <form action="{{ route('checkout.store') }}" method="POST" class="space-y-8">
                @csrf

                @foreach($cart as $productId => $quantity)
                    <input type="hidden" name="selected_products[]" value="{{ $productId }}" />
                @endforeach

                <!-- Data Diri -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-semibold mb-4">Data Diri</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="buyer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="buyer_name" 
                                name="buyer_name" 
                                value="{{ old('buyer_name', auth()->user()->name) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('buyer_name') border-red-500 @enderror"
                                required
                            >
                            @error('buyer_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="buyer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor WhatsApp <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="tel" 
                                id="buyer_phone" 
                                name="buyer_phone" 
                                value="{{ old('buyer_phone') }}"
                                placeholder="62812345678 (tanpa +)"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('buyer_phone') border-red-500 @enderror"
                                required
                            >
                            @error('buyer_phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="buyer_email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="email" 
                                id="buyer_email" 
                                name="buyer_email" 
                                value="{{ old('buyer_email', auth()->user()->email) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('buyer_email') border-red-500 @enderror"
                                required
                            >
                            @error('buyer_email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Pengiriman -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-semibold mb-4">Alamat Pengiriman</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                Kota <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="city" 
                                name="city" 
                                value="{{ old('city') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('city') border-red-500 @enderror"
                                required
                            >
                            @error('city')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="address" 
                                name="address" 
                                rows="4"
                                placeholder="Jalan, Nomor Rumah, RT/RW, Kelurahan, dll"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror"
                                required
                            >{{ old('address') }}</textarea>
                            @error('address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Metode Pembayaran -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-semibold mb-4">Metode Pembayaran</h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input 
                                type="radio" 
                                id="payment_cod" 
                                name="payment_method" 
                                value="cod" 
                                {{ old('payment_method') === 'cod' || !old('payment_method') ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600"
                            >
                            <label for="payment_cod" class="ml-3 cursor-pointer">
                                <span class="font-medium">Cash on Delivery (COD)</span>
                                <p class="text-gray-600 text-sm">Bayar langsung saat barang tiba di tangan Anda</p>
                            </label>
                        </div>

                        <div class="flex items-start pt-4 border-t">
                            <input 
                                type="radio" 
                                id="payment_transfer" 
                                name="payment_method" 
                                value="transfer" 
                                {{ old('payment_method') === 'transfer' ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 mt-1"
                            >
                            <label for="payment_transfer" class="ml-3 cursor-pointer">
                                <span class="font-medium">
                                    @if(config('midtrans.enabled'))
                                        Midtrans (Virtual Account / E‑wallet)
                                    @else
                                        Transfer Bank Manual
                                    @endif
                                </span>
                                <p class="text-gray-600 text-sm">
                                    @if(config('midtrans.enabled'))
                                        Anda akan diarahkan ke halaman pembayaran Midtrans setelah submit. Jika menggunakan VA, instruksi dan nomor akan tampil di halaman pembayaran.
                                    @else
                                        Transfer ke rekening BCA atau Mandiri
                                    @endif
                                </p>

                                @if(config('midtrans.enabled'))
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach(config('midtrans.enabled_payments') as $method)
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-medium bg-gray-100 rounded text-gray-700">
                                                {{ strtoupper($method) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </label>
                        </div>
                    </div>
                    @error('payment_method')
                        <p class="text-red-500 text-sm mt-3">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tombol Submit -->
                <div>
                    <button 
                        type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition duration-200"
                    >
                        Lanjutkan ke Pembayaran
                    </button>
                    <a 
                        href="{{ route('cart.index') }}" 
                        class="block text-center mt-3 text-gray-600 hover:text-gray-800"
                    >
                        Kembali ke Keranjang
                    </a>
                </div>
            </form>
        </div>

        <!-- Ringkasan Pesanan -->
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-lg shadow sticky top-8">
                <h2 class="text-xl font-semibold mb-4">Ringkasan Pesanan</h2>

                <div class="space-y-3 mb-6 pb-6 border-b">
                    @foreach($products as $product)
                        @php
                            $quantity = $cart[$product->id] ?? 0;
                            if ($quantity <= 0) continue;
                        @endphp
                        <div class="flex justify-between text-sm">
                            <div>
                                <p class="font-medium">{{ $product->name }}</p>
                                <p class="text-gray-600">{{ $quantity }}x @ Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>
                            <p class="font-medium">Rp{{ number_format($product->price * $quantity, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="space-y-2 mb-6 pb-6 border-b">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Biaya Pelayanan</span>
                        <span>Rp{{ number_format($serviceFee, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="flex justify-between text-lg font-bold bg-blue-50 p-3 rounded">
                    <span>Total</span>
                    <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
                </div>

                <p class="text-gray-600 text-xs mt-4">
                    Biaya pelayanan sebesar Rp{{ number_format($serviceFee, 0, ',', '.') }} sudah termasuk dalam total.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
