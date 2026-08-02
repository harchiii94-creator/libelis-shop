@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm uppercase tracking-[0.3em] text-[#2FA884]">Produk</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-900">Semua produk</h1>
            <p class="mt-3 text-slate-600">Pilih dari kategori sembako, kebutuhan rumah, dan pakaian untuk kebutuhan sehari-hari.</p>
            
            <form action="{{ route('products.index') }}" method="GET" class="mt-6 flex max-w-md gap-2">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk..." class="flex-1 rounded-full border border-slate-300 bg-white px-5 py-2 text-sm outline-none focus:border-[#2FA884] focus:ring-2 focus:ring-[#6FCF97]/30" />
                <button type="submit" class="rounded-full bg-[#6FCF97] px-6 py-2 text-sm font-semibold text-white hover:bg-[#2FA884] transition">Cari</button>
            </form>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('products.index') }}" class="rounded-full border px-4 py-2 text-sm font-semibold {{ $activeCategory ? 'border-slate-300 text-slate-700 bg-white' : 'border-[#2FA884] bg-[#2FA884] text-white' }} transition">Semua</a>
            @foreach($categories as $key => $label)
                <a href="{{ route('products.index', ['category' => $key]) }}" class="rounded-full border px-4 py-2 text-sm font-semibold {{ $activeCategory === $key ? 'border-[#2FA884] bg-[#2FA884] text-white' : 'border-slate-300 bg-white text-slate-700' }} transition">{{ $label }}</a>
            @endforeach
        </div>
    </div>

    <div class="mt-10 grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
        @forelse($products as $product)
            <article class="overflow-hidden rounded-3xl bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-60 w-full object-cover" />
                <div class="p-5">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ $product->category }}</p>
                    <h2 class="mt-3 text-xl font-semibold text-slate-900">{{ $product->name }}</h2>
                    <p class="mt-3 text-lg font-semibold text-slate-900">{{ $product->formatted_price }}</p>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <a href="{{ route('products.show', $product->id) }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:border-[#2FA884] hover:text-[#2FA884] transition">Detail</a>
                        <form action="{{ route('cart.add') }}" method="POST" class="inline-block">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}" />
                            <button type="submit" class="rounded-full bg-[#6FCF97] px-4 py-2 text-sm font-semibold text-white hover:bg-[#2FA884] transition">Tambah ke Cart</button>
                        </form>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-3xl bg-white p-10 shadow-sm text-center text-slate-600">Produk tidak ditemukan untuk kategori ini.</div>
        @endforelse
    </div>

    <div class="mt-10 flex justify-center">
        {{ $products->links() }}
    </div>
</div>
@endsection
