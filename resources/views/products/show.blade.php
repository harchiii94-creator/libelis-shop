@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid gap-10 lg:grid-cols-[1.2fr_0.8fr]">
        <div class="overflow-hidden rounded-[2rem] bg-white shadow-sm">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-[520px] w-full object-cover" />
        </div>

        <div class="flex flex-col gap-6">
            <div class="rounded-[2rem] bg-white p-8 shadow-sm">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ $product->category }}</p>
                <h1 class="mt-4 text-4xl font-semibold text-slate-900">{{ $product->name }}</h1>
                <p class="mt-5 text-2xl font-semibold text-[#2FA884]">{{ $product->formatted_price }}</p>
                <p class="mt-3 text-sm font-semibold text-slate-700">Stok: <span class="text-slate-900">{{ $product->stock }}</span></p>
                <p class="mt-4 text-slate-600 leading-relaxed">{{ $product->description }}</p>
                <div class="mt-4 flex items-center gap-2 text-sm text-slate-600">
                    <span class="font-semibold text-slate-900">{{ $product->averageRating() }}</span>
                    <span class="text-amber-500">★</span>
                    <span>({{ $product->reviewCount() }} ulasan)</span>
                </div>
                <div class="mt-8 flex flex-wrap gap-3">
                    @if($product->is_best_seller)
                        <span class="rounded-full bg-[#6FCF97]/15 px-4 py-2 text-sm font-semibold text-[#2FA884]">Best Seller</span>
                    @endif
                    @if($product->is_new_arrival)
                        <span class="rounded-full bg-[#1F6CFF]/15 px-4 py-2 text-sm font-semibold text-[#1F6CFF]">New Arrival</span>
                    @endif
                </div>
            </div>

            <div class="rounded-[2rem] bg-white p-8 shadow-sm">
                @if($product->stock > 0)
                    <form action="{{ route('cart.add') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}" />
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Jumlah</label>
                            <input type="number" name="quantity" min="1" value="1" class="mt-2 w-24 rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-[#2FA884] focus:ring-2 focus:ring-[#6FCF97]/30" />
                        </div>
                        <button type="submit" class="w-full rounded-full bg-[#6FCF97] px-6 py-3 text-sm font-semibold text-white shadow hover:bg-[#2FA884] transition">Tambah ke Cart</button>
                    </form>
                @else
                    <div class="rounded-3xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                        Stok produk saat ini habis. Silakan pilih produk lain atau cek kembali nanti.
                    </div>
                @endif
                <a href="{{ route('products.index') }}" class="mt-4 inline-flex text-sm font-semibold text-[#2FA884] hover:text-[#1a8d74]">&larr; Kembali ke katalog</a>
            </div>
        </div>
    </div>

    <div class="mt-10 rounded-[2rem] bg-white p-8 shadow-sm">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Ulasan Pembeli</h2>
                <p class="mt-2 text-sm text-slate-600">Lihat pengalaman pelanggan lainnya sebelum membeli.</p>
            </div>
        </div>

        @if($product->reviews->isEmpty())
            <div class="mt-6 rounded-3xl border border-dashed border-slate-300 bg-slate-50 p-6 text-sm text-slate-600">
                Belum ada ulasan untuk produk ini.
            </div>
        @else
            <div class="mt-6 space-y-4">
                @foreach($product->reviews->sortByDesc('created_at') as $review)
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $review->user->name ?? 'Pengguna' }}</p>
                                <p class="text-sm text-slate-500">{{ $review->created_at->translatedFormat('d F Y') }}</p>
                            </div>
                            <div class="text-amber-500">{{ str_repeat('★', $review->rating) }}</div>
                        </div>
                        @if($review->comment)
                            <p class="mt-3 text-sm leading-6 text-slate-700">{{ $review->comment }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
