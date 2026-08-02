@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Tambah Kategori</p>
        <h1 class="mt-2 text-3xl font-semibold text-slate-900">Buat kategori baru</h1>
    </div>

    <div class="rounded-[2rem] bg-white p-6 shadow-sm">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="text-sm font-semibold text-slate-700">Nama kategori</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="mt-2 w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-[#2FA884] focus:ring-2 focus:ring-[#6FCF97]/30" />
                @error('name')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="rounded-full bg-[#6FCF97] px-5 py-3 text-sm font-semibold text-white shadow hover:bg-[#2FA884] transition">Simpan Kategori</button>
        </form>
    </div>
</div>
@endsection
