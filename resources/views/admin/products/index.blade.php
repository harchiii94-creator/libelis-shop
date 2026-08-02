@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Produk</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-900">Kelola Produk</h1>
        </div>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center rounded-full bg-[#6FCF97] px-5 py-3 text-sm font-semibold text-white hover:bg-[#2FA884] transition">Tambah Produk</a>
    </div>

    <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="px-6 py-4">Preview</th>
                    <th class="px-6 py-4">Nama</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4">Harga</th>
                    <th class="px-6 py-4">Label</th>
                    <th class="px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($products as $product)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-16 w-16 rounded-3xl object-cover" />
                        </td>
                        <td class="px-6 py-4 text-slate-900">{{ $product->name }}</td>
                        <td class="px-6 py-4 text-slate-700">{{ ucfirst($product->category) }}</td>
                        <td class="px-6 py-4 text-slate-900">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-slate-700">
                            @if($product->is_best_seller)
                                <span class="mr-2 inline-flex rounded-full bg-[#E6FFEF] px-3 py-1 text-xs font-semibold text-[#2FA884]">Best</span>
                            @endif
                            @if($product->is_new_arrival)
                                <span class="inline-flex rounded-full bg-[#EFF6FF] px-3 py-1 text-xs font-semibold text-[#1F6CFF]">New</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.products.edit', $product) }}" class="mr-2 rounded-full border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 hover:border-[#2FA884] hover:text-[#2FA884] transition">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus produk ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-full bg-rose-500 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-600 transition">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-500">Belum ada produk tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-end">
        {{ $products->links() }}
    </div>
</div>
@endsection
