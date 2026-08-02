@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-24 text-center">
    <div class="inline-flex rounded-[2rem] bg-white p-16 shadow-lg">
        <div>
            <p class="text-sm uppercase tracking-[0.4em] text-[#2FA884]">404</p>
            <h1 class="mt-6 text-5xl font-semibold text-slate-900">Halaman tidak ditemukan</h1>
            <p class="mt-4 text-slate-600">Maaf, halaman yang Anda cari tidak tersedia. Kembali ke beranda dan lanjutkan belanja kebutuhan sehari-hari.</p>
            <div class="mt-10 flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                <a href="{{ route('home') }}" class="rounded-full bg-[#6FCF97] px-6 py-3 text-sm font-semibold text-white hover:bg-[#2FA884] transition">Kembali ke Beranda</a>
                <a href="{{ route('products.index') }}" class="rounded-full border border-slate-300 px-6 py-3 text-sm font-semibold text-slate-900 hover:border-[#2FA884] hover:text-[#2FA884] transition">Lihat Produk</a>
            </div>
        </div>
    </div>
</div>
@endsection
