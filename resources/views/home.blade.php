@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
    <section class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr] items-center">
        <div class="space-y-6">
            <span class="inline-flex rounded-full bg-[#6FCF97]/15 px-4 py-1 text-sm font-semibold uppercase tracking-[0.3em] text-[#2FA884]">Toko Kelontong</span>
            <h1 class="text-4xl font-semibold tracking-tight text-slate-900 sm:text-5xl">Belanja kebutuhan rumah, sembako, &amp; pakaian dengan nyaman.</h1>
            <p class="max-w-2xl text-slate-600">Nikmati katalog lengkap, pilihan best seller, dan promo gratis ongkir untuk pembelanjaan di atas Rp200.000.</p>
            <div class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center rounded-full bg-[#6FCF97] px-6 py-3 text-white shadow hover:bg-[#2FA884] transition">Lihat Produk</a>
                <a href="#best-seller" class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-6 py-3 text-slate-900 shadow-sm hover:border-[#2FA884] hover:text-[#2FA884] transition">Best Seller</a>
            </div>
        </div>

        <div class="rounded-[2rem] bg-[#F8FFF8] p-6 shadow-xl ring-1 ring-slate-200">
            <div class="overflow-hidden rounded-[1.75rem] h-80 bg-cover bg-center" style="background-image: url('{{ asset('images/images (5).jpg') }}');"></div>
            <div class="mt-6 space-y-3">
                <p class="text-sm uppercase tracking-[0.3em] text-slate-500">Highlight</p>
                <h2 class="text-2xl font-semibold text-slate-900">Inspirasi belanja cepat untuk keluarga.</h2>
                <p class="text-slate-600">Temukan produk rumah tangga, sembako, dan pakaian yang bikin belanja jadi lebih mudah.</p>
            </div>
        </div>
    </section>

    <section id="best-seller" class="mt-20">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-[#2FA884]">Best Seller</p>
                <h2 class="mt-2 text-3xl font-semibold text-slate-900">Produk paling laris</h2>
            </div>
            <a href="{{ route('products.index') }}?category=sembako" class="text-sm font-semibold text-[#2FA884] hover:text-[#1a8d74] transition">Lihat semua produk</a>
        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            @foreach($bestSellers as $product)
                <article class="overflow-hidden rounded-3xl bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-60 w-full object-cover" />
                    <div class="p-5">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ $product->category }}</p>
                        <h3 class="mt-3 text-xl font-semibold text-slate-900">{{ $product->name }}</h3>
                        <p class="mt-3 text-slate-600">{{ $product->formatted_price }}</p>
                        <a href="{{ route('products.show', $product->id) }}" class="mt-5 inline-flex items-center rounded-full bg-[#6FCF97] px-4 py-2 text-sm font-semibold text-white hover:bg-[#2FA884] transition">Detail Produk</a>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <section class="mt-20 grid gap-6 sm:grid-cols-3">
        <div class="rounded-[2rem] bg-white p-8 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#6FCF97]/15 text-[#2FA884]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 7.5M17 13l1.5 7.5M6 21h12" />
                </svg>
            </div>
            <h3 class="mt-6 text-xl font-semibold text-slate-900">Sembako</h3>
            <p class="mt-3 text-slate-600">Beras, minyak, gula, dan kebutuhan dapur lengkap untuk stok rumah.</p>
        </div>
        <div class="rounded-[2rem] bg-white p-8 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#6FCF97]/15 text-[#2FA884]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h3 class="mt-6 text-xl font-semibold text-slate-900">Kebutuhan Rumah</h3>
            <p class="mt-3 text-slate-600">Sabun, tisu, sapu, dan semua kebutuhan bersih-bersih rumah.</p>
        </div>
        <div class="rounded-[2rem] bg-white p-8 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#6FCF97]/15 text-[#2FA884]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16" />
                </svg>
            </div>
            <h3 class="mt-6 text-xl font-semibold text-slate-900">Pakaian</h3>
            <p class="mt-3 text-slate-600">Kaos, celana, jaket, dan pilihan pakaian harian untuk keluarga.</p>
        </div>
    </section>

    <section class="mt-20 rounded-[2rem] bg-[#F4FFF6] p-8 shadow-sm border border-[#DFF6E2]">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-[#2FA884]">Promo</p>
                <h2 class="mt-3 text-3xl font-semibold text-slate-900">Gratis ongkir untuk pembelanjaan di atas Rp200.000</h2>
                <p class="mt-3 max-w-2xl text-slate-600">Tambahkan produk favorit Anda ke keranjang dan nikmati ongkir gratis untuk semua kebutuhan rumah tangga.</p>
            </div>
            <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center rounded-full bg-[#2FA884] px-6 py-3 text-sm font-semibold text-white shadow hover:bg-[#1a846d] transition">Belanja Sekarang</a>
        </div>
    </section>

    <section class="mt-20">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-[#2FA884]">New Arrival</p>
                <h2 class="mt-2 text-3xl font-semibold text-slate-900">Produk terbaru</h2>
            </div>
            <a href="{{ route('products.index') }}?category=pakaian" class="text-sm font-semibold text-[#2FA884] hover:text-[#1a8d74] transition">Jelajahi koleksi terbaru</a>
        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            @foreach($newArrivals as $product)
                <article class="overflow-hidden rounded-3xl bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-60 w-full object-cover" />
                    <div class="p-5">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ $product->category }}</p>
                        <h3 class="mt-3 text-xl font-semibold text-slate-900">{{ $product->name }}</h3>
                        <p class="mt-3 text-slate-600">{{ $product->formatted_price }}</p>
                        <a href="{{ route('products.show', $product->id) }}" class="mt-5 inline-flex items-center rounded-full bg-[#6FCF97] px-4 py-2 text-sm font-semibold text-white hover:bg-[#2FA884] transition">Detail Produk</a>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
</div>
@endsection
