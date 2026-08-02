<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>{{ $title ?? 'Admin libellis-shop' }} | {{ $siteName }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        </style>
    </head>
    <body class="min-h-screen bg-[#F5F7F9] text-slate-900 antialiased">
        <div class="min-h-screen">
            <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-50">
                <div class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-4 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-3">
                        <div class="h-11 w-11 rounded-2xl bg-[#6FCF97] grid place-items-center text-white text-lg font-bold shadow-sm">A</div>
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">Admin Panel</p>
                            <p class="text-base font-semibold text-slate-900">libellis-shop</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="rounded-full bg-[#E6FFEF] px-3 py-2 text-sm font-semibold text-[#2FA884]">Admin</span>
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="rounded-full bg-[#FEFEFE] border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-[#2FA884] hover:text-[#2FA884]">Logout</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="mx-auto flex max-w-7xl gap-6 px-4 py-8 sm:px-6 lg:px-8 xl:gap-8">
                <aside class="hidden w-72 shrink-0 rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm lg:block">
                    <nav class="space-y-2 text-sm font-medium text-slate-700">
                        <p class="mb-4 text-xs uppercase tracking-[0.35em] text-slate-400">Navigasi Admin</p>
                        <a href="{{ route('admin.dashboard') }}" class="block rounded-3xl px-4 py-3 transition hover:bg-[#F4FFF6] hover:text-[#2FA884] {{ request()->routeIs('admin.dashboard') ? 'bg-[#F4FFF6] text-[#2FA884]' : '' }}">Dashboard</a>
                        <a href="{{ route('admin.orders.index') }}" class="block rounded-3xl px-4 py-3 transition hover:bg-[#F4FFF6] hover:text-[#2FA884] {{ request()->routeIs('admin.orders.*') ? 'bg-[#F4FFF6] text-[#2FA884]' : '' }}">Pesanan</a>
                        <a href="{{ route('admin.products.index') }}" class="block rounded-3xl px-4 py-3 transition hover:bg-[#F4FFF6] hover:text-[#2FA884] {{ request()->routeIs('admin.products.*') ? 'bg-[#F4FFF6] text-[#2FA884]' : '' }}">Produk</a>
                        <a href="{{ route('admin.categories.index') }}" class="block rounded-3xl px-4 py-3 transition hover:bg-[#F4FFF6] hover:text-[#2FA884] {{ request()->routeIs('admin.categories.*') ? 'bg-[#F4FFF6] text-[#2FA884]' : '' }}">Kategori</a>
                        <a href="{{ route('admin.settings.index') }}" class="block rounded-3xl px-4 py-3 transition hover:bg-[#F4FFF6] hover:text-[#2FA884] {{ request()->routeIs('admin.settings.*') ? 'bg-[#F4FFF6] text-[#2FA884]' : '' }}">Pengaturan</a>
                    </nav>
                </aside>

                <main class="min-w-0 flex-1">
                    @if(session('success'))
                        <div class="mb-6 rounded-[2rem] border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-900 shadow-sm transition-opacity duration-300">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-6 rounded-[2rem] border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-900 shadow-sm transition-opacity duration-300">
                            {{ session('error') }}
                        </div>
                    @endif
                    @yield('content')
                </main>
            </div>
        </div>

        <div id="toast" class="pointer-events-none fixed right-4 bottom-4 z-50 hidden max-w-sm rounded-3xl bg-slate-950 px-5 py-4 text-sm text-white shadow-2xl opacity-0 transition-all duration-300"></div>
        @if(session('success'))
            <script>window.addEventListener('DOMContentLoaded', ()=> showToast(@json(session('success')), 'success'))</script>
        @endif
        @if(session('error'))
            <script>window.addEventListener('DOMContentLoaded', ()=> showToast(@json(session('error')), 'error'))</script>
        @endif
    </body>
</html>
