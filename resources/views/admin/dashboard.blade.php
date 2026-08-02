@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <section class="rounded-[2rem] bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Rekap Penjualan</p>
                <h2 class="mt-2 text-2xl font-semibold text-slate-900">Ringkasan penjualan toko</h2>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.sales.export.pdf') }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-[#2FA884] hover:text-[#2FA884]">Export PDF</a>
                <a href="{{ route('admin.sales.export.excel') }}" class="rounded-full bg-[#2FA884] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#239272]">Export Excel</a>
            </div>
        </div>

        <div class="mt-6 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-[2rem] bg-[#F8FFFB] p-6 shadow-sm">
                <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Total Penjualan Hari Ini</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $summary['sales_today'] }}</p>
            </article>
            <article class="rounded-[2rem] bg-[#F8FFFB] p-6 shadow-sm">
                <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Total Penjualan Bulan Ini</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $summary['sales_this_month'] }}</p>
            </article>
            <article class="rounded-[2rem] bg-[#F8FFFB] p-6 shadow-sm">
                <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Total Pendapatan</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $summary['revenue'] }}</p>
            </article>
            <article class="rounded-[2rem] bg-[#F8FFFB] p-6 shadow-sm">
                <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Total Transaksi</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $summary['transactions'] }}</p>
            </article>
        </div>
    </section>

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
        <article class="rounded-[2rem] bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
            <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Total Produk</p>
            <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $totalProducts }}</p>
        </article>
        <article class="rounded-[2rem] bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
            <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Total Kategori</p>
            <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $totalCategories }}</p>
        </article>
        <article class="rounded-[2rem] bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
            <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Best Seller</p>
            <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $totalBestSeller }}</p>
        </article>
        <article class="rounded-[2rem] bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
            <p class="text-sm uppercase tracking-[0.3em] text-slate-400">New Arrival</p>
            <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $totalNewArrival }}</p>
        </article>
    </div>

    <section class="rounded-[2rem] bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Produk per kategori</p>
                <h2 class="mt-2 text-2xl font-semibold text-slate-900">Ringkasan kategori</h2>
            </div>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($productsPerCategory as $group)
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                    <p class="text-sm uppercase tracking-[0.3em] text-slate-500">{{ ucfirst($group->category) }}</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $group->total }}</p>
                </div>
            @empty
                <p class="text-slate-500">Tidak ada produk untuk ditampilkan.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
