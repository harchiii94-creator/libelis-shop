@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-md px-4 sm:px-6 lg:px-8 py-16">
    <div class="rounded-[2rem] border border-slate-200 bg-white p-10 shadow-sm">
        <div class="mb-8 text-center">
            <p class="text-sm uppercase tracking-[0.3em] text-[#2FA884]">Profil</p>
            <h1 class="mt-3 text-3xl font-semibold text-slate-900">Kelola Akun Anda</h1>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="text-sm font-semibold text-slate-700">Nama Lengkap</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required class="mt-2 w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 outline-none focus:border-[#2FA884] focus:ring-2 focus:ring-[#6FCF97]/30" />
                @error('name')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="text-sm font-semibold text-slate-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="mt-2 w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 outline-none focus:border-[#2FA884] focus:ring-2 focus:ring-[#6FCF97]/30" />
                @error('email')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="text-sm font-semibold text-slate-700">Ubah Kata Sandi (opsional)</label>
                <input id="password" name="password" type="password" class="mt-2 w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 outline-none focus:border-[#2FA884] focus:ring-2 focus:ring-[#6FCF97]/30" />
                @error('password')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="text-sm font-semibold text-slate-700">Konfirmasi Kata Sandi</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="mt-2 w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 outline-none focus:border-[#2FA884] focus:ring-2 focus:ring-[#6FCF97]/30" />
            </div>

            <button type="submit" class="w-full rounded-full bg-[#6FCF97] px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-[#2FA884] transition">Simpan Perubahan</button>
        </form>

        <div class="mt-8 rounded-[2rem] border border-slate-200 bg-slate-50 p-6 shadow-sm">
            <p class="text-sm uppercase tracking-[0.3em] text-[#2FA884]">Akses Cepat</p>
            <h2 class="mt-3 text-xl font-semibold text-slate-900">Lacak dan lihat pesanan Anda</h2>
            <p class="mt-2 text-gray-600">Gunakan link ini untuk langsung menuju halaman riwayat atau lacak pesanan jika sudah punya nomor invoice.</p>

            <div class="mt-6">
                <a href="{{ route('order.history') }}" class="inline-flex w-full items-center justify-center rounded-full bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition">
                    Riwayat Pesanan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
