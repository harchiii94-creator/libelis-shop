@extends('layouts.app')

@section('content')
<div class="flex min-h-screen items-center justify-center bg-slate-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-3xl rounded-[2rem] border border-slate-200 bg-white p-8 shadow-xl">
        <div class="mb-8">
            <div class="flex justify-center">
                <div class="w-full max-w-[28rem] rounded-full border border-slate-200 bg-slate-50 p-1 shadow-sm">
                    <div class="grid grid-cols-2 gap-1 rounded-full bg-slate-50 p-1">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full bg-white px-6 py-3 text-sm font-semibold text-slate-900 shadow-sm transition">Login</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full px-6 py-3 text-sm font-semibold text-slate-600 transition hover:text-emerald-700">Register</a>
                    </div>
                </div>
            </div>

            <div class="mt-8 text-center">
                <p class="text-sm uppercase tracking-[0.35em] text-emerald-700">Login</p>
                <h1 class="mt-3 text-3xl font-semibold text-slate-900">Masuk ke akun Anda</h1>
                <p class="mt-3 text-sm text-slate-600">Gunakan akun Anda untuk masuk dan mengelola pesanan.</p>
            </div>
        </div>

        <form action="{{ route('login.submit') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="text-sm font-semibold text-slate-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="mt-2 w-full rounded-full border border-slate-200 bg-slate-50 px-5 py-4 text-sm text-slate-900 outline-none transition focus:border-[#2FA884] focus:ring-2 focus:ring-[#6FCF97]/30" />
                @error('email')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="text-sm font-semibold text-slate-700">Kata Sandi</label>
                <input id="password" name="password" type="password" required class="mt-2 w-full rounded-full border border-slate-200 bg-slate-50 px-5 py-4 text-sm text-slate-900 outline-none transition focus:border-[#2FA884] focus:ring-2 focus:ring-[#6FCF97]/30" />
                @error('password')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 text-sm text-slate-600">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-[#6FCF97] focus:ring-[#6FCF97]" />
                    Ingat saya
                </label>
            </div>

            <button type="submit" class="w-full rounded-full bg-[#6FCF97] px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-[#2FA884] transition">Login</button>

            <p class="text-center text-sm text-slate-600">Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-[#2FA884] hover:text-[#1a8d74]">Daftar sekarang</a></p>
        </form>
    </div>
</div>
@endsection
