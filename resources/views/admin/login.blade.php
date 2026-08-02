@extends('layouts.admin')

@section('content')
<div class="mx-auto max-w-md rounded-[2rem] border border-slate-200 bg-white p-8 shadow-sm transition duration-300 sm:px-10">
    <div class="mb-8 text-center">
        <p class="text-xs uppercase tracking-[0.3em] text-[#2FA884]">Admin Login</p>
        <h1 class="mt-4 text-3xl font-semibold text-slate-900">Masuk ke dashboard</h1>
    </div>

    <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-semibold text-slate-700">Email admin</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-2 w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-[#2FA884] focus:ring-2 focus:ring-[#6FCF97]/30" />
            @error('email')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700">Kata sandi</label>
            <input type="password" name="password" required class="mt-2 w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-[#2FA884] focus:ring-2 focus:ring-[#6FCF97]/30" />
            @error('password')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember" class="h-4 w-4 rounded border-slate-300 text-[#2FA884] focus:ring-[#2FA884]" />
                <label for="remember" class="text-sm text-slate-600">Ingat saya</label>
            </div>
        </div>

        <button type="submit" class="w-full rounded-full bg-[#6FCF97] px-5 py-3 text-sm font-semibold text-white shadow hover:bg-[#2FA884] transition">Masuk</button>
    </form>
</div>
@endsection
