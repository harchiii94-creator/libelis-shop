@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Tambah Produk</p>
        <h1 class="mt-2 text-3xl font-semibold text-slate-900">Data produk baru</h1>
    </div>

    <div class="rounded-[2rem] bg-white p-6 shadow-sm">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <label class="text-sm font-semibold text-slate-700">Nama produk</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="mt-2 w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-[#2FA884] focus:ring-2 focus:ring-[#6FCF97]/30" />
                    @error('name')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700">Kategori</label>
                    <select name="category" required class="mt-2 w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-[#2FA884] focus:ring-2 focus:ring-[#6FCF97]/30">
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->name }}" {{ old('category') === $category->name ? 'selected' : '' }}>{{ ucfirst($category->name) }}</option>
                        @endforeach
                    </select>
                    @error('category')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700">Harga (Rp)</label>
                    <input type="number" name="price" value="{{ old('price') }}" min="0" required class="mt-2 w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-[#2FA884] focus:ring-2 focus:ring-[#6FCF97]/30" />
                    @error('price')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700">Stok</label>
                    <input type="number" name="stock" value="{{ old('stock') }}" min="0" required class="mt-2 w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-[#2FA884] focus:ring-2 focus:ring-[#6FCF97]/30" />
                    @error('stock')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700">Upload foto produk</label>
                    <input type="file" name="image" required accept="image/*" class="mt-2 w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none file:bg-transparent file:border-0 file:text-[#2FA884] file:font-semibold file:mr-4" />
                    @error('image')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-700">Deskripsi</label>
                <textarea name="description" rows="5" required class="mt-2 w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-[#2FA884] focus:ring-2 focus:ring-[#6FCF97]/30">{{ old('description') }}</textarea>
                @error('description')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-wrap gap-4">
                    <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700">
                        <input type="checkbox" name="is_best_seller" value="1" {{ old('is_best_seller') ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-[#2FA884] focus:ring-[#2FA884]" />
                        Best Seller
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700">
                        <input type="checkbox" name="is_new_arrival" value="1" {{ old('is_new_arrival') ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-[#2FA884] focus:ring-[#2FA884]" />
                        New Arrival
                    </label>
                </div>
                <button type="submit" class="rounded-full bg-[#6FCF97] px-5 py-3 text-sm font-semibold text-white shadow hover:bg-[#2FA884] transition">Simpan Produk</button>
            </div>
        </form>
    </div>
</div>
@endsection
